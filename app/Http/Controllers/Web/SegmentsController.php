<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Segment;
use Illuminate\Http\Request;

class SegmentsController extends Controller
{
    public function index(){

        $segments = Segment::all();
        return view('admin.specification.data-segment', compact('segments'));
    }
}
