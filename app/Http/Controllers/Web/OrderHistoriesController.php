<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoriesController extends Controller
{
    public function index()
    {
        $orderHistories = OrderHistory::all();
        return view('admin.transaction.order-histories', compact('orderHistories'));
    }

    public function create()
    {
        return view('order_histories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|id|exists:orders,id',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        OrderHistory::create([
            'id' => (string) \Illuminate\Support\Str::id(),
            'order_id' => $request->order_id,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return redirect()->route('order_histories.index');
    }

    public function show(OrderHistory $orderHistory)
    {
        return view('order_histories.show', compact('orderHistory'));
    }

    public function edit(OrderHistory $orderHistory)
    {
        return view('order_histories.edit', compact('orderHistory'));
    }

    public function update(Request $request, OrderHistory $orderHistory)
    {
        $request->validate([
            'order_id' => 'required|id|exists:orders,id',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $orderHistory->update($request->all());

        return redirect()->route('order_histories.index');
    }

    public function destroy(OrderHistory $orderHistory)
    {
        $orderHistory->delete();
        return redirect()->route('order_histories.index');
    }
}
