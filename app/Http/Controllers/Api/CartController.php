<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Ensure user ID is not null before proceeding
        if (!$user || !$user->id) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Ensure the user is not an admin
        if ($user->role == 'Admin') {
            return response()->json(['message' => 'Admins are not allowed to create a cart'], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'app_coupon_id' => 'nullable|exists:app_coupons,id',
            'discount' => 'required|numeric',
            'total_discount' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Add user ID to the validated data
        $validated['user_id'] = $user->id;

        // Create the cart
        $cart = Cart::create([
            'user_id' => $validated['user_id'],
            'app_coupon_id' => $validated['app_coupon_id'],
            'discount' => $validated['discount'],
            'total_discount' => $validated['total_discount'],
            'total_price' => $validated['total_price'],
        ]);

        return response()->json([
            'message' => 'Cart created successfully',
            'data' => $cart
        ], 201);
    }


    public function read(Request $request)
    {
        // Mendecode JWT token dari header Authorization
        $user = $request->user();

        // Memeriksa apakah user ditemukan
        if (!$user) {
            return response()->json([
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        // Mendapatkan keranjang berdasarkan user_id pengguna yang terautentikasi
        $carts = Cart::with(['user', 'appCoupon'])->where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Your Carts',
            'data' => $carts
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'app_coupon_id' => 'nullable|exists:app_coupons,id',
            'discount' => 'sometimes|required|numeric',
            'total_discount' => 'sometimes|required|numeric',
            'total_price' => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Ensure user ID is not null before proceeding
        if (!$user || !$user->id) {
            return response()->json(['message' => 'User ID not found'], 401);
        }

        // Find the cart by ID
        $cart = Cart::find($id);

        if ($cart) {
            // Check if the cart belongs to the authenticated user
            if ($cart->user_id != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Update the cart
            $cart->update([
                'app_coupon_id' => $validated['app_coupon_id'] ?? $cart->app_coupon_id,
                'discount' => $validated['discount'] ?? $cart->discount,
                'total_discount' => $validated['total_discount'] ?? $cart->total_discount,
                'total_price' => $validated['total_price'] ?? $cart->total_price,
            ]);

            return response()->json([
                'message' => 'Cart updated successfully',
                'data' => $cart
            ], 200);
        }

        return response()->json([
            'message' => 'Cart not found'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        // Find the cart
        $cart = Cart::find($id);

        if ($cart) {
            // Check if the cart belongs to the authenticated user
            if ($cart->user_id != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Delete the cart
            $cart->delete();

            return response()->json([
                'message' => 'Cart deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Cart not found'
        ], 404);
    }
}
