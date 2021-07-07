<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with('not_read_messages')->whereJsonContains('users', ['id' => auth()->user()->id])->get();
        return view('chats.index', compact('chats'));
    }

    public function create()
    {
        $users = User::where('role', '!=', auth()->user()->role)->get();
        return view('chats.create', compact('users'));
    }

    public function store(Request $request)
    {
        $receiver = User::findOrFail($request->receiver_id);
        $last = Chat::latest()->first();
        $name = 'chat-1';
        
        if (is_object($last)) {
            $id = ($last->id*1) + 1;
            $name = 'chat-' . $id;
        }

        if (auth()->user()->role == 'teacher') {
            $users = [['id' => auth()->user()->id], ['id' => $receiver->id]];
        } else $users = [['id' => $receiver->id], ['id' => auth()->user()->id]];

        $chat = Chat::whereJsonContains('users', $users)->get()->first();

        if (!is_object($chat)) {
            $chat = Chat::create([
                'name' => $name,
                'users' => $users
            ]);
        }
        
        return redirect()->action([ChatController::class, 'show'], $chat->id);
    }

    public function show($id)
    {
        $chat = Chat::with('not_read_messages')->findOrFail($id);
        $chat->not_read_messages()->update([
            'read_at' => now()
        ]);
        return view('chats.show', compact('chat'));
    }
}
