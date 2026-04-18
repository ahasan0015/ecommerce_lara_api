<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    //cart page view
    public function index()
    {
        // if user login data collect from database
        if (Auth::check()) {
            $cart = Cart::with('items.variant.product', 'items.variant.images')
                ->where('user_id', Auth::id())
                ->first();
            $cartItems = $cart ? $cart->items : collect();
        } else {
            // if not login blank submit because data collect fromLocalStorage
            $cartItems = collect();
        }

        return view('frontend.pages.cart_page.cart', compact('cartItems'));
    }

    // login user save data to database
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'guest', 'message' => 'Processing via LocalStorage']);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // cheack prodct cart item already in the 
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($cartItem) {
            // increase cart item quantity
            $cartItem->increment('quantity', $request->quantity ?? 1);
        } else {
            //if not add new
            CartItem::create([
                'cart_id' => $cart->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity ?? 1
            ]);
        }


        // Navbar update total count CartController.php
        $totalCount = CartItem::where('cart_id', $cart->id)->sum('quantity');

        return response()->json([
            'status' => 'success',
            'total_count' => $totalCount,
            'message' => 'Added to database cart'
        ]);
    }
    // collect data from local stroage
    public function syncCart(Request $request)
    {
        if (!Auth::check()) return response()->json(['error' => 'Unauthorized'], 401);

        $guestCart = $request->input('cart_data');
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        foreach ($guestCart as $item) {
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('variant_id', $item['variant_id'])
                ->first();

            if ($existingItem) {
                $existingItem->increment('quantity', $item['quantity']);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity']
                ]);
            }
        }
        return response()->json(['status' => 'synced']);
    }

    //remove from cart
    public function removeFromCart($id)
    {
        // Here $id is cart_items table id
        $item = CartItem::find($id);

        if ($item) {
            $item->delete();
            return response()->json(['status' => 'success', 'message' => 'Item removed']);
        }

        return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
    }

    // Cart item quantity updta method
    public function updateQuantity(Request $request)
    {
        // validation
        $request->validate([
            'cart_id' => 'required',
            'action' => 'required|in:increase,decrease'
        ]);

        $cartItem = CartItem::find($request->cart_id);

        if ($cartItem) {
            if ($request->action === 'increase') {
                $cartItem->increment('quantity');
            } else {
                // not less than 1
                if ($cartItem->quantity > 1) {
                    $cartItem->decrement('quantity');
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Quantity cannot be less than 1'
                    ], 400);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Quantity updated'
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
    }

    //cheackout cart-controller method
    public function getCartData()
    {
        // ১. লগইন করা ইউজারের কার্ট আইটেম সংগ্রহ (Eager Loading সহ)
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['variant.product', 'variant.images'])
            ->get();

        // ২. ডেটাকে ফরমেট করা যেন ফ্রন্টএন্ড সহজে পড়তে পারে
        $formattedItems = $cartItems->map(function ($item) {
            return [
                'id'         => $item->id,
                'variant_id' => $item->variant_id,
                'quantity'   => $item->quantity,
                'size'       => $item->size, // যদি আপনার কার্ট টেবিলে সাইজ কলাম থাকে
                'name'       => $item->variant->product->name,
                'price'      => $item->variant->sale_price,
                'image'      => optional($item->variant->images->first())->image
                    ? asset('storage/' . $item->variant->images->first()->image)
                    : asset('assets/images/placeholder.jpg'),
            ];
        });

        // ৩. JSON রেসপন্স পাঠানো
        return response()->json([
            'status' => 'success',
            'items'  => $formattedItems,
            'count'  => $cartItems->count()
        ]);
    }
}
