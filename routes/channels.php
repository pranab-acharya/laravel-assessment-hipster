<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin notifications private channel
Broadcast::channel('App.Models.Admin.{id}', function ($user, $id) {
    $admin = auth('admin')->user();

    return $admin && (int) $admin->id === (int) $id;
});
