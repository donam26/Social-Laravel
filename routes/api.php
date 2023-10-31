<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {

    //Auth
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('/user/{id}', [AuthController::class, 'updateUser']);

    //Posts
    Route::get('post', [PostController::class, 'index']);
    Route::post('post', [PostController::class, 'store']);

    Route::get('feel', [PostController::class, 'feels']);

    //Likes
    Route::post('/like', [LikeController::class, 'store']);

    //Comment
    Route::post('/comment', [CommentController::class, 'store']);

    //Suggest
    Route::get('/suggest', [FriendController::class, 'suggest']);
    Route::get('/suggestList', [FriendController::class, 'suggestList']);

    Route::get('/requestList', [FriendController::class, 'requestList']);

    //Add friend
    Route::post('/addFriend/{id}', [FriendController::class, 'friendRequest']);
    Route::post('/acceptFriend/{id}', [FriendController::class, 'acceptFriend']);

    //Message
    Route::get('/listMess', [ConversationController::class, 'listMess']);
    Route::post('/addConversation', [ConversationController::class, 'addConversation']);

    Route::post('/sendMessage/{id}', [MessageController::class, 'sendMessage']);

});

Route::get('test', function(){
    // $message = App\Models\Message::find(1);
    broadcast(new \App\Events\Test());
});