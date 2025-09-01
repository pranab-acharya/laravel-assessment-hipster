<?php

namespace App\Listeners;

use App\Events\UserPresenceChanged;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Events\Login;

class UpdateUserLoginStatus
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /**
         * @var User|Admin $user
         */
        $user = $event->user;

        $user->update([
            'is_online' => true,
            'last_seen' => now(),
        ]);

        $userType = $user instanceof Admin ? 'admin' : 'customer';
        broadcast(new UserPresenceChanged($user, true, $userType));
    }
}
