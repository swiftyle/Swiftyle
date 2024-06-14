<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $user = $request->user();

        if ($user->role !== 'Seller') {
            return response()->json(['message' => 'Hanya seller yang bisa membuat toko'], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $userId = $user->id;


        // Ensure user ID is not null before proceeding
        if (!$userId) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Add user email as modified_by
        $validated['user_id'] = $userId;

        if ($request->hasFile('logo')) {
            $filePath = $request->file('logo')->store('images', 'public');
            $validated['logo'] = $filePath;
        }

        // Create the shop record
        $shop = Shop::create($validated);

        return response()->json([
            'message' => 'Toko berhasil dibuat',
            'data' => $shop
        ], 201);
    }

    public function read(Request $request)
    {
        $user = $request->user();

        // Mendapatkan user dari decoded token
        $user = User::find($user->id);

        // Memeriksa apakah user ditemukan
        if (!$user) {
            return response()->json([
                'msg' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Memastikan bahwa hanya admin atau seller yang dapat mengakses
        if ($user->role == 'Admin') {
            // Admin dapat mengakses semua data toko
            $shops = Shop::all();
        } elseif ($user->role == 'Seller') {
            // Seller hanya dapat mengakses data toko mereka sendiri
            $shops = Shop::where('user_id', $user->id)->get();
            return response()->json([
                'message' => 'Data toko pengguna',
                'data' => $shops
            ], 200);
        } else {
            // Selain Admin dan Seller tidak memiliki izin untuk mengakses data ini
            return response()->json([
                'msg' => 'Anda tidak memiliki izin untuk mengakses data ini'
            ], 403);
        }

        return response()->json([
            'message' => 'Data semua toko',
            'data' => $shops
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Find the shop record to update
        $shop = $user->shops()->find($id);

        if (!$shop) {
            return response()->json(['message' => 'Toko tidak ditemukan'], 404);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'rating' => 'sometimes|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        $shop = Shop::find($id);
        if ($shop) {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if (!is_null($shop->image)) {
                    Storage::disk('public')->delete($shop->image);
                    // Store new image
                    $filePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $filePath;
                } else {

                    $filePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $filePath;

                }
            }

            $shop->update($validated);

            return response()->json([
                'msg' => 'Data dengan id: ' . $id . ' berhasil diupdate',
                'data' => $shop
            ], 200);
        }

        return response()->json([
            'msg' => 'Data dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        try {
            // Decode JWT token to get user data
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            if ($user->role == 'Seller') {
                // Seller hanya dapat mengakses data toko mereka sendiri
                $shops = Shop::where('user_id', $user->id)->delete();
                return response()->json([
                    'message' => 'Toko berhasil dihapus'
                ], 200);
            } else {
                // Selain Admin dan Seller tidak memiliki izin untuk mengakses data ini
                return response()->json([
                    'msg' => 'Anda tidak memiliki izin untuk mengakses data ini'
                ], 403);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }

}