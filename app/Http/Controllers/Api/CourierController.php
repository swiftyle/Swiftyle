<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CourierController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'courier_categories_id' => 'required|exists:courier_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $userId = $user->id;
        $userEmail = $user->email;

        // Ensure user ID is not null before proceeding
        if (!$userId) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Add user email as modified_by
        $validated['modified_by'] = $userEmail;

        if ($request->hasFile('logo')) {
            $filePath = $request->file('logo')->store('images', 'public');
            $validated['logo'] = $filePath;
        }

        // Create the courier
        $courier = Courier::create([
            'name' => $validated['name'],
            'logo' => $validated['logo'],
            'courier_categories_id' => $validated['courier_categories_id'],
        ]);

        return response()->json([
            'message' => 'Kurir berhasil dibuat',
            'data' => $courier
        ], 201);
    }

    public function read()
    {
        $couriers = Courier::with('category')->get();

        return response()->json([
            'message' => 'Data Kurir',
            'data' => $couriers
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'courier_categories_id' => 'sometimes|exists:courier_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $userId = $user->id;
        $userEmail = $user->email;

        // Ensure user ID is not null before proceeding
        if (!$userId) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Add user email as modified_by
        $validated['modified_by'] = $userEmail;
        // Find the courier
        $courier = Courier::find($id);

        if ($courier) {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if (!is_null($courier->image)) {
                    Storage::disk('public')->delete($courier->image);
                    // Store new image
                    $filePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $filePath;
                } else {
                    $filePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $filePath;

                }
            }
        }

        if ($courier) {
            // Update the courier
            $courier->update($validated);

            return response()->json([
                'message' => 'Kurir dengan id: ' . $id . ' berhasil diupdate',
                'data' => $courier
            ], 200);
        }

        return response()->json([
            'message' => 'Kurir dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete($id)
    {
        // Find the courier
        $courier = Courier::find($id);

        if ($courier) {
            // Delete the courier
            $courier->delete();

            return response()->json([
                'message' => 'Kurir dengan id: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Kurir dengan id: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
