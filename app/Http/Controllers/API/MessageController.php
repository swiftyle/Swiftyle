<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return response()->json(['data' => $messages], 200);
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        return response()->json(['data' => $message], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'chat_uuid' => 'required|uuid',
            'sender_uuid' => 'required|uuid',
            'message' => 'required|string',
            'status' => 'required|in:sent,delivered,read',
        ]);

        $message = Message::create($validatedData);

        return response()->json(['message' => 'Message created successfully', 'data' => $message], 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'chat_uuid' => 'uuid',
            'sender_uuid' => 'uuid',
            'message' => 'string',
            'status' => 'in:sent,delivered,read',
        ]);

        $message = Message::findOrFail($id);
        $message->update($validatedData);

        return response()->json(['message' => 'Message updated successfully', 'data' => $message], 200);
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(['message' => 'Message deleted successfully'], 200);
    }
}
