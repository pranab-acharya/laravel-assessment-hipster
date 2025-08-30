<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $categories = ['Apparel', 'Electronics', 'Home & Kitchen', 'Outdoors', 'Stationery'];

        return [
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(12),
            'price' => fake()->randomFloat(2, 1, 500),
            'image' => 'default-product.jpg',
            'category' => fake()->randomElement($categories),
            'stock' => fake()->numberBetween(0, 1000),
        ];
    }
}
