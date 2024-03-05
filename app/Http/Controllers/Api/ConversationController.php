<?php

namespace App\Http\Controllers\Api;

use App\Events\TestEvent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\AddGroupToUser;

class ConversationController extends Controller
{

    protected $conversation;
    protected $participant;
    public function __construct(Conversation $conversation, Participant $participant)
    {
        $this->conversation = $conversation;
        $this->participant = $participant;
    }

    public function listMess()
    {
        $user_id = auth()->user()->id;
        $conversationIds = $this->participant->where('user_id', $user_id)->pluck('conversation_id')->toArray();
        $listMess = $this->conversation
            ->whereIn('id', $conversationIds)
            ->with(['latestMessage', 'participants' => function ($query) use ($user_id) {
                $query->where('user_id', '!=', $user_id);
            }, 'participants.user']) // Load the user for the participants
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $listMess,
        ]);
    }

    public function createGroupChat(Request $request)
    {
        
        $user_id = auth()->user()->id;
        $participant_ids = $request->input('participant_ids');
        $participantIdsArray = explode(',', $participant_ids);
        $participantIdsArray[] = $user_id;
        $data = [
            'name' => $request->input('name'),
            'created_user' => $user_id,
        ];
        if ($request->hasFile('image')) {
            $data['image'] = $request->input('image');
        } else {
            $data['image'] = 'group-people.jpg';
        }
        $conversation = $this->conversation->create($data);
        if ($conversation) {
            foreach ($participantIdsArray as $participant) {
                $group = $this->participant->create([
                    'user_id' => $participant,
                    'conversation_id' => $conversation->id,
                ]);
                broadcast(new AddGroupToUser($conversation,$participant));
            };
            return response()->json([
                'status' => 'success',
                'message' => 'Tạo nhóm thành công',
                'data' => $conversation,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Lỗi xảy ra'
        ]);
    }

    // public function addConversation(Request $request)
    // {
    //     $id_auth = auth()->user()->id;
    //     $user_id = $request->input('user_id');
    //     $name = $request->input('name');

    //     $conversationCheck = $this->conversation
    //         ->where(function ($query) use ($user1Id, $user2Id) {
    //             $query->where('user_id', $user1Id)
    //                 ->where('receiver_id', $user2Id);
    //         })->orWhere(function ($query) use ($user1Id, $user2Id) {
    //             $query->where('user_id', $user2Id)
    //                 ->where('receiver_id', $user1Id);
    //         })->first();

    //     if (!$conversationCheck) {
    //         $newConver = $this->conversation->create([
    //             'user_id' => $user_id,
    //             'name' => $name,
    //         ]);
    //         $newConver->participants()->create([
    //             'user_id' => $user_id,
    //         ]);
    //     }
    //     return response()->json(['succes' => 'Tao cuoc tro chuyen thanh cong']);
    // }

    public function getMessage($id_conver)
    {
        $conver = $this->conversation->find($id_conver);
        $messages = $conver->messages;
        return response()->json($messages);
    }

    public function getUserRoom($id)
    {
        $user_id = auth()->user()->id;
        $participants = $this->conversation
            ->where('id', $id)
            ->with(['participants' => function ($query) use ($user_id) {
                $query->where('user_id', '!=', $user_id);
            }, 'participants.user'])
            ->first();
        return response()->json($participants);
    }
}
