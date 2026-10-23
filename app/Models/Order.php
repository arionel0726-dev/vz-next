<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'user_id',
    'order_number',
    'status',
    'subtotal',
    'total',
    'contact_name',
    'contact_email',
    'contact_phone',
    'delivery_address',
    'notes',
])]
class Order extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELED = 'canceled';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * A short, unique, human-facing order reference.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $candidate = now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (self::where('order_number', $candidate)->exists());

        return $candidate;
    }
}
