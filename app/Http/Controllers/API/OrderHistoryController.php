<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orderHistories = OrderHistory::all();
        return response()->json($orderHistories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_uuid' => 'required|uuid|exists:orders,uuid',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $orderHistory = OrderHistory::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'order_uuid' => $request->order_uuid,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return response()->json($orderHistory, 201);
    }

    public function show($uuid)
    {
        $orderHistory = OrderHistory::findOrFail($uuid);
        return response()->json($orderHistory);
    }

    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'order_uuid' => 'required|uuid|exists:orders,uuid',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $orderHistory = OrderHistory::findOrFail($uuid);
        $orderHistory->update($request->all());

        return response()->json($orderHistory);
    }

    public function destroy($uuid)
    {
        $orderHistory = OrderHistory::findOrFail($uuid);
        $orderHistory->delete();

        return response()->json(null, 204);
    }
}
