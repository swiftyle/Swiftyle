<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function create(Request $request)
    {
        // Decode JWT token to get user data
        $user1 = $request->user();

        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'user2_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(422);
        }

        $validated = $validator->validated();

        // Create the chat record
        $chat = Chat::create([
            'user1_id' => $user1->id,
            'user2_id' => $validated['user2_id'],
        ]);

        return response()->json([
            'message' => 'Chat berhasil dibuat',
            'data' => $chat
        ], 201);
    }

    public function read(Request $request)
    {
        // Memeriksa apakah user ditemukan
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'msg' => 'Pengguna tidak terotentikasi'
            ], 401);
        }

        // Get all chat records where the user is either user1 or user2
        $chats = Chat::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->get();

        return response()->json([
            'message' => 'Data semua chat untuk pengguna saat ini',
            'data' => $chats
        ], 200);
    }


    // public function update(Request $request, $id)
    // {
    //     $user = $request->user();

    //     // Validate incoming request
    //     $validator = Validator::make($request->all(), [
    //         'user2_id' => 'required|exists:users,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->messages())->setStatusCode(422);
    //     }

    //     $validated = $validator->validated();

    //     // Find the chat record to update
    //     $chat = Chat::find($id);

    //     if (!$chat) {
    //         return response()->json(['message' => 'Chat tidak ditemukan'], 404);
    //     }

    //     // Check if the authenticated user is part of the chat
    //     if ($chat->user1_id !== $user->id && $chat->user2_id !== $user->id) {
    //         return response()->json(['message' => 'Anda tidak memiliki izin untuk mengupdate chat ini'], 403);
    //     }

    //     // Update the chat record
    //     $chat->user2_id = $validated['user2_id'];
    //     $chat->save();

    //     return response()->json([
    //         'message' => 'Chat berhasil diupdate',
    //         'data' => $chat
    //     ], 200);
    // }

    public function delete(Request $request, $id)
    {
        $user = $request->user();

        // Find the chat record to delete
        $chat = Chat::find($id);

        if (!$chat) {
            return response()->json(['message' => 'Chat tidak ditemukan'], 404);
        }

        // Check if the authenticated user is part of the chat
        if ($chat->user1_id !== $user->id && $chat->user2_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus chat ini'], 403);
        }

        // Delete the chat record
        $chat->delete();

        return response()->json([
            'message' => 'Chat berhasil dihapus'
        ], 200);
    }
}
