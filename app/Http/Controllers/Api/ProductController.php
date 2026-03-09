<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // List Products
    public function index()
    {
        $product = Product::with('brand', 'category', 'status')->paginate(10);
        // $product = ProductStatus::get();
        return response()->json([
            'success' => true,
            'data' => $product
        ]);


        // {
        //     $products = DB::table('products')
        //         ->join('brands', 'products.brand_id', '=', 'brands.id')
        //         ->join('categories', 'products.category_id', '=', 'categories.id')
        //         ->join('product_status', 'products.status_id', '=', 'product_status.id')
        //         ->select(
        //             'products.*',
        //             'brands.name as brand_name',
        //             'categories.name as category_name',
        //             'product_status.name as status_name'
        //         )
        //         ->paginate(10);

        //     return response()->json([
        //         'success' => true,
        //         'data' => $products
        //     ]);
        // }
    }

    // Store Product
public function store(Request $request)
{
    
    // ১. স্লাগ তৈরি
    $slug = Str::slug($request->name);
    $request->merge(['slug' => $slug]);

    // ২. ভ্যালিডেশন (এখানে 'image' যোগ করা হয়েছে)
    $validator = Validator::make($request->all(), [
        'category_id' => 'required|exists:categories,id',
        'brand_id'    => 'required|exists:brands,id',
        'status_id'   => 'required|exists:product_statuses,id',
        'name'        => 'required|string|max:200',
        'slug'        => 'required|unique:products,slug',
        'base_price'  => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // ইমেজের ভ্যালিডেশন
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    // ৩. ট্রানজ্যাকশন ও ডাটা সেভ
    return DB::transaction(function () use ($request) {
        try {
            // ইমেজ আপলোড লজিক
            $imagePath = null;
            if ($request->hasFile('image')) {
                // 'public/uploads/products' ফোল্ডারে সেভ হবে
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $product = Product::create([
                'category_id' => $request->category_id,
                'brand_id'    => $request->brand_id,
                'status_id'   => $request->status_id,
                'name'        => $request->name,
                'slug'        => $request->slug,
                'description' => $request->description,
                'base_price'  => $request->base_price,
                'image'       => $imagePath, // ডাটাবেজে ইমেজের পাথ সেভ করা
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'data'    => $product
            ], 201);

        } catch (\Exception $e) {
            Log::error("Product Store Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during saving.'
            ], 500);
        }
    });
}

    // Show Product
    public function show($id)
    {
        return Product::with('brand', 'category', 'status')->findOrFail($id);
    }

    // Update Product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'status_id' => 'required|exists:product_status,id',
            'base_price' => 'required|numeric'
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'brand_id' => $request->brand_id,
            'category_id' => $request->category_id,
            'status_id' => $request->status_id,
            'description' => $request->description,
            'base_price' => $request->base_price
        ]);

        return response()->json($product);
    }

    // Delete Product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
