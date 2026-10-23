<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'product_id',
    'sku',
    'title',
    'volume',
    'color_hex_list',
    'price',
    'stock',
    'wholesale_price',
    'wholesale_stock',
    'discount_price',
    'is_published',
    'sort_order',
])]
class ProductVariant extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'color_hex_list' => 'array',
            'price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'stock' => 'integer',
            'wholesale_stock' => 'integer',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductVariantImage::class);
    }
}
