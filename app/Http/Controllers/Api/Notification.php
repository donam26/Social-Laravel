<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification as ModelsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Notification extends Controller
{
    public function index()
    {
        $notifications = ModelsNotification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ]);
    }

    
}
