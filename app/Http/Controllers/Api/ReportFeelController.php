<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Violate;
use Illuminate\Http\Request;

class ReportFeelController extends Controller
{
    public function store(Request $request)
    {
        $user_id = auth()->user();
        $feel = Post::where('id', $request->feel_id)->get();
        $data = [
            'accuser' => $user_id,
            'feel_id' => $request->feel_id,
            'content' => $request->content,
        ];
        Violate::create($data);

        return response()->json([
            'success' => 'Success',
            'message' => 'Đã gửi báo cáo tới quản trị viên',
        ]);
    }
}
