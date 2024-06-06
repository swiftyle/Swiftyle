<?php

// App/Http/Controllers/CartController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the cart items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartItems = Cart::all();
        return response()->json($cartItems);
    }

    /**
     * Store a newly created cart item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_uuid' => 'required|exists:products,uuid',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cartItem = Cart::create($request->all());

        return response()->json($cartItem, 201);
    }

    /**
     * Display the specified cart item.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $cartItem = Cart::where('uuid', $uuid)->firstOrFail();
        return response()->json($cartItem);
    }

    /**
     * Update the specified cart item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $cartItem = Cart::where('uuid', $uuid)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'product_uuid' => 'sometimes|required|exists:products,uuid',
            'quantity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'subtotal' => 'sometimes|required|numeric|min:0',
            'total_price' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $cartItem->update($request->all());

        return response()->json($cartItem);
    }

    /**
     * Remove the specified cart item from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $cartItem = Cart::where('uuid', $uuid)->firstOrFail();
        $cartItem->delete();

        return response()->json(null, 204);
    }
}
