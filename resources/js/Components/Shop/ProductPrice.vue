<script setup lang="ts">
interface Props {
    price: number;
    oldPrice?: number;
    bonusAmount?: number;
    currency?: string;
    /** When set alongside oldPrice, shows a "N% off" line above the price
        (reference layout) instead of the strikethrough price inline. */
    salePercent?: number;
}

const props = withDefaults(defineProps<Props>(), {
    oldPrice: undefined,
    bonusAmount: undefined,
    currency: 'MDL',
    salePercent: undefined,
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
        <p v-if="hasDiscount && salePercent" class="product-price__discount-line">
            {{ salePercent }}% off
            <span class="product-price__old">{{ formatAmount(oldPrice as number) }} {{ currency }}</span>
        </p>

        <div class="product-price__row">
            <span class="product-price__current">
                {{ formatAmount(price) }} {{ currency }}
            </span>

            <span v-if="hasDiscount && !salePercent" class="product-price__old">
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

.product-price__discount-line {
    display: flex;
    align-items: baseline;
    gap: 0.375rem;
    font-size: 0.75rem;
    color: var(--color-muted);
}

.product-price__row {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
}

.product-price__current {
    font-size: 1rem;
    font-weight: 600;
    /* The reference keeps the price black even when discounted — color
       is reserved for badges, never price/body text. */
    color: var(--color-ink);
}

.product-price__old {
    font-size: 0.8125rem;
    color: var(--color-muted);
    text-decoration: line-through;
}

.product-price__bonus {
    font-size: 0.75rem;
    color: var(--color-muted);
}
</style>
