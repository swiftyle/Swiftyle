<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $checkouts = Checkout::all();
        return view('checkout.index', compact('checkouts'));
    }

    public function show($id)
    {
        $checkout = Checkout::findOrFail($id);
        return view('checkout.show', compact('checkout'));
    }

    public function create()
    {
        // Tampilkan form untuk membuat checkout
        return view('checkout.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'payment_method' => 'required|string',
        ]);

        $checkout = Checkout::create($validatedData);

        return redirect()->route('checkout.show', $checkout->id)->with('success', 'Checkout created successfully');
    }

    public function edit($id)
    {
        $checkout = Checkout::findOrFail($id);
        return view('checkout.edit', compact('checkout'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'order_uuid' => 'uuid',
            'user_uuid' => 'uuid',
            'payment_method' => 'string',
        ]);

        $checkout = Checkout::findOrFail($id);
        $checkout->update($validatedData);

        return redirect()->route('checkout.show', $checkout->id)->with('success', 'Checkout updated successfully');
    }

    public function destroy($id)
    {
        $checkout = Checkout::findOrFail($id);
        $checkout->delete();

        return redirect()->route('checkout.index')->with('success', 'Checkout deleted successfully');
    }
}
