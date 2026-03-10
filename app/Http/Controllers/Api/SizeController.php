<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Size::orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20|unique:sizes,name',
        ]);

        $size = Size::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Size created successfully',
            'data' => $size
        ], 201);
    }

    public function show(Size $size)
    {
        return response()->json(['success' => true, 'data' => $size]);
    }

    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([
            'name' => "required|string|max:20|unique:sizes,name,{$size->id}",
        ]);

        $size->update($validated);

        return response()->json(['success' => true, 'message' => 'Size updated', 'data' => $size]);
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return response()->json(['success' => true, 'message' => 'Size deleted']);
    }
}