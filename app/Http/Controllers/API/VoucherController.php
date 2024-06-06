<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return Voucher::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers',
            'type' => 'required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $voucher = Voucher::create($request->all());

        return response()->json($voucher, 201);
    }

    public function show($uuid)
    {
        return Voucher::findOrFail($uuid);
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $uuid,
            'type' => 'required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $voucher = Voucher::findOrFail($uuid);
        $voucher->update($request->all());

        return response()->json($voucher);
    }

    public function destroy($uuid)
    {
        $voucher = Voucher::findOrFail($uuid);
        $voucher->delete();

        return response()->json(null, 204);
    }
}
