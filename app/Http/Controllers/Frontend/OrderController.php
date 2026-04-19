<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
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
}
