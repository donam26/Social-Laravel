<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Events\CommentFeel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $postId = $request->post_id;
        $content = $request->content;
        $post = Post::find($postId);
        $comment = $this->comment->create([
            'user_id' => $user->id,
            'post_id' => $postId,
            'content' => $content,
            'status' => 1,
        ]);
        $notification = Notification::create([
            'user_id' => $post->user_id,
            'member_name' => $user->name,
            'content' => $content,
            'type' => '2',
            'status' => '0',
            'member_image' => $user->image,

            // link của bài viết
            'link' => '/feel/' . $postId,
        ]);
        broadcast(new CommentFeel ($post, $notification));

        return response()->json([
            'status' => 'success',
            'data' => $comment
        ]);
    }

}
