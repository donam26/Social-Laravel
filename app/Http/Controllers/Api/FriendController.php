<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    protected $friend;
    protected $user;
    public function __construct(Friend $friend, User $user)
    {
        $this->friend = $friend;
        $this->user = $user;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function suggest()
    {

        $userId = Auth::id();
        $friends = $this->friend->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })
            ->select('friend_id', 'user_id')
            ->get();

        $friendIds = [];
        foreach ($friends as $friend) {
            if ($friend['friend_id'] === Auth::id()) {
                $friendIds[] = $friend['user_id'];
            } else {
                $friendIds[] = $friend['friend_id'];
            }
        }
        $list_suggest = $this->user->where('id', '!=', $userId)->whereNotIn('id', $friendIds)->inRandomOrder()->limit(5)->get();
        return response()->json($list_suggest);
    }

    public function suggestList()
    {
        $userId = auth()->user()->id;
        $friends = $this->friend->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })
            ->select('friend_id', 'user_id')
            ->get();

        $friendIds = [];
        foreach ($friends as $friend) {
            if ($friend['friend_id'] === Auth::id()) {
                $friendIds[] = $friend['user_id'];
            } else {
                $friendIds[] = $friend['friend_id'];
            }
        }
        $list_suggest = $this->user->where('id', '!=', $userId)->whereNotIn('id', $friendIds)->inRandomOrder()->get();
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

        return response()->json(['success' => 'Đã gửi kết bạn']);
    }
    public function acceptFriend($friend_id)
    {
        $user_id = auth()->user()->id;
        $friendship = $this->friend->where('user_id', $friend_id)
            ->where('friend_id', $user_id)
            ->where('status', 0) // Trạng thái "chờ bạn bè chấp nhận"
            ->first();
        $friendship->update([
            'status' => 1,
        ]);

        return response()->json(['success' => 'Chấp nhận thành công']);
    }

    public function cancelFriend($friend_id)
    {
        $user_id = auth()->user()->id;
        $friendship = $this->friend->where('user_id', $friend_id)
            ->where('friend_id', $user_id)
            ->where('status', 0) // Trạng thái "chờ bạn bè chấp nhận"
            ->first();
        $friendship->update([
            'status' => 1,
        ]);

        return response()->json(['success' => 'Chấp nhận thành công']);
    }
    public function requestList()
    {
        $id_user = auth()->user()->id;
        $idsFriend = $this->friend->where('friend_id', $id_user)->where('status', 0)->pluck('user_id');
        $list_user = $this->user->whereIn('id', $idsFriend)->get();
        return response()->json($list_user);
    }
    public function listFriends()
    {
        $userId = auth()->user()->id;
        $friends = $this->friend->where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('friend_id', $userId);
        })
            ->where('status', 1) // Đã kết bạn
            ->select('friend_id', 'user_id')
            ->get();

        $friendId = [];
        foreach ($friends as $friend) {
            if ($friend['friend_id'] === Auth::id()) {
                $friendId[] = $friend['user_id'];
            } else {
                $friendId[] = $friend['friend_id'];
            }
        }
        $infoFriend = $this->user->select('id', 'name', 'image', 'email')->whereIn('id', $friendId)->get();
        return response()->json($infoFriend);
    }

    public function dataFriend(Request $request)
    {
        $pageNumber = 1;
        $perPage = 10;
        $userId = auth()->user()->id;
        if ($request->has('page')) {
            $pageNumber = $request->input('page');
        }
        $listFriend = User::where('name', 'like', '%' . $request->input('name') . '%')->where('id', '!=', $userId)->get();
        return response()->json([
            'status' => 'success',
            'data' => $listFriend,
        ]);
    }
}
