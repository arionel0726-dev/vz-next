<script setup lang="ts">
import type { ProductVariantDetail } from '../../types/product';

interface Props {
    variants: ProductVariantDetail[];
    modelValue: number | string | null;
}

defineProps<Props>();

const emit = defineEmits<{
    'update:modelValue': [value: number | string];
}>();
</script>

<template>
    <div class="product-variant-selector">
        <p class="product-variant-selector__label">Options</p>

        <div class="product-variant-selector__options">
            <button
                v-for="variant in variants"
                :key="variant.id"
                type="button"
                class="product-variant-selector__option"
                :class="{
                    'is-selected': variant.id === modelValue,
                    'is-disabled': !variant.inStock,
                }"
                :disabled="!variant.inStock"
                :aria-pressed="variant.id === modelValue"
                @click="emit('update:modelValue', variant.id)"
            >
                <span
                    v-if="variant.colorHex && variant.colorHex.length"
                    class="product-variant-selector__swatch"
                    :style="{ backgroundColor: variant.colorHex[0] }"
                    aria-hidden="true"
                />

                <span class="product-variant-selector__label-text">
                    {{ variant.label ?? variant.volume ?? `Variant ${variant.id}` }}
                </span>
            </button>
        </div>
    </div>
</template>

<style scoped>
.product-variant-selector {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-variant-selector__label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: #1b1b18;
}

.product-variant-selector__options {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.product-variant-selector__option {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.875rem;
    border-radius: 0.5rem;
    border: 1px solid #d9d9d6;
    background-color: #fff;
    font-size: 0.875rem;
    color: #1b1b18;
    cursor: pointer;
}

.product-variant-selector__option.is-selected {
    border-color: #1b1b18;
    background-color: #1b1b18;
    color: #fff;
}

.product-variant-selector__option.is-disabled {
    cursor: not-allowed;
    opacity: 0.5;
    text-decoration: line-through;
}

.product-variant-selector__swatch {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border-radius: 999px;
    border: 1px solid rgba(0, 0, 0, 0.15);
}
</style>
