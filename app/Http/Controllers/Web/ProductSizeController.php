<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    public function index()
    {
        $productSizes = ProductSize::all();
        return view('product_size.index', compact('productSizes'));
    }

    public function show($id)
    {
        $productSize = ProductSize::findOrFail($id);
        return view('product_size.show', compact('productSize'));
    }

    public function create()
    {
        // Tampilkan form untuk membuat ukuran produk
        return view('product_size.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|id',
            'size' => 'required|string',
            'stock' => 'required|integer',
        ]);

        $productSize = ProductSize::create($validatedData);

        return redirect()->route('product_size.show', $productSize->id)->with('success', 'Product size created successfully');
    }

    public function edit($id)
    {
        $productSize = ProductSize::findOrFail($id);
        return view('product_size.edit', compact('productSize'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_id' => 'id',
            'size' => 'string',
            'stock' => 'integer',
        ]);

        $productSize = ProductSize::findOrFail($id);
        $productSize->update($validatedData);

        return redirect()->route('product_size.show', $productSize->id)->with('success', 'Product size updated successfully');
    }

    public function destroy($id)
    {
        $productSize = ProductSize::findOrFail($id);
        $productSize->delete();

        return redirect()->route('product_size.index')->with('success', 'Product size deleted successfully');
    }
}
