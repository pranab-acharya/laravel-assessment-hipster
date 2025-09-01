<?php

namespace App\Listeners;

use App\Events\UserPresenceChanged;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

class UpdateUserLogoutStatus
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        /**
         * @var User|Admin $user
         */
        $user = $event->user;

        $user->update([
            'is_online' => false,
            'last_seen' => now(),
        ]);

        Log::info("User {$user->name} logged out");
        $userType = $user instanceof Admin ? 'admin' : 'customer';
        broadcast(new UserPresenceChanged($user, false, $userType));
    }
}
