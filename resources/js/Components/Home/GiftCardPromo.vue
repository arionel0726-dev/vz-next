<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';

interface Props {
    title: string;
    subtitle: string;
    ctaLabel: string;
    ctaUrl: string;
}

defineProps<Props>();
</script>

<template>
    <section class="gift-card-promo">
        <div class="gift-card-promo__container">
            <!-- No artwork yet -- text-only until a real gift-card
                 image/graphic exists. -->
            <h2 class="gift-card-promo__title">{{ title }}</h2>
            <p class="gift-card-promo__subtitle">{{ subtitle }}</p>

            <UiButton variant="primary" :href="ctaUrl" class="gift-card-promo__cta">
                {{ ctaLabel }}
            </UiButton>
        </div>
    </section>
</template>

<style scoped>
.gift-card-promo {
    background-color: var(--color-surface-muted);
}

.gift-card-promo__container {
    max-width: 90rem;
    margin: 0 auto;
    padding: 3rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.gift-card-promo__title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-ink);
}

.gift-card-promo__subtitle {
    font-size: 1rem;
    color: var(--color-muted);
    max-width: 32rem;
}

.gift-card-promo__cta {
    margin-top: 0.75rem;
    align-self: flex-start;
}

@media (min-width: 64rem) {
    .gift-card-promo__title {
        font-size: 2.25rem;
    }
}
</style>
