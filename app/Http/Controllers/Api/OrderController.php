<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Events\OrderPackaged;
use App\Events\OrderReceived;
use App\Events\OrderReviewed;
use App\Events\OrderShipped;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'shipping_id' => 'required|exists:shippings,id', // Ensure the table name is correct
            'status' => 'required|in:delivered,received,reviewed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the order
        $order = Order::create([
            'user_id' => $request->input('user_id'),
            'shipping_id' => $request->input('shipping_id'),
            'status' => $request->input('status'),
        ]);

        event(new OrderCreated($order));

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order
        ], 201);
    }

    public function read(Request $request)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch orders associated with the authenticated user
        $orders = Order::where('user_id', $user->id)->get();

        return response()->json([
            'message' => 'Orders fetched successfully',
            'data' => $orders
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

        // Update the order status and trigger the respective event
        $status = $request->input('status');

        if ($status === 'packaged' && $user->role === 'Seller') {
            event(new OrderPackaged($order));
        } elseif ($status === 'shipped' && $user->role === 'Seller') {
            event(new OrderShipped($order));
        } elseif ($status === 'delivered' && $user->role === 'Courier') {
            event(new OrderDelivered($order));
        } elseif ($status === 'received' && $order->user_id === $user->id) {
            event(new OrderReceived($order));
        } elseif ($status === 'reviewed' && $order->user_id === $user->id) {
            event(new OrderReviewed($order));
        } else {
            return response()->json(['message' => 'Unauthorized to change status to ' . $status], 403);
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