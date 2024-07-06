<?php

namespace App\Http\Controllers\Api;

use App\Events\ReviewCreated;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        // Add user ID to the validated data
        $validated = $validator->validated();
        $validated['user_id'] = $user->id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('reviewImage');
            $file->move($filePath, $fileName);
            $validated['image'] = 'public/reviewImage/' . $fileName;
        }

        try {
            // Create the review
            $review = Review::create($validated);

            // Trigger event
            event(new ReviewCreated($review));

            return response()->json([
                'message' => 'Review created successfully',
                'data' => $review
            ], 201);
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error creating review: ' . $e->getMessage());

            return response()->json([
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function readAll(Request $request)
    {
        $reviews = Review::all();

        return response()->json([
            'message' => 'Reviews fetched successfully',
            'data' => $reviews
        ], 200);
    }

    public function read(Request $request)
        {
            $user = $request->user();
    
            // Misalnya, Anda ingin mendapatkan semua review yang ditulis oleh pengguna
            $reviews = Review::where('user_id', $user->id)->get();
    
            if ($reviews->isEmpty()) {
                return response()->json(['message' => 'Reviews not found'], 404);
            }
    
            return response()->json([
                'message' => 'Reviews fetched successfully',
                'data' => $reviews
            ], 200);
        }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'product_id' => 'exists:products,id',
            'rating' => 'integer|min:1|max:5',
            'order_id' => 'exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $validated = $validator->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = public_path('reviewImage');
            $file->move($filePath, $fileName);
            $validated['image'] = 'public/reviewImage/' . $fileName;
        }

        $review->update($validated);

        return response()->json([
            'message' => 'Review updated successfully',
            'data' => $review
        ], 200);
    }

    public function delete($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }
}
