<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Preference;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function index(){

        $preferences = Preference::all();
        return view('admin.specification.data-preference', compact('preferences'));
    }
}
