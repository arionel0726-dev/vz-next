<script lang="ts">
/** First two swatches are shown; the rest collapse into a "+N" chip. */
const MAX_VISIBLE_SWATCHES = 2;
</script>

<script setup lang="ts">
import UiBadge from '../UI/UiBadge.vue';
import UiIcon from '../UI/UiIcon.vue';
import ProductPrice from './ProductPrice.vue';
import { resolveMediaUrl } from '../../utils/media';
import type { Product } from '../../types/product';

export type ProductCardVariant = 'grid' | 'slider' | 'compact';

interface Props {
    product: Product;
    variant?: ProductCardVariant;
    showCartButton?: boolean;
    showWishlist?: boolean;
    showBadges?: boolean;
}

withDefaults(defineProps<Props>(), {
    variant: 'grid',
    showCartButton: true,
    showWishlist: true,
    showBadges: true,
});

const emit = defineEmits<{
    'add-to-cart': [product: Product];
    open: [product: Product];
    'toggle-wishlist': [product: Product];
}>();

function swatchBackground(colorHex?: string[] | null): string | undefined {
    if (!colorHex || colorHex.length === 0) {
        return undefined;
    }

    if (colorHex.length === 1) {
        return colorHex[0];
    }

    // A bicolor/split swatch for variants with more than one color.
    return `linear-gradient(90deg, ${colorHex[0]} 50%, ${colorHex[1]} 50%)`;
}
</script>

<template>
    <article
        class="product-card"
        :class="`product-card--${variant}`"
    >
        <div class="product-card__media">
            <button
                type="button"
                class="product-card__image-button"
                @click="emit('open', product)"
            >
                <img
                    class="product-card__image"
                    :src="resolveMediaUrl(product.image)"
                    :alt="product.title"
                    loading="lazy"
                >
            </button>

            <div v-if="showBadges" class="product-card__badges">
                <UiBadge v-if="product.isFlash" variant="flash" icon-only aria-label="Flash deal">
                    <UiIcon name="lightning" :size="14" />
                </UiBadge>

                <UiBadge v-if="product.isExclusive" variant="exclusive">Only VN</UiBadge>
                <UiBadge v-if="product.isHit" variant="hit">Hit</UiBadge>
                <UiBadge v-if="product.isNew" variant="new">New</UiBadge>
                <UiBadge v-if="product.isGift" variant="gift">+Gift</UiBadge>
            </div>

            <button
                v-if="showWishlist"
                type="button"
                class="product-card__wishlist"
                :aria-label="product.isLiked ? 'Remove from wishlist' : 'Add to wishlist'"
                @click="emit('toggle-wishlist', product)"
            >
                <UiIcon :name="product.isLiked ? 'heart-filled' : 'heart'" :size="18" />
            </button>

            <button
                v-if="showCartButton && product.inStock !== false"
                type="button"
                class="product-card__cart-button"
                aria-label="Add to cart"
                @click="emit('add-to-cart', product)"
            >
                <UiIcon name="bag" :size="16" />
            </button>
        </div>

        <div
            v-if="product.variants && product.variants.length > 0"
            class="product-card__swatches"
        >
            <template v-for="swatch in product.variants.slice(0, MAX_VISIBLE_SWATCHES)" :key="swatch.id">
                <span
                    v-if="swatch.colorHex && swatch.colorHex.length > 0"
                    class="product-card__swatch"
                    :style="{ background: swatchBackground(swatch.colorHex) }"
                    :aria-label="swatch.label"
                />

                <span v-else class="product-card__swatch product-card__swatch--chip">
                    {{ swatch.label }}
                </span>
            </template>

            <span
                v-if="product.variants.length > MAX_VISIBLE_SWATCHES"
                class="product-card__swatch-more"
            >
                +{{ product.variants.length - MAX_VISIBLE_SWATCHES }}
            </span>
        </div>

        <div class="product-card__body">
            <p v-if="product.category" class="product-card__kicker">
                {{ product.category }}
            </p>

            <button
                type="button"
                class="product-card__title"
                @click="emit('open', product)"
            >
                <span v-if="product.brand" class="product-card__brand">{{ product.brand }}</span>
                {{ product.title }}
            </button>

            <ProductPrice
                class="product-card__price"
                :price="product.price"
                :old-price="product.oldPrice"
                :bonus-amount="product.bonusAmount"
                :sale-percent="product.salePercent"
                :currency="product.currency"
            />
        </div>
    </article>
</template>

<style scoped>
.product-card {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.product-card--grid {
    width: 100%;
}

.product-card--slider {
    width: 14rem;
    flex-shrink: 0;
}

.product-card--compact {
    width: 100%;
    flex-direction: row;
    gap: 0.75rem;
}

.product-card--compact .product-card__media {
    width: 5rem;
    flex-shrink: 0;
}

.product-card__media {
    position: relative;
    border-radius: 0;
    overflow: hidden;
    background-color: var(--color-surface-muted);
    aspect-ratio: 1 / 1;
}

.product-card__image-button {
    display: block;
    width: 100%;
    height: 100%;
    border: none;
    background: transparent;
    padding: 0;
    cursor: pointer;
}

.product-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-card__badges {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

/* Plain outline icon directly on the photo, no backing plate — the
   reference never puts a white chip behind the wishlist heart. */
.product-card__wishlist {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: inline-flex;
    border: none;
    background: transparent;
    padding: 0;
    color: var(--color-ink);
    cursor: pointer;
}

/* The one circular element on the card — a floating quick-add chip,
   not a standard chrome icon-button (those stay square; see
   UiButton.vue). Reference screenshots show this specific control as
   a solid black circle over the photo. */
.product-card__cart-button {
    position: absolute;
    bottom: 0.5rem;
    right: 0.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border: none;
    border-radius: 50%;
    background-color: var(--color-ink);
    color: var(--color-canvas);
    cursor: pointer;
}

.product-card--compact .product-card__cart-button,
.product-card--compact .product-card__badges,
.product-card--compact .product-card__swatches {
    display: none;
}

.product-card__swatches {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.product-card__swatch {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    border: 1px solid var(--color-border);
}

/* A variant with no color (e.g. a fragrance volume) renders as a
   rounded chip with its label instead of a color circle. */
.product-card__swatch--chip {
    width: auto;
    height: auto;
    min-width: 1.5rem;
    padding: 0.125rem 0.5rem;
    border-radius: 999px;
    font-size: 0.6875rem;
    color: var(--color-ink);
}

.product-card__swatch-more {
    font-size: 0.75rem;
    color: var(--color-muted);
}

.product-card__body {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-card__kicker {
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    color: var(--color-kicker);
}

.product-card__title {
    display: block;
    width: 100%;
    text-align: left;
    border: none;
    background: transparent;
    padding: 0;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--color-ink);
    cursor: pointer;
}

.product-card__brand {
    text-transform: uppercase;
    margin-right: 0.3em;
}

.product-card__price {
    margin-top: 0.25rem;
}
</style>
