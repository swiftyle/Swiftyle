<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\Courier;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Preference;
use App\Models\SizeColor;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

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
        $cart = Cart::with('cartItems.product.sizes.colors', 'cartItems.product.styles')->find($validated['cart_id']);

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

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Create the checkout
            $checkout = Checkout::create([
                'cart_id' => $validated['cart_id'],
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
                'checkout_id' => $checkout->id, // Include checkout_id
                'status' => 'to deliver',
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
                    'order_id' => $transaction->order->id,
                    'gross_amount' => $transaction->amount,
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
            ];

            // Check for user preferences and create new preferences if necessary
            $userPreferences = $user->preferences()->pluck('style_id')->toArray();
            foreach ($cart->cartItems as $cartItem) {
                foreach ($cartItem->product->styles as $style) {
                    $styleId = $style->id;
                    if (!in_array($styleId, $userPreferences)) {
                        Preference::create([
                            'user_id' => $user->id,
                            'style_id' => $styleId,
                        ]);
                        Log::info('Preference created for user: ' . $user->id . ' with style_id: ' . $styleId);
                    }
                }
            }

            // Reduce stock in size_color table and update sell count
            foreach ($cart->cartItems as $cartItem) {
                foreach ($cartItem->product->sizes as $size) {
                    foreach ($size->colors as $color) {
                        $sizeColor = SizeColor::where('size_id', $size->id)
                            ->where('color_id', $color->id)
                            ->first();
                        if ($sizeColor) {
                            $sizeColor->stock -= $cartItem->quantity;
                            $sizeColor->save();
                            Log::info('Stock reduced for SizeColor: ' . json_encode(['size_id' => $sizeColor->size_id, 'color_id' => $sizeColor->color_id, 'quantity' => $cartItem->quantity]));
                        } else {
                            Log::error('SizeColor not found: ' . json_encode(['size_id' => $size->id, 'color_id' => $color->id]));
                        }
                    }
                }

                // Update the sell count for the product
                $product = $cartItem->product;
                $product->sell += $cartItem->quantity;
                $product->save();
                Log::info('Sell count updated for product: ' . $product->id . ' by ' . $cartItem->quantity);
            }

            // Attach products to the checkout_product pivot table
            foreach ($cart->cartItems as $cartItem) {
                CheckoutProduct::create([
                    'checkout_id' => $checkout->id,
                    'product_id' => $cartItem->product_id,
                ]);
            }
            Log::info('Products attached to checkout_product for checkout: ' . $checkout->id);

            // Delete all cartItems related to the cart
            $cart->cartItems()->delete();
            Log::info('Cart items deleted for cart: ' . $cart->id);

            // Set app_coupon_id to null
            $cart->update(['app_coupon_id' => null]);
            Log::info('Cart app_coupon_id set to null for cart: ' . $cart->id);

            // Create a notification
            $notificationData = [
                'type' => 'OrderCreated',
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'data' => json_encode([
                    'message' => 'Your order with ID ' . $order->id . ' has been created successfully on ' . now() . '.',
                    'order_id' => $order->id,
                    'timestamp' => now(),
                ]),
            ];
            Notification::create($notificationData);

            try {
                // Get Snap payment URL
                $snapUrl = Snap::createTransaction($midtransParams)->redirect_url;
                Transaction::where('order_id', $randomOrderId)->update(['payment_url' => $snapUrl]);
                DB::commit();
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
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to create Midtrans transaction',
                    'error' => $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error during checkout creation: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create checkout',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function read()
    {
        $user = auth()->user();
        Log::info('Checkout read request by user: ' . $user->id);

        // Retrieve all checkouts based on user_id from the logged-in user
        $checkouts = Checkout::with([
            'cart',
            'address',
            'courier'
        ])->whereHas('cart', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        if ($checkouts->isEmpty()) {
            Log::error('Checkouts not found for user: ' . $user->id);
            return response()->json(['message' => 'Checkouts not found for user'], 404);
        }

        // Collect product details for each checkout
        $checkoutsWithProducts = $checkouts->map(function ($checkout) {
            $products = $checkout->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'sizes' => $product->sizes,
                    'colors' => $product->sizes->flatMap->colors,
                    'styles' => $product->styles,
                    'image' => $product->image,
                    'quantity' => $product->quantity,
                ];
            });

            return [
                'checkout' => $checkout,
                'products' => $products,
            ];
        });

        return response()->json([
            'message' => 'Checkouts retrieved successfully',
            'data' => $checkoutsWithProducts,
        ], 200);
    }


}
