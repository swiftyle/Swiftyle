<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ShopCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
{
    public function create(Request $request)
    {
        try {
            $user = $request->user();

            // Find or create the cart associated with the user
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            Log::info('Create CartItem: Cart Found or Created', ['cart_id' => $cart->id]);

            // Validate request data
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:products,id',
                'shop_coupon_id' => 'nullable|exists:shop_coupons,id',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                Log::warning('Create CartItem: Validation Failed', ['errors' => $validator->messages()]);
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $validated = $validator->validated();

            // Check if the product already exists in the cart
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingCartItem) {
                // If the product already exists, update the quantity
                $existingCartItem->quantity += $validated['quantity'];
                $existingCartItem->subtotal = $existingCartItem->price * $existingCartItem->quantity;

                // Apply shop coupon if provided
                if (isset($validated['shop_coupon_id'])) {
                    $coupon = ShopCoupon::find($validated['shop_coupon_id']);
                    if ($coupon) {
                        $existingCartItem->shop_coupon_id = $coupon->id;
                        $existingCartItem->discount = $this->calculateDiscount($existingCartItem->subtotal, $coupon);
                    } else {
                        $existingCartItem->discount = 0;
                    }
                } else {
                    $existingCartItem->discount = 0;
                }

                $existingCartItem->save();
                Log::info('Create CartItem: Existing Cart Item Updated', ['cart_item_id' => $existingCartItem->id]);

                $this->updateCartTotals($cart);

                return response()->json(['message' => 'Cart item updated successfully', 'data' => $existingCartItem], 200);
            }

            // Get the product price
            $product = Product::findOrFail($validated['product_id']);
            $price = $product->price;

            // Create new cart item
            $newCartItem = new CartItem();
            $newCartItem->cart_id = $cart->id;
            $newCartItem->product_id = $validated['product_id'];
            $newCartItem->quantity = $validated['quantity'];
            $newCartItem->price = $price;
            $newCartItem->subtotal = $price * $validated['quantity'];

            // Apply shop coupon if provided
            if (isset($validated['shop_coupon_id'])) {
                $coupon = ShopCoupon::find($validated['shop_coupon_id']);
                if ($coupon) {
                    $newCartItem->shop_coupon_id = $coupon->id;
                    $newCartItem->discount = $this->calculateDiscount($newCartItem->subtotal, $coupon);
                } else {
                    $newCartItem->discount = 0;
                }
            } else {
                $newCartItem->discount = 0;
            }

            $newCartItem->save();
            Log::info('Create CartItem: New Cart Item Created', ['cart_item_id' => $newCartItem->id]);

            $this->updateCartTotals($cart);

            return response()->json(['message' => 'Cart item created successfully', 'data' => $newCartItem], 201);
        } catch (\Exception $e) {
            Log::error('Create CartItem: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create cart item'], 500);
        }
    }

    public function read(Request $request)
    {
        try {
            $user = $request->user();

            // Find the cart associated with the user
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart) {
                return response()->json(['message' => 'Cart not found for this user'], 404);
            }

            // Fetch cart items for the found cart
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
            Log::info('Read CartItems: Items Fetched', ['count' => $cartItems->count()]);

            return response()->json(['message' => 'Cart items fetched successfully', 'data' => $cartItems], 200);
        } catch (\Exception $e) {
            Log::error('Read CartItems: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch cart items'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            Log::info('Update CartItem: User ID', ['user_id' => $user->id, 'cart_item_id' => $id]);

            $validator = Validator::make($request->all(), [
                'product_id' => 'exists:products,id',
                'shop_coupon_id' => 'nullable|exists:shop_coupons,id',
                'quantity' => 'integer|min:1',
            ]);

            if ($validator->fails()) {
                Log::warning('Update CartItem: Validation Failed', ['errors' => $validator->messages()]);
                return response()->json(['errors' => $validator->messages()], 422);
            }

            $validated = $validator->validated();

            $cartItem = CartItem::find($id);
            if (!$cartItem) {
                Log::error('Update CartItem: Cart Item Not Found', ['cart_item_id' => $id]);
                return response()->json(['message' => 'Cart item not found'], 404);
            }

            // Check if the cart item belongs to the user's cart
            if ($cartItem->cart->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Update the cart item
            if (isset($validated['product_id'])) {
                $product = Product::find($validated['product_id']);
                if (!$product) {
                    Log::error('Update CartItem: Product Not Found', ['product_id' => $validated['product_id']]);
                    return response()->json(['message' => 'Product not found'], 404);
                }
                $cartItem->product_id = $validated['product_id'];
                $cartItem->price = $product->price;
            }

            if (isset($validated['quantity'])) {
                // Update quantity and recalculate subtotal
                $cartItem->quantity = $validated['quantity'];
                $cartItem->subtotal = $cartItem->price * $cartItem->quantity;
            }

            if (isset($validated['shop_coupon_id'])) {
                $coupon = ShopCoupon::find($validated['shop_coupon_id']);
                if ($coupon) {
                    $cartItem->shop_coupon_id = $coupon->id;
                    $cartItem->discount = $this->calculateDiscount($cartItem->subtotal, $coupon);
                } else {
                    $cartItem->discount = 0;
                }
            } else {
                $cartItem->discount = 0;
            }

            $cartItem->save();
            Log::info('Update CartItem: Cart Item Updated', ['cart_item_id' => $cartItem->id]);

            $this->updateCartTotals($cartItem->cart);

            return response()->json(['message' => 'Cart item updated successfully', 'data' => $cartItem], 200);
        } catch (\Exception $e) {
            Log::error('Update CartItem: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update cart item'], 500);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $user = $request->user();
            Log::info('Delete CartItem: User ID', ['user_id' => $user->id, 'cart_item_id' => $id]);

            $cartItem = CartItem::find($id);
            if (!$cartItem) {
                Log::error('Delete CartItem: Cart Item Not Found', ['cart_item_id' => $id]);
                return response()->json(['message' => 'Cart item not found'], 404);
            }

            // Check if the cart item belongs to the user's cart
            if ($cartItem->cart->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $cart = $cartItem->cart;
            $cartItem->delete();

            Log::info('Delete CartItem: Cart Item Deleted', ['cart_item_id' => $cartItem->id]);

            $this->updateCartTotals($cart);

            return response()->json(['message' => 'Cart item deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Delete CartItem: Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete cart item'], 500);
        }
    }

    private function updateCartTotals(Cart $cart)
    {
        try {
            $totalPrice = 0;
            $totalDiscount = 0;

            foreach ($cart->cartItems as $item) {
                // Recalculate subtotal and discount
                $item->subtotal = $item->price * $item->quantity;
                $item->discount = $item->shop_coupon_id ? $this->calculateDiscount($item->subtotal, ShopCoupon::find($item->shop_coupon_id)) : 0;
                $item->save();

                $totalPrice += $item->subtotal;
                $totalDiscount += $item->discount;
                $totalPrice = $totalPrice - $totalDiscount;
            }

            // Update cart totals
            $cart->total_price = $totalPrice;
            $cart->total_discount = $totalDiscount;
            $cart->save();

            Log::info('UpdateCartTotals: Cart Totals Updated', [
                'cart_id' => $cart->id,
                'total_price' => $cart->total_price,
                'total_discount' => $cart->total_discount,
            ]);
        } catch (\Exception $e) {
            Log::error('UpdateCartTotals: Error', ['error' => $e->getMessage()]);
        }
    }

    private function calculateDiscount($subtotal, $coupon)
    {
        return $subtotal * ($coupon->discount_amount / 100);
    }
}
