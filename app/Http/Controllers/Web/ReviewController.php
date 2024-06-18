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

    public function show($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.show', compact('review'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|id',
            'product_id' => 'required|id',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review = Review::create($validator->validated());

        return redirect()->route('reviews.show', $review->id)->with('success', 'Review created successfully');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'id',
            'product_id' => 'id',
            'content' => 'string',
            'rating' => 'integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $review->update($validator->validated());

        return redirect()->route('reviews.show', $review->id)->with('success', 'Review updated successfully');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully');
    }
}
