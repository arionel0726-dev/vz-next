<script setup lang="ts">
import { ref } from 'vue';
import UiIcon from '../UI/UiIcon.vue';
import ProductCard from '../Shop/ProductCard.vue';
import type { Product } from '../../types/product';

interface Props {
    title: string;
    products: Product[];
    showWishlist?: boolean;
}

withDefaults(defineProps<Props>(), {
    showWishlist: true,
});

const emit = defineEmits<{
    'add-to-cart': [product: Product];
    open: [product: Product];
    'toggle-wishlist': [product: Product];
}>();

const trackRef = ref<HTMLElement | null>(null);

function scrollBy(direction: 1 | -1): void {
    const track = trackRef.value;

    if (!track) {
        return;
    }

    // Scroll by roughly two cards at a time.
    track.scrollBy({ left: direction * track.clientWidth * 0.6, behavior: 'smooth' });
}
</script>

<template>
    <section v-if="products.length" class="product-slider">
        <div class="product-slider__header">
            <h2 class="product-slider__title">{{ title }}</h2>

            <div class="product-slider__arrows">
                <button
                    type="button"
                    class="product-slider__arrow"
                    aria-label="Previous"
                    @click="scrollBy(-1)"
                >
                    <UiIcon name="chevron-left" :size="18" />
                </button>

                <button
                    type="button"
                    class="product-slider__arrow"
                    aria-label="Next"
                    @click="scrollBy(1)"
                >
                    <UiIcon name="chevron-right" :size="18" />
                </button>
            </div>
        </div>

        <div ref="trackRef" class="product-slider__track">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
                variant="slider"
                :show-wishlist="showWishlist"
                @add-to-cart="emit('add-to-cart', product)"
                @open="emit('open', product)"
                @toggle-wishlist="emit('toggle-wishlist', product)"
            />
        </div>
    </section>
</template>

<style scoped>
.product-slider {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.product-slider__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.product-slider__title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-ink);
}

.product-slider__arrows {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.product-slider__arrow {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border: none;
    background: transparent;
    color: var(--color-ink);
    cursor: pointer;
}

.product-slider__arrow:hover {
    color: var(--color-muted);
}

.product-slider__track {
    /* How many cards are visible at once -- fractional on small
       screens as a "there's more" scroll hint, exactly 4 on desktop. */
    --slider-visible: 1.4;
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    scroll-snap-type: x proximity;
    padding-bottom: 0.25rem;
    scrollbar-width: none;
}

.product-slider__track::-webkit-scrollbar {
    display: none;
}

.product-slider__track > :deep(.product-card) {
    scroll-snap-align: start;
}

/* Overrides ProductCard's own fixed 14rem slider width so exactly
   --slider-visible cards fit the track's width, gaps included. */
.product-slider__track > :deep(.product-card--slider) {
    width: auto;
    flex: 0 0 calc((100% - (var(--slider-visible) - 1) * 1rem) / var(--slider-visible));
}

@media (min-width: 40rem) {
    .product-slider__track {
        --slider-visible: 2.4;
    }
}

@media (min-width: 64rem) {
    .product-slider__title {
        font-size: 1.75rem;
    }

    .product-slider__track {
        --slider-visible: 4;
    }
}
</style>
