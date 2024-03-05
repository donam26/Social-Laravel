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

Broadcast::channel('conversation.{conversation_id}', function ($user,$conversation_id) {
    return true;
});

Broadcast::channel('AddGroupToUser.{user_id}', function ($user,$conversation_id) {
    return true;
});

Broadcast::channel('ToUser.{user_id}', function ($user,$conversation_id) {
    return true;
});

Broadcast::channel('NotificationMember.{user_id}', function ($user,$conversation_id) {
    return true;
});

Broadcast::channel('RequestMember.{user_id}', function () {
    return true;
});

Broadcast::channel('FeelBeLongTo.{user_id}', function () {
    return true;
});

Broadcast::channel('feel.{id}', function ($user,$id) {
    return true;
}); 