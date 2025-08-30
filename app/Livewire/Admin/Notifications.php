<?php

namespace App\Livewire\Admin;

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
        $admin = Auth::guard('admin')->user();
        if (! $admin) {
            return;
        }
        /** @var DatabaseNotification|null $n */
        $n = $admin->notifications()->where('id', $id)->first();
        if ($n && $n->read_at === null) {
            $n->markAsRead();
            $this->dispatch('notify', message: 'Notification marked as read');
        }
    }

    public function render()
    {
        $admin = Auth::guard('admin')->user();
        $notifications = $admin ? $admin->notifications()->latest()->paginate(12) : collect();

        return view('livewire.admin.notifications', compact('notifications'));
    }
}
