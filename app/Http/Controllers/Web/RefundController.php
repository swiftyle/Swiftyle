<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::all();
        return view('refunds.index', compact('refunds'));
    }

    public function show($id)
    {
        $refund = Refund::findOrFail($id);
        return view('refunds.show', compact('refund'));
    }

    public function create()
    {
        return view('refunds.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'refund_request_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'transaction_uuid' => 'required|uuid',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'reason' => 'string|nullable',
        ]);

        $refund = Refund::create($validatedData);

        return redirect()->route('refunds.show', $refund->uuid)->with('success', 'Refund created successfully');
    }

    public function edit($id)
    {
        $refund = Refund::findOrFail($id);
        return view('refunds.edit', compact('refund'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'refund_request_uuid' => 'uuid',
            'user_uuid' => 'uuid',
            'transaction_uuid' => 'uuid',
            'amount' => 'numeric',
            'status' => 'string',
            'reason' => 'string|nullable',
        ]);

        $refund = Refund::findOrFail($id);
        $refund->update($validatedData);

        return redirect()->route('refunds.show', $refund->uuid)->with('success', 'Refund updated successfully');
    }

    public function destroy($id)
    {
        $refund = Refund::findOrFail($id);
        $refund->delete();

        return redirect()->route('refunds.index')->with('success', 'Refund deleted successfully');
    }
}
