<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Display a listing of the shops.
     *
     * @return \Illuminate\View\View
     */
    
    public function index()
    {
        $shops = Shop::paginate(10);
        $totalShops = Shop::count();
        $activeShops = Shop::whereNotNull('deleted_at')->count();
        $inactiveShops = Shop::whereNull('deleted_at')->count();

        return view('admin.seller.data-shop', [
            'shops' => $shops,
            'totalShops' => $totalShops,
            'activeShops' => $activeShops,
            'inactiveShops' => $inactiveShops,
        ]);
    }

    /**
     * Show the form for creating a new shop.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shops.create');
    }

    /**
     * Store a newly created shop in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255|unique:shops',
            'logo' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:shops',
            'email' => 'required|string|email|max:255|unique:shops',
            'rating' => 'nullable|numeric|between:0,10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $shop = Shop::create($request->all());

        return redirect()->route('shops.index')->with('success', 'Shop created successfully.');
    }

    /**
     * Display the specified shop.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $shop = Shop::where('id', $id)->firstOrFail();
        return view('admin.shops.edit-shop', compact('shop'));
    }

    /**
     * Show the form for editing the specified shop.
     *
     * @param  string  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $shop = Shop::where('id', $id)->firstOrFail();
        return view('shops.edit', compact('shop'));
    }

    /**
     * Update the specified shop in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::where('id', $id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:shops,name,' . $shop->id,
            'logo' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:255|unique:shops,phone,' . $shop->id,
            'email' => 'sometimes|required|string|email|max:255|unique:shops,email,' . $shop->id,
            'rating' => 'nullable|numeric|between:0,10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $shop->update($request->all());

        return redirect()->route('shops.index')->with('success', 'Shop updated successfully.');
    }

    /**
     * Remove the specified shop from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $shop = Shop::where('id', $id)->firstOrFail();
        $shop->delete();

        return redirect()->route('shops.index')->with('success', 'Shop deleted successfully.');
    }
}
