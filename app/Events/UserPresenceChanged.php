<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPresenceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public $user,
        public bool $isOnline,
        public string $userType
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-dashboard'),
            new PresenceChannel('online-users'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'UserPresenceChanged';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'is_online' => $this->isOnline,
            'user_type' => $this->userType,
            'last_seen' => $this->user->last_seen?->toISOString(),
        ];
    }
}
