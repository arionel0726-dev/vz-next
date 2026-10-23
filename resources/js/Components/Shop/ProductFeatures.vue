<script setup lang="ts">
import type { ProductFeatureGroup } from '../../types/product';

interface Props {
    features: ProductFeatureGroup[];
}

defineProps<Props>();
</script>

<template>
    <section v-if="features.length" class="product-features">
        <h2 class="product-features__title">Characteristics</h2>

        <dl class="product-features__list">
            <template v-for="group in features" :key="group.feature">
                <dt class="product-features__term">{{ group.feature }}</dt>
                <dd class="product-features__value">{{ group.values.join(', ') }}</dd>
            </template>
        </dl>
    </section>
</template>

<style scoped>
.product-features {
    margin-top: 2rem;
}

.product-features__title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.product-features__list {
    display: grid;
    grid-template-columns: max-content 1fr;
    gap: 0.5rem 1rem;
    margin: 0;
}

.product-features__term {
    font-size: 0.875rem;
    color: #706f6c;
}

.product-features__value {
    font-size: 0.875rem;
    color: #1b1b18;
    margin: 0;
}
</style>
