<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category_id',
    'brand_id',
    'sku',
    'barcode',
    'search_tag',
    'title',
    'h1_title',
    'breadcrumb_title',
    'description',
    'text',
    'slug',
    'instruction',
    'components',
    'seo_title',
    'seo_keywords',
    'seo_description',
    'price',
    'stock',
    'wholesale_price',
    'wholesale_stock',
    'discount_price',
    'sale_percent',
    'sex',
    'volume',
    'is_new',
    'is_best_selling',
    'is_published',
    'sort_order',
])]
class Product extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'title' => 'array',
            'h1_title' => 'array',
            'breadcrumb_title' => 'array',
            'description' => 'array',
            'text' => 'array',
            'slug' => 'array',
            'instruction' => 'array',
            'components' => 'array',
            'seo_title' => 'array',
            'seo_keywords' => 'array',
            'seo_description' => 'array',
            'price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'stock' => 'integer',
            'wholesale_stock' => 'integer',
            'sale_percent' => 'integer',
            'sex' => 'integer',
            'is_new' => 'boolean',
            'is_best_selling' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function productFeatureValues(): HasMany
    {
        return $this->hasMany(ProductFeatureValue::class);
    }

    public function featureValues(): BelongsToMany
    {
        return $this->belongsToMany(FeatureValue::class, 'product_feature_value');
    }

    public function productRelations(): HasMany
    {
        return $this->hasMany(ProductRelation::class);
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_relations',
            'product_id',
            'related_product_id',
        )->withPivot('type');
    }
}
