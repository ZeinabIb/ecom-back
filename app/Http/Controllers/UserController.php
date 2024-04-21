<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        $sellers = User::where('usertype', 'seller')->get();


        $sellerId = $sellers->first()->id;
        $messages = Chat::where('user_id', $sellerId)->get();

        return view('chat-users', ['users' => $sellers, 'messages' => $messages]);
    }



    public function storeMessage(Request $request)
    {

        $validatedData = $request->validate([
            'message' => 'required|string|max:255',
        ]);


        $message = new Chat();
        $message->user_id = auth()->id();
        $message->message = $validatedData['message'];
        $message->save();

        return response()->json(['message' => 'Message stored successfully'], 200);
    }
        public function startChat(User $user)
    {
        return view('index', ['recipient' => $user]);
    }

    public function fetchMessages($userId)
{

    $messages = Chat::where('user_id', $userId)->get();

    return response()->json($messages);
}




}
