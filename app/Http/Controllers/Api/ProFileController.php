<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ProFileController extends Controller
{
    public function profile(Request $request, $id)
    {
        $profile = User::find($id);
        $list_image = $profile->posts()->with('likes')->with('comments.user')->orderBy('created_at', 'desc')->where('status', 1)->get();

        $status_friend = Friend::where(function ($query)  use ($id) {
            $query->where('user_id', Auth::id())
                ->where('friend_id', $id);
        })
            ->orWhere(function ($query) use ($id) {
                $query->where('user_id', $id)
                    ->where('friend_id', Auth::id());
            })->first();
        $conversation = Conversation::whereHas('participants', function ($query) use ($id) {
            $query->whereIn('user_id', [Auth::id(), $id]);
        }, '=', 2)
            ->pluck('id')
            ->first();

        return response()->json([
            'status' => 'success',
            'status_friend' => $status_friend,
            'profile' => $profile,
            'data' => $list_image,
            'conversation' => $conversation
        ]);
    }

    public function imageProfile(Request $request, $id)
    {
        $user = User::find(Auth::id());
        $list_image = $user->posts()->whereNotNull('image')->orderBy('created_at', 'desc')->get();
        return response()->json($list_image);
    }
}
