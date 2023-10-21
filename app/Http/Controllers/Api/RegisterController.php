<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {       
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ];

        $userCreate = $this->user->create($data);
        return response()->json(['success' => 'Success']);
    }
}
