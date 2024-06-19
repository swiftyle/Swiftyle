<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('products')->get();
        return view('promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::all();
        return view('promotions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage_discount,fixed_discount,buy_one_get_one',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'products' => 'nullable|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.discount_amount' => 'nullable|numeric|min:0',
            'products.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $promotion = Promotion::create([
            'id' => (string) \Illuminate\Support\Str::id(),
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'discount_amount' => $request->discount_amount,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($request->has('products')) {
            foreach ($request->products as $product) {
                $promotion->products()->attach($product['id'], [
                    'discount_amount' => $product['discount_amount'] ?? null,
                    'discount_percentage' => $product['discount_percentage'] ?? null,
                ]);
            }
        }

        return redirect()->route('promotions.index');
    }

    public function show(Promotion $promotion)
    {
        return view('promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $products = Product::all();
        return view('promotions.edit', compact('promotion', 'products'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage_discount,fixed_discount,buy_one_get_one',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'products' => 'nullable|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.discount_amount' => 'nullable|numeric|min:0',
            'products.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $promotion->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'discount_amount' => $request->discount_amount,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    
        if ($request->has('products')) {
            $promotion->products()->detach();
    
            foreach ($request->products as $product) {
                $promotion->products()->attach($product['id'], [
                    'discount_amount' => $product['discount_amount'] ?? null,
                    'discount_percentage' => $product['discount_percentage'] ?? null,
                ]);
            }
        }
    
        return redirect()->route('promotions.index');
    }
    
    public function destroy(Promotion $promotion)
    {
        $promotion->products()->detach();
        $promotion->delete();
        return redirect()->route('promotions.index');
    }
}