<?php

namespace Tests\Unit;

use App\Jobs\ImportProductsChunk;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportProductsChunkTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_skips_rows_without_name_and_sets_default_image_and_upserts(): void
    {
        $header = ['name', 'description', 'price', 'image', 'category', 'stock'];
        $rows = [
            // invalid: empty name -> should be skipped
            ['', 'desc x', '9.99', null, 'Cat A', '5'],
            // valid with explicit image
            ['Product A', 'Nice A', '10.50', 'a.jpg', 'Cat A', '3'],
            // valid without image -> default-product.jpg
            ['Product B', 'Nice B', '20', null, 'Cat B', '7'],
        ];

        // initial import
        $job = new ImportProductsChunk($header, $rows);
        $job->handle();

        $this->assertDatabaseHas('products', [
            'name' => 'Product A',
            'category' => 'Cat A',
            'image' => 'a.jpg',
            'stock' => 3,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Product B',
            'category' => 'Cat B',
            'image' => 'default-product.jpg',
            'stock' => 7,
        ]);

        // ensure skipped row not inserted
        $this->assertDatabaseMissing('products', [
            'description' => 'desc x',
        ]);

        // upsert behavior: update Product A price/stock/image
        $rowsUpdate = [
            ['Product A', 'Nice A updated', '11.00', 'a2.jpg', 'Cat A', '9'],
        ];
        (new ImportProductsChunk($header, $rowsUpdate))->handle();

        $this->assertDatabaseHas('products', [
            'name' => 'Product A',
            'category' => 'Cat A',
            'image' => 'a2.jpg',
            'stock' => 9,
            // description is in update list as well
            'description' => 'Nice A updated',
        ]);

        // total products should still be 2
        $this->assertSame(2, Product::count());
    }
}
