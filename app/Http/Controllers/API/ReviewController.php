<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return response()->json(['data' => $reviews], 200);
    }

    public function show($uuid)
    {
        $review = Review::findOrFail($uuid);
        return response()->json(['data' => $review], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|uuid',
            'product_uuid' => 'required|uuid',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = Review::create($validator->validated());

        return response()->json(['message' => 'Review created successfully', 'data' => $review], 201);
    }

    public function update(Request $request, $uuid)
    {
        $review = Review::findOrFail($uuid);

        $validator = Validator::make($request->all(), [
            'user_uuid' => 'uuid',
            'product_uuid' => 'uuid',
            'content' => 'string',
            'rating' => 'integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review->update($validator->validated());

        return response()->json(['message' => 'Review updated successfully', 'data' => $review], 200);
    }

    public function destroy($uuid)
    {
        $review = Review::findOrFail($uuid);
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }
}
