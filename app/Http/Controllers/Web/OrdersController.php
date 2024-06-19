<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::paginate(10);
        $totalOrders = Order::count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $receivedOrders = Order::where('status', 'received')->count();
        $reviewedOrders = Order::where('status', 'reviewed')->count();

        return view('admin.seller.data-order', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'deliveredOrders' => $deliveredOrders,
            'receivedOrders' => $receivedOrders,
            'reviewedOrders' => $reviewedOrders,
        ]);
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
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'transaction_id' => 'required|string|max:255|unique:orders',
            'shipping_id' => 'required|exists:shipping,id',
            'status' => 'required|in:delivered,received,reviewed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = Order::create($request->all());

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('admin.orders.edit-order', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $order = Order::where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'sometimes|required|string|max:255|unique:orders,transaction_id,' . $order->id,
            'shipping_id' => 'sometimes|required|exists:shipping,id',
            'status' => 'sometimes|required|in:delivered,received,reviewed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order->update($request->all());

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = Order::where('id', $id)->firstOrFail();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
