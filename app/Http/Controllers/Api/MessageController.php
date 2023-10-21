<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function sendMessage(Request $request, $id_conver)
    {
        $user_id = auth()->user()->id;
        $content = $request->input('content');
        $messageSend = $this->message->create([
            'user_id' => $user_id,
            'conversation_id' => $id_conver,
            'content' => $content,
            'status' => 1
        ]);

        return response()->json($messageSend);
    }

    
}
