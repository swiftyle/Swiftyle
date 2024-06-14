<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Preference;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PreferenceController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // User is authenticated, proceed with validation and data creation
        $validator = Validator::make($request->all(), [
            'style_id' => 'required|exists:styles,id',
            'genre_id' => 'required|exists:genres,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the preference with user relationship
        $preference = $user->preferences()->create($validated);

        return response()->json([
            'message' => 'Preference berhasil dibuat',
            'data' => $preference
        ], 201);
    }

    public function read()
    {
        // Ambil semua data preference
        $preferences = Preference::all();

        return response()->json([
            'message' => 'Data semua preference',
            'data' => $preferences
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Decode JWT token to get user data
        $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'style_id' => 'sometimes|required|exists:styles,id',
            'genre_id' => 'sometimes|required|exists:genres,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Dapatkan data preference yang akan diupdate
        $preference = $user->preferences()->find($id);

        if (!$preference) {
            return response()->json(['message' => 'Preference tidak ditemukan'], 404);
        }

        // Update data preference sesuai dengan data yang divalidasi
        $validated = $validator->validated();
        $preference->update($validated);

        return response()->json([
            'message' => 'Preference berhasil diupdate',
            'data' => $preference
        ], 200);
    }

    public function delete($id)
    {
        // Decode JWT token to get user data
        $data = JWT::decode(request()->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
        $user = User::find($data->id);

        // Cari preference yang akan dihapus
        $preference = $user->preferences()->find($id);

        if (!$preference) {
            return response()->json(['message' => 'Preference tidak ditemukan'], 404);
        }

        // Hapus preference
        $preference->delete();

        return response()->json([
            'message' => 'Preference berhasil dihapus'
        ], 200);
    }

    public function saveUserPreferences(Request $request, $orderId)
    {
        $user = $request->user();
        
        // Find the order by orderId
        $order = Order::findOrFail($orderId);

        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to access this order'], 403);
        }

        // Get the product from the order
        $product = $order->shipping->checkout->cart->cartItems()->first()->product;

        if (!$product) {
            return response()->json(['message' => 'Product not found in the order'], 404);
        }

        // Get the style_id associated with the product
        $styleId = $product->styles()->first()->id;

        // Save the preference (assuming user_preferences is a pivot table)
        $user->preferences()->syncWithoutDetaching([$styleId]);

        return response()->json(['message' => 'User preferences updated successfully'], 200);
    }
}

