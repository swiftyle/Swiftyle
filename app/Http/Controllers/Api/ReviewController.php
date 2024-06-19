<?php

namespace App\Http\Controllers\Api;

use App\Events\ReviewCreated;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'content' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Create the review
        $review = Review::create([
            'user_id' => $request->input('user_id'),
            'product_id' => $request->input('product_id'),
            'content' => $request->input('content'),
            'rating' => $request->input('rating'),
            'order_id' => $request->input('order_id'),
        ]);

        event(new ReviewCreated($review));
        return response()->json([
            'message' => 'Review created successfully',
            'data' => $review
        ], 201);
    }

    public function readAll(Request $request)
    {
        $user = $request->user();
        // Fetch all reviews
        $reviews = Review::all();

        return response()->json([
            'message' => 'Reviews fetched successfully',
            'data' => $reviews
        ], 200);
    }

    public function read(Request $request, $id)
    {
        $user = $request->user();
        // Fetch review by ID
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'])->setStatusCode(404);
        }

        return response()->json([
            'message' => 'Review fetched successfully',
            'data' => $review
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'product_id' => 'exists:products,id',
            'rating' => 'integer|min:1|max:5',
            'order_id' => 'exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        // Find the review
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'])->setStatusCode(404);
        }

        // Update the review
        $review->user_id = $request->input('user_id', $review->user_id);
        $review->product_id = $request->input('product_id', $review->product_id);
        $review->content = $request->input('content', $review->content);
        $review->rating = $request->input('rating', $review->rating);
        $review->order_id = $request->input('order_id', $review->order_id);
        $review->save();

        return response()->json([
            'message' => 'Review updated successfully',
            'data' => $review
        ], 200);
    }

    public function delete($id)
    {
        // Find the review
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'])->setStatusCode(404);
        }

        // Delete the review
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }
}
