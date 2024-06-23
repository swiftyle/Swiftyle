<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppCoupon;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppCouponController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $user = $request->user();

        // User is authenticated, proceed with validation and data creation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:app_coupons,code',
            'type' => 'required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'required|numeric',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the coupon with user relationship
        $coupon = $user->coupons()->create($validated);

        return response()->json([
            'message' => 'Kupon berhasil dibuat',
            'data' => $coupon
        ], 201);
    }

    public function read(Request $request)
    {
        $user = $request->user();
        // Ambil semua data coupon
        $coupons = AppCoupon::all();

        return response()->json([
            'message' => 'Data semua kupon',
            'data' => $coupons
        ], 200);
    }

    public function readByName(Request $request, $name)
    {
        $user = $request->user();
        // Ambil data coupon berdasarkan nama
        $coupon = AppCoupon::where('name', $name)->first();

        if (!$coupon) {
            return response()->json(['message' => 'Kupon tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => $coupon
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Decode JWT token to get user data
        $user = $request->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:app_coupons,code,' . $id,
            'type' => 'sometimes|required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'sometimes|required|numeric',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Dapatkan data coupon yang akan diupdate
        $coupon = $user->coupons()->find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Kupon tidak ditemukan'], 404);
        }

        // Update data coupon sesuai dengan data yang divalidasi
        $validated = $validator->validated();
        $coupon->update($validated);

        return response()->json([
            'message' => 'Kupon berhasil diupdate',
            'data' => $coupon
        ], 200);
    }

    public function delete(Request $request, $id)
    {
   
        $user= $request->user();
        // Cari coupon yang akan dihapus
        $coupon = $user->coupons()->find($id);

        if (!$coupon) {
            return response()->json(['message' => 'Kupon tidak ditemukan'], 404);
        }

        // Hapus coupon
        $coupon->delete();

        return response()->json([
            'message' => 'Kupon berhasil dihapus'
        ], 200);
    }
}

