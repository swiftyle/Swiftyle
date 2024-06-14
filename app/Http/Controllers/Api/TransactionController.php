<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Transaction;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
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
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:purchase,refund,payment,withdrawal',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the transaction
        $transaction = Transaction::create([
            'user_id' => $request->input('user_id'),
            'order_id' => $request->input('order_id'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ]);

        return response()->json([
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ], 201);
    }

    public function read(Request $request, $userId)
    {
        try {
            // Decode JWT token to get user data
            $data = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET_KEY'), 'HS256'));
            $user = User::find($data->id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch transactions associated with the given user ID
        $transactions = Transaction::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Transactions fetched successfully',
            'data' => $transactions
        ], 200);
    }
}
