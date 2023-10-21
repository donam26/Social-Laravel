<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Participant;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    public function __construct(Conversation $conversation, Participant $participant)
    {
        $this->conversation = $conversation;
        $this->participant = $participant;
    }

    public function listMess()
    {
        $user_id = auth()->user()->id;
        $listMess = $this->participant->where('user_id',$user_id)->with('conversation.latestMessage')->with('user')->get();
        return response()->json($listMess);
    }

    public function addConversation(Request $request)
    {
        $id_auth = auth()->user()->id;
        $user_id = $request->input('user_id');
        $name = $request->input('name');

        $conversationCheck = $this->conversation
            ->where(function ($query) use ($user1Id, $user2Id) {
                $query->where('user_id', $user1Id)
                    ->where('receiver_id', $user2Id);
            })->orWhere(function ($query) use ($user1Id, $user2Id) {
            $query->where('user_id', $user2Id)
                ->where('receiver_id', $user1Id);
        })->first();

        if (!$conversationCheck) {
            $newConver = $this->conversation->create([
                'user_id' => $user_id,
                'name' => $name,
            ]);
            $newConver->participants()->create([
                'user_id' => $user_id,
            ]);
        }
        return response()->json(['succes' => 'Tao cuoc tro chuyen thanh cong']);
    }

    public function getMessage($id_conver)
    {
        $conver = $this->conversation->find($id_conver);
        $messages = $conver->messages;
        return response()->json($messages);
    }

}
