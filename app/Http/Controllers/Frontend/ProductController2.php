<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController2 extends Controller
{
     public function index()
    {
        // প্রোডাক্টের সাথে তার ভ্যারিয়েন্ট এবং ইমেজগুলো একবারে নিয়ে আসা
        $products = Product::with(['variants.images', 'category'])->get();
    

        return view('frontend.pages.tshirts', compact('products'));
    }
}
