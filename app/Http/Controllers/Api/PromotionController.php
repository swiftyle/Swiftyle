<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // User is authenticated, proceed with validation and data creation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage_discount,fixed_discount,buy_one_get_one',
            'discount_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Retrieve user ID and email
        $userId = $user->id;
        $userEmail = $user->email;

        // Ensure user ID is not null before proceeding
        if (!$userId) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Add user email as modified_by
        $validated['modified_by'] = $userEmail;

        // Create the promotion
        $promotion = Promotion::create($validated);

        return response()->json([
            'message' => "Data Berhasil Disimpan",
            'data' => $promotion
        ], 200);
    }

    public function read(Request $request)
    {
        $user = $request->user();
        $promotions = Promotion::all();
        return response()->json([
            'msg' => 'Data Promosi Keseluruhan',
            'data' => $promotions
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:percentage_discount,fixed_discount,buy_one_get_one',
            'discount_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Retrieve user ID and email
        $userId = $user->id;
        $userEmail = $user->email;

        // Ensure user ID is not null before proceeding
        if (!$userId) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Add user email as modified_by
        $validated['modified_by'] = $userEmail;

        $promotion = Promotion::find($id);

        if ($promotion) {
            $promotion->update($validated);

            return response()->json([
                'msg' => 'Data dengan id: ' . $id . ' berhasil diupdate',
                'data' => $promotion
            ], 200);
        }

        return response()->json([
            'msg' => 'Data dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        $promotion = Promotion::find($id);

        if ($promotion) {
            $promotion->delete();

            return response()->json([
                'msg' => 'Data promosi dengan ID: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'msg' => 'Data promosi dengan ID: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
