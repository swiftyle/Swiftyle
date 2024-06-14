<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $shippings = Shipping::all();
        return view('shippings.index', compact('shippings'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('shippings.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validation logic here if needed
        Shipping::create($request->all());
        return redirect()->route('shippings.index');
    }

    // Display the specified resource.
    public function show(Shipping $shipping)
    {
        return view('shippings.show', compact('shipping'));
    }

    // Show the form for editing the specified resource.
    public function edit(Shipping $shipping)
    {
        return view('shippings.edit', compact('shipping'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Shipping $shipping)
    {
        // Validation logic here if needed
        $shipping->update($request->all());
        return redirect()->route('shippings.index');
    }

    // Remove the specified resource from storage.
    public function destroy(Shipping $shipping)
    {
        $shipping->delete();
        return redirect()->route('shippings.index');
    }
}
