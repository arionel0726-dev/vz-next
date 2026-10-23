<?php

namespace App\Cart;

use App\Models\Product;
use App\Models\ProductVariant;

/**
 * One line in the session cart, already resolved against the
 * current Product/ProductVariant — never trust stale session data
 * for price or stock, always re-read it from the database.
 */
final class CartLineItem
{
    public function __construct(
        public readonly string $key,
        public readonly Product $product,
        public readonly ?ProductVariant $variant,
        public readonly int $quantity,
        public readonly float $unitPrice,
        public readonly ?float $oldUnitPrice,
        public readonly int $stock,
    ) {
    }

    /**
     * Line total at the original (pre-discount) price.
     */
    public function subtotal(): float
    {
        return round(($this->oldUnitPrice ?? $this->unitPrice) * $this->quantity, 2);
    }

    /**
     * Line total at the actual price to pay.
     */
    public function total(): float
    {
        return round($this->unitPrice * $this->quantity, 2);
    }
}
