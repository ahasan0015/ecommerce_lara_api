<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant; // নিশ্চিত করুন মডেলটি ইমপোর্ট হয়েছে
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    /**
     * নির্দিষ্ট প্রোডাক্টের সব ভ্যারিয়েন্ট ফেচ করা
     */
    public function getProductVariants($id)
    {
        // with() এর ভেতর রিলেশনগুলো আপনার ProductVariant মডেলে থাকতে হবে
        $variants = ProductVariant::with(['size', 'color', 'status'])
                    ->where('product_id', $id)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $variants
        ]);
    }

    /**
     * Bulk Store Logic
     */
    public function storeBulkVariants(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_ids'  => 'required|array',
            'size_ids'   => 'required|array',
            'base_price' => 'required|numeric',
            'status_id'  => 'required|exists:variant_statuses,id'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->color_ids as $colorId) {
                foreach ($request->size_ids as $sizeId) {
                    
                    $exists = ProductVariant::where('product_id', $request->product_id)
                        ->where('color_id', $colorId)
                        ->where('size_id', $sizeId)
                        ->exists();

                    if (!$exists) {
                        $sku = 'P' . $request->product_id . '-C' . $colorId . '-S' . $sizeId . '-' . Str::upper(Str::random(4));

                        ProductVariant::create([
                            'product_id' => $request->product_id,
                            'color_id'   => $colorId,
                            'size_id'    => $sizeId,
                            'status_id'  => $request->status_id,
                            'sale_price' => $request->base_price,
                            'sku'        => $sku,
                            'stock'      => 0,
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Variants created!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * স্টক আপডেট করা
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $variant = ProductVariant::findOrFail($id);
        $variant->update(['stock' => $request->stock]);

        return response()->json(['success' => true, 'message' => 'Stock updated.']);
    }

    /**
     * ডিলিট করা
     */
    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variant->delete();
        return response()->json(['success' => true, 'message' => 'Deleted.']);
    }
}