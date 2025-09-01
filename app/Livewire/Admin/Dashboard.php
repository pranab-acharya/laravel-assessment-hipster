<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{
    public $onlineUsers = [];
    public $onlineAdmins = [];
    public $totalOnlineCustomers = 0;
    public $totalOnlineAdmins = 0;

    public function mount()
    {
        $this->loadOnlineUsers();
    }

    public function loadOnlineUsers(): void
    {
        $this->onlineUsers = User::online()
            ->select('id', 'name', 'email', 'last_seen')
            ->get()
            ->toArray();

        $this->onlineAdmins = Admin::online()
            ->select('id', 'name', 'email', 'last_seen')
            ->get()
            ->toArray();

        $this->totalOnlineCustomers = count($this->onlineUsers);
        $this->totalOnlineAdmins = count($this->onlineAdmins);
    }

    #[On('user-presence-updated')]
    public function refreshPresence()
    {
        $this->loadOnlineUsers();
    }

    #[On('echo:admin-dashboard,UserPresenceChanged')]
    public function handlePresenceUpdate($event)
    {
        $this->loadOnlineUsers();
    }

    public function render(): View
    {
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::count();
        $totalAdmins = Admin::count();
        $totalOnlineCustomers = $this->totalOnlineCustomers;

        return view('livewire.admin.dashboard', compact('totalProducts', 'totalOrders', 'totalPendingOrders', 'totalCustomers', 'totalAdmins'));
    }
}
