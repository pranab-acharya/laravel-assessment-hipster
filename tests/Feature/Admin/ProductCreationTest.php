<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ProductManager;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_product_without_image_and_default_is_used(): void
    {
        Livewire::test(ProductManager::class)
            ->set('name', 'Test Product')
            ->set('description', 'A test product')
            ->set('price', 123.45)
            ->set('category', 'Gadgets')
            ->set('stock', 10)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'category' => 'Gadgets',
            'price' => 123.45,
            'stock' => 10,
            'image' => 'default-product.jpg',
        ]);

        $this->assertEquals(1, Product::count());
    }
}
