<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RefundRequestController extends Controller
{
    public function index()
    {
        $refundRequests = RefundRequest::all();
        return view('refundRequests.index', compact('refundRequests'));
    }

    public function show($id)
    {
        $refundRequest = RefundRequest::findOrFail($id);
        return view('refundRequests.show', compact('refundRequest'));
    }

    public function create()
    {
        return view('refundRequests.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|id',
            'order_id' => 'required|id',
            'reason' => 'required|string',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $refundRequest = RefundRequest::create($validator->validated());

        return redirect()->route('refundRequests.show', $refundRequest->id)->with('success', 'Refund request created successfully');
    }

    public function edit($id)
    {
        $refundRequest = RefundRequest::findOrFail($id);
        return view('refundRequests.edit', compact('refundRequest'));
    }

    public function update(Request $request, $id)
    {
        $refundRequest = RefundRequest::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'id',
            'order_id' => 'id',
            'reason' => 'string',
            'status' => 'string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $refundRequest->update($validator->validated());

        return redirect()->route('refundRequests.show', $refundRequest->id)->with('success', 'Refund request updated successfully');
    }

    public function destroy($id)
    {
        $refundRequest = RefundRequest::findOrFail($id);
        $refundRequest->delete();

        return redirect()->route('refundRequests.index')->with('success', 'Refund request deleted successfully');
    }
}
