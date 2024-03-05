<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchUserController extends Controller
{
    public function search()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return response()->json($users);
    }

    public function listFriend()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return response()->json($users);
    }

    public function searchUser($id)
    {
        $user = User::where('id', $id)->first();
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
}
