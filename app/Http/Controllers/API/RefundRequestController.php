<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundRequestController extends Controller
{
    public function index()
    {
        $refundRequests = RefundRequest::all();
        return response()->json(['data' => $refundRequests], 200);
    }

    public function show($uuid)
    {
        $refundRequest = RefundRequest::findOrFail($uuid);
        return response()->json(['data' => $refundRequest], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|uuid',
            'order_uuid' => 'required|uuid',
            'reason' => 'required|string',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $refundRequest = RefundRequest::create($validator->validated());

        return response()->json(['message' => 'Refund request created successfully', 'data' => $refundRequest], 201);
    }

    public function update(Request $request, $uuid)
    {
        $refundRequest = RefundRequest::findOrFail($uuid);

        $validator = Validator::make($request->all(), [
            'user_uuid' => 'uuid',
            'order_uuid' => 'uuid',
            'reason' => 'string',
            'status' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $refundRequest->update($validator->validated());

        return response()->json(['message' => 'Refund request updated successfully', 'data' => $refundRequest], 200);
    }

    public function destroy($uuid)
    {
        $refundRequest = RefundRequest::findOrFail($uuid);
        $refundRequest->delete();

        return response()->json(['message' => 'Refund request deleted successfully'], 200);
    }
}
