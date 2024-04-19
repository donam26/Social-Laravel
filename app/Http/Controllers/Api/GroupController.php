<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Notification;
use App\Models\User;
use App\Models\Friend;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NotificationMember;
use App\Events\AcceptRequestMember;
use App\Events\RequestMember;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function listGroup()
    {
        $listGroup = User::find(Auth::id())->group()->wherePivot('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'data' => $listGroup
        ]);
    }
    public function listMember($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        $members = $group->user()->wherePivot('status', 1)->get();
        return response()->json([
            'status' => 'success',
            'data' => $members
        ]);
    }
    public function profileGroup($id)
    {
        $profile = Group::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $profile
        ]);
    }
    public function createGroup(Request $request)
    {
        // Validate và tạo nhóm mới
        $data = [
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'created_user' => Auth::id(),
            'status' => 1,
        ];

        if ($request->hasFile('image')) {
            $imageName = $request->file('image');
            $imageFullName =  time() . $imageName->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $imageFullName);
            $data['image'] = $imageFullName;
        } else {
            $data['image'] = 'GroupPeople.jpg';
        }

        $group = Group::create($data);
        $user = User::find(Auth::id());
        $user->group()->attach($group->id, ['status' => 1]);

        return response()->json([
            'status' => 'success',
            'data' => $group,
        ]);
    }
    public function addMember(Request $request, $group_id)
    {
        $group = Group::find($group_id);
        $user_ids = $request->input('user_ids');
        $users = User::whereIn('id', $user_ids)->get(); 

        $group->user()->attach($user_ids, ['status' => 0]);
        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'content' => ' đã mời bạn vào nhóm ' . $group->name,
                'type' => '4',
                'status' => '0',
                'member_name' => $user->name,
                'member_image' => $user->image,
                'link' => '/group/' . $group->id,
            ]);
            broadcast(new NotificationMember($user, $group, $notification));
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Đã gửi lời mời thành công',
        ]);
    }

    public function listMemberSugest($id)
    {
        $userId = auth()->user()->id;
        $friends = Friend::where(function ($query) use ($userId) {
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
        $groupMembers = DB::table('group_user')
            ->where('group_id', $id)
            ->pluck('user_id')
            ->toArray();


        $suggestedFriends = DB::table('users')
            ->whereIn('id', $friendId)
            ->whereNotIn('id', $groupMembers)
            ->select('id', 'name', 'image', 'email')
            ->get();

        return response()->json($suggestedFriends);
    }
    public function suggestGroup()
    {
        $userId = auth()->user()->id;
        $suggestedGroups = Group::whereDoesntHave('user', function ($query) use ($userId) {
            $query->where('user_id', '=', $userId);
        })->get();

        return response()->json($suggestedGroups);
    }
    public function acceptMember($id)
    {
        $group = Group::find($id);
        $userId = Auth::id();
        $user = User::find($userId);
        if ($user->group()->wherePivot('group_id', $id)->exists()) {
            $user->group()->updateExistingPivot($id, ['status' => '1']); {
                return response()->json([
                    'status' => 'success',                
                    'message' => 'Bạn đã tham gia vào nhóm'
                ]);
            }
        }
        if ($group->status === 1) {
            $group->user()->attach($userId, ['status' => 1]);
            return response()->json([
                'status' => 'success',
                'message' => 'Bạn đã tham gia vào nhóm'
            ]);
        } else {
            if ($user->group()->wherePivot('group_id', $id)->exists()) { {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Đã gửi yêu cầu, vui lòng chờ chấp nhận!'
                    ]);
                }
            }
            $group->user()->attach($userId, ['status' => 0]);
            $group->update(['status' => '0']);
            $notification = Notification::create([
                'user_id' => $group->created_user,
                'content' => ' yêu cầu vào nhóm ' . $group->name,
                'type' => '5',
                'status' => '0',
                'member_name' => Auth::user()->name,
                'member_image' => Auth::user()->image,
                'link' => '/group/' . $group->id,
            ]);
            broadcast(new RequestMember($notification, $group));
            return response()->json([
                'status' => 'success',
                'message' => 'Đã gửi yêu cầu, vui lòng chờ chấp nhận!'
            ]);
        }
    }
    public function outGroup($id)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $group = Group::find($id);
        $user->group()->detach($group);
        return response()->json([
            'status' => 'success',
            'message' => 'Bạn đã rời nhóm',
            'data' => $group,
        ]);
    }
    public function deleteGroup($id)
    {
        $auth_id = Auth::id();
        Group::where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa nhóm thành công',
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = [
            'name' =>  $request->input('name'),
            'desc' =>  $request->input('desc'),
        ];
        if ($request->file('image')) {
            $imageName = $request->file('image');
            $imageFullName =  time() . $imageName->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $imageFullName);
            $data['image'] = $imageFullName;
        }
        $group = Group::find($id);
        $group->update($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật thành công',
            'data' => $group
        ]);
    }
    public function approvePost($id)
    {
        $approve_posts = Post::where('group_id', $id)->where('status', 0)->with('user')->get();
        return response()->json([
            'status' => 'success',
            'data' => $approve_posts,
        ]);
    }
    public function approveMember($id)
    {
        $group = Group::find($id);

        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        $members = $group->user()->wherePivot('status', 0)->get();
        return response()->json([
            'status' => 'success',
            'data' => $members
        ]);
    }
    public function acpMember(Request $request, $group_id)
    {
        $user = User::find($request->input('user_id'));
        $group = Group::find($group_id);
        $user->group()->updateExistingPivot($group_id, ['status' => '1']);
        $notification = Notification::create([
            'user_id' => $user->id,
            'content' => ' đã chấp nhận bạn vào nhóm ' . $group->name,
            'type' => '4',
            'status' => '0',
            'member_name' => $user->name,
            'member_image' => $user->image,
            'link' => '/group/' . $group->id,
        ]);
        broadcast(new AcceptRequestMember($user, $group, $notification));
        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm user vào nhóm',
            'data' => $group_id . '  ' . $user->id,
        ]);
    }
    public function delMember(Request $request, $id)
    {
        $user = User::find($request->input('user_id'));
        $group = Group::find($id);
        $user->group()->detach($group);
        return response()->json([
            'status' => 'success',
            'message' => 'Bạn đã rời nhóm',
        ]);
    }
}
