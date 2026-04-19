<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'অর্ডার করতে দয়া করে লগইন করুন।');
        }

        $cart = Cart::with([
            'items.variant.product', // cartItems এর বদলে items
            'items.variant.size',
            'items.variant.color',
            'items.variant.images'
        ])->where('user_id', Auth::id())->first();

        // চেক করার সময়ও items ব্যবহার করুন
        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('home')->with('error', 'আপনার কার্ট খালি!');
        }

        $cartItems = $cart->items;

        $subtotal = $cartItems->sum(function ($item) {
            return $item->variant->sale_price * $item->quantity;
        });

        $shippingFee = 60;
        $total = $subtotal + $shippingFee;
        // dd($cartItems);

        return view('frontend.pages.checkout_page.checkout', compact(
            'cartItems',
            'subtotal',
            'shippingFee',
            'total'
        ));
    }
}
