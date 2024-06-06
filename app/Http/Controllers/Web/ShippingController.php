<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::all();
        return view('shippings.index', compact('shippings'));
    }

    public function create()
    {
        $orders = Order::all();
        return view('shippings.create', compact('orders'));
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

        Shipping::create($request->all());

        return redirect()->route('shippings.index');
    }

    public function show(Shipping $shipping)
    {
        return view('shippings.show', compact('shipping'));
    }

    public function edit(Shipping $shipping)
    {
        $orders = Order::all();
        return view('shippings.edit', compact('shipping', 'orders'));
    }

    public function update(Request $request, Shipping $shipping)
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

        $shipping->update($request->all());

        return redirect()->route('shippings.index');
    }

    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return redirect()->route('shippings.index');
    }
}
