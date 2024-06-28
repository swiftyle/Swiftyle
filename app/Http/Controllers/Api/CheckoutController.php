<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Courier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'cart_id' => [
                'required',
                'exists:carts,id',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if the cart belongs to the authenticated user
                    $cart = Cart::find($value);
                    if (!$cart || $cart->user_id !== $user->id) {
                        return $fail('Invalid cart_id');
                    }
                },
            ],
            'payment_id' => 'required|exists:payments,id', // Validate payment_id existence
            'address_id' => [
                'required',
                'exists:addresses,id',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if the address belongs to the authenticated user
                    $address = $user->addresses()->find($value);
                    if (!$address) {
                        return $fail('Invalid address_id');
                    }
                },
            ],
            'courier_id' => 'required|exists:couriers,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Find the cart with its items
        $cart = Cart::with('cartItems')->find($validated['cart_id']);

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        // Find the courier and get the associated courier category and costs
        $courier = Courier::with('category')->find($validated['courier_id']);

        if (!$courier || !$courier->category || !$courier->category->courier_costs) {
            return response()->json(['message' => 'Courier or Courier Category not found or missing costs'], 404);
        }

        // Calculate total amount based on cart total price and courier costs
        $totalAmount = $cart->total_price + $courier->category->courier_costs;

        // Create the checkout
        $checkout = Checkout::create([
            'cart_id' => $validated['cart_id'],
            'payment_id' => $validated['payment_id'],
            'address_id' => $validated['address_id'],
            'courier_id' => $validated['courier_id'],
            'total_amount' => $totalAmount,
        ]);

        return response()->json([
            'message' => 'Checkout created successfully',
            'data' => $checkout
        ], 201);
    }

    public function read()
    {
        $checkouts = Checkout::with(['cart', 'payment', 'address', 'courier'])->get();

        return response()->json([
            'message' => 'All Checkouts',
            'data' => $checkouts
        ], 200);
    }
}