<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return view('chats.index', compact('chats'));
    }

    public function create()
    {
        return view('chats.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user1_uuid' => 'required|uuid|exists:users,uuid',
            'user2_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        Chat::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user1_uuid' => $request->user1_uuid,
            'user2_uuid' => $request->user2_uuid,
        ]);

        return redirect()->route('chats.index');
    }

    public function show(Chat $chat)
    {
        return view('chats.show', compact('chat'));
    }

    public function edit(Chat $chat)
    {
        return view('chats.edit', compact('chat'));
    }

    public function update(Request $request, Chat $chat)
    {
        $request->validate([
            'user1_uuid' => 'required|uuid|exists:users,uuid',
            'user2_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        $chat->update($request->all());

        return redirect()->route('chats.index');
    }

    public function destroy(Chat $chat)
    {
        $chat->delete();
        return redirect()->route('chats.index');
    }
}
