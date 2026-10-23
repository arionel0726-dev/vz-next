<script setup lang="ts">
import { ref, watch } from 'vue';
import UiIcon from '../UI/UiIcon.vue';

interface Props {
    images: string[];
    alt?: string;
}

const props = withDefaults(defineProps<Props>(), {
    alt: '',
});

const activeIndex = ref(0);

watch(
    () => props.images,
    () => {
        activeIndex.value = 0;
    },
);

function select(index: number): void {
    activeIndex.value = index;
}

function previous(): void {
    activeIndex.value = activeIndex.value === 0
        ? props.images.length - 1
        : activeIndex.value - 1;
}

function next(): void {
    activeIndex.value = activeIndex.value === props.images.length - 1
        ? 0
        : activeIndex.value + 1;
}
</script>

<template>
    <div class="product-gallery">
        <div class="product-gallery__viewport">
            <button
                v-if="images.length > 1"
                type="button"
                class="product-gallery__arrow product-gallery__arrow--prev"
                aria-label="Previous image"
                @click="previous"
            >
                <UiIcon name="chevron-left" />
            </button>

            <img
                v-if="images.length"
                class="product-gallery__image"
                :src="images[activeIndex]"
                :alt="alt"
            >

            <div v-else class="product-gallery__placeholder" />

            <button
                v-if="images.length > 1"
                type="button"
                class="product-gallery__arrow product-gallery__arrow--next"
                aria-label="Next image"
                @click="next"
            >
                <UiIcon name="chevron-right" />
            </button>
        </div>

        <div v-if="images.length > 1" class="product-gallery__thumbs">
            <button
                v-for="(image, index) in images"
                :key="image + index"
                type="button"
                class="product-gallery__thumb"
                :class="{ 'is-active': index === activeIndex }"
                :aria-current="index === activeIndex"
                @click="select(index)"
            >
                <img :src="image" :alt="alt">
            </button>
        </div>
    </div>
</template>

<style scoped>
.product-gallery {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.product-gallery__viewport {
    position: relative;
    aspect-ratio: 1 / 1;
    border-radius: 0.75rem;
    overflow: hidden;
    background-color: #f1f1ef;
}

.product-gallery__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-gallery__placeholder {
    width: 100%;
    height: 100%;
    background-color: #e3e3e0;
}

.product-gallery__arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 999px;
    border: none;
    background-color: #fff;
    color: #1b1b18;
    cursor: pointer;
}

.product-gallery__arrow--prev {
    left: 0.5rem;
}

.product-gallery__arrow--next {
    right: 0.5rem;
}

.product-gallery__thumbs {
    display: flex;
    gap: 0.5rem;
    overflow-x: auto;
}

.product-gallery__thumb {
    flex-shrink: 0;
    width: 4rem;
    height: 4rem;
    border-radius: 0.5rem;
    overflow: hidden;
    border: 1px solid transparent;
    padding: 0;
    cursor: pointer;
    background-color: #f1f1ef;
}

.product-gallery__thumb.is-active {
    border-color: #1b1b18;
}

.product-gallery__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
