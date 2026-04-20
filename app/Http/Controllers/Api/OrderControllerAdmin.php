<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
        // each order details
        $order = Order::with(['user', 'items.product', 'items.variant'])->findOrFail($id);
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status_id' => 'required|integer|exists:order_statuses,id'
        ]);

        try {
            $order = Order::findOrFail($id);

            //  order_status_id update in database
            $order->order_status_id = $request->order_status_id;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Order Status Change Successfully',
                'new_status_id' => $order->order_status_id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error ' . $e->getMessage()
            ], 500);
        }
    }
}
