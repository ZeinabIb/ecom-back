<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    public function saveMessage(Request $request)
    {
        $user = auth()->user();
        $content = $request->input('message');

        $chat = new Chat();
        $chat->user_id = $user->id;
        $chat->content = $content;
        $chat->save();

        return response()->json(['success' => true]);
    }

    public function fetchMessages()
    {
        $user = auth()->user();
        $messages = Chat::where('user_id', $user->id)->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }
}
