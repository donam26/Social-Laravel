<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Events\Test;

class MessageController extends Controller
{
    protected $message;
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
 
    public function sendMessage(Request $request, $id_conver)
    {
        $user_id = auth()->user()->id;
        $content = $request->input('content');

        $message = $this->message->create([
            'user_id' => $user_id,
            'conversation_id' => $id_conver,
            'content' => $content,
            'status' => 1
        ]);
        broadcast(new Test ($message))->toOthers();
        return response()->json($message);
    }

    
}
