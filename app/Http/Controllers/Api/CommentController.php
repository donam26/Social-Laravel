<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $postId = $request->post_id;
        $content = $request->content;

        $this->comment->create([
            'user_id' => $user_id,
            'post_id' => $postId,
            'content' => $content,
            'status' => 1,
        ]);
        return response()->json(['Comment' => $content]);
    }

}
