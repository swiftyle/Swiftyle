<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MainCategoryController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        // User is authenticated, proceed with validation and data creation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
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

        // Create the main category
        $mainCategory = MainCategory::create($validated);

        return response()->json([
            'message' => "Data Berhasil Disimpan",
            'data' => $mainCategory
        ], 200);
    }

    public function read()
    {
        $mainCategories = MainCategory::all();
        return response()->json([
            'msg' => 'Data Kategori Utama Keseluruhan',
            'data' => $mainCategories
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
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

        $mainCategory = MainCategory::find($id);

        if ($mainCategory) {
            $mainCategory->update($validated);

            return response()->json([
                'msg' => 'Data dengan id: ' . $id . ' berhasil diupdate',
                'data' => $mainCategory
            ], 200);
        }

        return response()->json([
            'msg' => 'Data dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete($id)
    {
        $mainCategory = MainCategory::find($id);

        if ($mainCategory) {
            $mainCategory->delete();

            return response()->json([
                'msg' => 'Data kategori utama dengan ID: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'msg' => 'Data kategori utama dengan ID: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
