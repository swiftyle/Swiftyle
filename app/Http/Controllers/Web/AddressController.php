<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'user_uuid' => 'required|uuid|exists:users,uuid',
            'country' => 'required|enum_value:' . \App\Enums\Country::class,
            'postal_code' => 'required|integer',
        ]);

        Address::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'address' => $request->address,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_uuid' => $request->user_uuid,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
        ]);

        return redirect()->route('addresses.index');
    }

    public function show(Address $address)
    {
        return view('addresses.show', compact('address'));
    }

    public function edit(Address $address)
    {
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'user_uuid' => 'required|uuid|exists:users,uuid',
            'country' => 'required|enum_value:' . \App\Enums\Country::class,
            'postal_code' => 'required|integer',
        ]);

        $address->update($request->all());

        return redirect()->route('addresses.index');
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return redirect()->route('addresses.index');
    }
}
