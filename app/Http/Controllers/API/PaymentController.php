<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json(['data' => $payments], 200);
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return response()->json(['data' => $payment], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_uuid' => 'required|uuid',
            'order_uuid' => 'uuid|nullable',
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment = Payment::create($validatedData);

        return response()->json(['message' => 'Payment created successfully', 'data' => $payment], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_uuid' => 'uuid',
            'order_uuid' => 'uuid|nullable',
            'amount' => 'numeric',
            'method' => 'string',
            'status' => 'in:pending,completed,failed',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($validatedData);

        return response()->json(['message' => 'Payment updated successfully', 'data' => $payment], 200);
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully'], 200);
    }
}
