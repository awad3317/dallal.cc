<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private-channel.user.{id}', function ($user, $id) {
    // return (int) Auth::id() == (int) $id;
    return true;
});
