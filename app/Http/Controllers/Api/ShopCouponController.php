<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopCoupon;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopCouponController extends Controller
{
    public function create(Request $request)
    {

        $user = $request->user();

        // Memeriksa apakah user ditemukan
        if (!$user) {
            return response()->json([
                'msg' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Memastikan bahwa hanya seller yang dapat membuat kupon toko
        if ($user->role !== 'Seller') {
            return response()->json([
                'msg' => 'Hanya seller yang bisa membuat kupon toko'
            ], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'shop_id' => 'required|exists:shops,id,user_id,' . $user->id,
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:shop_coupons,code',
            'type' => 'required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the shop coupon
        $shopCoupon = ShopCoupon::create([
            'shop_id' => $validated['shop_id'],
            'name' => $validated['name'],
            'code' => $validated['code'],
            'type' => $validated['type'],
            'discount_amount' => $validated['discount_amount'],
            'max_uses' => $validated['max_uses'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return response()->json([
            'message' => 'Kupon Toko berhasil dibuat',
            'data' => $shopCoupon
        ], 201);
    }


    public function read(Request $request)
    {
        $user = $request->user();
        // Mendapatkan user dari decoded token

        // Memeriksa apakah user ditemukan
        if (!$user) {
            return response()->json([
                'msg' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Memastikan bahwa hanya admin atau seller yang dapat mengakses
        if ($user->role == 'Admin') {
            // Admin dapat mengakses semua data kupon toko
            $shopCoupons = ShopCoupon::with('shop')->get();
        } elseif ($user->role == 'Seller') {
            // Seller hanya dapat mengakses data kupon toko yang terkait dengan toko mereka
            $shopCoupons = ShopCoupon::with('shop')
                ->whereHas('shop', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->get();
        } else {
            // Selain Admin dan Seller tidak memiliki izin untuk mengakses data ini
            return response()->json([
                'msg' => 'Anda tidak memiliki izin untuk mengakses data ini'
            ], 403);
        }

        return response()->json([
            'message' => 'Data Kupon Toko',
            'data' => $shopCoupons
        ], 200);
    }


    public function update(Request $request, $id)
    {
            $user = $request->user();

            // Memeriksa apakah user ditemukan
            if (!$user) {
                return response()->json([
                    'msg' => 'Pengguna tidak ditemukan'
                ], 404);
            }

            // Memastikan bahwa hanya seller yang dapat mengupdate kupon toko
            if ($user->role !== 'Seller') {
                return response()->json([
                    'msg' => 'Hanya seller yang bisa mengupdate kupon toko'
                ], 403);
            }

            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|unique:shop_coupons,code,' . $id,
                'type' => 'sometimes|required|in:percentage_discount,fixed_discount',
                'discount_amount' => 'sometimes|required|numeric',
                'max_uses' => 'sometimes|integer',
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages())->setStatusCode(422);
            }

            $validated = $validator->validated();
            $shopCoupon = ShopCoupon::find($id);

            if ($shopCoupon) {
                // Make sure the user has permission to update this coupon
                $shopCoupon = ShopCoupon::with('shop')
                    ->whereHas('shop', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })->first();

                if (!$shopCoupon) {
                    return response()->json([
                        'message' => 'Kupon Toko dengan id: ' . $id . ' tidak ditemukan atau tidak memiliki izin untuk diperbarui'
                    ], 404);
                }

                $shopCoupon->update([
                    'name' => $validated['name'] ?? $shopCoupon->name,
                    'code' => $validated['code'] ?? $shopCoupon->code,
                    'type' => $validated['type'] ?? $shopCoupon->type,
                    'discount_amount' => $validated['discount_amount'] ?? $shopCoupon->discount_amount,
                    'max_uses' => $validated['max_uses'] ?? $shopCoupon->max_uses,
                    'start_date' => $validated['start_date'] ?? $shopCoupon->start_date,
                    'end_date' => $validated['end_date'] ?? $shopCoupon->end_date,
                ]);

                return response()->json([
                    'message' => 'Kupon Toko dengan id: ' . $id . ' berhasil diupdate',
                    'data' => $shopCoupon
                ], 200);
            }

            return response()->json([
                'message' => 'Kupon Toko dengan id: ' . $id . ' tidak ditemukan'
            ], 404);
    }


    public function delete(Request $request, $id)
    {
        try {
            // Decode JWT token dari header Authorization
            $token = $request->bearerToken();
            $data = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            // Mendapatkan user dari decoded token
            $user = User::find($data->id);

            // Memeriksa apakah user ditemukan
            if (!$user) {
                return response()->json([
                    'msg' => 'Pengguna tidak ditemukan'
                ], 404);
            }

            // Memastikan bahwa hanya seller yang dapat menghapus kupon toko
            if ($user->role !== 'Seller') {
                return response()->json([
                    'msg' => 'Hanya seller yang bisa menghapus kupon toko'
                ], 403);
            }

            // Logging to verify user

            // Retrieve the shop coupon ensuring it belongs to the user's shop
            $shopCoupon = ShopCoupon::where('id', $id)
                ->whereHas('shop', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->first();

            // Memeriksa apakah kupon ditemukan dan user memiliki izin
            if (!$shopCoupon) {
                return response()->json([
                    'message' => 'Kupon Toko dengan id: ' . $id . ' tidak ditemukan atau tidak memiliki akses'
                ], 404);
            }

            // Logging to verify coupon before deletion

            // Hapus kupon toko
            $shopCoupon->delete();

            return response()->json([
                'message' => 'Kupon Toko dengan id: ' . $id . ' berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            // Logging exception
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


}
