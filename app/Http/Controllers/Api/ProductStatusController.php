<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStatusController extends Controller
{
    public function index()
    {
        $productstatus = DB::table('product_statuses')
            ->select('product_statuses.*')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $productstatus
        ], 200);
    }
}