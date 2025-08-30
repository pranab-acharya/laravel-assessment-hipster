<?php

namespace App\Livewire\Customer;

use App\Enums\OrderStatus;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use RuntimeException;

#[Title('Checkout')]
class Checkout extends Component
{
    public array $cart = [];

    public function mount()
    {
        $this->cart = session()->get('cart', []);
    }

    public function removeItem(int $productId): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
        $this->cart = $cart;
        $this->dispatch('notify', message: 'Item removed');
    }

    public function placeOrder()
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Cart is empty');

            return;
        }

        DB::transaction(function () {
            $userId = Auth::id();
            $total = 0;
            $itemsData = [];

            // Validate stock and compute total
            foreach ($this->cart as $productId => $line) {
                $product = Product::lockForUpdate()->find($productId);
                if (! $product) {
                    throw new RuntimeException('Product not found: ' . $productId);
                }
                $qty = (int) ($line['quantity'] ?? 0);
                if ($qty <= 0) {
                    continue;
                }
                if ($product->stock < $qty) {
                    throw new RuntimeException("Insufficient stock for {$product->name}");
                }
                $price = (float) $product->price;
                $total += $price * $qty;
                $itemsData[] = [$product, $qty, $price];
            }

            if ($total <= 0) {
                throw new RuntimeException('Nothing to checkout');
            }

            $order = Order::create([
                'user_id' => $userId,
                'total' => $total,
                'status' => OrderStatus::PENDING,
            ]);

            foreach ($itemsData as [$product, $qty, $price]) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);
                // decrement stock
                $product->decrement('stock', $qty);
            }

            // Clear cart
            session()->forget('cart');
            $this->cart = [];

            // Notify admins about the new order
            Admin::query()->each(function (Admin $admin) use ($order) {
                $admin->notify(new OrderPlacedNotification($order));
            });
        });

        $this->dispatch('notify', message: 'Order placed!');

        return redirect()->route('customer.orders');
    }

    public function render()
    {
        $lines = [];
        $total = 0;
        foreach ($this->cart as $productId => $line) {
            $product = Product::find($productId);
            if (! $product) {
                continue;
            }
            $qty = (int) ($line['quantity'] ?? 0);
            $price = (float) $product->price;
            $subtotal = $price * $qty;
            $total += $subtotal;
            $lines[] = compact('product', 'qty', 'price', 'subtotal');
        }

        return view('livewire.customer.checkout', compact('lines', 'total'));
    }
}
