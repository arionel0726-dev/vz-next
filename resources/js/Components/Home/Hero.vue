<script setup lang="ts">
import { ref, computed } from 'vue';
import UiButton from '../UI/UiButton.vue';
import UiIcon from '../UI/UiIcon.vue';

export interface HeroSlide {
    id: number | string;
    title: string;
    subtitle: string;
    ctaLabel: string;
    ctaUrl: string;
    /** 1-3 product images, shown side by side on the left. */
    images: string[];
    /** CSS background (color or gradient) behind the slide. */
    background: string;
}

interface Props {
    slides: HeroSlide[];
}

const props = defineProps<Props>();

const activeIndex = ref(0);

const activeSlide = computed(() => props.slides[activeIndex.value]);

function select(index: number): void {
    activeIndex.value = index;
}
</script>

<template>
    <section v-if="activeSlide" class="hero">
        <div class="hero__slide" :style="{ background: activeSlide.background }">
            <div class="hero__images">
                <img
                    v-for="(image, index) in activeSlide.images"
                    :key="index"
                    :src="image"
                    class="hero__image"
                    alt=""
                >
            </div>

            <div class="hero__content">
                <h1 class="hero__title">{{ activeSlide.title }}</h1>
                <p class="hero__subtitle">{{ activeSlide.subtitle }}</p>

                <UiButton variant="primary" :href="activeSlide.ctaUrl" class="hero__cta">
                    {{ activeSlide.ctaLabel }}
                    <template #icon-right>
                        <UiIcon name="chevron-right" :size="16" />
                    </template>
                </UiButton>
            </div>
        </div>

        <div v-if="slides.length > 1" class="hero__dots">
            <button
                v-for="(slide, index) in slides"
                :key="slide.id"
                type="button"
                class="hero__dot"
                :class="{ 'is-active': index === activeIndex }"
                :aria-label="`Slide ${index + 1}`"
                :aria-current="index === activeIndex"
                @click="select(index)"
            />
        </div>
    </section>
</template>

<style scoped>
.hero {
    display: flex;
    flex-direction: column;
}

.hero__slide {
    display: grid;
    grid-template-columns: 1fr;
    align-items: center;
    gap: 2rem;
    min-height: 22rem;
    padding: 2.5rem 1.5rem;
}

.hero__images {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    gap: 1rem;
    order: 2;
}

.hero__image {
    max-height: 16rem;
    width: auto;
    object-fit: contain;
}

.hero__content {
    order: 1;
    text-align: center;
}

.hero__title {
    font-size: 2.25rem;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--color-ink);
}

.hero__subtitle {
    margin-top: 0.5rem;
    font-size: 1rem;
    color: var(--color-muted);
}

.hero__cta {
    margin-top: 1.5rem;
}

.hero__dots {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 0;
    background-color: var(--color-surface-muted);
}

.hero__dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    border: none;
    padding: 0;
    background-color: rgba(17, 17, 17, 0.25);
    cursor: pointer;
    transition: background-color 0.15s ease, transform 0.15s ease;
}

.hero__dot.is-active {
    background-color: var(--color-ink);
    transform: scale(1.25);
}

@media (min-width: 64rem) {
    .hero__slide {
        grid-template-columns: 1.1fr 0.9fr;
        min-height: 34rem;
        padding: 0 3rem;
    }

    .hero__images {
        order: 1;
        justify-content: flex-end;
    }

    .hero__image {
        max-height: 26rem;
    }

    .hero__content {
        order: 2;
        text-align: left;
    }

    .hero__title {
        font-size: 3.5rem;
    }

    .hero__subtitle {
        font-size: 1.125rem;
    }
}
</style>
