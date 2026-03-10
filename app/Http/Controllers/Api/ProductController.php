<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

// Intervention Image ব্যবহার করার জন্য


class ProductController extends Controller
{
    // List Products
    public function index()
    {
        $product = Product::with('brand', 'category', 'status')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }


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


    //update Store product method upload and convert imgae size 

    public function store(Request $request)
    {
        // ১. স্লাগ তৈরি
        $slug = Str::slug($request->name);

        // ২. ভ্যালিডেশন
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'required|exists:brands,id',
            'status_id'   => 'required|exists:product_statuses,id',
            'name'        => 'required|string|max:200',
            'base_price'  => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120' // ৫ এমবি পর্যন্ত অনুমতি
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // ৩. ডাটাবেজ ট্রানজ্যাকশন শুরু
        return DB::transaction(function () use ($request, $slug) {
            try {
                $imagePath = null;

                // ৪. ইমেজ প্রসেসিং (যদি ফাইল থাকে)
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileName = time() . '_' . Str::random(10) . '.webp';
                    $folder = 'products';

                    // স্টোরেজ চেক
                    if (!Storage::disk('public')->exists($folder)) {
                        Storage::disk('public')->makeDirectory($folder);
                    }

                    // Intervention Image v3 লজিক (Laravel 12 Friendly)
                    $manager = new ImageManager(new Driver()); // GD Driver
                    $image = $manager->read($file);

                    // ১২০০x১২০০ রিসাইজ ও স্কয়ার প্যাডিং
                    $image->scaleDown(width: 1200);
                    $image->pad(1200, 1200, '#ffffff'); // খালি জায়গায় সাদা ব্যাকগ্রাউন্ড

                    // WebP ফরম্যাটে ৮% কোয়ালিটিতে কনভার্ট
                    $encoded = $image->toWebp(80);

                    // স্টোরেজে সেভ
                    $imagePath = $folder . '/' . $fileName;
                    Storage::disk('public')->put($imagePath, (string) $encoded);
                }

                // ৫. ডাটাবেজে প্রোডাক্ট তৈরি
                $product = Product::create([
                    'category_id' => $request->category_id,
                    'brand_id'    => $request->brand_id,
                    'status_id'   => $request->status_id,
                    'name'        => $request->name,
                    'slug'        => $slug . '-' . time(), // ইউনিক স্লাগ
                    'description' => $request->description,
                    'base_price'  => $request->base_price,
                    'image'       => $imagePath,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Product created with 1200x1200px optimized image!',
                    'data'    => $product
                ], 201);
            } catch (\Exception $e) {
                // এরর হলে লগ এ সেভ হবে
                Log::error("Product Store Error: " . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store product: ' . $e->getMessage()
                ], 500);
            }
        });
    }
    // Store Product
    // public function store(Request $request)
    // {

    //     // ১. স্লাগ তৈরি
    //     $slug = Str::slug($request->name);
    //     $request->merge(['slug' => $slug]);

    //     // ২. ভ্যালিডেশন (এখানে 'image' যোগ করা হয়েছে)
    //     $validator = Validator::make($request->all(), [
    //         'category_id' => 'required|exists:categories,id',
    //         'brand_id'    => 'required|exists:brands,id',
    //         'status_id'   => 'required|exists:product_statuses,id',
    //         'name'        => 'required|string|max:200',
    //         'slug'        => 'required|unique:products,slug',
    //         'base_price'  => 'required|numeric|min:0',
    //         'description' => 'nullable|string',
    //         'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // ইমেজের ভ্যালিডেশন
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors'  => $validator->errors()
    //         ], 422);
    //     }

    //     // ৩. ট্রানজ্যাকশন ও ডাটা সেভ
    //     return DB::transaction(function () use ($request) {
    //         try {
    //             // ইমেজ আপলোড লজিক
    //             $imagePath = null;
    //             if ($request->hasFile('image')) {
    //                 // 'public/uploads/products' ফোল্ডারে সেভ হবে
    //                 $imagePath = $request->file('image')->store('products', 'public');
    //             }

    //             $product = Product::create([
    //                 'category_id' => $request->category_id,
    //                 'brand_id'    => $request->brand_id,
    //                 'status_id'   => $request->status_id,
    //                 'name'        => $request->name,
    //                 'slug'        => $request->slug,
    //                 'description' => $request->description,
    //                 'base_price'  => $request->base_price,
    //                 'image'       => $imagePath, // ডাটাবেজে ইমেজের পাথ সেভ করা
    //             ]);

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Product created successfully!',
    //                 'data'    => $product
    //             ], 201);

    //         } catch (\Exception $e) {
    //             Log::error("Product Store Error: " . $e->getMessage());
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Something went wrong during saving.'
    //             ], 500);
    //         }
    //     });
    // }

    // Show Product
    public function show($id)
    {
        return Product::with('brand', 'category', 'status')->findOrFail($id);
    }
    //Update product Update method 
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // ১. ভ্যালিডেশন
        $request->validate([
            'name'        => 'required|string|max:200',
            'brand_id'    => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'status_id'   => 'required|exists:product_statuses,id',
            'base_price'  => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // ৫ এমবি ম্যাক্স
        ]);

        // ২. ইমেজ প্রসেসিং
        if ($request->hasFile('image')) {
            // নতুন ইমেজ থাকলে পুরানো ইমেজ ডিলিট করা
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $imageName = 'product_' . time() . '.webp'; // WebP ফরম্যাটে সেভ করা ভালো

            // ইমেজ রিসাইজ (১২০০x১২০০) এবং ফরম্যাট কনভার্ট
            $img = $manager->read($image);
            $img->cover(1200, 1200); // সেন্ট্রাল ক্রপ ও রিসাইজ
            $encoded = $img->toWebp(85); // ৮৫% কোয়ালিটি WebP

            // স্টোরেজে সেভ করা
            Storage::disk('public')->put('products/' . $imageName, (string) $encoded);
            $product->image = 'products/' . $imageName;
        }

        // ৩. ডাটা আপডেট
        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name) . '-' . $product->id, // আইডি যোগ করলে স্লাগ ইউনিক থাকবে
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'status_id'   => $request->status_id,
            'description' => $request->description,
            'base_price'  => $request->base_price,
            'image'       => $product->image, // আপডেট করা ইমেজ পাথ
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data'    => $product
        ]);
    }
    // Update Product
    // public function update(Request $request, $id)
    // {
    //     $product = Product::findOrFail($id);

    //     $request->validate([
    //         'name' => 'required|string|max:200',
    //         'brand_id' => 'required|exists:brands,id',
    //         'category_id' => 'required|exists:categories,id',
    //         'status_id' => 'required|exists:product_status,id',
    //         'base_price' => 'required|numeric'
    //     ]);

    //     $product->update([
    //         'name' => $request->name,
    //         'slug' => Str::slug($request->name),
    //         'brand_id' => $request->brand_id,
    //         'category_id' => $request->category_id,
    //         'status_id' => $request->status_id,
    //         'description' => $request->description,
    //         'base_price' => $request->base_price
    //     ]);

    //     return response()->json($product);
    // }

    // Delete Product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }
}
