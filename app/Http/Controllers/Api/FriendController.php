<?php

namespace App\Http\Controllers\Api;

use App\Events\SendRequestAddFriend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    protected $friend;
    protected $user;
    public function __construct(Friend $friend, User $user)
    {
        $this->friend = $friend;
        $this->user = $user;
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
        $notification = Notification::create([
            'user_id' => $friend_id,
            'member_name' => auth()->user()->name,
            'content' => 'đã gửi cho bạn một lời mời kết bạn',
            'type' => '6',
            'status' => '0',
            'member_image' => auth()->user()->image,
            // link của bài viết
            'link' => '/user/' . $user_id,
        ]);
        broadcast(new SendRequestAddFriend ($friendShips, $notification));

        return response()->json([
            'status' => 'success',
            'message' => 'Đã gửi kết bạn',
        ]);
    }
    public function acceptFriend($friend_id)
    {
        $user_id = auth()->user()->id;
        $data = Friend::where(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $user_id)
                  ->where('friend_id', $friend_id);
        })
        ->orWhere(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('friend_id', $user_id);
        })
        ->where('status', 0)
        ->get();

        $data->each(function ($conversation) {
            $conversation->status = 1;
            $conversation->save();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Đã hủy kết bạn',
            'data' => $user_id,
        ]);
    }

    public function cancelFriend(Request $request, $friend_id)
    {
        $user_id = $request->input('authId');
        $data = Friend::where(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $user_id)
                  ->where('friend_id', $friend_id);
        })
        ->orWhere(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('friend_id', $user_id);
        })
        ->where('status', 1)
        ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã hủy kết bạn',
            'data' => $user_id,
        ]);
    }
    public function cancelRequest(Request $request, $friend_id)
    {
        $user_id = $request->input('authId');
        $data = Friend::where(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $user_id)
                  ->where('friend_id', $friend_id);
        })
        ->orWhere(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('friend_id', $user_id);
        })
        ->where('status', 0)
        ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã hủy yêu cầu kết bạn',
            'data' => $user_id,
        ]);
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

    public function acceptFriendNoti($friend_id)
    {
        $user_id = auth()->user()->id;
        $data = Friend::where(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $user_id)
                  ->where('friend_id', $friend_id);
        })
        ->orWhere(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('friend_id', $user_id);
        })
        ->where('status', 0)
        ->get();

        $data->each(function ($conversation) {
            $conversation->status = 1;
            $conversation->save();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Đã hủy kết bạn',
            'data' => $user_id,
        ]);
    }

    public function cancelRequestNoti(Request $request, $friend_id)
    {
        $user_id = auth()->user()->id;
        $notification_id = $request->input('notificationId');
        $data = Friend::where(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $user_id)
                  ->where('friend_id', $friend_id);
        })
        ->orWhere(function ($query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('friend_id', $user_id);
        })
        ->where('status', 0)
        ->delete();

        Notification::where('id',$notification_id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã hủy yêu cầu kết bạn',
            'user_id' => $user_id,
            'friend_id' => $friend_id,
        ]);
    }
}
