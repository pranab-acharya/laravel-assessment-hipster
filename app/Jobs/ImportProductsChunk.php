<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportProductsChunk implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var array<int, string> */
    public array $header;

    /** @var array<int, array<int, string|null>> */
    public array $rows;

    public int $timeout = 120;

    /**
     * @param  array<int, string>  $header
     * @param  array<int, array<int, string|null>>  $rows
     */
    public function __construct(array $header, array $rows)
    {
        $this->header = $header;
        $this->rows = $rows;
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $map = collect($this->header)
            ->map(fn ($h) => strtolower(trim((string) $h)))
            ->values()->toArray();

        $now = now();
        $inserts = [];

        foreach ($this->rows as $row) {
            $data = [];
            foreach ($map as $i => $key) {
                $data[$key] = $row[$i] ?? null;
            }

            $name = trim((string) ($data['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $price = (float) ($data['price'] ?? 0);
            $stock = (int) ($data['stock'] ?? 0);
            $image = $data['image'] ?? null;
            if (! $image) {
                $image = 'default-product.jpg';
            }

            $inserts[] = [
                'name' => $name,
                'description' => $data['description'] ?? null,
                'price' => $price,
                'image' => $image,
                'category' => (string) ($data['category'] ?? ''),
                'stock' => $stock,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($inserts)) {
            Product::upsert(
                $inserts,
                ['name', 'category'],
                ['description', 'price', 'image', 'stock', 'updated_at']
            );
        }
    }
}
