<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user1_uuid' => 'required|uuid|exists:users,uuid',
            'user2_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $chat = Chat::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user1_uuid' => $request->user1_uuid,
            'user2_uuid' => $request->user2_uuid,
        ]);

        return response()->json($chat, 201);
    }

    public function show($uuid)
    {
        $chat = Chat::findOrFail($uuid);
        return response()->json($chat);
    }

    public function update(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'user1_uuid' => 'required|uuid|exists:users,uuid',
            'user2_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $chat = Chat::findOrFail($uuid);
        $chat->update($request->all());

        return response()->json($chat);
    }

    public function destroy($uuid)
    {
        $chat = Chat::findOrFail($uuid);
        $chat->delete();

        return response()->json(null, 204);
    }
}
