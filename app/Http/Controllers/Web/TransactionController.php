<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return view('transactions.index', compact('transactions'));
    }

    public function show($uuid)
    {
        $transaction = Transaction::findOrFail($uuid);
        return view('transactions.show', compact('transaction'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|uuid',
            'product_uuid' => 'required|uuid',
            'amount' => 'required|numeric',
            'type' => 'required|in:purchase,refund,payment,withdrawal',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $transaction = Transaction::create($validator->validated());

        return redirect()->route('transactions.show', $transaction->uuid)->with('success', 'Transaction created successfully');
    }

    public function edit($uuid)
    {
        $transaction = Transaction::findOrFail($uuid);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $uuid)
    {
        $transaction = Transaction::findOrFail($uuid);

        $validator = Validator::make($request->all(), [
            'user_uuid' => 'uuid',
            'product_uuid' => 'uuid',
            'amount' => 'numeric',
            'type' => 'in:purchase,refund,payment,withdrawal',
            'status' => 'string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $transaction->update($validator->validated());

        return redirect()->route('transactions.show', $transaction->uuid)->with('success', 'Transaction updated successfully');
    }

    public function destroy($uuid)
    {
        $transaction = Transaction::findOrFail($uuid);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully');
    }
}
