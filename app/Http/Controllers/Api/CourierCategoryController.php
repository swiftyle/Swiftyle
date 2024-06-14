<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourierCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourierCategoryController extends Controller
{
    public function create(Request $request)
    {

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'courier_costs' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the courier category
        $courierCategory = CourierCategory::create([
            'name' => $validated['name'],
            'courier_costs' => $validated['courier_costs'],
        ]);

        return response()->json([
            'message' => 'Kategori Kurir berhasil dibuat',
            'data' => $courierCategory
        ], 201);
    }

    public function read()
    {
        $courierCategories = CourierCategory::all();

        return response()->json([
            'message' => 'Data Kategori Kurir',
            'data' => $courierCategories
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'courier_costs' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Find the courier category
        $courierCategory = CourierCategory::find($id);

        if ($courierCategory) {
            // Update the courier category
            $courierCategory->update($validated);

            return response()->json([
                'message' => 'Kategori Kurir dengan id: ' . $id . ' berhasil diupdate',
                'data' => $courierCategory
            ], 200);
        }

        return response()->json([
            'message' => 'Kategori Kurir dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete($id)
    {
        // Find the courier category
        $courierCategory = CourierCategory::find($id);

        if ($courierCategory) {
            // Delete the courier category
            $courierCategory->delete();

            return response()->json([
                'message' => 'Kategori Kurir dengan id: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Kategori Kurir dengan id: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
