<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * ১. সব ক্যাটাগরি দেখা (Brand এবং Status নামসহ)
     */
    public function index()
    {
        $categories = DB::table('categories')
            ->leftJoin('brands', 'categories.brand_id', '=', 'brands.id')
            ->leftJoin('product_statuses', 'categories.status_id', '=', 'product_statuses.id')
            ->select(
                'categories.*', 
                'brands.name as brand_name', 
                'product_statuses.name as status_name'
            )
            ->orderBy('categories.id', 'desc')
            ->get();

        return response()->json($categories, 200);
    }

    /**
     * ২. নতুন ক্যাটাগরি তৈরি (অটো স্লাগসহ)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'brand_id' => 'nullable|exists:brands,id',
            'status_id' => 'required|exists:product_statuses,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // ইউনিক স্লাগ জেনারেট করা
        $slug = Str::slug($request->name);
        
        $id = DB::table('categories')->insertGetId([
            'name' => $request->name,
            'slug' => $slug,
            'brand_id' => $request->brand_id,
            'status_id' => $request->status_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Category created successfully!', 'id' => $id], 201);
    }

    /**
     * ৩. নির্দিষ্ট ক্যাটাগরির তথ্য দেখা
     */
    public function show($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200);
    }

    /**
     * ৪. ক্যাটাগরি আপডেট করা
     */
    public function update(Request $request, $id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        if (!$category) return response()->json(['message' => 'Category not found'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'brand_id' => 'nullable|exists:brands,id',
            'status_id' => 'required|exists:product_statuses,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::table('categories')->where('id', $id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'brand_id' => $request->brand_id,
            'status_id' => $request->status_id,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Category updated successfully!'], 200);
    }

    /**
     * ৫. ক্যাটাগরি ডিলিট করা
     */
    public function destroy($id)
    {
        $deleted = DB::table('categories')->where('id', $id)->delete();
        
        if ($deleted) {
            return response()->json(['message' => 'Category deleted successfully'], 200);
        }

        return response()->json(['message' => 'Category not found'], 404);
    }
}