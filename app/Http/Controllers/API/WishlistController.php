<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Display a listing of the wishlist items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $wishlist = Wishlist::all();
        return response()->json(['wishlist' => $wishlist]);
    }

    /**
     * Store a newly created wishlist item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_uuid' => 'required|exists:products,uuid',
            'user_uuid' => 'required|exists:users,uuid',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $wishlist = Wishlist::create($request->all());
        return response()->json(['message' => 'Wishlist item created successfully', 'wishlist' => $wishlist], 201);
    }

    /**
     * Display the specified wishlist item.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid)
    {
        $wishlist = Wishlist::where('uuid', $uuid)->firstOrFail();
        return response()->json(['wishlist' => $wishlist]);
    }

    /**
     * Update the specified wishlist item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $uuid)
    {
        $wishlist = Wishlist::where('uuid', $uuid)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'product_uuid' => 'exists:products,uuid',
            'user_uuid' => 'exists:users,uuid',
            'name' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $wishlist->update($request->all());
        return response()->json(['message' => 'Wishlist item updated successfully', 'wishlist' => $wishlist]);
    }

    /**
     * Remove the specified wishlist item from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($uuid)
    {
        $wishlist = Wishlist::where('uuid', $uuid)->firstOrFail();
        $wishlist->delete();

        return response()->json(['message' => 'Wishlist item deleted successfully']);
    }
}