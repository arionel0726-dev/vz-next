<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'image',
    'h1_title',
    'breadcrumb_title',
    'description',
    'seo_title',
    'seo_keywords',
    'seo_description',
    'seo_text',
    'is_published',
    'sort_order',
])]
class Brand extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'h1_title' => 'array',
            'breadcrumb_title' => 'array',
            'description' => 'array',
            'seo_title' => 'array',
            'seo_keywords' => 'array',
            'seo_description' => 'array',
            'seo_text' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
