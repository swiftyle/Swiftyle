<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    public function index()
    {
        $productSizes = ProductSize::all();
        return response()->json(['data' => $productSizes], 200);
    }

    public function show($id)
    {
        $productSize = ProductSize::findOrFail($id);
        return response()->json(['data' => $productSize], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_uuid' => 'required|uuid',
            'size' => 'required|string',
            'stock' => 'required|integer',
        ]);

        $productSize = ProductSize::create($validatedData);

        return response()->json(['message' => 'Product size created successfully', 'data' => $productSize], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_uuid' => 'uuid',
            'size' => 'string',
            'stock' => 'integer',
        ]);

        $productSize = ProductSize::findOrFail($id);
        $productSize->update($validatedData);

        return response()->json(['message' => 'Product size updated successfully', 'data' => $productSize], 200);
    }

    public function destroy($id)
    {
        $productSize = ProductSize::findOrFail($id);
        $productSize->delete();

        return response()->json(['message' => 'Product size deleted successfully'], 200);
    }
}
