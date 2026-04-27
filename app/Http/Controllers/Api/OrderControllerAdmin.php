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

            $order = Order::with([
                'user',
                'shippingAddress',
                'status', // Order (Pending/Processing)
                'paymentStatus', // Order (Paid/Unpaid)
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
                $order = Order::with('items')->findOrFail($id);
                $oldStatus = (int) $order->order_status_id;
                $newStatus = (int) $request->order_status_id;

                // if status not change don't change stock
                if ($oldStatus === $newStatus) {
                    return response()->json(['message' => 'Status is already the same.'], 200);
                }

                /**
                 * Logic: If orderCancelled stock increment
                 */
                if ($oldStatus != 4 && $newStatus == 4) {
                    foreach ($order->items as $item) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    }
                }

                /**
                 * Logic: if stock decrement status change to Pending/Processing/Completed
                 */
                if ($oldStatus == 4 && $newStatus != 4) {
                    foreach ($order->items as $item) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            if ($variant->stock >= $item->quantity) {
                                $variant->decrement('stock', $item->quantity);
                            } else {
                                //if not stock available rollback
                                throw new \Exception("Product not in stock");
                            }
                        }
                    }
                }

                // Order Status
                $order->order_status_id = $newStatus;
                $order->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Status updated successfully to ' . $order->status->name,
                    'new_status_id' => $order->order_status_id
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Process failed: ' . $e->getMessage()
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
