<script setup lang="ts">
export type IconName =
    | 'close'
    | 'chevron-down'
    | 'chevron-left'
    | 'chevron-right'
    | 'check'
    | 'plus'
    | 'minus';

interface Props {
    name?: IconName;
    size?: number | string;
}

withDefaults(defineProps<Props>(), {
    name: undefined,
    size: 20,
});
</script>

<template>
    <svg
        class="ui-icon"
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        aria-hidden="true"
    >
        <slot>
            <path
                v-if="name === 'close'"
                d="M5 5L19 19M19 5L5 19"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
            />

            <path
                v-else-if="name === 'chevron-down'"
                d="M5 8L12 15L19 8"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <path
                v-else-if="name === 'chevron-left'"
                d="M15 5L8 12L15 19"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <path
                v-else-if="name === 'chevron-right'"
                d="M9 5L16 12L9 19"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <path
                v-else-if="name === 'check'"
                d="M5 12.5L9.5 17L19 7"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <path
                v-else-if="name === 'plus'"
                d="M12 5V19M5 12H19"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
            />

            <path
                v-else-if="name === 'minus'"
                d="M5 12H19"
                stroke="currentColor"
                stroke-width="1.6"
                stroke-linecap="round"
            />
        </slot>
    </svg>
</template>

<style scoped>
.ui-icon {
    display: inline-block;
    flex-shrink: 0;
}
</style>
