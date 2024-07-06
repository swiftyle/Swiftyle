<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RefundRequestExport;


class RefundRequestController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('size', 10); // Default to 10 items per page
        $refundRequest = RefundRequest::paginate($pageSize);

        return view('admin.refund.refund-request', compact('refundRequest'));
    }

    public function show($id)
    {
        $refundRequest = RefundRequest::findOrFail($id);
        return view('admin.refund.refundRequests.show', compact('refundRequest'));
    }

    public function create()
    {
        return view('admin.refund.refundRequests.create');
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

        return redirect()->route('admin.refund.refundRequests.show', $refundRequest->id)->with('success', 'Refund request created successfully');
    }

    public function edit($id)
    {
        $refundRequest = RefundRequest::findOrFail($id);
        return view('admin.refund.refundRequests.edit', compact('refundRequest'));
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

        return redirect()->route('admin.refund.refundRequests.show', $refundRequest->id)->with('success', 'Refund request updated successfully');
    }

    public function destroy($id)
    {
        $refundRequest = RefundRequest::findOrFail($id);
        $refundRequest->delete();

        return redirect()->route('refundRequests.index')->with('success', 'Refund request deleted successfully');
    }
    
    
    public function printRefundRequest()
    {
        $refundRequest = RefundRequest::all();
        return view('admin.refund.print-refund-request', compact('refundRequest'));
    }

    public function exportexcel() 
    {
        return Excel::download(new RefundRequestExport, 'Refund Request.xlsx');
    }

    public function exportRefundRequest()
    {
        $refundRequest = RefundRequest::all();
        return view('admin.refund.export-refund-request', compact('refundRequest'));
    }
    public function addRefundRequest()
    {
        $refundRequest = RefundRequest::all();
        return view('admin.refund.add-refund-request', compact('refundRequest'));
    }

    public function approve($id)
{
    $refundRequest = RefundRequest::findOrFail($id);
    $refundRequest->status = 'approved';
    $refundRequest->save();

    return redirect()->route('refund-request.index')->with('success', 'Refund request approved successfully.');
}

public function reject($id)
{
    $refundRequest = RefundRequest::findOrFail($id);
    $refundRequest->status = 'rejected';
    $refundRequest->save();

    return redirect()->route('refund-request.index')->with('success', 'Refund request rejected successfully.');
}

}
