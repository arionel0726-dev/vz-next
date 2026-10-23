<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';
import UiBadge from '../UI/UiBadge.vue';
import UiIcon from '../UI/UiIcon.vue';
import ProductPrice from './ProductPrice.vue';
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
                    :src="product.image"
                    :alt="product.title"
                    loading="lazy"
                >
            </button>

            <div v-if="showBadges" class="product-card__badges">
                <UiBadge v-if="product.isNew" variant="new">New</UiBadge>

                <UiBadge v-if="product.isSale" variant="sale">
                    <template v-if="product.salePercent">
                        -{{ product.salePercent }}%
                    </template>
                    <template v-else>Sale</template>
                </UiBadge>

                <UiBadge v-if="product.isHit" variant="hit">Hit</UiBadge>
            </div>

            <UiButton
                v-if="showWishlist"
                variant="icon"
                class="product-card__wishlist"
                :aria-label="product.isLiked ? 'Remove from wishlist' : 'Add to wishlist'"
                @click="emit('toggle-wishlist', product)"
            >
                <UiIcon name="check" v-if="product.isLiked" />
                <UiIcon name="plus" v-else />
            </UiButton>

            <UiButton
                v-if="showCartButton && product.inStock !== false"
                variant="secondary"
                class="product-card__cart-button"
                @click="emit('add-to-cart', product)"
            >
                In cart
            </UiButton>
        </div>

        <div class="product-card__body">
            <p v-if="product.brand" class="product-card__brand">
                {{ product.brand }}
            </p>

            <button
                type="button"
                class="product-card__title"
                @click="emit('open', product)"
            >
                {{ product.title }}
            </button>

            <ProductPrice
                class="product-card__price"
                :price="product.price"
                :old-price="product.oldPrice"
                :bonus-amount="product.bonusAmount"
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
    border-radius: 0.75rem;
    overflow: hidden;
    background-color: #f1f1ef;
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

.product-card__wishlist {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background-color: #fff;
}

.product-card__cart-button {
    position: absolute;
    bottom: 0.5rem;
    right: 0.5rem;
}

.product-card--compact .product-card__cart-button,
.product-card--compact .product-card__badges {
    display: none;
}

.product-card__body {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-card__brand {
    font-size: 0.75rem;
    color: #706f6c;
}

.product-card__title {
    display: block;
    width: 100%;
    text-align: left;
    border: none;
    background: transparent;
    padding: 0;
    font-size: 0.9375rem;
    font-weight: 500;
    color: #1b1b18;
    cursor: pointer;
}

.product-card__price {
    margin-top: 0.25rem;
}
</style>
