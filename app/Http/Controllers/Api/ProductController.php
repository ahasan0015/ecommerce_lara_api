<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStatus;
use App\Models\ProductVariant;
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
    // public function index()
    // {
    //     $product = Product::with('brand', 'category', 'status')
    //         ->orderBy('id', 'desc')
    //         ->paginate(10);

    //     return response()->json([
    //         'success' => true,
    //         'data' => $product
    //     ]);
    // }
    public function index()
    {
        // 1️⃣ Main product data with category, brand, status
        $products = DB::table('products')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'products.slug',
                'products.description',
                'categories.name as category_name',
                'brands.name as brand_name',
                'product_statuses.name as status_name'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_statuses', 'products.status_id', '=', 'product_statuses.id')
            ->orderBy('products.created_at', 'desc')
            ->paginate(10);

        // 2️⃣ Loop through products to get variants + images
        foreach ($products as $product) {
            $variants = DB::table('product_variants')
                ->select(
                    'product_variants.id as variant_id',
                    'product_variants.sku',
                    'product_variants.sale_price',
                    'product_variants.stock',
                    'colors.name as color',
                    'sizes.name as size'
                )
                ->leftJoin('colors', 'product_variants.color_id', '=', 'colors.id')
                ->leftJoin('sizes', 'product_variants.size_id', '=', 'sizes.id')
                ->where('product_variants.product_id', $product->product_id)
                ->get();

            foreach ($variants as $variant) {
                $images = DB::table('product_images')
                    ->select('image', 'is_main')
                    ->where('product_variant_id', $variant->variant_id)
                    ->get();

                $variant->images = $images;
            }

            $product->variants = $variants;
        }

        // 3️⃣ Return as JSON
        return response()->json([
            'success'      => true,
            'data'         => $products->items(), // শুধু product array
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'total'        => $products->total(),
        ]);
    }
    // Get Single Product Details
    public function show($id)
    {
        try {
            $product = DB::table('products')
                ->select(
                    'products.id',
                    'products.name',
                    'products.slug',
                    'products.description',
                    'products.base_price',
                    'products.main_image',
                    'categories.name as category_name',
                    'brands.name as brand_name',
                    'product_statuses.name as status_name',
                    'products.category_id',
                    'products.brand_id',
                    'products.status_id'
                )
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('product_statuses', 'products.status_id', '=', 'product_statuses.id')
                ->where('products.id', $id)
                ->first();

            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $gallery = DB::table('product_images')
                ->select('image', 'is_main')
                ->where('product_id', $id)
                ->get();

            $variants = DB::table('product_variants')
                ->select(
                    'product_variants.id',
                    'product_variants.sku',
                    'product_variants.sale_price',
                    'product_variants.stock',
                    'colors.name as color_name',
                    'sizes.name as size_name'
                )
                ->leftJoin('colors', 'product_variants.color_id', '=', 'colors.id')
                ->leftJoin('sizes', 'product_variants.size_id', '=', 'sizes.id')
                ->where('product_variants.product_id', $id)
                ->get();

            $product->gallery = $gallery;
            $product->variants = $variants;

            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }


    //======store method using DB::table=========
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'        => 'required|string|max:255',
    //         'category_id' => 'required|integer',
    //         'brand_id'    => 'required|integer',
    //         'status_id'   => 'required|integer',
    //         'description' => 'nullable|string',

    //         'variants' => 'required|array|min:1',
    //         'variants.*.sku' => 'required|string',
    //         'variants.*.status_id' => 'required|integer',
    //         'variants.*.sale_price' => 'required|numeric',
    //         'variants.*.stock' => 'required|integer',
    //         'variants.*.images'   => 'nullable|array',
    //         'variants.*.images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     return DB::transaction(function () use ($request) {

    //         // 1️⃣ Insert Product
    //         $productId = DB::table('products')->insertGetId([
    //             'name'        => $request->name,
    //             'slug'        => Str::slug($request->name) . '-' . time(),
    //             'description' => $request->description,
    //             'category_id' => $request->category_id,
    //             'brand_id'    => $request->brand_id,
    //             'status_id'   => $request->status_id,
    //             'base_price'   => $request->base_price,
    //             'created_at'  => now(),
    //             'updated_at'  => now(),
    //         ]);

    //         // 2️⃣ Insert Variants
    //         foreach ($request->variants as $index => $variantData) {

    //             $variantId = DB::table('product_variants')->insertGetId([
    //                 'product_id' => $productId,
    //                 'color_id'   => $variantData['color_id'] ?? null,
    //                 'size_id'    => $variantData['size_id'] ?? null,
    //                 'sku'        => $variantData['sku'],
    //                 'status_id'  => $variantData['status_id'],
    //                 'sale_price' => $variantData['sale_price'],
    //                 'stock'      => $variantData['stock'],
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             // 3️⃣ Insert Variant Images

    //             if ($request->hasFile("variants.{$index}.images")) {
    //                 $files = $request->file("variants.{$index}.images");

    //                 foreach ($files as $file) {
    //                     if ($file->isValid()) {
    //                         // ফাইলের নাম ইউনিক করা (uniqid ব্যবহার করা হয়েছে যাতে ওভাররাইট না হয়)
    //                         $filename = Str::slug($request->name) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

    //                         // স্টোরেজে সেভ করা
    //                         $path = $file->storeAs('products/variants', $filename, 'public');

    //                         // ডাটাবেজে ইনসার্ট
    //                         DB::table('product_images')->insert([
    //                             'product_variant_id' => $variantId,
    //                             'image'              => $path,
    //                             'is_main'            => 0,
    //                             'created_at'         => now(),
    //                             'updated_at'         => now(),
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Product created successfully',
    //             'product_id' => $productId
    //         ], 201);
    //     });
    // }

    ///===updated store method try catch=====
    public function store(Request $request)
    {
        // SKU Validation Check
        $request->validate([
            'name'           => 'required|string|max:255',
            'color_id'       => 'required|integer',
            'main_image'     => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants'       => 'required|array|min:1',
            'variants.*.sku' => 'required|string|unique:product_variants,sku', // CRITICAL FIX
        ], [
            'variants.*.sku.unique' => 'The SKU ":input" has already been taken. Please use Auto-generate.',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // Main Image Upload
                $mainImagePath = $request->file('main_image')->store('products/main', 'public');

                // ৩. Product Insert
                $productId = DB::table('products')->insertGetId([
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name) . '-' . time(),
                    'category_id' => $request->category_id,
                    'brand_id'    => $request->brand_id,
                    'status_id'   => $request->status_id ?? 1,
                    'base_price'  => $request->base_price,
                    'main_image'  => $mainImagePath,
                    'description' => $request->description,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                // Image
                if ($request->hasFile('gallery_images')) {
                    foreach ($request->file('gallery_images') as $file) {
                        $path = $file->store('products/gallery', 'public');
                        DB::table('product_images')->insert([
                            'product_id' => $productId,
                            'image'      => $path,
                            'is_main'    => 0,
                            'created_at' => now(),
                        ]);
                    }
                }

                // Variant Insert With Global Color ID
                foreach ($request->variants as $v) {
                    DB::table('product_variants')->insert([
                        'product_id' => $productId,
                        'color_id'   => $request->color_id,
                        'size_id'    => $v['size_id'],
                        'sku'        => $v['sku'],
                        'sale_price' => $v['sale_price'],
                        'stock'      => $v['stock'],
                        'status_id'  => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                return response()->json(['success' => true, 'message' => 'Product and variants created!'], 201);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()], 500);
        }
    }
    //Update product Update method 
    public function update(Request $request, $id)
    {
        // ১. ফাইন্ড প্রোডাক্ট
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        // ২. ভ্যালিডেশন
        $request->validate([
            'name'        => 'required|string|max:200',
            'brand_id'    => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'status_id'   => 'required|exists:product_statuses,id',
            'base_price'  => 'required|numeric',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // ৫ এমবি ম্যাক্স
            'description' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($request, $product) {

                $imagePath = $product->main_image;


                if ($request->hasFile('image')) {

                    if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                        Storage::disk('public')->delete($product->main_image);
                    }

                    $image = $request->file('image');
                    $manager = new ImageManager(new Driver());
                    $imageName = 'product_' . time() . '_' . uniqid() . '.webp';


                    $img = $manager->read($image);
                    $img->cover(1000, 1000);
                    $encoded = $img->toWebp(85);


                    $path = 'products/main/' . $imageName;
                    Storage::disk('public')->put($path, (string) $encoded);
                    $imagePath = $path;
                }

                $product->update([
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name) . '-' . $product->id,
                    'brand_id'    => $request->brand_id,
                    'category_id' => $request->category_id,
                    'status_id'   => $request->status_id,
                    'description' => $request->description,
                    'base_price'  => $request->base_price,
                    'main_image'  => $imagePath,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully!',
                    'data'    => $product
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
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
