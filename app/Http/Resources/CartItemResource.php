<?php

namespace App\Http\Resources;

use App\Cart\CartLineItem;
use App\Support\Translation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Formats a CartLineItem for the frontend: id, product, variant,
 * quantity, unitPrice, oldPrice, subtotal (at the original price),
 * total (at the actual price), stock.
 */
class CartItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var CartLineItem $item */
        $item = $this->resource;

        return [
            'id' => $item->key,
            'product' => [
                'id' => $item->product->id,
                'slug' => Translation::pick($item->product->slug),
                'title' => Translation::pick($item->product->title),
                'brand' => $item->product->relationLoaded('brand')
                    ? $item->product->brand?->title
                    : null,
                'image' => $item->product->relationLoaded('images')
                    ? optional($item->product->images->first())->path
                    : null,
            ],
            'variant' => $item->variant !== null ? [
                'id' => $item->variant->id,
                'label' => Translation::pick($item->variant->title) ?? $item->variant->volume,
            ] : null,
            'quantity' => $item->quantity,
            'unitPrice' => $item->unitPrice,
            'oldPrice' => $item->oldUnitPrice,
            'subtotal' => $item->subtotal(),
            'total' => $item->total(),
            'stock' => $item->stock,
        ];
    }
}
