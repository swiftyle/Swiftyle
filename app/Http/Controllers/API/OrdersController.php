<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json(['orders' => $orders]);
    }

    /**
     * Display the specified order.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        return response()->json(['order' => $order]);
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'user_uuid' => 'exists:users,uuid',
            'product_uuid' => 'exists:products,uuid',
            'quantity' => 'integer|min:1',
            'total' => 'integer|min:0',
            'address_id' => 'nullable|exists:addresses,id',
            'user_payment_uuid' => 'nullable|exists:user_payments,uuid',
            'status' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update($request->all());
        return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
