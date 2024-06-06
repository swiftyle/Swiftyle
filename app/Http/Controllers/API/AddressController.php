<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'user_uuid' => 'required|uuid|exists:users,uuid',
            'country' => 'required|enum_value:' . \App\Enums\Country::class,
            'postal_code' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $address = Address::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'address' => $request->address,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'user_uuid' => $request->user_uuid,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
        ]);

        return response()->json($address, 201);
    }

    public function show($uuid)
    {
        $address = Address::findOrFail($uuid);
        return response()->json($address);
    }

    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'user_uuid' => 'required|uuid|exists:users,uuid',
            'country' => 'required|enum_value:' . \App\Enums\Country::class,
            'postal_code' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $address = Address::findOrFail($uuid);
        $address->update($request->all());

        return response()->json($address);
    }

    public function destroy($uuid)
    {
        $address = Address::findOrFail($uuid);
        $address->delete();

        return response()->json(null, 204);
    }
}
