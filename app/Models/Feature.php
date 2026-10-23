<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'feature_type_id',
    'name',
    'input_type',
    'has_free_values',
    'sort_order',
])]
class Feature extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'array',
            'has_free_values' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function featureType(): BelongsTo
    {
        return $this->belongsTo(FeatureType::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(FeatureValue::class);
    }
}
