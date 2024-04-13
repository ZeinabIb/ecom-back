<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class PusherController extends Controller
{
    public function index(){
        return view('index');

    }
    public function showUserList() {
        // Retrieve the list of users from the database
        $users = User::where('id', '!=', auth()->id())->get();

        return view('user_list', ['users' => $users]);
    }

    public function startChatWithUser(User $user) {
        // Redirect to the chat page with the selected user's information
        return view('index', ['recipient' => $user]);
    }
    public function broadcast(Request $request){


        broadcast(new PusherBroadcast($request->get('message')))->toOthers();
        return view('broadcast', ['message' => $request->get('message')]);

    }

    // public function broadcast(Request $request){
    //     // Get the authenticated user (sender)
    //     $sender = Auth::user();

    //     // Get the message from the request
    //     $message = $request->get('message');

    //     // Identify the receiver(s) - example logic (modify as needed)
    //     // For example, if you want to send the message to all users except the sender:
    //     $receivers = User::where('id', '!=', $sender->id)->get();

    //     // Broadcast the message to each receiver
    //     foreach ($receivers as $receiver) {
    //         broadcast(new PusherBroadcast($message, $sender, $receiver))->toOthers();
    //     }

    //     return view('broadcast', ['message' => $message]);
    // }

    public function receive(Request $request){
        return view('receive', ['message' => $request->get('message')]);

    }
}
