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
        $request->validate(['content'=>'required|min:3']);

        $chat = Chat::with(['messages'])->findOrFail($request->chat_id);

        $aux = array_filter($chat->users, function($item){
            return $item['id'] != auth()->user()->id;
        });

        $aux = array_values($aux);

        $receiver_id = User::findOrFail($aux[0]['id']);

        $message = Message::create([
            "chat_id"=> $chat->id,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $receiver_id->id,
            "content" => $request->content
        ]);

        broadcast(new NewMessage($message))->toOthers();

        return redirect()->action([ChatController::class, 'show'], $chat->id);
    }
}
