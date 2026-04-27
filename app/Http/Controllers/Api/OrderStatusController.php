<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function index()
    {
        $statuses = OrderStatus::select('id', 'name')->get();
        return response()->json($statuses);
    }
}
