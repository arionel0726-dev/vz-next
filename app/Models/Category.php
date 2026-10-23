<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id',
    'feature_type_id',
    'title',
    'h1_title',
    'breadcrumb_title',
    'description',
    'slug',
    'seo_title',
    'seo_keywords',
    'seo_description',
    'seo_text',
    'image',
    'nav_image',
    'nav_image_hover',
    'wallpaper',
    'depth_level',
    'is_top_menu',
    'is_published',
    'sort_order',
])]
class Category extends Model
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
            'slug' => 'array',
            'seo_title' => 'array',
            'seo_keywords' => 'array',
            'seo_description' => 'array',
            'seo_text' => 'array',
            'depth_level' => 'integer',
            'is_top_menu' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function featureType(): BelongsTo
    {
        return $this->belongsTo(FeatureType::class);
    }

    public function banners(): HasMany
    {
        return $this->hasMany(CategoryBanner::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
