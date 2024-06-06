<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return response()->json(['data' => $transactions], 200);
    }

    public function show($uuid)
    {
        $transaction = Transaction::findOrFail($uuid);
        return response()->json(['data' => $transaction], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|uuid',
            'product_uuid' => 'required|uuid',
            'amount' => 'required|numeric',
            'type' => 'required|in:purchase,refund,payment,withdrawal',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::create($validator->validated());

        return response()->json(['message' => 'Transaction created successfully', 'data' => $transaction], 201);
    }

    public function update(Request $request, $uuid)
    {
        $transaction = Transaction::findOrFail($uuid);

        $validator = Validator::make($request->all(), [
            'user_uuid' => 'uuid',
            'product_uuid' => 'uuid',
            'amount' => 'numeric',
            'type' => 'in:purchase,refund,payment,withdrawal',
            'status' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update($validator->validated());

        return response()->json(['message' => 'Transaction updated successfully', 'data' => $transaction], 200);
    }

    public function destroy($uuid)
    {
        $transaction = Transaction::findOrFail($uuid);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }
}
