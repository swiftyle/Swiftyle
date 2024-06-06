<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return Shipping::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_uuid' => 'required|exists:orders,uuid',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'shipping_method' => 'required|string',
            'shipping_cost' => 'required|numeric',
            'status' => 'in:pending,shipped,delivered,cancelled'
        ]);

        $shipping = Shipping::create($request->all());

        return response()->json($shipping, 201);
    }

    public function show($uuid)
    {
        return Shipping::findOrFail($uuid);
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric',
            'status' => 'nullable|in:pending,shipped,delivered,cancelled'
        ]);

        $shipping = Shipping::findOrFail($uuid);
        $shipping->update($request->all());

        return response()->json($shipping);
    }

    public function destroy($uuid)
    {
        $shipping = Shipping::findOrFail($uuid);
        $shipping->delete();

        return response()->json(null, 204);
    }
}
