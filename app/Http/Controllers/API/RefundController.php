<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::all();
        return response()->json(['data' => $refunds], 200);
    }

    public function show($id)
    {
        $refund = Refund::findOrFail($id);
        return response()->json(['data' => $refund], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'refund_request_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'transaction_uuid' => 'required|uuid',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'reason' => 'string|nullable',
        ]);

        $refund = Refund::create($validatedData);

        return response()->json(['message' => 'Refund created successfully', 'data' => $refund], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'refund_request_uuid' => 'uuid',
            'user_uuid' => 'uuid',
            'transaction_uuid' => 'uuid',
            'amount' => 'numeric',
            'status' => 'string',
            'reason' => 'string|nullable',
        ]);

        $refund = Refund::findOrFail($id);
        $refund->update($validatedData);

        return response()->json(['message' => 'Refund updated successfully', 'data' => $refund], 200);
    }

    public function destroy($id)
    {
        $refund = Refund::findOrFail($id);
        $refund->delete();

        return response()->json(['message' => 'Refund deleted successfully'], 200);
    }
}
