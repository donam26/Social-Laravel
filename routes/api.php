<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SearchUserController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\Notification;
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
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',

], function ($router) {

    //Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('/user/{id}', [AuthController::class, 'updateUser']);

    //Posts
    Route::get('post', [PostController::class, 'index']);
    Route::post('post', [PostController::class, 'store']);
    Route::get('myFeels', [PostController::class, 'myFeel']);
    Route::get('groupFeels/{id}', [PostController::class, 'groupFeels']);
    Route::get('feels', [PostController::class, 'feels']);
    Route::get('feel/{id}', [PostController::class, 'feel']);
    Route::get('userFeel/{id}', [PostController::class, 'userFeel']);
    Route::post('deleteFeel', [PostController::class, 'deleteFeel']);
    Route::post('hiddenFeel', [PostController::class, 'hiddenFeel']);
    Route::post('displayFeel', [PostController::class, 'displayFeel']);
    
    //Likes
    Route::post('/like', [LikeController::class, 'store']);

    //Comment
    Route::post('/comment', [CommentController::class, 'store']);

    //Suggest
    Route::get('/suggest', [FriendController::class, 'suggest']);
    Route::get('/suggestList', [FriendController::class, 'suggestList']);
    Route::get('/requestList', [FriendController::class, 'requestList']);

    //Status friend
    Route::post('/addFriend/{id}', [FriendController::class, 'friendRequest']);
    Route::post('/acceptFriend/{id}', [FriendController::class, 'acceptFriend']);
    Route::post('/cancelFriend/{id}', [FriendController::class, 'cancelFriend']);

    //Message
    Route::get('/listMess', [ConversationController::class, 'listMess']);
    Route::post('/addConversation', [ConversationController::class, 'addConversation']);
    Route::post('/sendMessage/{id}', [MessageController::class, 'sendMessage']);
    Route::get('/getMessage/{id}', [ConversationController::class, 'getMessage']);
    Route::get('/getUserRoom/{id}', [ConversationController::class, 'getUserRoom']);
    Route::post('/createGroupChat', [ConversationController::class, 'createGroupChat']);
    
    //Profile
    Route::get('/profile/{id}', [ProfileController::class, 'profile']);
    Route::get('/listImage/{id}', [ProfileController::class, 'imageProfile']);

    //Group
    Route::get('/listGroup', [GroupController::class, 'listGroup']);
    Route::get('/group/{id}', [GroupController::class, 'profileGroup']);
    Route::post('/createGroup', [GroupController::class, 'createGroup']);
    Route::post('/addMember/{id}', [GroupController::class, 'addMember']);
    Route::get('/members/{id}', [GroupController::class, 'listMember']);
    Route::get('/listMemberSugest/{id}', [GroupController::class, 'listMemberSugest']);
    Route::get('/suggestGroup', [GroupController::class, 'suggestGroup']);
    Route::post('/requestMember/{id}', [GroupController::class, 'acceptMember']);
    Route::post('/outGroup/{id}', [GroupController::class, 'outGroup']);
    Route::post('/deleteGroup/{id}', [GroupController::class, 'deleteGroup']);
    Route::post('/updateGroup/{id}', [GroupController::class, 'update']);
    Route::get('/approvePost/{id}', [GroupController::class, 'approvePost']);
    Route::get('/approvePosts', [GroupController::class, 'approvePosts']);
    Route::get('/approveMember/{id}', [GroupController::class, 'approveMember']);
    Route::get('/approveMembers', [GroupController::class, 'approveMembers']);

    Route::post('/acpMember/{id}', [GroupController::class, 'acpMember']);
    Route::post('/approveMembers/{id}', [GroupController::class, 'approveMembers']);
    
    // Search
    Route::get('/dataFriend', [FriendController::class, 'dataFriend']);
    Route::get('/dataSuggest', [FriendController::class, 'dataSuggest']);
    Route::get('/dataAccept', [FriendController::class, 'dataAccept']);
    Route::get('/dataFeel', [PostController::class, 'dataFeel']);

    // Search User
    Route::get('/searchUser', [SearchUserController::class, 'search']);
    Route::get('/listFriend', [FriendController::class, 'listFriends']);
    Route::post('/image', [ImageController::class, 'index']);


    //Notification
    Route::get('/notification', [Notification::class, 'index']);

    // Api Search
    Route::get('/search/user/{id}', [SearchUserController::class, 'search']);
});
