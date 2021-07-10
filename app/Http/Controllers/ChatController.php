<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::whereHas('users', function ($q) {
            $q->where('chat_user.user_id', auth()->id());
        })->get();
        return view('chats.index', compact('chats'));
    }

    public function create()
    {
        $users = User::where('role', '!=', auth()->user()->role)->get();
        return view('chats.create', compact('users'));
    }

    public function store(Request $request)
    {
        $user_a = auth()->user();
        $user_b = User::findOrFail($request->receiver_id);

        $chat = $user_a->chats()->whereHas('users', function ($q) use ($user_b) {
            $q->where('chat_user.user_id', $user_b->id);
        })->first();

        if (!$chat) {
            $chat = Chat::create([]);
            $chat->users()->sync([$user_a->id, $user_b->id]);
        }

        return redirect()->action([ChatController::class, 'show'], $chat->id);
    }

    public function show($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->not_read_messages()->update([
            'read_at' => now()
        ]);
        abort_unless($chat->users->contains(auth()->id()), 403);
        return view('chats.show', compact('chat'));
    }

    public function get_messages(Chat $chat)
    {
        $messages = $chat->messages()->with('user')->get();

        return response()->json([
            'messages' => $messages
        ]);
    }
}
