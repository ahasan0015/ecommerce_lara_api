<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // ১. কার্ট পেজ ভিউ করা
    public function index()
    {
        // ইউজার লগইন থাকলে ডাটাবেজ থেকে ডেটা নিবে
        if (Auth::check()) {
            $cart = Cart::with('items.variant.product', 'items.variant.images')
                        ->where('user_id', Auth::id())
                        ->first();
            $cartItems = $cart ? $cart->items : collect();
        } else {
            // লগইন না থাকলে খালি কালেকশন পাঠাবে (কারণ ডেটা আসবে LocalStorage থেকে)
            $cartItems = collect();
        }

        return view('frontend.pages.cart_page.cart', compact('cartItems'));
    }

    // ২. লগইন করা ইউজারের জন্য সরাসরি ডাটাবেজে সেভ করা
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'guest', 'message' => 'Processing via LocalStorage']);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'variant_id' => $request->variant_id],
            ['quantity' => \DB::raw('quantity + ' . ($request->quantity ?? 1))]
        );

        return response()->json(['status' => 'success', 'message' => 'Added to database cart']);
    }

    // ৩. সিঙ্ক মেথড: লোকাল স্টোরেজ থেকে ডেটা ডাটাবেজে আনা
    public function syncCart(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

        $guestCart = $request->input('cart_data'); // জাভাস্ক্রিপ্ট থেকে পাঠানো অ্যারে
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($guestCart as $item) {
            $existingItem = CartItem::where('cart_id', $cart->id)
                                    ->where('variant_id', $item['variant_id'])
                                    ->first();

            if ($existingItem) {
                // অলরেডি থাকলে কোয়ান্টিটি যোগ হবে
                $existingItem->increment('quantity', $item['quantity']);
            } else {
                // না থাকলে নতুন তৈরি হবে
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity']
                ]);
            }
        }

        return response()->json(['status' => 'synced']);
    }
}