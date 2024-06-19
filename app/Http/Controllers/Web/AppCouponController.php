<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AppCoupon;
use Illuminate\Http\Request;

class AppCouponController extends Controller
{
    public function index()
    {
        $AppCoupons = AppCoupon::all();
        return view('AppCoupons.index', compact('AppCoupons'));
    }

    public function create()
    {
        return view('AppCoupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:AppCoupons',
            'type' => 'required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        AppCoupon::create($request->all());

        return redirect()->route('AppCoupons.index');
    }

    public function show(AppCoupon $AppCoupon)
    {
        return view('AppCoupons.show', compact('AppCoupon'));
    }

    public function edit(AppCoupon $AppCoupon)
    {
        return view('AppCoupons.edit', compact('AppCoupon'));
    }

    public function update(Request $request, AppCoupon $AppCoupon)
    {
        $request->validate([
            'code' => 'required|unique:AppCoupons,code,' . $AppCoupon->id,
            'type' => 'required|in:percentage_discount,fixed_discount',
            'discount_amount' => 'required|numeric',
            'max_uses' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $AppCoupon->update($request->all());

        return redirect()->route('AppCoupons.index');
    }

    public function destroy(AppCoupon $AppCoupon)
    {
        $AppCoupon->delete();
        return redirect()->route('AppCoupons.index');
    }
}
