<?php

namespace App\Cart;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

/**
 * A session-backed cart. The session only stores
 * {"productId:variantId": quantity} — product/variant data (price,
 * stock, title, images) is always re-read from the database on
 * every read, so the cart can never show stale prices.
 */
class CartService
{
    private const SESSION_KEY = 'cart';

    public function __construct(private readonly SessionManager $session)
    {
    }

    public function add(Product $product, ?ProductVariant $variant, int $quantity): CartLineItem
    {
        $this->assertPurchasable($product, $variant);

        $key = $this->makeKey($product->id, $variant?->id);
        $lines = $this->raw();

        $newQuantity = ($lines[$key] ?? 0) + $quantity;
        $stock = $this->effectiveStock($product, $variant);

        if ($newQuantity > $stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Requested quantity exceeds available stock.',
            ]);
        }

        $lines[$key] = $newQuantity;
        $this->persist($lines);

        return $this->hydrate($key, $newQuantity, $product, $variant);
    }

    public function update(string $key, int $quantity): CartLineItem
    {
        $lines = $this->raw();

        if (! array_key_exists($key, $lines)) {
            throw ValidationException::withMessages([
                'key' => 'Cart item not found.',
            ]);
        }

        [$product, $variant] = $this->resolveLine($key);

        $this->assertPurchasable($product, $variant);

        $stock = $this->effectiveStock($product, $variant);

        if ($quantity > $stock) {
            throw ValidationException::withMessages([
                'quantity' => 'Requested quantity exceeds available stock.',
            ]);
        }

        $lines[$key] = $quantity;
        $this->persist($lines);

        return $this->hydrate($key, $quantity, $product, $variant);
    }

    public function remove(string $key): void
    {
        $lines = $this->raw();
        unset($lines[$key]);
        $this->persist($lines);
    }

    public function clear(): void
    {
        $this->persist([]);
    }

    /**
     * @return Collection<int, CartLineItem>
     */
    public function items(): Collection
    {
        $lines = $this->raw();
        $result = collect();

        foreach ($lines as $key => $quantity) {
            try {
                [$product, $variant] = $this->resolveLine($key);
            } catch (ValidationException) {
                // The product/variant was deleted or unpublished since
                // being added — drop the stale line rather than error
                // out the whole cart page.
                continue;
            }

            $result->push($this->hydrate($key, $quantity, $product, $variant));
        }

        return $result;
    }

    /**
     * @return array{itemsCount: int, subtotal: float, discountTotal: float, total: float}
     */
    public function totals(): array
    {
        $items = $this->items();

        $subtotal = round($items->sum(fn (CartLineItem $item) => $item->subtotal()), 2);
        $total = round($items->sum(fn (CartLineItem $item) => $item->total()), 2);

        return [
            'itemsCount' => (int) $items->sum('quantity'),
            'subtotal' => $subtotal,
            'discountTotal' => round($subtotal - $total, 2),
            'total' => $total,
        ];
    }

    private function makeKey(int $productId, ?int $variantId): string
    {
        return $productId.':'.($variantId ?? 0);
    }

    /**
     * @return array{0: Product, 1: ProductVariant|null}
     */
    private function resolveLine(string $key): array
    {
        $parts = explode(':', $key, 2);
        $productId = (int) ($parts[0] ?? 0);
        $variantId = (int) ($parts[1] ?? 0);

        $product = Product::query()
            ->with(['brand', 'images'])
            ->where('is_published', true)
            ->find($productId);

        if (! $product) {
            throw ValidationException::withMessages([
                'product_id' => 'Product is no longer available.',
            ]);
        }

        $variant = null;

        if ($variantId > 0) {
            $variant = ProductVariant::query()
                ->where('product_id', $product->id)
                ->where('is_published', true)
                ->find($variantId);

            if (! $variant) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Variant is no longer available.',
                ]);
            }
        }

        return [$product, $variant];
    }

    private function assertPurchasable(Product $product, ?ProductVariant $variant): void
    {
        if (! $product->is_published) {
            throw ValidationException::withMessages([
                'product_id' => 'Product is not published.',
            ]);
        }

        if ($variant !== null) {
            if ($variant->product_id !== $product->id) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Variant does not belong to this product.',
                ]);
            }

            if (! $variant->is_published) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Variant is not published.',
                ]);
            }
        }

        if ($this->effectiveStock($product, $variant) <= 0) {
            throw ValidationException::withMessages([
                'quantity' => 'Product is out of stock.',
            ]);
        }
    }

    private function effectiveStock(Product $product, ?ProductVariant $variant): int
    {
        return $variant !== null ? (int) $variant->stock : (int) $product->stock;
    }

    /**
     * @return array{0: float, 1: float|null}
     */
    private function effectivePrice(Product $product, ?ProductVariant $variant): array
    {
        $price = (float) ($variant->price ?? $product->price);

        $discountPrice = $variant !== null
            ? ($variant->discount_price !== null ? (float) $variant->discount_price : null)
            : ($product->discount_price !== null ? (float) $product->discount_price : null);

        $hasDiscount = $discountPrice !== null && $discountPrice > 0 && $discountPrice < $price;

        return [
            $hasDiscount ? $discountPrice : $price,
            $hasDiscount ? $price : null,
        ];
    }

    private function hydrate(
        string $key,
        int $quantity,
        Product $product,
        ?ProductVariant $variant
    ): CartLineItem {
        [$unitPrice, $oldUnitPrice] = $this->effectivePrice($product, $variant);

        return new CartLineItem(
            key: $key,
            product: $product,
            variant: $variant,
            quantity: $quantity,
            unitPrice: $unitPrice,
            oldUnitPrice: $oldUnitPrice,
            stock: $this->effectiveStock($product, $variant),
        );
    }

    /**
     * @return array<string, int>
     */
    private function raw(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    /**
     * @param  array<string, int>  $lines
     */
    private function persist(array $lines): void
    {
        $this->session->put(self::SESSION_KEY, $lines);
    }
}
