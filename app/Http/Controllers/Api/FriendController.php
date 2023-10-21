<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\User;

class FriendController extends Controller
{
    public function __construct(Friend $friend, User $user)
    {
        $this->friend = $friend;
        $this->user = $user;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function suggest()
    {
        $id_user = auth()->user()->id;
        $friendIds = $this->friend->where('user_id', $id_user)->pluck('friend_id');
        $list_suggest = $this->user->where('id', '!=', $id_user)->whereNotIn('id', $friendIds)->inRandomOrder()->limit(5)->get();
        return response()->json($list_suggest);
    }

    public function suggestList()
    {
        $id_user = auth()->user()->id;
        $friendIds = $this->friend->where('user_id', $id_user)->pluck('friend_id');
        $list_suggest = $this->user->where('id', '!=', $id_user)->whereNotIn('id', $friendIds)->inRandomOrder()->get();
        return response()->json($list_suggest);
    }

    public function friendRequest($friend_id)
    {
        $user_id = auth()->user()->id;

        $friendShips = $this->friend->create([
            'user_id' => $user_id,
            'friend_id' => $friend_id,
            'status' => 0,
        ]);
    }

    public function requestList()
    {
        $id_user = auth()->user()->id;
        $idsFriend = $this->friend->where('friend_id', $id_user)->where('status', 0)->pluck('user_id');
        $list_user = $this->user->whereIn('id', $idsFriend)->get();
        return response()->json($list_user);
    }

    public function acceptFriend($id)
    {
        $id_user = auth()->user()->id;
        $friendship = $this->friend->where('user_id', $id)
            ->where('friend_id', $id_user)
            ->where('status', 0) // Trạng thái "chờ bạn bè chấp nhận"
            ->first();
        $friendship->update([
            'status' => 1,
        ]);

        return response()->json(['success' => 'Chấp nhận thành công']);
    }

    public function listFriends()
    {
        $userId = auth()->user()->id;
        $friends = $this->friend->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })
            ->where('status', 1) // Đã kết bạn
            ->get();

        return response()->json($friends);
    }
}
