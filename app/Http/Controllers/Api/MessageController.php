<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required|exists:chats,id',
            'content' => 'required|string',
            'status' => 'sometimes|in:send,read,pending,deleted,edited'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the message
        $message = Message::create([
            'chat_id' => $validated['chat_id'],
            'content' => $validated['content'],
            'status' => $validated['status'] ?? 'pending',
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dibuat',
            'data' => $message
        ], 201);
    }

    public function read()
    {
        $messages = Message::all();

        return response()->json([
            'message' => 'Data Pesan',
            'data' => $messages
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|in:send,read,pending,deleted,edited'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Find the message
        $message = Message::find($id);

        if ($message) {
            // Update the message
            $message->update($validated);

            return response()->json([
                'message' => 'Pesan dengan id: ' . $id . ' berhasil diupdate',
                'data' => $message
            ], 200);
        }

        return response()->json([
            'message' => 'Pesan dengan id: ' . $id . ' tidak ditemukan'
        ], 404);
    }

    public function delete($id)
    {
        // Find the message
        $message = Message::find($id);

        if ($message) {
            // Soft delete the message
            $message->delete();

            return response()->json([
                'message' => 'Pesan dengan id: ' . $id . ' berhasil dihapus'
            ], 200);
        }

        return response()->json([
            'message' => 'Pesan dengan id: ' . $id . ' tidak ditemukan',
        ], 404);
    }
}
