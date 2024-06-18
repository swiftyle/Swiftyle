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
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $wishlist = Wishlist::where('id', $id)->firstOrFail();
        return view('wishlist.edit', compact('wishlist'));
    }

    /**
     * Update the specified wishlist item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $wishlist = Wishlist::where('id', $id)->firstOrFail();

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
        ]);

        $wishlist->update($request->all());
        return redirect()->route('wishlist.index')->with('success', 'Wishlist item updated successfully.');
    }

    /**
     * Remove the specified wishlist item from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::where('id', $id)->firstOrFail();
        $wishlist->delete();

        return redirect()->route('wishlist.index')->with('success', 'Wishlist item deleted successfully.');
    }
}
