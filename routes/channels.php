<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
 */

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('testchannel', function ($user) {
    return true;
});

Broadcast::channel('privatetestchannel.{conversation_id}', function ($user, $conversation_id) {
    return true;
});

// Broadcast::channel('testchannel.{conversation_id}', function ($user, $conversation_id) {
//         return Auth::check();
// });
