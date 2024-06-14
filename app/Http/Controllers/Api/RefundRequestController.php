<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundRequestController extends Controller
{
    public function create(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the refund request
        $refundRequest = RefundRequest::create([
            'user_id' => $request->input('user_id'),
            'order_id' => $request->input('order_id'),
            'reason' => $request->input('reason'),
        ]);

        return response()->json([
            'message' => 'Refund request created successfully',
            'data' => $refundRequest
        ], 201);
    }

    public function readAll()
    {
        // Fetch all refund requests
        $refundRequests = RefundRequest::all();

        return response()->json([
            'message' => 'Refund requests fetched successfully',
            'data' => $refundRequests
        ], 200);
    }

    public function read($id)
    {
        // Fetch refund request by ID
        $refundRequest = RefundRequest::find($id);

        if (!$refundRequest) {
            return response()->json(['message' => 'Refund request not found'])->setStatusCode(404);
        }

        return response()->json([
            'message' => 'Refund request fetched successfully',
            'data' => $refundRequest
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'order_id' => 'exists:orders,id',
            'reason' => 'string',
            'status' => 'in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the refund request
        $refundRequest = RefundRequest::find($id);

        if (!$refundRequest) {
            return response()->json(['message' => 'Refund request not found'])->setStatusCode(404);
        }

        // Update the refund request
        $refundRequest->user_id = $request->input('user_id', $refundRequest->user_id);
        $refundRequest->order_id = $request->input('order_id', $refundRequest->order_id);
        $refundRequest->reason = $request->input('reason', $refundRequest->reason);
        $refundRequest->status = $request->input('status', $refundRequest->status);
        $refundRequest->save();

        return response()->json([
            'message' => 'Refund request updated successfully',
            'data' => $refundRequest
        ], 200);
    }

    public function delete($id)
    {
        // Find the refund request
        $refundRequest = RefundRequest::find($id);

        if (!$refundRequest) {
            return response()->json(['message' => 'Refund request not found'])->setStatusCode(404);
        }

        // Delete the refund request
        $refundRequest->delete();

        return response()->json(['message' => 'Refund request deleted successfully'], 200);
    }
}
