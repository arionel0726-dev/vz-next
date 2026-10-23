<script setup lang="ts">
import ProductCard from './ProductCard.vue';
import type { Product } from '../../types/product';
import type { CatalogViewMode } from '../../types/catalog';

interface Props {
    products: Product[];
    viewMode?: CatalogViewMode;
    showWishlist?: boolean;
}

withDefaults(defineProps<Props>(), {
    viewMode: 'grid',
    showWishlist: true,
});

const emit = defineEmits<{
    'add-to-cart': [product: Product];
    open: [product: Product];
    'toggle-wishlist': [product: Product];
}>();
</script>

<template>
    <div class="product-grid" :class="`product-grid--${viewMode}`">
        <ProductCard
            v-for="product in products"
            :key="product.id"
            :product="product"
            :variant="viewMode === 'list' ? 'compact' : 'grid'"
            :show-wishlist="showWishlist"
            @add-to-cart="emit('add-to-cart', product)"
            @open="emit('open', product)"
            @toggle-wishlist="emit('toggle-wishlist', product)"
        />
    </div>
</template>

<style scoped>
.product-grid--grid {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    gap: 1rem;
}

.product-grid--list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (min-width: 40rem) {
    .product-grid--grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 64rem) {
    .product-grid--grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 80rem) {
    .product-grid--grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>
