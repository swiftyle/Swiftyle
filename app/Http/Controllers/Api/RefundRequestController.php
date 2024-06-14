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
        // Get the currently authenticated user
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the refund request
        $refundRequest = RefundRequest::create([
            'user_id' => $user->id,  // Automatically set the user_id from the authenticated user
            'order_id' => $request->input('order_id'),
            'reason' => $request->input('reason'),
        ]);

        return response()->json([
            'message' => 'Refund request created successfully',
            'data' => $refundRequest
        ], 201);
    }


    public function read(Request $request)
    {
        // Get the currently authenticated user
        $user = $request->user();

        if ($user->role === 'Admin') {
            // Admin can view all refund requests
            $refundRequests = RefundRequest::all();
        } elseif ($user->role === 'Seller') {
            // Sellers can only see refund requests for their products
            $refundRequests = RefundRequest::whereHas('order.shipping.checkout.cart.cartItems.product', function ($query) use ($user) {
                $query->where('shop_id', $user->shop->id); // Assuming the seller has one shop associated
            })->get();
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'message' => 'Refund requests fetched successfully',
            'data' => $refundRequests
        ], 200);
    }


    public function readById($id)
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
        // Get the currently authenticated user
        $user = $request->user();

        // Check if the user is an Admin
        if ($user->role !== 'Admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the refund request
        $refundRequest = RefundRequest::find($id);

        if (!$refundRequest) {
            return response()->json(['message' => 'Refund request not found'])->setStatusCode(404);
        }

        // Update the refund request status
        $refundRequest->status = $request->input('status');
        $refundRequest->save();

        return response()->json([
            'message' => 'Refund request status updated successfully',
            'data' => $refundRequest
        ], 200);
    }
}
