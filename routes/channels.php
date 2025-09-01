<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin notifications private channel
Broadcast::channel('App.Models.Admin.{id}', function ($user, $id) {
    $admin = auth('admin')->user();

    return $admin && (int) $admin->id === (int) $id;
});

Broadcast::channel('admin-dashboard', function ($user) {
    return $user instanceof Admin;
});

Broadcast::channel('online-users', function ($user) {
    $userType = $user instanceof Admin ? 'admin' : 'customer';

    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'type' => $userType,
        'joined_at' => now()->toISOString(),
    ];
});
