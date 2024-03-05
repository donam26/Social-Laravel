<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function index()
    {
        return response()->json(["success" => true]);
    }
}
