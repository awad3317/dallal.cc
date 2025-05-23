<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private-channel.user.{id}', function ($user, $id) {
    // return (int) $user->id === (int) $id;
    return true;
});
Broadcast::channel('testChannel.{id}', function () {
    return true;
});
