<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController2 extends Controller
{
    public function index()
    {
        // শুধুমাত্র Mens T-Shirt ক্যাটাগরির প্রোডাক্টগুলো ফিল্টার করে আনা হচ্ছে
        $products = Product::whereHas('category', function ($query) {
            $query->where('name', 'Mens T-Shirt');
        })->with(['variants.images', 'category'])->get();

        return view('frontend.pages.tshirts', compact('products'));
    }

    //Panjabi Controller
    public function panjabi()
    {
        // শুধুমাত্র 'Mens Panjabi' ক্যাটাগরির প্রোডাক্টগুলো আনা হচ্ছে
        $products = Product::whereHas('category', function ($query) {
            $query->where('name', 'Mens Panjabi'); // আপনার ডাটাবেজে যে নাম আছে হুবহু সেটা দিন
        })->with(['variants.images', 'category'])->get();

        return view('frontend.pages.panjabi', compact('products'));
    }
}
