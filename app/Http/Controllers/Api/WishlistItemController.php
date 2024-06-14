<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\WishlistItem;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistItemController extends Controller
{
    public function create(Request $request)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'wishlist_id' => 'required|exists:wishlist,id',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the wishlist item
        $wishlistItem = WishlistItem::create([
            'wishlist_id' => $request->input('wishlist_id'),
            'product_id' => $request->input('product_id'),
        ]);

        return response()->json([
            'message' => 'Wishlist item created successfully',
            'data' => $wishlistItem
        ], 201);
    }

    public function read(Request $request)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch wishlist items associated with user's wishlists
        $wishlistItems = WishlistItem::whereIn('wishlist_id', function ($query) use ($user) {
            $query->select('id')
                ->from('wishlist')
                ->where('user_id', $user->id);
        })->get();

        return response()->json([
            'message' => 'Wishlist items fetched successfully',
            'data' => $wishlistItems
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the wishlist item
        $wishlistItem = WishlistItem::find($id);

        if ($wishlistItem) {
            // Check if the wishlist item belongs to the authenticated user
            $wishlist = Wishlist::where('id', $wishlistItem->wishlist_id)
                ->where('user_id', $user->id)
                ->first();

            if ($wishlist) {
                // Delete the wishlist item
                $wishlistItem->delete();

                return response()->json([
                    'message' => 'Wishlist item deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Unauthorized to delete this wishlist item'
                ], 403);
            }
        }

        return response()->json([
            'message' => 'Wishlist item not found'
        ], 404);
    }
}
