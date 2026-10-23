<script setup lang="ts">
import UiBadge from '../UI/UiBadge.vue';
import UiButton from '../UI/UiButton.vue';
import ProductPrice from './ProductPrice.vue';

interface Props {
    title: string;
    brand?: string;
    category?: string;
    isNew?: boolean;
    isSale?: boolean;
    salePercent?: number;
    isHit?: boolean;
    price: number;
    oldPrice?: number;
    bonusAmount?: number;
    currency?: string;
    inStock: boolean;
}

withDefaults(defineProps<Props>(), {
    brand: undefined,
    category: undefined,
    isNew: false,
    isSale: false,
    salePercent: undefined,
    isHit: false,
    oldPrice: undefined,
    bonusAmount: undefined,
    currency: undefined,
});

const emit = defineEmits<{
    'add-to-cart': [];
}>();
</script>

<template>
    <div class="product-info">
        <p v-if="category" class="product-info__category">{{ category }}</p>
        <p v-if="brand" class="product-info__brand">{{ brand }}</p>

        <h1 class="product-info__title">{{ title }}</h1>

        <div v-if="isNew || isSale || isHit" class="product-info__badges">
            <UiBadge v-if="isNew" variant="new">New</UiBadge>

            <UiBadge v-if="isSale" variant="sale">
                <template v-if="salePercent">-{{ salePercent }}%</template>
                <template v-else>Sale</template>
            </UiBadge>

            <UiBadge v-if="isHit" variant="hit">Hit</UiBadge>
        </div>

        <ProductPrice
            class="product-info__price"
            :price="price"
            :old-price="oldPrice"
            :bonus-amount="bonusAmount"
            :currency="currency"
        />

        <p class="product-info__stock" :class="{ 'is-out': !inStock }">
            {{ inStock ? 'In stock' : 'Out of stock' }}
        </p>

        <UiButton
            variant="primary"
            class="product-info__cart-button"
            :disabled="!inStock"
            @click="emit('add-to-cart')"
        >
            Add to cart
        </UiButton>
    </div>
</template>

<style scoped>
.product-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-info__category {
    font-size: 0.8125rem;
    color: #706f6c;
}

.product-info__brand {
    font-size: 0.875rem;
    font-weight: 500;
    color: #1b1b18;
}

.product-info__title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1b1b18;
}

.product-info__badges {
    display: flex;
    gap: 0.375rem;
    margin: 0.25rem 0;
}

.product-info__price {
    margin: 0.5rem 0;
}

.product-info__stock {
    font-size: 0.875rem;
    color: #1f7a4d;
}

.product-info__stock.is-out {
    color: #c53030;
}

.product-info__cart-button {
    margin-top: 0.5rem;
    align-self: flex-start;
}
</style>
