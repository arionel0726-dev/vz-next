<?php

namespace App\Http\Resources;

use App\Support\Translation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Maps a Product (with brand/category/images/variants eager loaded)
 * into the shape resources/js/types/product.ts expects.
 *
 * Price/discount/stock logic lives here — the frontend only renders
 * what the server already computed.
 */
class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = (float) $this->price;
        $discountPrice = $this->discount_price !== null ? (float) $this->discount_price : null;

        $hasDiscount = $discountPrice !== null && $discountPrice > 0 && $discountPrice < $price;

        $currentPrice = $hasDiscount ? $discountPrice : $price;
        $oldPrice = $hasDiscount ? $price : null;

        $salePercent = $hasDiscount && $price > 0
            ? (int) round((($price - $discountPrice) / $price) * 100)
            : null;

        return [
            'id' => $this->id,
            'slug' => Translation::pick($this->slug),
            'title' => Translation::pick($this->title),
            'brand' => $this->whenLoaded('brand', fn () => $this->brand?->title),
            'category' => $this->whenLoaded(
                'category',
                fn () => Translation::pick($this->category?->title)
            ),
            'image' => $this->whenLoaded(
                'images',
                fn () => optional($this->images->first())->path
            ),
            'gallery' => $this->whenLoaded(
                'images',
                fn () => $this->images->pluck('path')->values()->all()
            ),
            'price' => $currentPrice,
            'oldPrice' => $oldPrice,
            'bonusAmount' => null,
            'currency' => 'MDL',
            'isNew' => (bool) $this->is_new,
            'isSale' => $hasDiscount,
            'salePercent' => $salePercent,
            'isHit' => (bool) $this->is_best_selling,
            'inStock' => (int) $this->stock > 0,
            'hasVariants' => $this->whenLoaded(
                'variants',
                fn () => $this->variants->isNotEmpty(),
                false
            ),
            'variants' => $this->whenLoaded(
                'variants',
                fn () => $this->variants->map(function ($variant) {
                    $variantPrice = (float) $variant->price;
                    $variantDiscount = $variant->discount_price !== null
                        ? (float) $variant->discount_price
                        : null;

                    $variantHasDiscount = $variantDiscount !== null
                        && $variantDiscount > 0
                        && $variantDiscount < $variantPrice;

                    return [
                        'id' => $variant->id,
                        'label' => Translation::pick($variant->title) ?? $variant->volume,
                        'price' => $variantHasDiscount ? $variantDiscount : $variantPrice,
                        'oldPrice' => $variantHasDiscount ? $variantPrice : null,
                        'inStock' => (int) $variant->stock > 0,
                    ];
                })->values()->all()
            ),
        ];
    }
}
