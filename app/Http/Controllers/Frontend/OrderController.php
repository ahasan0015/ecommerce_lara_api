<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        // ১. ভ্যালিডেশন
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required',
            'city' => 'required',
            'payment_method' => 'required'
        ]);

        // ২. কার্ট থেকে ডেটা সংগ্রহ
        $cartItems = Cart::where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        // ৩. সাবটোটাল ক্যালকুলেশন
        $subtotal = $cartItems->sum(function ($item) {
            return $item->variant->sale_price * $item->quantity;
        });

        $shippingFee = 60; // আপনার ব্লেড ফাইল অনুযায়ী
        $total = $subtotal + $shippingFee;

        // ৪. ডাটাবেস ট্রানজেকশন (নিরাপত্তার জন্য)
        DB::beginTransaction();

        try {
            // ৫. অর্ডার টেবিল এ ডেটা ইনসার্ট
            $order = new Order();
            $order->user_id = auth()->id();
            $order->order_status_id = 1; // ধরি ১ মানে 'Pending'
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->subtotal = $subtotal;
            $order->total = $total;
            $order->payment_method = $request->payment_method;
            // এখানে নাম, ফোন, এড্রেস সেভ করার জন্য আপনার টেবিলে কলাম না থাকলে যোগ করে নিবেন
            $order->save();

            // ৬. অর্ডার আইটেম টেবিল এ ডেটা মুভ করা
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'variant_id' => $cartItem->variant_id,
                    'price'      => $cartItem->variant->sale_price,
                    'quantity'   => $cartItem->quantity,
                    'total'      => $cartItem->variant->sale_price * $cartItem->quantity,
                ]);
            }

            // ৭. কার্ট ক্লিয়ার করা
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect('/')->with('success', 'অর্ডার সফল হয়েছে! আপনার অর্ডার নম্বর: ' . $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'অর্ডার প্রসেস করার সময় সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }
}
