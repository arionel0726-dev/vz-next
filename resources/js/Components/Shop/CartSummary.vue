<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';
import type { CartTotals } from '../../types/cart';

interface Props {
    totals: CartTotals;
    showCheckoutButton?: boolean;
}

withDefaults(defineProps<Props>(), {
    showCheckoutButton: true,
});
</script>

<template>
    <section class="cart-summary">
        <h2 class="cart-summary__title">Order summary</h2>

        <div class="cart-summary__rows">
            <div class="cart-summary__row">
                <span>Items ({{ totals.itemsCount }})</span>
                <span>{{ totals.subtotal.toFixed(2) }} MDL</span>
            </div>

            <div
                v-if="totals.discountTotal > 0"
                class="cart-summary__row cart-summary__row--discount"
            >
                <span>Discount</span>
                <span>-{{ totals.discountTotal.toFixed(2) }} MDL</span>
            </div>
        </div>

        <div class="cart-summary__total">
            <span>Total</span>
            <strong>{{ totals.total.toFixed(2) }} MDL</strong>
        </div>

        <UiButton
            v-if="showCheckoutButton"
            href="/checkout"
            variant="primary"
            class="cart-summary__checkout"
        >
            Checkout
        </UiButton>
    </section>
</template>

<style scoped>
.cart-summary {
    border: 1px solid #ededeb;
    border-radius: 0.75rem;
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    height: fit-content;
}

.cart-summary__title {
    font-size: 1.0625rem;
    font-weight: 600;
}

.cart-summary__rows {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.cart-summary__row {
    display: flex;
    justify-content: space-between;
    font-size: 0.875rem;
    color: #4a4a47;
}

.cart-summary__row--discount {
    color: #1f7a4d;
}

.cart-summary__total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 0.75rem;
    border-top: 1px solid #ededeb;
    font-size: 1rem;
}

.cart-summary__checkout {
    width: 100%;
    justify-content: center;
}
</style>
