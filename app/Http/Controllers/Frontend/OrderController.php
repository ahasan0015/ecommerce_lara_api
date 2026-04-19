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
        $request->validate([
            'name'    => 'required|string',
            'phone'   => 'required',
            'address' => 'required',
            'city'    => 'required',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.variant')->first();
        if (!$cart || $cart->items->count() === 0) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        try {
            DB::beginTransaction();

            // shipping address save
            $shipping = ShippingAddress::create([
                'user_id'     => Auth::id(),
                'name'        => $request->name,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'city'        => $request->city,
                'postal_code' => $request->postal_code ?? '1200',
                'country'     => 'Bangladesh',
            ]);

            //calculation
            $subtotal = $cart->items->sum(fn($item) => $item->variant->sale_price * $item->quantity);
            $total = $subtotal + 60; //shipping charge

            // Order Create
            $order = Order::create([
                'user_id'           => Auth::id(),
                'order_status_id'   => 1,
                'payment_status_id' => 1, // <--- এটি যোগ করুন (১ = Unpaid)
                'order_number'      => 'ORD-' . strtoupper(Str::random(10)),
                'subtotal'          => $subtotal,
                'discount'          => 0,
                'total'             => $total,
                'payment_method'    => $request->payment_method, // রেডিও বাটনের ভ্যালু ধরুন
            ]);

            //Order Item Loop
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item->variant->product_id,
                    'product_variant_id' => $item->variant_id, // <--- কলামের নাম আপনার মাইগ্রেশন অনুযায়ী দিন
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->sale_price,
                ]);
            }

            //Cart Clear
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'Your order has been successfully received!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
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

        $shipping = \App\Models\ShippingAddress::where('user_id', $order->user_id)
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
        // ১. অর্ডার ডাটা গেট করা
        $order = Order::where('order_number', $order_number)
            ->with(['items.variant.product', 'items.variant.size', 'user'])
            ->firstOrFail();

        // ২. শিপিং অ্যাড্রেস ম্যানুয়ালি গেট করা (RelationNotFoundException এড়াতে)
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
