<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
class SearchFeelController extends Controller
{
    public function searchFeel($id)
    {
        $user = Post::where('id', $id)->with('user:id,name')->first();
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }
}
