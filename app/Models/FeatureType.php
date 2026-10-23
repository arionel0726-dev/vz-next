<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class FeatureType extends Model
{
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
