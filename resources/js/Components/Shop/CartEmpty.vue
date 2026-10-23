<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';
import UiIcon from '../UI/UiIcon.vue';
</script>

<template>
    <div class="cart-empty">
        <UiIcon name="cart" :size="40" />

        <h2 class="cart-empty__title">Your cart is empty</h2>
        <p class="cart-empty__text">Add some products to see them here.</p>

        <UiButton variant="primary" href="/catalog">
            Go to catalog
        </UiButton>
    </div>
</template>

<style scoped>
.cart-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 3rem 1rem;
    text-align: center;
    color: #706f6c;
}

.cart-empty__title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1b1b18;
}

.cart-empty__text {
    font-size: 0.9375rem;
}
</style>
