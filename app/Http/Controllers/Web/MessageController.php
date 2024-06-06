<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return view('message.index', compact('messages'));
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('message.show', compact('message'));
    }

    public function create()
    {
        // Tampilkan form untuk membuat pesan
        return view('message.create');
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

        return redirect()->route('message.show', $message->id)->with('success', 'Message created successfully');
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);
        return view('message.edit', compact('message'));
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

        return redirect()->route('message.show', $message->id)->with('success', 'Message updated successfully');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('message.index')->with('success', 'Message deleted successfully');
    }
}
