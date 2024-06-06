<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // Methods for index, create, and store

    /**
     * Show the form for editing the specified wishlist item.
     *
     * @param  string  $uuid
     * @return \Illuminate\View\View
     */
    public function edit($uuid)
    {
        $wishlist = Wishlist::where('uuid', $uuid)->firstOrFail();
        return view('wishlist.edit', compact('wishlist'));
    }

    /**
     * Update the specified wishlist item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $uuid)
    {
        $wishlist = Wishlist::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'product_uuid' => 'required|exists:products,uuid',
            'user_uuid' => 'required|exists:users,uuid',
            'name' => 'required|string|max:255',
        ]);

        $wishlist->update($request->all());
        return redirect()->route('wishlist.index')->with('success', 'Wishlist item updated successfully.');
    }

    /**
     * Remove the specified wishlist item from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($uuid)
    {
        $wishlist = Wishlist::where('uuid', $uuid)->firstOrFail();
        $wishlist->delete();

        return redirect()->route('wishlist.index')->with('success', 'Wishlist item deleted successfully.');
    }
}
