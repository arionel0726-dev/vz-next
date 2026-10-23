<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import CartItem from '../../Components/Shop/CartItem.vue';
import CartSummary from '../../Components/Shop/CartSummary.vue';
import CartEmpty from '../../Components/Shop/CartEmpty.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import { siteNavItems, siteFooterColumns, siteAnnouncement } from '../../constants/navigation';
import type { CartItem as CartItemType, CartTotals } from '../../types/cart';

interface Props {
    items: CartItemType[];
    totals: CartTotals;
}

defineProps<Props>();

function handleUpdateQuantity(key: string, quantity: number): void {
    router.patch(`/cart/items/${key}`, { quantity }, { preserveScroll: true });
}

function handleRemove(key: string): void {
    router.delete(`/cart/items/${key}`, { preserveScroll: true });
}

function handleClear(): void {
    router.delete('/cart', { preserveScroll: true });
}

</script>

<template>
    <SiteLayout
        :nav-items="siteNavItems"
        :footer-columns="siteFooterColumns"
        :announcement="siteAnnouncement"
    >
        <div class="cart-page">
            <h1 class="cart-page__title">Cart</h1>

            <CartEmpty v-if="items.length === 0" />

            <div v-else class="cart-page__layout">
                <div class="cart-page__items">
                    <CartItem
                        v-for="item in items"
                        :key="item.id"
                        :item="item"
                        @update-quantity="handleUpdateQuantity"
                        @remove="handleRemove"
                    />

                    <UiButton
                        variant="ghost"
                        class="cart-page__clear"
                        @click="handleClear"
                    >
                        Clear cart
                    </UiButton>
                </div>

                <CartSummary :totals="totals" />
            </div>
        </div>
    </SiteLayout>
</template>

<style scoped>
.cart-page {
    max-width: 64rem;
    margin: 0 auto;
    padding: 1.5rem 1.5rem 3rem;
}

.cart-page__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.cart-page__layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    align-items: start;
}

.cart-page__items {
    display: flex;
    flex-direction: column;
}

.cart-page__clear {
    align-self: flex-start;
    margin-top: 1rem;
}

@media (min-width: 64rem) {
    .cart-page__layout {
        grid-template-columns: minmax(0, 1fr) 20rem;
    }
}
</style>
