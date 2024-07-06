<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

class OrdersController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
    $size = $request->query('size', 10); // Default to 10 if no size parameter is provided
    $orders = Order::paginate($size);

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

        $orders = Order::create($request->all());

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
        $orders = Order::where('id', $id)->firstOrFail();
        return view('admin.orders.edit-order', compact('orders'));
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $orders = Order::where('id', $id)->firstOrFail();
        return view('orders.edit', compact('orders'));
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
        $orders = Order::where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'sometimes|required|string|max:255|unique:orders,transaction_id,' . $orders->id,
            'shipping_id' => 'sometimes|required|exists:shipping,id',
            'status' => 'sometimes|required|in:delivered,received,reviewed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $orders->update($request->all());

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
        $orders = Order::where('id', $id)->firstOrFail();
        $orders->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function printOrder()
    {
        $orders = Order::all();
        return view('admin.seller.print-order', compact('orders'));
    }

    public function exportexcel() 
    {
        return Excel::download(new OrderExport, 'order.xlsx');
    }

    public function exportOrder()
    {
        $orders = Order::all();
        return view('admin.seller.export-data-order', compact('orders'));
    }
    public function addOrder()
    {
        $orders = Order::all();
        return view('admin.seller.add-order', compact('orders'));
    }
}
