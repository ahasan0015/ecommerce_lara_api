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
     * ১. সব ব্র্যান্ডের লিস্ট (Status নামসহ)
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

    /**
     * ২. নতুন ব্র্যান্ড সেভ করা (ইমেজ আপলোডসহ)
     */
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
            // storage/app/public/brands ফোল্ডারে সেভ হবে
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
     * ৩. নির্দিষ্ট একটি ব্র্যান্ডের ডিটেইলস
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
     * ৪. ব্র্যান্ড আপডেট করা (পুরনো লোগো ডিলিট লজিকসহ)
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
            // নতুন ফাইল আসলে পুরনো ফাইল ডিলিট করা
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
     * ৫. ব্র্যান্ড ডিলিট করা
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
