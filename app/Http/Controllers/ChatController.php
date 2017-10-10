<?php

namespace App\Http\Controllers;

use App\Message;
use App\Events\MessageSent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat');
    }

    public function fetchMessage()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $id = Auth::id();
        $user = User::find($id);

        $message = $user->messages()->create([
            'message' => $request->get('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent! '.$message];
    }
}
