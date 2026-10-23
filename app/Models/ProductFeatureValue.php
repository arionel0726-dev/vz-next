<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('product_feature_value')]
#[Fillable([
    'product_id',
    'feature_value_id',
])]
class ProductFeatureValue extends Model
{
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function featureValue(): BelongsTo
    {
        return $this->belongsTo(FeatureValue::class);
    }
}
