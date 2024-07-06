<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function create(Request $request)
    {
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

        // Create the transaction in your database
        $transaction = Transaction::create([
            'user_id' => $request->input('user_id'),
            'order_id' => $request->input('order_id'),
            'amount' => $request->input('amount'),
            'type' => $request->input('type'),
            'status' => $request->input('status'),
        ]);

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

            return response()->json([
                'message' => 'Transaction created successfully',
                'data' => $transaction,
                'payment_url' => $snapUrl
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Midtrans transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
        public function read(Request $request)
        {
            // Get the authenticated user
            $user = $request->user();
    
            // Fetch transactions associated with the given user ID
            $transactions = Transaction::where('user_id', $user->id)->get();
    
            return response()->json([
                'message' => 'Transactions fetched successfully',
                'data' => $transactions
            ], 200);
        }
    

    public function getStatus(Request $request, $transactionId)
    {
        try {
            $status = \Midtrans\Transaction::status($transactionId);
            return response()->json([
                'message' => 'Transaction status fetched successfully',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch transaction status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload);

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;

        $transaction = Transaction::find($orderId);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update the transaction status based on the notification status
        if ($transactionStatus == 'capture') {
            $transaction->status = 'completed';
        } elseif ($transactionStatus == 'settlement') {
            $transaction->status = 'settled';
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            $transaction->status = 'failed';
        } else {
            $transaction->status = 'pending';
        }

        $transaction->save();

        return response()->json(['message' => 'Notification processed successfully'], 200);
    }
}
