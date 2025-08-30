<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatusUpdatedNotification;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Orders Manager')]
class OrdersManager extends Component
{
    use WithPagination;

    public ?int $editingId = null;

    #[Rule('required')]
    public string $status = '';

    public function edit(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $this->editingId = $orderId;
        $this->status = $order->status->value;
    }

    public function updateStatus(): void
    {
        $this->validate();
        if (! $this->editingId) {
            return;
        }
        $order = Order::findOrFail($this->editingId);
        $order->update(['status' => $this->status]);

        $this->dispatch('notify', message: "Order #{$order->id} status updated to {$order->status->name}");
        $this->dispatch('order-status-updated', userId: $order->user_id, orderId: $order->id, status: $order->status->value);
        optional($order->user)->notify(new OrderStatusUpdatedNotification($order));

        $this->reset(['editingId', 'status']);
    }

    public function render()
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->paginate(12);
        $statuses = array_column(OrderStatus::cases(), 'value');

        return view('livewire.admin.orders-manager', compact('orders', 'statuses'));
    }
}
