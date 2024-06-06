<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('products')->get();
        return response()->json($promotions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage_discount,fixed_discount,buy_one_get_one',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'products' => 'nullable|array',
            'products.*.uuid' => 'required|exists:products,uuid',
            'products.*.discount_amount' => 'nullable|numeric|min:0',
            'products.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $promotion = Promotion::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
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
                $promotion->products()->attach($product['uuid'], [
                    'discount_amount' => $product['discount_amount'] ?? null,
                    'discount_percentage' => $product['discount_percentage'] ?? null,
                ]);
            }
        }

        $promotion = $promotion->fresh('products');

        return response()->json($promotion, 201);
    }

    public function show($uuid)
    {
        $promotion = Promotion::with('products')->findOrFail($uuid);
        return response()->json($promotion);
    }

    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage_discount,fixed_discount,buy_one_get_one',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'products' => 'nullable|array',
            'products.*.uuid' => 'required|exists:products,uuid',
            'products.*.discount_amount' => 'nullable|numeric|min:0',
            'products.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $promotion = Promotion::findOrFail($uuid);
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
                $promotion->products()->attach($product['uuid'], [
                    'discount_amount' => $product['discount_amount'] ?? null,
                    'discount_percentage' => $product['discount_percentage'] ?? null,
                ]);
            }
        }

        $promotion = $promotion->fresh('products');

        return response()->json($promotion);
    }

    public function destroy($uuid)
    {
        $promotion = Promotion::findOrFail($uuid);
        $promotion->products()->detach();
        $promotion->delete();

        return response()->json(null, 204);
    }
}
