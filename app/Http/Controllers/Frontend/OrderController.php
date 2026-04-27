<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => ['required', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/'],
            'address' => 'required|string',
            'city'    => 'required|string',
            'thana'   => 'required|string',
            'payment_method' => 'required'
        ], [
            'phone.regex' => 'Please input 11 digit phone Number',
        ]);

        try {
            DB::beginTransaction();

            // (Row Level Locking)

            $cart = Cart::where('user_id', Auth::id())
                ->with(['items.variant' => function ($q) {
                    $q->lockForUpdate();
                }])
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->back()->with('error', 'Your Cart is Empty');
            }

            // stock check loop
            foreach ($cart->items as $item) {
                if (!$item->variant || $item->variant->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Sorry, {$item->variant->product->name} পর্যাপ্ত স্টকে নেই।");
                }
            }

            // Shipping charge and phone number clean
            $shippingCharge = ($request->city === 'Dhaka') ? 60 : 150;
            $cleanPhone = preg_replace('/^(?:\+88|88)/', '', $request->phone);

            //shipping address save
            ShippingAddress::create([
                'user_id'     => Auth::id(),
                'name'        => $request->name,
                'phone'       => $cleanPhone,
                'address'     => $request->address,
                'city'        => $request->city,
                'thana'       => $request->thana,
                'postal_code' => $request->postal_code ?? '1200',
                'country'     => 'Bangladesh',
            ]);

            $subtotal = $cart->items->sum(fn($item) => $item->variant->sale_price * $item->quantity);
            $total = $subtotal + $shippingCharge;

            // Order Create
            $order = Order::create([
                'user_id'           => Auth::id(),
                'order_status_id'   => 1, // Pending
                'payment_status_id' => 1, // Unpaid
                'order_number'      => 'ORD-' . strtoupper(Str::random(10)),
                'subtotal'          => $subtotal,
                'shipping_charge'   => $shippingCharge,
                'discount'          => 0,
                'total'             => $total,
                'payment_method'    => $request->payment_method,
            ]);

            //item save and stock decrise
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->variant->product_id,
                    'product_variant_id' => $item->variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->sale_price,
                ]);

                //stock decrement
                $item->variant->decrement('stock', $item->quantity);
            }

            //cart clear
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'Your order is successfull');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Sorry, Something Error. Try Again');
        }
    }
    public function orderSuccess($order_number)
    {
        $order = Order::where('order_number', $order_number)->with('items.variant.product')->firstOrFail();
        return view('frontend.pages.order_page.order_success', compact('order'));
    }

    public function CustomerInvoice($order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['items.variant.product', 'items.variant.size', 'user'])
            ->firstOrFail();

        $shipping = ShippingAddress::where('user_id', $order->user_id)
            ->latest()
            ->first();

        $qrData = route('order.success', $order->order_number);
        $url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($qrData);

        try {
            $imageData = base64_encode(file_get_contents($url));
            $qrCode = 'data:image/png;base64,' . $imageData;
        } catch (\Exception $e) {
            $qrCode = null;
        }

        $pdf = Pdf::loadView('frontend.pages.order_page.invoice', compact('order', 'qrCode', 'shipping'))
            ->setPaper('a4', 'portrait') // এখানে 'a4' সেট করা হয়েছে
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
    }

    //for shop pos invoice
    public function downloadInvoiceShop($order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['items.variant.product', 'items.variant.size', 'items.variant.color', 'user'])
            ->firstOrFail();

        // QR Code generation using Base64 for better compatibility
        $qrData = route('order.success', $order->order_number);
        $url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($qrData);

        try {
            // ইমেজটিকে ডাটাতে কনভার্ট করা হচ্ছে যাতে dompdf সহজে রিড করতে পারে
            $imageData = base64_encode(file_get_contents($url));
            $qrCode = 'data:image/png;base64,' . $imageData;
        } catch (\Exception $e) {
            // যদি ইন্টারনেট বা এপিআই এর কারণে ইমেজ না আসে তবে নাল থাকবে
            $qrCode = null;
        }

        $pdf = Pdf::loadView('frontend.pages.order_page.pos_invoice_shop', compact('order', 'qrCode'))
            ->setPaper([0, 0, 226.77, 600], 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans', // ৳ সিম্বল সাপোর্ট করার জন্য বেস্ট
            ]);

        return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
    }

    //for custormer invoice 
    public function downloadInvoice($order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['items.variant.product', 'items.variant.size', 'user', 'shippingAddress']) // shippingAddress 
            ->firstOrFail();

        // QR Code Base64 logic (No need to save file in public/images)
        $qrData = route('order.success', $order->order_number);
        $url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($qrData);

        try {
            $imageData = base64_encode(file_get_contents($url));
            $qrCode = 'data:image/png;base64,' . $imageData;
        } catch (\Exception $e) {
            $qrCode = null;
        }

        $pdf = Pdf::loadView('frontend.pages.order_page.pos_invoice_customer', compact('order', 'qrCode'))
            ->setPaper([0, 0, 226.77, 650], 'portrait') // Height 
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
    }

    //Main Controller
    public function downloadInvoiceMain($order_number)
    {
        // order Data
        $order = Order::where('order_number', $order_number)
            ->with(['items.variant.product', 'items.variant.size', 'user'])
            ->firstOrFail();

        //(RelationNotFoundException
        $shipping = \App\Models\ShippingAddress::where('user_id', $order->user_id)
            ->latest()
            ->first();

        // ৩. QR Code logic (Base64 conversion for 100% visibility)
        $qrData = route('order.success', $order->order_number);
        $url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . urlencode($qrData);

        try {
            $imageData = base64_encode(file_get_contents($url));
            $qrCode = 'data:image/png;base64,' . $imageData;
        } catch (\Exception $e) {
            $qrCode = null;
        }

        // ৪. PDF জেনারেট করা
        $pdf = Pdf::loadView('frontend.pages.order_page.pos_invoice_customer_main', compact('order', 'qrCode', 'shipping'))
            ->setPaper([0, 0, 226.77, 650], 'portrait') // POS Standard width
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        return $pdf->stream('Invoice-' . $order->order_number . '.pdf');
    }
}
