<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['content' => 'required|min:3']);

        $message = auth()->user()->messages()->create([
            "content" => $request->content,
            'chat_id' => $request->chat_id
        ])->load('user');

        broadcast(new NewMessage($message))->toOthers();

        return $message;
        // return redirect()->action([ChatController::class, 'show'], $message->chat->id);
    }
}
