<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';
import UiIcon from '../UI/UiIcon.vue';
import ProductPrice from './ProductPrice.vue';
import type { CartItem } from '../../types/cart';

interface Props {
    item: CartItem;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    'update-quantity': [key: string, quantity: number];
    remove: [key: string];
}>();

function decrement(): void {
    if (props.item.quantity > 1) {
        emit('update-quantity', props.item.id, props.item.quantity - 1);
    }
}

function increment(): void {
    if (props.item.quantity < props.item.stock) {
        emit('update-quantity', props.item.id, props.item.quantity + 1);
    }
}
</script>

<template>
    <article class="cart-item">
        <img
            v-if="item.product.image"
            class="cart-item__image"
            :src="item.product.image"
            :alt="item.product.title"
        >

        <div v-else class="cart-item__image cart-item__image--placeholder" />

        <div class="cart-item__body">
            <p v-if="item.product.brand" class="cart-item__brand">{{ item.product.brand }}</p>
            <p class="cart-item__title">{{ item.product.title }}</p>
            <p v-if="item.variant?.label" class="cart-item__variant">{{ item.variant.label }}</p>

            <div class="cart-item__row">
                <div class="cart-item__quantity">
                    <UiButton
                        variant="icon"
                        aria-label="Decrease quantity"
                        :disabled="item.quantity <= 1"
                        @click="decrement"
                    >
                        <UiIcon name="minus" :size="16" />
                    </UiButton>

                    <span class="cart-item__quantity-value">{{ item.quantity }}</span>

                    <UiButton
                        variant="icon"
                        aria-label="Increase quantity"
                        :disabled="item.quantity >= item.stock"
                        @click="increment"
                    >
                        <UiIcon name="plus" :size="16" />
                    </UiButton>
                </div>

                <ProductPrice
                    :price="item.unitPrice"
                    :old-price="item.oldPrice ?? undefined"
                />
            </div>
        </div>

        <UiButton
            variant="icon"
            class="cart-item__remove"
            aria-label="Remove item"
            @click="emit('remove', item.id)"
        >
            <UiIcon name="close" :size="16" />
        </UiButton>
    </article>
</template>

<style scoped>
.cart-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #ededeb;
}

.cart-item__image {
    width: 5rem;
    height: 5rem;
    flex-shrink: 0;
    border-radius: 0.5rem;
    object-fit: cover;
    background-color: #f1f1ef;
}

.cart-item__image--placeholder {
    background-color: #e3e3e0;
}

.cart-item__body {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.cart-item__brand {
    font-size: 0.75rem;
    color: #706f6c;
}

.cart-item__title {
    font-size: 0.9375rem;
    font-weight: 500;
    color: #1b1b18;
}

.cart-item__variant {
    font-size: 0.8125rem;
    color: #706f6c;
}

.cart-item__row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.5rem;
}

.cart-item__quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cart-item__quantity-value {
    min-width: 1.5rem;
    text-align: center;
    font-size: 0.9375rem;
}

.cart-item__remove {
    align-self: flex-start;
}
</style>
