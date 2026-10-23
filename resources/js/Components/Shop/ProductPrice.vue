<script setup lang="ts">
interface Props {
    price: number;
    oldPrice?: number;
    bonusAmount?: number;
    currency?: string;
}

const props = withDefaults(defineProps<Props>(), {
    oldPrice: undefined,
    bonusAmount: undefined,
    currency: 'MDL',
});

function formatAmount(value: number): string {
    return new Intl.NumberFormat('ru-RU', {
        maximumFractionDigits: 0,
    }).format(value);
}

const hasDiscount = (props.oldPrice ?? 0) > props.price;
const hasBonus = !hasDiscount && (props.bonusAmount ?? 0) > 0;
</script>

<template>
    <div class="product-price" :class="{ 'has-discount': hasDiscount }">
        <div class="product-price__row">
            <span class="product-price__current">
                {{ formatAmount(price) }} {{ currency }}
            </span>

            <span v-if="hasDiscount" class="product-price__old">
                {{ formatAmount(oldPrice as number) }} {{ currency }}
            </span>
        </div>

        <p v-if="hasBonus" class="product-price__bonus">
            +{{ formatAmount(bonusAmount as number) }} bonus
        </p>
    </div>
</template>

<style scoped>
.product-price {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.product-price__row {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
}

.product-price__current {
    font-size: 1rem;
    font-weight: 600;
    color: #1b1b18;
}

.has-discount .product-price__current {
    color: #c53030;
}

.product-price__old {
    font-size: 0.8125rem;
    color: #a1a09a;
    text-decoration: line-through;
}

.product-price__bonus {
    font-size: 0.75rem;
    color: #38469e;
}
</style>
