<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Orders')]
class Orders extends Component
{
    use WithPagination;

    protected $listeners = [
        'order-status-updated' => 'handleOrderStatusUpdated',
    ];

    public function handleOrderStatusUpdated($payload = [])
    {
        $payload = is_array($payload) ? $payload : [];
        $userId = $payload['userId'] ?? null;
        if ($userId && (int) $userId === (int) Auth::id()) {
            $this->resetPage();
            $this->dispatch('$refresh');
        }
    }

    public function render()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.customer.orders', compact('orders'));
    }
}
