<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PreferenceExport;

class PreferencesController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->input('size', 10); // Default to 10 items per page
        $preferences = ($pageSize == -1) ? Preference::all() : Preference::paginate($pageSize);

        return view('admin.product.data-preference', compact('preferences'));
    }

    
    public function printPreference()
    {
        $preferences = Preference::all();
        return view('admin.product.print-preference', compact('preferences'));
    }

    public function exportexcel() 
    {
        return Excel::download(new PreferenceExport, 'preferences.xlsx');
    }

    public function exportPreference()
    {
        $preferences = Preference::all();
        return view('admin.product.export-data-preference', compact('preferences'));
    }

    public function addpreference()
    {
        $preferences = Preference::all();
        return view('admin.product.add-preference', compact('preferences'));
    }
}

