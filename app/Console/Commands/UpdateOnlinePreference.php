<?php

namespace App\Console\Commands;

use App\Events\UserPresenceChanged;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateOnlinePreference extends Command
{
    protected int $timesinceOnline = 2;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:mark-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark user inactive if there last_seen time is more than 2 minutes ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = Carbon::now()->subMinutes($this->timesinceOnline);
        $offlineUsers = User::where('is_online', true)
            ->where('last_seen', '<', $threshold)
            ->get();

        foreach ($offlineUsers as $user) {
            $user->update(['is_online' => false]);
            broadcast(new UserPresenceChanged($user, false, 'customer'));

            $this->info("Marked customer {$user->name} as offline");
        }
        $offlineAdmins = Admin::where('is_online', true)
            ->where('last_seen', '<', $threshold)
            ->get();

        foreach ($offlineAdmins as $admin) {
            $admin->update(['is_online' => false]);

            // Broadcast the presence change
            broadcast(new UserPresenceChanged($admin, false, 'admin'));

            $this->info("Marked admin {$admin->name} as offline");
        }
    }
}
