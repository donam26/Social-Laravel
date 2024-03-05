<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'Vui lòng điền mật khẩu',
            'email.required' => 'Vui lòng điền email',
            'email.email' => 'Định dạng email sai',
        ]);
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thông tin chưa đúng. Kiểm tra lại!',
            ], 401);
        }

        $user = auth()->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'sex' => $user->sex,
            'address' => $user->address,
            'email' => $user->email,
            'image' => $user->image,
            'access_token' => $token,
        ]);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 'Erorr',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'sex' => $request->sex,
            'address' => $request->address,
        ];
        if ($request->file('image')) {
            $imageName = $request->file('image');
            $imageFullName =  time() . $imageName->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $imageFullName);
            $data['image'] = $imageFullName;
        }

        $user->update($data);
        return response()->json([
            'success' => 'Success',
            'data' => $user
        ]);
    }
}
