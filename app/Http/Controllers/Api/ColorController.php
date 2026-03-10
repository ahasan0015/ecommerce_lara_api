<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Color::orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:30|unique:colors,name',
            'hex_code' => 'required|string|max:10'
        ]);

        $color = Color::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Color created successfully',
            'data' => $color
        ], 201);
    }

    public function show(Color $color)
    {
        return response()->json(['success' => true, 'data' => $color]);
    }

    public function update(Request $request, Color $color)
    {
        $validated = $request->validate([
            'name' => "required|string|max:30|unique:colors,name,{$color->id}",
            'hex_code' => 'required|string|max:10'
        ]);

        $color->update($validated);

        return response()->json(['success' => true, 'message' => 'Color updated', 'data' => $color]);
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return response()->json(['success' => true, 'message' => 'Color deleted']);
    }
}