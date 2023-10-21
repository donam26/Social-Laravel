<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function __construct(Like $like)
    {
        $this->like = $like;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $postId = $request->post_id;
        $like = $this->like->where('user_id',$user_id)->where('post_id',$postId)->first();
        
        if ($like) {
            $like->delete();
            return response()->json(['success'=>'Bo thich']);
        } 
        else {
            $this->like->create([
                'user_id' => $user_id,
                'post_id' => $postId,
                'status' => 1,
            ]);
            return response()->json(['success'=>'Da thich']);
        }
        return 'ok';
    }

}
