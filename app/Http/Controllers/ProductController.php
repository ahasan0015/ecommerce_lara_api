<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // প্রোডাক্টের সাথে তার ভ্যারিয়েন্ট এবং ইমেজগুলো একবারে নিয়ে আসা
        $products = Product::with(['variants.images', 'category'])->get();

        return view('frontend.pages.tshirts', compact('products'));
    }
}
