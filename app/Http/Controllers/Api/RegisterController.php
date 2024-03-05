<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {       
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed', 
            'password_confirmation' => 'required|string|min:6', 
        ], [
            'name.required' => 'Vui lòng điền tên',
            'address.required' => 'Vui lòng điền địa chỉ',
            'password.required' => 'Vui lòng điền mật khẩu',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng',
            'password_confirmation.required' => 'Vui lòng điền mật khẩu xác nhận',
            'email.required' => 'Vui lòng điền email',
            'email.unique' => 'Email đã tồn tại, vui lòng sử dụng email khác',
            'email.email' => 'Định dạng email sai',
        ]);
        $data = [
            'name' => $request->input('name'),
            'sex' => $request->input('sex'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ];
        if($request->input('sex') === 1) {
            $data['image'] = 'woman.jpg';
        } else {
            $data['image'] = 'man.jpg';
        }

        $userCreate = $this->user->create($data);
        if($userCreate) {
            return response()->json(['status' => 'Success']);
        }
        return response()->json(['status' => 'Error']);

    }
}
