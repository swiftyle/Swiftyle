<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }

    public function show($uuid)
    {
        $review = Review::findOrFail($uuid);
        return view('reviews.show', compact('review'));
    }

    public function create()
    {
        return view('reviews.create');
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review = Review::create($validator->validated());

        return redirect()->route('reviews.show', $review->uuid)->with('success', 'Review created successfully');
    }

    public function edit($uuid)
    {
        $review = Review::findOrFail($uuid);
        return view('reviews.edit', compact('review'));
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review->update($validator->validated());

        return redirect()->route('reviews.show', $review->uuid)->with('success', 'Review updated successfully');
    }

    public function destroy($uuid)
    {
        $review = Review::findOrFail($uuid);
        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully');
    }
}
