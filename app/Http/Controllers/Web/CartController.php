<?php

// App/Http/Controllers/Web/CartController.php
namespace App\Http\Controllers\Web;

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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = Cart::all();
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Show the form for creating a new cart item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $products = Product::all();
        return view('cart.create', compact('products'));
    }

    /**
     * Store a newly created cart item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Cart::create($request->all());

        return redirect()->route('cart.index')->with('success', 'Cart item created successfully.');
    }

    /**
     * Display the specified cart item.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $cartItem = Cart::where('id', $id)->firstOrFail();
        return view('cart.show', compact('cartItem'));
    }

    /**
     * Show the form for editing the specified cart item.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $cartItem = Cart::where('id', $id)->firstOrFail();
        $products = Product::all();
        return view('cart.edit', compact('cartItem', 'products'));
    }

    /**
     * Update the specified cart item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
            'subtotal' => 'sometimes|required|numeric|min:0',
            'total_price' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cartItem->update($request->all());

        return redirect()->route('cart.index')->with('success', 'Cart item updated successfully.');
    }

    /**
     * Remove the specified cart item from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)->firstOrFail();
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Cart item deleted successfully.');
    }
}
