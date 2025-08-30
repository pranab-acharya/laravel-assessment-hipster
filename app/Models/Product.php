<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'stock',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Accessor for image URL with default fallback if file missing.
     */
    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $filename = $this->image ?: '';
                $storagePath = public_path('storage/images/' . $filename);
                if ($filename && file_exists($storagePath)) {
                    return asset('storage/images/' . $filename);
                }

                return asset('images/product_default.webp');
            }
        );
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
