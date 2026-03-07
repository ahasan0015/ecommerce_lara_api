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
     * 1. Display all categories with Status name only
     */
    public function index()
    {
        $categories = DB::table('categories')
            // Joining only with product_statuses
            ->leftJoin('product_statuses', 'categories.status_id', '=', 'product_statuses.id')
            ->select(
                'categories.*',
                'product_statuses.name as status_name'
            )
            ->orderBy('categories.id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * 2. Store a new category (No Brand)
     */
    public function store(Request $request)
    {
        $slug = Str::slug($request->name);
        $request->merge(['slug' => $slug]);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:100',
            'slug'      => 'required|unique:categories,slug',
            'status_id' => 'required|exists:product_statuses,id' 
        ], [
            'name.required' => 'Category name is required.',
            'slug.unique'   => 'This category name already exists.',
            'status_id.exists' => 'The selected status is invalid.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request, $slug) {
            try {
                $id = DB::table('categories')->insertGetId([
                    'name'       => $request->name,
                    'slug'       => $slug,
                    'status_id'  => $request->status_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Category created successfully!',
                    'data'    => ['id' => $id]
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ], 500);
            }
        });
    }

    /**
     * 3. Show specific category
     */
    public function show($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * 4. Update category (No Brand)
     */
    public function update(Request $request, $id)
    {
        $slug = Str::slug($request->name);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:100',
            'slug'      => 'required|unique:categories,slug,' . $id,
            'status_id' => 'required|exists:product_statuses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::table('categories')->where('id', $id)->update([
            'name'       => $request->name,
            'slug'       => $slug,
            'status_id'  => $request->status_id,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Category updated successfully!'
        ]);
    }

    /**
     * 5. Delete category
     */
    public function destroy($id)
    {
        $deleted = DB::table('categories')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Category not found'
        ], 404);
    }
}