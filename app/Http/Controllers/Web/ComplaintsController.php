<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ComplaintExport;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
    $pageSize = $request->input('size', 10); // Default to 10 items per page
    $complaints = Complaint::paginate($pageSize);

    return view('admin.transaction.data-complaint', compact('complaints'));
    }

    public function printComplaints()
    {
        $complaints = Complaint::all();
        return view('admin.transaction.print-complaint', compact('complaints'));
    }

    public function exportexcel() 
    {
        return Excel::download(new ComplaintExport, 'complaints.xlsx');
    }

    public function exportComplaints()
    {
        $complaints = Complaint::all();
        return view('admin.transaction.export-data-complaint', compact('complaints'));
    }
    public function addHistories()
    {
        $orderHistories = Complaint::all();
        return view('admin.transaction.add-complaint', compact('complaints'));
    }
}
