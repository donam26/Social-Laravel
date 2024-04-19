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
            }, 'participants.user'])
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
        if ($request->file('image')) {
            $imageName = $request->file('image');
            $imageFullName =  time() . $imageName->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $imageFullName);
            $data['image'] = $imageFullName;
        }
        $conversation = $this->conversation->create($data);
        if ($conversation) {
            foreach ($participantIdsArray as $participant) {
                $group = $this->participant->create([
                    'user_id' => $participant,
                    'conversation_id' => $conversation->id,
                ]);
                broadcast(new AddGroupToUser($conversation, $participant));
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

    public function addConversation(Request $request)
    {
        $user = Auth::user();
        $friend_id = $request->input('user_id');
        $userParticipantIds = Participant::where('user_id', $user->id)->pluck('conversation_id');
        $user2ParticipantIds = Participant::where('user_id', $friend_id)->pluck('conversation_id');
        $commonConversations = $userParticipantIds->intersect($user2ParticipantIds);
        $conversation = Conversation::whereIn('id', $commonConversations)
            ->whereHas('participants', function ($query) {
                $query->select('conversation_id')
                    ->groupBy('conversation_id')
                    ->havingRaw('COUNT(*) = 2');
            })
            ->first();
        if (!$conversation) {
            $newConversation = Conversation::create([
                'name' => null,
                'image' => null,
                'created_user' => $user->id,
            ]);
            Participant::create([
                'user_id' => $user->id,
                'conversation_id' => $newConversation->id,
            ]);
            Participant::create([
                'user_id' => $friend_id,
                'conversation_id' => $newConversation->id,
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Create New',
                'data' =>  $newConversation,
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Redirect',
            'data' =>  $conversation,
        ]);
    }

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
