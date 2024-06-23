<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Check if the user is a seller
        if ($user->role !== 'Seller') {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the color record
        $color = Color::create($validated);

        return response()->json([
            'message' => 'Warna berhasil dibuat',
            'data' => $color
        ], 201);
    }

    public function read()
    {
        // Ambil semua data style
        $colors = color::all();

        return response()->json([
            'message' => 'Data semua color',
            'data' => $colors
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Check if the user is a seller
        if ($user->role !== 'Seller') {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }

        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Warna tidak ditemukan'], 404);
        }

        // // Check if the color is associated with the seller's shop
        // if (!$this->isColorOwnedBySeller($user, $color)) {
        //     return response()->json(['message' => 'Anda tidak memiliki izin untuk mengubah warna ini'], 403);
        // }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'stock' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Update the color record
        $color->update($validated);

        return response()->json([
            'message' => 'Warna berhasil diupdate',
            'data' => $color
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        // Check if the user is a seller
        if ($user->role !== 'Seller') {
            return response()->json(['message' => 'You do not have permission to perform this action'], 403);
        }

        $color = Color::find($id);

        if (!$color) {
            return response()->json(['message' => 'Warna tidak ditemukan'], 404);
        }

        // Check if the color is associated with the seller's shop
        // if (!$this->isColorOwnedBySeller($user, $color)) {
        //     return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus warna ini'], 403);
        // }

        // Delete the color record
        $color->delete();

        return response()->json([
            'message' => 'Warna berhasil dihapus'
        ], 200);
    }

    private function isColorOwnedBySeller($user, $color)
    {
        // Check if the user has shops
        if (empty($user->shops) || !is_iterable($user->shops)) {
            return false;
        }

        // Traverse the relationships to check if the color is associated with the seller's shop
        foreach ($user->shops as $shop) {
            if (empty($shop->products) || !is_iterable($shop->products)) {
                continue;
            }

            foreach ($shop->products as $product) {
                if (empty($product->productSizes) || !is_iterable($product->productSizes)) {
                    continue;
                }

                foreach ($product->productSizes as $productSize) {
                    if (empty($productSize->size->sizeColors) || !is_iterable($productSize->size->sizeColors)) {
                        continue;
                    }

                    foreach ($productSize->size->sizeColors as $sizeColor) {
                        if ($sizeColor->color_id == $color->id) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

}
