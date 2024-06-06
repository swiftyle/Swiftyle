<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintsController extends Controller
{
    public function index()
    {
        $complaints = Complaint::all();
        return response()->json($complaints);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_uuid' => 'required|uuid',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $complaint = Complaint::create($request->all());
        return response()->json($complaint, 201);
    }

    public function show($uuid)
    {
        $complaint = Complaint::where('uuid', $uuid)->firstOrFail();
        return response()->json($complaint);
    }

    public function update(Request $request, $uuid)
    {
        $complaint = Complaint::where('uuid', $uuid)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'subject' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,resolved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $complaint->update($request->all());
        return response()->json($complaint);
    }

    public function destroy($uuid)
    {
        $complaint = Complaint::where('uuid', $uuid)->firstOrFail();
        $complaint->delete();
        return response()->json(null, 204);
    }
}
