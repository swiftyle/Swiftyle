<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Style;
use Illuminate\Http\Request;

class StylesController extends Controller
{
    public function index()
    {
        $styles = Style::all();
        return view('admin.specification.data-style', compact('styles'));
    }
}
