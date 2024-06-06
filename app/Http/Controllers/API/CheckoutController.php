<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $checkouts = Checkout::all();
        return response()->json(['data' => $checkouts], 200);
    }

    public function show($id)
    {
        $checkout = Checkout::findOrFail($id);
        return response()->json(['data' => $checkout], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'payment_method' => 'required|string',
        ]);

        $checkout = Checkout::create($validatedData);

        return response()->json(['message' => 'Checkout created successfully', 'data' => $checkout], 201);
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

        return response()->json(['message' => 'Checkout updated successfully', 'data' => $checkout], 200);
    }

    public function destroy($id)
    {
        $checkout = Checkout::findOrFail($id);
        $checkout->delete();

        return response()->json(['message' => 'Checkout deleted successfully'], 200);
    }
}
