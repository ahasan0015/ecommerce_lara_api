<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderControllerAdmin extends Controller
{
    // OrderController.php

    public function index()
    {
        // all order with customer details
        $orders = Order::with('user')->latest()->get();
        return response()->json($orders);
    }

    public function show($id)
    {
        try {
            // অর্ডার ডিটেইলস পেজে 'Order' মডেল লোড করতে হবে, 'Product' নয়
            $order = Order::with([
                'user',
                'items.product',
                'items.variant.size',
                'items.variant.color'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data'    => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order Not Found: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status_id' => 'required|integer|exists:order_statuses,id'
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {

                // Load order with items
                $order = Order::with('items')->findOrFail($id);

                $oldStatus = $order->order_status_id;
                $newStatus = (int) $request->order_status_id;

                /**
                 * Logic 1: Decrease stock when order is delivered
                 * If order was not delivered before and now marked as Delivered (4)
                 */
                if ($oldStatus != 4 && $newStatus == 4) {
                    foreach ($order->items as $item) {
                        $variant = ProductVariant::find($item->product_variant_id);

                        if ($variant) {
                            $variant->decrement('stock', $item->quantity);
                        }
                    }
                }

                /**
                 * Logic 2: Restore stock when order is cancelled
                 * If order was delivered (4) but now changed to Cancelled (5)
                 */
                if ($oldStatus == 4 && $newStatus == 5) {
                    foreach ($order->items as $item) {
                        $variant = ProductVariant::find($item->product_variant_id);

                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    }
                }

                // Update order status
                $order->order_status_id = $newStatus;
                $order->save();

                return response()->json([
                    'status' => 'success',
                    'message' => $this->getStatusMessage($newStatus),
                    'new_status_id' => $order->order_status_id
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Process could not be completed: ' . $e->getMessage()
            ], 500);
        }
    }


    private function getStatusMessage($statusId)
    {
        return match ($statusId) {
            4 => 'অর্ডারটি ডেলিভারড এবং স্টক আপডেট করা হয়েছে।',
            5 => 'অর্ডারটি ক্যানসেল এবং স্টক ফেরত দেওয়া হয়েছে।',
            default => 'অর্ডার স্ট্যাটাস সফলভাবে পরিবর্তন হয়েছে।',
        };
    }
}
