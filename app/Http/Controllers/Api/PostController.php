<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $post;
    public function __construct(Post $post)
    {
        $this->post = $post;
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
            'group_id' => $request->group_id,
        ];
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagePaths = [];

            foreach ($images as $image) {
                $imageFullName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageFullName);
                $imagePaths[] = $imageFullName;
            }
            $data['image'] = $imagePaths[0];
        }
        if($request->group_id != 0) {
            $data['status'] = 0;
            $post = $user_id->posts()->create($data);
            return response()->json([
                'success' => 'Success',
                'message' => 'Đã gửi bài viết, chờ duyệt bài!',
                'data' => $post->load('user')->load('likes')->load('comments.user')
            ]);
        } else {
            $data['status'] = 1;
            $post = $user_id->posts()->create($data);
            return response()->json([
                'success' => 'Success',
                'message' => 'Bạn vừa đăng một bài viết mới!',
                'data' => $post->load('user')->load('likes')->load('comments.user')
            ]);
        }
    }


    public function feels()
    {
        $friendIds1 = Friend::where('friend_id', Auth::id())->get();
        $friendIds2 = Friend::where('user_id', Auth::id())->get();
        $friendId1 = $friendIds1->pluck('user_id')->toArray();
        $friendId2 = $friendIds2->pluck('friend_id')->toArray();

        $user_id_post = array_merge($friendId1, $friendId2);

        $feels = $this->post->whereIn('user_id', $user_id_post)->orWhere('user_id', Auth::id())->where('group_id',0)->where('status', 1)->with('user')->with('likes')->with('comments.user')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $feels,
        ]);
    }

    public function myFeel()
    {
        $user_id = Auth::id();
        $feels = $this->post->where('user_id', $user_id)->where('group_id',0)->with('user')->with('likes')->with('comments.user')->orderByDesc('created_at')->get();
        return response()->json([
            'status' => 'success',
            'data' => $feels,
        ]);
    }
    public function groupFeels($id)
    {
        $feels = $this->post->where('group_id',$id)->where('status', 1)->with('user')->with('likes')->with('comments.user')->orderByDesc('created_at')->get();
        return response()->json([
            'status' => 'success',
            'data' => $feels,
        ]);    
    }
    

    public function hiddenFeel(Request $request)
    {
        $feel_id = $request->input('feel_id');
        $feel = $this->post->find($feel_id);
        $feel->status = 0;
        $feel->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Đã ẩn bài viết',
        ]);
    }

    public function deleteFeel(Request $request)
    {
        $feel_id = $request->input('feel_id');
        $feel = $this->post->find($feel_id);
        $feel->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa bài viết',
        ]);
    }
    public function displayFeel(Request $request)
    {
        $feel_id = $request->input('feel_id');
        $feel = $this->post->find($feel_id);
        $feel->status = 1;
        $feel->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Đã duyệt bài viết',
        ]);
    }
    public function feel(Request $request, $id) 
    {
        $feel = Post::where('id', $id)->where('status', 1)->with('user')->with('likes')->with('comments.user')->first();
        if($feel===null) {
            return response()->json([
                'status' => 'limit',
                'message' => 'Bài viết bị hạn chế, không thể xem!',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $feel,
        ]);
    }

    public function dataFeel(Request $request)
    {
        $pageNumber = 1;    
        $perPage = 10;
        if($request->has('page')) {
            $pageNumber = $request->input('page');
        }
        $listFeel = Post::where('status',1)->where('title', 'like', '%' . $request->input('name') . '%')->with('user')->get();
        return response()->json([
            'status' => 'success',
            'data' => $listFeel,
        ]);
    }

    public function userFeel($id) 
    {
        $feels = $this->post->where('user_id',$id)->where('status', 1)->with('user')->with('likes')->with('comments.user')->orderByDesc('created_at')->get();
        return response()->json([
            'status' => 'success',
            'data' => $feels,
        ]);  
    }
}
