<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Livewire\Customer\Checkout;
use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class OrderPlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_place_order_and_stock_is_decremented_and_cart_cleared(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        // Admins to receive notifications
        Admin::factory()->count(2)->create();

        $p1 = Product::factory()->create(['price' => 100.00, 'stock' => 5]);
        $p2 = Product::factory()->create(['price' => 50.00, 'stock' => 2]);

        // Seed session cart
        $cart = [
            $p1->id => ['name' => $p1->name, 'price' => (float) $p1->price, 'quantity' => 2], // 200
            $p2->id => ['name' => $p2->name, 'price' => (float) $p2->price, 'quantity' => 1], // 50
        ];

        $this->withSession(['cart' => $cart]);

        Livewire::test(Checkout::class)
            ->call('placeOrder')
            ->assertRedirect(route('customer.orders'));

        // Order created with total 250 and pending status
        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertSame($user->id, $order->user_id);
        $this->assertEquals(250.00, (float) $order->total);
        $this->assertTrue($order->status === OrderStatus::PENDING);

        // Order items created
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $p1->id,
            'quantity' => 2,
            'price' => (float) $p1->price,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $p2->id,
            'quantity' => 1,
            'price' => (float) $p2->price,
        ]);

        // Stock decremented
        $this->assertEquals(3, $p1->fresh()->stock);
        $this->assertEquals(1, $p2->fresh()->stock);

        // Cart cleared
        $this->assertEmpty(session('cart', []));

        // Notifications were sent to admins (we don't assert exact content here)
        Notification::assertCount(2);
    }

    public function test_place_order_fails_when_cart_empty(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $this->withSession(['cart' => []]);

        Livewire::test(Checkout::class)
            ->call('placeOrder')
            ->assertNoRedirect();

        $this->assertDatabaseCount('orders', 0);
    }
}
