<script setup lang="ts">
import ProductCard from './ProductCard.vue';
import type { Product } from '../../types/product';

interface Props {
    products: Product[];
    showWishlist?: boolean;
}

withDefaults(defineProps<Props>(), {
    showWishlist: true,
});

const emit = defineEmits<{
    'add-to-cart': [product: Product];
    open: [product: Product];
}>();
</script>

<template>
    <section v-if="products.length" class="related-products">
        <h2 class="related-products__title">You may also like</h2>

        <div class="related-products__list">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
                variant="slider"
                :show-wishlist="showWishlist"
                @add-to-cart="emit('add-to-cart', product)"
                @open="emit('open', product)"
            />
        </div>
    </section>
</template>

<style scoped>
.related-products {
    margin-top: 2.5rem;
}

.related-products__title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.related-products__list {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}
</style>
