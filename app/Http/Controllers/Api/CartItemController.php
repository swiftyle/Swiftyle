<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartItemController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'shop_coupon_id' => 'nullable|exists:shop_coupons,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the cart item
        $cartItem = CartItem::create([
            'cart_id' => $request->input('cart_id'),
            'product_id' => $request->input('product_id'),
            'shop_coupon_id' => $request->input('shop_coupon_id'),
            'quantity' => $request->input('quantity'),
            'price' => $request->input('price'),
            'subtotal' => $request->input('subtotal'),
        ]);

        return response()->json([
            'message' => 'Cart item created successfully',
            'data' => $cartItem
        ], 201);
    }

    public function readAll(Request $request)
    {
        $user = $request->user();

        // Fetch all cart items
        $cartItems = CartItem::all();

        return response()->json([
            'message' => 'Cart items fetched successfully',
            'data' => $cartItems
        ], 200);
    }

    public function read(Request $request, $id)
    {
        $user = $request->user();

        // Fetch cart item by ID
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'])->setStatusCode(404);
        }

        return response()->json([
            'message' => 'Cart item fetched successfully',
            'data' => $cartItem
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'cart_id' => 'exists:carts,id',
            'product_id' => 'exists:products,id',
            'shop_coupon_id' => 'nullable|exists:shop_coupons,id',
            'quantity' => 'integer|min:1',
            'price' => 'numeric|min:0',
            'subtotal' => 'numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the cart item
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'])->setStatusCode(404);
        }
        // Update the cart item
        $cartItem->cart_id = $request->input('cart_id', $cartItem->cart_id);
        $cartItem->product_id = $request->input('product_id', $cartItem->product_id);
        $cartItem->shop_coupon_id = $request->input('shop_coupon_id', $cartItem->shop_coupon_id);
        $cartItem->quantity = $request->input('quantity', $cartItem->quantity);
        $cartItem->price = $request->input('price', $cartItem->price);
        $cartItem->subtotal = $request->input('subtotal', $cartItem->subtotal);
        $cartItem->save();

        return response()->json([
            'message' => 'Cart item updated successfully',
            'data' => $cartItem
        ], 200);
    }

    public function delete(Request $request, $id)
    {

        $user = $request->user();

        // Find the cart item
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'])->setStatusCode(404);
        }

        // Delete the cart item
        $cartItem->delete();

        return response()->json(['message' => 'Cart item deleted successfully'], 200);
    }
}
