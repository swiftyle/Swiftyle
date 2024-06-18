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
            'user1_id' => 'required|id|exists:users,id',
            'user2_id' => 'required|id|exists:users,id',
        ]);

        Chat::create([
            'id' => (string) \Illuminate\Support\Str::id(),
            'user1_id' => $request->user1_id,
            'user2_id' => $request->user2_id,
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
            'user1_id' => 'required|id|exists:users,id',
            'user2_id' => 'required|id|exists:users,id',
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
