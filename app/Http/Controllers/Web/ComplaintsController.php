<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    public function index()
    {
        $complaints = Complaint::all();  // Corrected variable name
        return view('admin.transaction.data-complaint', compact('complaints'));
    }
}
