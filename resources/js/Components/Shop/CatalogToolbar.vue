<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';
import UiIcon from '../UI/UiIcon.vue';
import type { CatalogViewMode, SortOption } from '../../types/catalog';

interface Props {
    productCount: number;
    viewMode?: CatalogViewMode;
    sortValue?: string;
    sortOptions?: SortOption[];
}

withDefaults(defineProps<Props>(), {
    viewMode: 'grid',
    sortValue: undefined,
    sortOptions: () => [],
});

const emit = defineEmits<{
    'open-filters': [];
    'update:viewMode': [value: CatalogViewMode];
    'update:sortValue': [value: string];
}>();

function handleSortChange(event: Event): void {
    emit('update:sortValue', (event.target as HTMLSelectElement).value);
}
</script>

<template>
    <div class="catalog-toolbar">
        <p class="catalog-toolbar__count">
            {{ productCount }} products
        </p>

        <div class="catalog-toolbar__actions">
            <UiButton variant="outline" @click="emit('open-filters')">
                <template #icon-left>
                    <UiIcon name="menu" :size="16" />
                </template>
                Filters
            </UiButton>

            <select
                v-if="sortOptions.length"
                class="catalog-toolbar__sort"
                :value="sortValue"
                @change="handleSortChange"
            >
                <option
                    v-for="option in sortOptions"
                    :key="option.value"
                    :value="option.value"
                >
                    {{ option.label }}
                </option>
            </select>

            <div class="catalog-toolbar__view">
                <UiButton
                    variant="icon"
                    :class="{ 'is-active': viewMode === 'grid' }"
                    aria-label="Grid view"
                    @click="emit('update:viewMode', 'grid')"
                >
                    <UiIcon name="menu" :size="18" />
                </UiButton>

                <UiButton
                    variant="icon"
                    :class="{ 'is-active': viewMode === 'list' }"
                    aria-label="List view"
                    @click="emit('update:viewMode', 'list')"
                >
                    <UiIcon name="minus" :size="18" />
                </UiButton>
            </div>
        </div>
    </div>
</template>

<style scoped>
.catalog-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.75rem 0;
}

.catalog-toolbar__count {
    font-size: 0.875rem;
    color: #706f6c;
}

.catalog-toolbar__actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.catalog-toolbar__sort {
    border: 1px solid #d9d9d6;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    color: #1b1b18;
    background-color: #fff;
}

.catalog-toolbar__view {
    display: flex;
    gap: 0.25rem;
}

.catalog-toolbar__view .is-active {
    background-color: #f1f1ef;
}
</style>
