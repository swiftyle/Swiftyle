<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppCoupon;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function create(Request $request)
    {
        try {
            $user = $request->user();

            // Ensure user ID is valid and not an admin
            if (!$user || !$user->id) {
                return response()->json(['message' => 'User ID not found'], 401);
            }

            if ($user->role == 'Admin') {
                return response()->json(['message' => 'Admins are not allowed to create a cart'], 403);
            }

            // Validate incoming request
            $validator = Validator::make($request->all(), [
                'app_coupon_id' => 'nullable|exists:app_coupons,id',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 422);
            }

            // Add user ID to the validated data
            $validated = $validator->validated();
            $validated['user_id'] = $user->id;

            // Create the cart
            $cart = Cart::create([
                'user_id' => $validated['user_id'],
                'app_coupon_id' => $validated['app_coupon_id'] ?? null,
                'discount' => 0,
                'total_discount' => 0,
                'total_price' => 0,
            ]);

            $this->updateCartTotals($cart);

            return response()->json([
                'message' => 'Cart created successfully',
                'data' => $cart
            ], 201);
        } catch (\Exception $e) {
            Log::error('CartController@create: Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to create cart', 'error' => $e->getMessage()], 500);
        }
    }

    public function read(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Fetch carts associated with the user
            $carts = Cart::where('user_id', $user->id)->get();

            // Load related items for each cart
            foreach ($carts as $cart) {
                $cart->cartItems = CartItem::where('cart_id', $cart->id)->get();
            }

            return response()->json(['message' => 'Your Carts', 'data' => $carts], 200);
        } catch (\Exception $e) {
            Log::error('CartController@read: Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to fetch carts', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->id) {
                return response()->json(['message' => 'User ID not found'], 401);
            }

            $validator = Validator::make($request->all(), [
                'app_coupon_id' => 'sometimes|exists:app_coupons,id',
                'total_discount' => 'sometimes|numeric',
                'total_price' => 'sometimes|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 422);
            }

            $cart = Cart::find($id);

            if (!$cart) {
                return response()->json(['message' => 'Cart not found'], 404);
            }

            if ($cart->user_id != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validated = $validator->validated();

            $cart->update([
                'app_coupon_id' => $validated['app_coupon_id'] ?? $cart->app_coupon_id,
                'total_discount' => $validated['total_discount'] ?? $cart->total_discount,
                'total_price' => $validated['total_price'] ?? $cart->total_price,
            ]);

            $this->updateCartTotals($cart);

            return response()->json(['message' => 'Cart updated successfully', 'data' => $cart], 200);
        } catch (\Exception $e) {
            Log::error('CartController@update: Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update cart', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user || !$user->id) {
                return response()->json(['message' => 'User ID not found'], 401);
            }

            $cart = Cart::find($id);

            if (!$cart) {
                return response()->json(['message' => 'Cart not found'], 404);
            }

            if ($cart->user_id != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $cart->delete();

            return response()->json(['message' => 'Cart deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('CartController@delete: Error', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to delete cart', 'error' => $e->getMessage()], 500);
        }
    }

    private function updateCartTotals(Cart $cart)
    {
        try {
            $totalPrice = 0;
            $totalDiscount = 0;

            foreach ($cart->cartItems as $item) {
                // Add subtotal of each item to calculate total price
                $totalPrice += $item->subtotal;

                // Add discount of each item to calculate total discount
                $totalDiscount += $item->discount;
            }

            // Apply additional discount from app_coupon_id if available
            if ($cart->app_coupon_id) {
                $appCoupon = AppCoupon::find($cart->app_coupon_id);
                if ($appCoupon) {
                    // Calculate discount based on percentage of total price
                    $additionalDiscount = $totalPrice * ($appCoupon->discount_amount / 100);
                    $totalDiscount += $additionalDiscount;
                }
            }

            // Update cart totals
            $cart->update([
                'total_price' => $totalPrice - $totalDiscount,
                'total_discount' => $totalDiscount,
            ]);

            Log::info('UpdateCartTotals: Cart Totals Updated', [
                'cart_id' => $cart->id,
                'total_price' => $totalPrice - $totalDiscount,
                'total_discount' => $totalDiscount,
            ]);
        } catch (\Exception $e) {
            Log::error('UpdateCartTotals: Error', ['error' => $e->getMessage()]);
        }
    }
}

