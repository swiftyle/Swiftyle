<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderHistory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderHistoryController extends Controller
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
            'order_id' => 'required|exists:orders,id',
            'description' => 'nullable|string',
            'status' => 'required|in:done,cancelled,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the order history
        $orderHistory = OrderHistory::create([
            'order_id' => $request->input('order_id'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return response()->json([
            'message' => 'Order history created successfully',
            'data' => $orderHistory
        ], 201);
    }

    public function read(Request $request, $orderId)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch order histories associated with the given order ID
        $orderHistories = OrderHistory::where('order_id', $orderId)->get();

        return response()->json([
            'message' => 'Order histories fetched successfully',
            'data' => $orderHistories
        ], 200);
    }
}
