<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

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
            'payment_id' => 'required|exists:payments,id',
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
            'total_amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();

        // Create the checkout
        $checkout = Checkout::create([
            'cart_id' => $validated['cart_id'],
            'payment_id' => $validated['payment_id'],
            'address_id' => $validated['address_id'],
            'courier_id' => $validated['courier_id'],
            'total_amount' => $validated['total_amount'],
        ]);

        // Process payment using Midtrans
        try {
            // Set Midtrans configuration
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$is3ds = true;

            // Prepare transaction details
            $transactionDetails = [
                'order_id' => 'ORDER-' . $checkout->id,
                'gross_amount' => $checkout->total_amount,
            ];

            // Get Snap payment URL
            $snapToken = Snap::getSnapToken($transactionDetails);

            // Construct redirect URL manually
            $redirectUrl = 'https://app.sandbox.midtrans.com/snap/v1/vtweb/' . $snapToken;

            // Return Snap token and redirect URL to client
            return response()->json([
                'message' => 'Checkout created successfully',
                'snap_token' => $snapToken,
                'redirect_url' => $redirectUrl,
                'data' => $checkout
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to process payment: ' . $e->getMessage()], 500);
        }
    }

    public function read()
    {
        $checkouts = Checkout::with(['cart', 'payment', 'address', 'courier'])->get();

        return response()->json([
            'message' => 'All Checkouts',
            'data' => $checkouts
        ], 200);
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         // Decode JWT token to get user data
    //         $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
    //         $user = User::find($data->id);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     // Validate incoming request
    //     $validator = Validator::make($request->all(), [
    //         'cart_id' => 'sometimes|required|exists:carts,id',
    //         'payment_id' => 'sometimes|required|exists:payments,id',
    //         'address_id' => 'sometimes|required|exists:addresses,id',
    //         'courier_id' => 'sometimes|required|exists:couriers,id',
    //         'total_amount' => 'sometimes|required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->messages())->setStatusCode(422);
    //     }

    //     $validated = $validator->validated();

    //     // Find the checkout
    //     $checkout = Checkout::find($id);

    //     if ($checkout) {
    //         // Check if the checkout belongs to the authenticated user (if needed)

    //         // Update the checkout
    //         $checkout->update([
    //             'cart_id' => $validated['cart_id'] ?? $checkout->cart_id,
    //             'payment_id' => $validated['payment_id'] ?? $checkout->payment_id,
    //             'address_id' => $validated['address_id'] ?? $checkout->address_id,
    //             'courier_id' => $validated['courier_id'] ?? $checkout->courier_id,
    //             'total_amount' => $validated['total_amount'] ?? $checkout->total_amount,
    //         ]);

    //         return response()->json([
    //             'message' => 'Checkout updated successfully',
    //             'data' => $checkout
    //         ], 200);
    //     }

    //     return response()->json([
    //         'message' => 'Checkout not found'
    //     ], 404);
    // }

    // public function delete(Request $request, $id)
    // {
    //     try {
    //         // Decode JWT token to get user data
    //         $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
    //         $user = User::find($data->id);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Unauthorized'], 401);
    //     }

    //     // Find the checkout
    //     $checkout = Checkout::find($id);

    //     if ($checkout) {
    //         // Check if the checkout belongs to the authenticated user (if needed)

    //         // Delete the checkout
    //         $checkout->delete();

    //         return response()->json([
    //             'message' => 'Checkout deleted successfully'
    //         ], 200);
    //     }

    //     return response()->json([
    //         'message' => 'Checkout not found'
    //     ], 404);
    // }
}
