<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::all();
        return view('admin.transaction.data-order', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_uuid' => 'required|exists:users,uuid',
            'product_uuid' => 'required|exists:products,uuid',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|integer|min:0',
            'address_id' => 'nullable|exists:addresses,id',
            'user_payment_uuid' => 'nullable|exists:user_payments,uuid',
            'status' => 'required|string|max:255',
        ]);

        $order = Order::create($request->all());
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order.
     *
     * @param  string  $uuid
     * @return \Illuminate\View\View
     */
    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param  string  $uuid
     * @return \Illuminate\View\View
     */
    public function edit($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'user_uuid' => 'required|exists:users,uuid',
            'product_uuid' => 'required|exists:products,uuid',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|integer|min:0',
            'address_id' => 'nullable|exists:addresses,id',
            'user_payment_uuid' => 'nullable|exists:user_payments,uuid',
            'status' => 'required|string|max:255',
        ]);

        $order->update($request->all());
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
