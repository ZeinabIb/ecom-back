<?php

namespace App\Http\Controllers;

use App\Events\PusherBroadcast;
use App\Events\SendNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PusherController extends Controller
{
    public function index(){
        return view('chat-users');
    }

    public function showUserList() {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('chat-users', ['users' => $users]);
    }

    public function startChatWithUser(User $user) {
        return view('chat-users', ['recipient' => $user]);
    }


    public function broadcast(Request $request){
        broadcast(new PusherBroadcast($request->get('message')))->toOthers();
        return view('broadcast', ['message' => $request->get('message')]);
    }

    public function receive(Request $request){
        return view('receive', ['message' => $request->get('message')]);
    }

    public function sendPusher(Request $request)
    {
        $message = $request->input('message');
        $data = [
            'sender' => auth()->user()->name,
            'message' => $message,
            'type' => 'sender'
        ];
        event(new SendNotification($data, 'my-channel', 'my-event'));
        return response()->json(['status' => 'success']);
    }
}
