<?php

namespace App\Http\Controllers\Api;

use App\Events\RefundProcessed;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\RefundRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function process(Request $request)
    {
        try {
            $user = $request->user();

            // Validasi incoming request
            $validator = Validator::make($request->all(), [
                'refund_request_id' => 'required|exists:refund_requests,id',
                'status' => 'required|in:accepted,canceled',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Mencari permintaan pengembalian
            $refundRequest = RefundRequest::findOrFail($request->input('refund_request_id'));
            Log::info('Refund request found', ['refund_request_id' => $refundRequest->id]);

            // Mencari transaksi yang terkait dengan permintaan pengembalian
            $transaction = Transaction::where('order_id', $refundRequest->order_id)->first();

            if (!$transaction) {
                Log::error('Transaction not found for refund request', ['refund_request_id' => $refundRequest->id]);
                return response()->json(['message' => 'Transaction not found'], 404);
            }
            Log::info('Transaction found', ['transaction_id' => $transaction->id]);

            // Mendapatkan email pengguna yang membuat permintaan
            $userEmail = $user->email;
            Log::info('User email retrieved', ['user_email' => $userEmail]);

            // Membuat entri refund baru
            DB::beginTransaction();

            $amount = $transaction->amount;

            $refund = Refund::create([
                'refund_request_id' => $refundRequest->id,
                'status' => $request->input('status'),
                'amount' => $amount,
                'confirmed_by' => $userEmail,
            ]);

            DB::commit();

            Log::info('Refund request created successfully', ['refund_id' => $refund->id]);

            return response()->json([
                'message' => 'Refund request created successfully',
                'data' => $refund,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Failed to process refund request', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to process refund request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }





    // try {
    //     // Find the refund request
    //     $refundRequest = RefundRequest::findOrFail($request->input('refund_request_id'));

    //     // Retrieve the order associated with the refund request
    //     $order = $refundRequest->order;

    //     if (!$order || !$order->transaction_id) {
    //         return response()->json(['message' => 'Transaction ID not found for this order'], 404);
    //     }

    //     // Initialize Midtrans
    //     \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    //     \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
    //     \Midtrans\Config::$isSanitized = true;
    //     \Midtrans\Config::$is3ds = true;

    //     // Process the refund through Midtrans
    //     $transactionId = $order->transaction_id;
    //     $params = [
    //         'refund_key' => 'order-' . $refundRequest->order_id . '-refund-' . time(),
    //         'amount' => $request->input('amount'),
    //         'reason' => 'Refund for Order ID: ' . $refundRequest->order_id
    //     ];

    //     $refundResponse = Transaction::refund($transactionId, $params);

    //     if ($refundResponse->status_code !== '200') {
    //         return response()->json(['message' => 'Failed to process refund: ' . $refundResponse->status_message], 400);
    //     }

    //     // Create the refund record
    //     $refund = Refund::create([
    //         'refund_request_id' => $refundRequest->id,
    //         'amount' => $request->input('amount'),
    //         'status' => 'refunded', // Set status to 'refunded'
    //     ]);

    //     // Update status of refund request to 'processed'
    //     $refundRequest->status = 'processed';
    //     $refundRequest->save();

    //     // Call event RefundProcessed to send notifications and other actions
    //     event(new RefundProcessed($refundRequest));

    //     // Response
    //     return response()->json([
    //         'message' => 'Refund processed successfully',
    //         'data' => $refund
    //     ], 201);
    // } catch (Exception $e) {
    //     return response()->json(['message' => 'Failed to process refund: ' . $e->getMessage()], 500);
    // }


    public function readAll()
    {
        // Fetch all refunds
        $refunds = Refund::all();

        return response()->json([
            'message' => 'Refunds fetched successfully',
            'data' => $refunds
        ], 200);
    }

    public function read($id)
    {
        // Fetch refund by ID
        $refund = Refund::find($id);

        if (!$refund) {
            return response()->json(['message' => 'Refund not found'])->setStatusCode(404);
        }

        return response()->json([
            'message' => 'Refund fetched successfully',
            'data' => $refund
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'refund_request_id' => 'exists:refund_requests,id',
            'amount' => 'numeric|min:0',
            'status' => 'in:refunded',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the refund
        $refund = Refund::find($id);

        if (!$refund) {
            return response()->json(['message' => 'Refund not found'])->setStatusCode(404);
        }

        // Update the refund
        $refund->refund_request_id = $request->input('refund_request_id', $refund->refund_request_id);
        $refund->amount = $request->input('amount', $refund->amount);
        $refund->status = $request->input('status', $refund->status);
        $refund->save();

        return response()->json([
            'message' => 'Refund updated successfully',
            'data' => $refund
        ], 200);
    }

    public function delete($id)
    {
        // Find the refund
        $refund = Refund::find($id);

        if (!$refund) {
            return response()->json(['message' => 'Refund not found'])->setStatusCode(404);
        }

        // Delete the refund
        $refund->delete();

        return response()->json(['message' => 'Refund deleted successfully'], 200);
    }
}
