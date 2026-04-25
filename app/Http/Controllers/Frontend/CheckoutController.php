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
            return redirect()->route('login')->with('info', 'Please Login First');
        }

        $cart = Cart::with([
            'items.variant.product', 
            'items.variant.size',
            'items.variant.color',
            'items.variant.images'
        ])->where('user_id', Auth::id())->first();

        
        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('dashboard')->with('error', 'Your Cart is Empty');
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
