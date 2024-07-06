<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderHistoryExport;

class OrderHistoriesController extends Controller
{
    public function index(Request $request)
{
    $pageSize = $request->input('size', 10); // Default to 10 items per page
    $orderHistories = OrderHistory::paginate($pageSize);

    return view('admin.transaction.data-order-histories', compact('orderHistories'));
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

    public function show(OrderHistory $orderHistories)
    {
        return view('order_histories.show', compact('orderHistories'));
    }

    public function edit(OrderHistory $orderHistories)
    {
        return view('order_histories.edit', compact('orderHistories'));
    }

    public function update(Request $request, OrderHistory $orderHistories)
    {
        $request->validate([
            'order_id' => 'required|id|exists:orders,id',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $orderHistories->update($request->all());

        return redirect()->route('order_histories.index');
    }

    public function destroy(OrderHistory $orderHistories)
    {
        $orderHistories->delete();
        return redirect()->route('order_histories.index');
    }
    
    public function printOrderHistories()
    {
        $orderHistories = OrderHistory::all();
        return view('admin.transaction.print-order-histories', compact('orderHistories'));
    }

    public function exportexcel() 
    {
        return Excel::download(new OrderHistoryExport, 'order-history.xlsx');
    }

    public function exportOrderHistories()
    {
        $orderHistories = OrderHistory::all();
        return view('admin.transaction.export-data-order-histories', compact('orderHistories'));
    }
    public function addHistories()
    {
        $orderHistories = OrderHistory::all();
        return view('admin.transaction.add-order-histories', compact('orderHistories'));
    }
}
