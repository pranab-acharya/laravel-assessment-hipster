<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'order_status_updated',
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'status' => $this->order->status->value,
            'message' => "Order #{$this->order->id} status updated to {$this->order->status->name}",
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'order_status_updated',
            'order_id' => $this->order->id,
            'user_id' => $this->order->user_id,
            'status' => $this->order->status->value,
            'message' => "Order #{$this->order->id} status updated to {$this->order->status->name}",
        ]);
    }
}
