<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController2 extends Controller
{
    public function tshirt()
    {
        $products = Product::whereHas('category', function ($query) {
            $query->where('name', 'Mens T-Shirt');
        })
            ->with([
                'category',
                'variants.size',
                'images'
            ])
            ->get();

        return view('frontend.pages.tshirts', compact('products'));
    }

    //Panjabi Controller
    public function panjabi()
    {
        // Only 'Mens Panjabi' Category product filter
        $products = Product::whereHas('category', function ($query) {
            $query->where('name', 'Mens Panjabi');
        })->with([
            'category',
            'variants.size',
            'images'
        ])->get();

        // dd($products);

        return view('frontend.pages.panjabi', compact('products'));
    }

    //women Collection
    public function pakistaniDress()
    {
        $products = Product::whereHas('category', function ($query) {
            $query->where('name', 'Pakistani Dress');
        })
            ->with([
                'category',
                'variants.size',
                'images'
            ])
            ->get();

        return view('frontend.pages.women.pakistanidress', compact('products'));
    }

    //Product Details
    public function productDetails($id)
    {
        $product = Product::with(['variants.images', 'variants.size'])->findOrFail($id);
        return view('frontend.pages.product.product_details', compact('product'));
    }
}
