<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class WishlistItemController extends Controller
{
    public function create(Request $request)
    {
        try {
            $user = $request->user();

            // Find or create the wishlist associated with the user
            $wishlist = Wishlist::firstOrCreate(['user_id' => $user->id]);
            Log::info('Create WishlistItem: Wishlist Found or Created', ['wishlist_id' => $wishlist->id]);

            // Validate request data
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
            ]);

            if ($validator->fails()) {
                Log::warning('Create WishlistItem: Validation Failed', ['errors' => $validator->messages()]);
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $validated = $validator->validated();

            // Check if the product already exists in the wishlist
            $existingWishlistItem = WishlistItem::where('wishlist_id', $wishlist->id)
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingWishlistItem) {
                Log::info('Create WishlistItem: Product already in wishlist', ['wishlist_item_id' => $existingWishlistItem->id]);
                return response()->json(['message' => 'Product already in wishlist', 'data' => $existingWishlistItem], 200);
            }

            // Create new wishlist item
            $newWishlistItem = new WishlistItem();
            $newWishlistItem->wishlist_id = $wishlist->id;
            $newWishlistItem->product_id = $validated['product_id'];
            $newWishlistItem->save();

            Log::info('Create WishlistItem: New Wishlist Item Created', ['wishlist_item_id' => $newWishlistItem->id]);

            return response()->json(['message' => 'Wishlist item created successfully', 'data' => $newWishlistItem], 201);
        } catch (\Exception $e) {
            Log::error('Create WishlistItem: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create wishlist item'], 500);
        }
    }

    public function read(Request $request)
    {
        try {
            $user = $request->user();

            // Find the wishlist associated with the user
            $wishlist = Wishlist::where('user_id', $user->id)->first();

            if (!$wishlist) {
                return response()->json(['message' => 'Wishlist not found for this user'], 404);
            }

            // Fetch wishlist items for the found wishlist
            $wishlistItems = WishlistItem::where('wishlist_id', $wishlist->id)->get();
            Log::info('Read WishlistItems: Items Fetched', ['count' => $wishlistItems->count()]);

            return response()->json(['message' => 'Wishlist items fetched successfully', 'data' => $wishlistItems], 200);
        } catch (\Exception $e) {
            Log::error('Read WishlistItems: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch wishlist items'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            Log::info('Update WishlistItem: User ID', ['user_id' => $user->id, 'wishlist_item_id' => $id]);

            $validator = Validator::make($request->all(), [
                'product_id' => 'exists:products,id',
            ]);

            if ($validator->fails()) {
                Log::warning('Update WishlistItem: Validation Failed', ['errors' => $validator->messages()]);
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $validated = $validator->validated();

            $wishlistItem = WishlistItem::find($id);
            if (!$wishlistItem) {
                Log::error('Update WishlistItem: Wishlist Item Not Found', ['wishlist_item_id' => $id]);
                return response()->json(['message' => 'Wishlist item not found'], 404);
            }

            // Check if the wishlist item belongs to the user's wishlist
            if ($wishlistItem->wishlist->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Update the wishlist item
            if (isset($validated['product_id'])) {
                $product = Product::find($validated['product_id']);
                if (!$product) {
                    Log::error('Update WishlistItem: Product Not Found', ['product_id' => $validated['product_id']]);
                    return response()->json(['message' => 'Product not found'], 404);
                }
                $wishlistItem->product_id = $validated['product_id'];
            }

            $wishlistItem->save();
            Log::info('Update WishlistItem: Wishlist Item Updated', ['wishlist_item_id' => $wishlistItem->id]);

            return response()->json(['message' => 'Wishlist item updated successfully', 'data' => $wishlistItem], 200);
        } catch (\Exception $e) {
            Log::error('Update WishlistItem: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update wishlist item'], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $user = $request->user();
            Log::info('Delete WishlistItem: User ID', ['user_id' => $user->id, 'wishlist_item_id' => $id]);

            $wishlistItem = WishlistItem::find($id);
            if (!$wishlistItem) {
                Log::error('Delete WishlistItem: Wishlist Item Not Found', ['wishlist_item_id' => $id]);
                return response()->json(['message' => 'Wishlist item not found'], 404);
            }

            // Check if the wishlist item belongs to the user's wishlist
            if ($wishlistItem->wishlist->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $wishlistItem->delete();

            Log::info('Delete WishlistItem: Wishlist Item Deleted', ['wishlist_item_id' => $wishlistItem->id]);

            return response()->json(['message' => 'Wishlist item deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Delete WishlistItem: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete wishlist item'], 500);
        }
    }
}

