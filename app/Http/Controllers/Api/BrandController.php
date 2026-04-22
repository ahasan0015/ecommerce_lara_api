<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * All Brand index
     */
    public function index()
    {
        $brands = DB::table('brands')
            ->leftJoin('product_statuses', 'brands.status_id', '=', 'product_statuses.id')
            ->select('brands.*', 'product_statuses.name as status_name')
            ->orderBy('brands.id', 'desc')
            ->get();

        return response()->json(
            [
                'success' => true,
                'data' => $brands
            ],
            200
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands,name|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        $id = DB::table('brands')->insertGetId([
            'name' => $request->name,
            'logo' => $logoPath,
            'status_id' => $request->status_id,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Brand created successfully!', 'id' => $id], 201);
    }

    /**
     * brand Show
     */
    public function show($id)
    {
        $brand = DB::table('brands')->where('id', $id)->first();
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $brand
        ], 200);
    }

    /**
     * Brand Update
     */
    public function update(Request $request, $id)
    {
        $brand = DB::table('brands')->where('id', $id)->first();
        if (!$brand) return response()->json(['message' => 'Brand not found'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:brands,name,' . $id,
            'logo' => 'nullable|image|max:2048',
            'status_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $logoPath = $brand->logo;

        if ($request->hasFile('logo')) {
           
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        DB::table('brands')->where('id', $id)->update([
            'name' => $request->name,
            'logo' => $logoPath,
            'status_id' => $request->status_id,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Brand updated successfully!'], 200);
    }

    /**
     * brand delete
     */
    public function destroy($id)
    {
        $brand = DB::table('brands')->where('id', $id)->first();
        if ($brand) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            DB::table('brands')->where('id', $id)->delete();
            return response()->json(['message' => 'Brand deleted successfully'], 200);
        }
        return response()->json(['message' => 'Brand not found'], 404);
    }

    public function getStatuses()
    {
        $statuses = DB::table('product_statuses')
            ->select('id', 'name')
            ->get();

        return response()->json($statuses);
    }
}
