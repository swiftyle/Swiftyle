<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Ramsey\Uuid\Type\Integer;

class CheckoutController extends Controller
{

    public function create(Request $request)
    {
        $user = $request->user();
        Log::info('Checkout create request by user: ' . $user->id);

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'cart_id' => [
                'required',
                'exists:carts,id',
                function ($attribute, $value, $fail) use ($user) {
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
                    $address = $user->addresses()->find($value);
                    if (!$address) {
                        return $fail('Invalid address_id');
                    }
                },
            ],
            'courier_id' => 'required|exists:couriers,id',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed: ' . json_encode($validator->messages()));
            return response()->json($validator->messages(), 422);
        }

        $validated = $validator->validated();
        Log::info('Validation passed: ' . json_encode($validated));

        // Find the cart with its items
        $cart = Cart::with('cartItems')->find($validated['cart_id']);

        if (!$cart) {
            Log::error('Cart not found: ' . $validated['cart_id']);
            return response()->json(['message' => 'Cart not found'], 404);
        }

        // Find the courier and get the associated courier category and costs
        $courier = Courier::with('category')->find($validated['courier_id']);

        if (!$courier || !$courier->category || !$courier->category->courier_costs) {
            Log::error('Courier or Courier Category not found or missing costs: ' . $validated['courier_id']);
            return response()->json(['message' => 'Courier or Courier Category not found or missing costs'], 404);
        }

        // Calculate total amount based on cart total price and courier costs
        $totalAmount = $cart->total_price + $courier->category->courier_costs;
        Log::info('Total amount calculated: ' . $totalAmount);

        // Create the checkout
        $checkout = Checkout::create([
            'cart_id' => $validated['cart_id'],
            'payment_id' => $validated['payment_id'],
            'address_id' => $validated['address_id'],
            'courier_id' => $validated['courier_id'],
            'total_amount' => $totalAmount,
        ]);
        Log::info('Checkout created: ' . $checkout->id);

        $randomOrderId = random_int(100000, 999999);

        // Create the order in your database
        $order = Order::create([
            'id' => $randomOrderId,
            'user_id' => $user->id,
            'status' => 'to_deliver',
        ]);

        // Create the transaction in your database
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id, // Link the transaction to the order
            'amount' => (int) $totalAmount,
            'status' => 'pending',
        ]);

        // Midtrans Configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');

        // Create Midtrans transaction parameters
        $midtransParams = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'gross_amount' => $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
        ];
       
        try {
            // Get Snap payment URL
            $snapUrl = Snap::createTransaction($midtransParams)->redirect_url;
            Transaction::where('order_id',$randomOrderId)->update(['payment_url' => $snapUrl]);
            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => [
                    'checkout' => $checkout,
                    'transaction' => $transaction,
                    'payment_url' => $snapUrl,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Midtrans transaction creation failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create Midtrans transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

// // Create the order record
// $order = Order::create([
//     'transaction_id' => $transaction->transaction_id,
//     'user_id' => $user->id,
//     'status' => 'to_deliver',
// ]);

// Log::info('Order created: ' . $order->id);


// catch (\Exception $e) {
//     Log::error('Error creating transaction or order: ' . $e->getMessage(), ['exception' => $e]);
//     return response()->json(['message' => 'Failed to create transaction or order', 'error' => $e->getMessage()], 500);
// }



// private function generateTransactionId()
// {
//     return strtoupper(bin2hex(random_bytes(8)));
// }



// public function read()
// {
//     $checkouts = Checkout::with(['cart', 'payment', 'address', 'courier'])->get();

//     return response()->json([
//         'message' => 'All Checkouts',
//         'data' => $checkouts
//     ], 200);
// }