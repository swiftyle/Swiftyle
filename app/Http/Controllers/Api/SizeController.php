<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function create(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the size
        $size = Size::create([
            'name' => $request->input('name'),
            'stock' => $request->input('stock'),
        ]);

        return response()->json([
            'message' => 'Size created successfully',
            'data' => $size
        ], 201);
    }

    public function readAll()
    {
        // Fetch all sizes
        $sizes = Size::all();

        return response()->json([
            'message' => 'Sizes fetched successfully',
            'data' => $sizes
        ], 200);
    }

    public function read($id)
    {
        // Fetch size by ID
        $sizes = Size::find($id);

        if (!$sizes) {
            return response()->json(['message' => 'Size not found'])->setStatusCode(404);
        }

        return response()->json([
            'message' => 'Size fetched successfully',
            'data' => $sizes
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'stock' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the size
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Size not found'])->setStatusCode(404);
        }

        // Update the size
        $size->name = $request->input('name', $size->name);
        $size->stock = $request->input('stock', $size->stock);
        $size->save();

        return response()->json([
            'message' => 'Size updated successfully',
            'data' => $size
        ], 200);
    }

    public function delete($id)
    {
        // Find the size
        $size = Size::find($id);

        if (!$size) {
            return response()->json(['message' => 'Size not found'])->setStatusCode(404);
        }

        // Delete the size
        $size->delete();

        return response()->json(['message' => 'Size deleted successfully'], 200);
    }
}
