<?php

namespace App\Livewire\Customer;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Notifications')]
class Notifications extends Component
{
    use WithPagination;

    public function markAsRead(string $id): void
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }
        /** @var DatabaseNotification|null $n */
        $n = $user->notifications()->where('id', $id)->first();
        if ($n && $n->read_at === null) {
            $n->markAsRead();
            $this->dispatch('notify', message: 'Notification marked as read');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $notifications = $user ? $user->notifications()->latest()->paginate(12) : collect();

        return view('livewire.customer.notifications', compact('notifications'));
    }
}
