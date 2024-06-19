<?php

namespace App\Http\Controllers\Api;

use App\Events\RefundProcessed;
use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\RefundRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function process(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'refund_request_id' => 'required|exists:refund_requests,id',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        try {
            // Find the refund request
            $refundRequest = RefundRequest::findOrFail($request->input('refund_request_id'));

            // Retrieve the order associated with the refund request
            $order = $refundRequest->order;

            if (!$order || !$order->transaction_id) {
                return response()->json(['message' => 'Transaction ID not found for this order'], 404);
            }

            // Initialize Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Process the refund through Midtrans
            $transactionId = $order->transaction_id;
            $params = [
                'refund_key' => 'order-' . $refundRequest->order_id . '-refund-' . time(),
                'amount' => $request->input('amount'),
                'reason' => 'Refund for Order ID: ' . $refundRequest->order_id
            ];

            $refundResponse = Transaction::refund($transactionId, $params);

            if ($refundResponse->status_code !== '200') {
                return response()->json(['message' => 'Failed to process refund: ' . $refundResponse->status_message], 400);
            }

            // Create the refund record
            $refund = Refund::create([
                'refund_request_id' => $refundRequest->id,
                'amount' => $request->input('amount'),
                'status' => 'refunded', // Set status to 'refunded'
            ]);

            // Update status of refund request to 'processed'
            $refundRequest->status = 'processed';
            $refundRequest->save();

            // Call event RefundProcessed to send notifications and other actions
            event(new RefundProcessed($refundRequest));

            // Response
            return response()->json([
                'message' => 'Refund processed successfully',
                'data' => $refund
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to process refund: ' . $e->getMessage()], 500);
        }
    }

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
