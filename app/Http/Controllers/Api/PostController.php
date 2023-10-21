<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function index()
    {
        $user_id = auth()->user()->id;
        $posts = $this->post->where('user_id', $user_id)->where('status', 1)->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $user_id = auth()->user();
        $data = [
            'title' => $request->title,
        ];
        if ($request->file('image')) {
            $imageName = $request->file('image');
            $imageFullName =  time(). $imageName->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $imageFullName);
            $data['image'] = $imageFullName;
        }
        $data['status'] = 1;

        $post = $user_id->posts()->create($data);
        return response()->json([
            'success' => 'Success',
        ]);
    }

    public function feels()
    {   
        $user = auth()->user();
        $friendIds = $user->friends->pluck('friend_id');
        $feels = $this->post->whereIn('user_id', $friendIds)->where('status', 1)->with('user')->with('likes')->with('comments.user:id,name')->orderBy('created_at', 'desc')->get();
        return response()->json($feels);
    }


}
