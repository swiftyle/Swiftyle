<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Courier;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShippingController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Check if the user is a seller
        if ($user->role !== 'Seller') {
            return response()->json([
                'message' => 'Unauthorized. Only sellers can create shipping records for their products'
            ], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'checkout_id' => 'required|exists:checkouts,id',
            'order_id' => 'required|exists:orders,id',
            'shipped_date' => 'required|date',
            'shipping_method' => 'required|in:car,ship,plane',
            'payment_status' => 'required|in:cod,paid',
            'estimated_delivery_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        try {
            // Find the checkout
            $checkout = Checkout::find($request->input('checkout_id'));
            if (!$checkout) {
                return response()->json([
                    'message' => 'Checkout not found'
                ], 404);
            }

            // Log the checkout data
            Log::info('Checkout data:', ['checkout' => $checkout]);

            // Traverse relationships to get the product
            $cart = $checkout->cart;
            if (!$cart) {
                return response()->json([
                    'message' => 'Cart not found for this checkout'
                ], 404);
            }

            // Include soft-deleted cartItems
            $cartItems = $cart->cartItems()->withTrashed()->get();
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'message' => 'Cart items not found for this cart'
                ], 404);
            }

            $product = $cartItems->first()->product;
            if (!$product) {
                return response()->json([
                    'message' => 'Product not found for this cart item'
                ], 404);
            }

            // Log the product data
            Log::info('Product data:', ['product' => $product]);

            $shop = $product->shops;
            if (!$shop) {
                return response()->json([
                    'message' => 'Shop not found for this product'
                ], 404);
            }

            if ($shop->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized. You can only create shipping records for your own products'
                ], 403);
            }

            // Get courier category and courier cost
            $courier = Courier::find($checkout->courier_id);
            if (!$courier) {
                return response()->json([
                    'message' => 'Courier not found'
                ], 404);
            }

            // Assuming there's a relationship like courier_costs through courier category
            $courierCost = $courier->category->courier_costs;

            // Calculate shipping cost based on some logic, for example, summing up costs
            $shippingCost = $courierCost;

            // Get courier name from courier_id in checkout
            $courierName = $courier->name;

            // Get shipping address from address_id in checkout
            $address = $checkout->address;
            if (!$address) {
                return response()->json([
                    'message' => 'Address not found'
                ], 404);
            }

            $shippingAddress = $this->formatAddress($address);

            // Update the order status
            $order = Order::find($request->input('order_id'));
            if (!$order) {
                return response()->json([
                    'message' => 'Order not found'
                ], 404);
            }

            $order->status = 'delivered';
            $order->save();

            // Create the shipping record
            $shipping = Shipping::create([
                'checkout_id' => $request->input('checkout_id'),
                'order_id' => $order->id,
                'shipping_address' => $shippingAddress,
                'courier_name' => $courierName,
                'tracking_number' => strtoupper(Str::random(10)), // Random tracking number
                'shipped_date' => $request->input('shipped_date'),
                'shipping_method' => $request->input('shipping_method'),
                'shipping_cost' => $shippingCost,
                'shipping_status' => 'delivered',
                'payment_status' => $request->input('payment_status'),
                'estimated_delivery_date' => $request->input('estimated_delivery_date'),
            ]);

            $user = $order->user; 
            $notificationData = [
                'type' => 'OrderDelivered',
                'notifiable_id' => $user->id,
                'notifiable_type' => 'App\Models\User',
                'data' => json_encode([
                    'message' => 'Your order with ID ' . $order->id . ' has been delivered on ' . now() . '.',
                    'order_id' => $order->id,
                    'timestamp' => now(),
                ]),
            ];
            Notification::create($notificationData);

            return response()->json([
                'message' => 'Shipping record created successfully',
                'data' => [
                    'order' => $order,
                    'shipping' => $shipping,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating shipping record: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    // Helper function to format address
    private function formatAddress($address)
    {
        return implode(', ', array_filter([
            $address->firstname,
            $address->lastname,
            $address->street,
            $address->house_number,
            $address->apartment_number,
            $address->district,
            $address->city,
            $address->province,
            $address->country,
            $address->postal_code,
            $address->phone_number,
        ]));
    }

    public function read(Request $request)
    {
        $user = $request->user();

        // Check if the user is a seller
        if ($user->role !== 'Seller') {
            return response()->json([
                'message' => 'Unauthorized. Only sellers can view their shipping records'
            ], 403);
        }

        try {
            // Fetch shipping records associated with the seller's products
            $shippingRecords = Shipping::whereHas('checkout.cart.cartItems.product.shops', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

            return response()->json([
                'message' => 'Shipping records fetched successfully',
                'data' => $shippingRecords
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching shipping records: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'checkout_id' => 'sometimes|required|exists:checkouts,id',
            'shipping_address' => 'sometimes|required|string|max:255',
            'courier_name' => 'sometimes|required|string|max:255',
            'tracking_number' => 'sometimes|required|string|max:255',
            'shipped_date' => 'sometimes|required|date',
            'shipping_method' => 'sometimes|required|in:car,ship,plane',
            'shipping_cost' => 'sometimes|required|numeric',
            'shipping_status' => 'sometimes|required|in:pending,shipped,delivered,cancelled',
            'payment_status' => 'sometimes|required|in:cod,paid',
            'estimated_delivery_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the shipping record
        $shipping = Shipping::find($id);

        if ($shipping) {
            // Update the shipping record
            $shipping->update([
                'checkout_id' => $request->input('checkout_id', $shipping->checkout_id),
                'shipping_address' => $request->input('shipping_address', $shipping->shipping_address),
                'courier_name' => $request->input('courier_name', $shipping->courier_name),
                'tracking_number' => $request->input('tracking_number', $shipping->tracking_number),
                'shipped_date' => $request->input('shipped_date', $shipping->shipped_date),
                'shipping_method' => $request->input('shipping_method', $shipping->shipping_method),
                'shipping_cost' => $request->input('shipping_cost', $shipping->shipping_cost),
                'shipping_status' => $request->input('shipping_status', $shipping->shipping_status),
                'payment_status' => $request->input('payment_status', $shipping->payment_status),
                'estimated_delivery_date' => $request->input('estimated_delivery_date', $shipping->estimated_delivery_date),
            ]);

            return response()->json([
                'message' => 'Shipping record updated successfully',
                'data' => $shipping
            ], 200);
        }

        return response()->json([
            'message' => 'Shipping record not found'
        ], 404);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        // Find the shipping record
        $shipping = Shipping::find($id);

        if ($shipping) {
            // Delete the shipping record
            $shipping->delete();

            return response()->json([
                'message' => 'Shipping record deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Shipping record not found'
        ], 404);
    }
}

