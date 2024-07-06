<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Events\OrderPackaged;
use App\Events\OrderReceived;
use App\Events\OrderReviewed;
use App\Events\OrderShipped;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'shipping_id' => 'required|exists:shippings,id', // Ensure the table name is correct
            'status' => 'required|in:delivered,received,reviewed',
            'transaction_id' => 'required|string|max:255|unique',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Menambahkan user ID ke data yang divalidasi
        $validated['user_id'] = $user->id;
        // Create the order
        $order = Order::create($validated);

        event(new OrderCreated($order));

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }



    public function read(Request $request)
    {
        $user = $request->user();

        // Fetch orders associated with the authenticated user
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Orders fetched successfully',
            'data' => $orders
        ], 200);
    }

    public function readById(Request $request, $id)
    {
        $user = $request->user();

        // Find the order by ID
        $order = Order::where('user_id', $user->id)->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json([
            'message' => 'Order fetched successfully',
            'data' => $order
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();

        // Find the order
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check if the order belongs to the authenticated user or the delivery person
        if ($order->user_id !== $user->id && $user->role !== 'Seller') {
            return response()->json(['message' => 'Unauthorized to update this order'], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:packaged,shipped,delivered,received,reviewed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }


        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => $order
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        // Find the order
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to delete this order'], 403);
        }

        // Delete the order
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}