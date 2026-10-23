<script setup lang="ts">
export type IconName =
    | 'close'
    | 'chevron-down'
    | 'chevron-left'
    | 'chevron-right'
    | 'check'
    | 'plus'
    | 'minus'
    | 'search'
    | 'cart'
    | 'heart'
    | 'user'
    | 'menu'
    | 'home';

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

            <template v-else-if="name === 'search'">
                <circle
                    cx="10.5"
                    cy="10.5"
                    r="6.5"
                    stroke="currentColor"
                    stroke-width="1.6"
                />

                <path
                    d="M15.5 15.5L20 20"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"
                />
            </template>

            <template v-else-if="name === 'cart'">
                <path
                    d="M6 8H18L19 21H5L6 8Z"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linejoin="round"
                />

                <path
                    d="M9 8V6.5C9 4.57 10.34 3 12 3C13.66 3 15 4.57 15 6.5V8"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                />
            </template>

            <path
                v-else-if="name === 'heart'"
                d="M20.84 4.61C19.81 3.58 18.41 3 16.94 3C15.48 3 14.08 3.58 13.04 4.61L12 5.65L10.96 4.61C9.93 3.58 8.53 3 7.07 3C5.61 3 4.21 3.58 3.18 4.61C2.15 5.64 1.57 7.04 1.57 8.5C1.57 9.96 2.15 11.36 3.18 12.39L12 21.21L20.84 12.39C21.86 11.36 22.45 9.96 22.45 8.5C22.45 7.04 21.86 5.64 20.84 4.61Z"
                stroke="currentColor"
                stroke-width="1.45"
                stroke-linecap="round"
                stroke-linejoin="round"
            />

            <template v-else-if="name === 'user'">
                <circle
                    cx="12"
                    cy="7"
                    r="4.2"
                    stroke="currentColor"
                    stroke-width="1.6"
                />

                <path
                    d="M4 21C4.5 16.7 7.3 14.3 12 14.3C16.7 14.3 19.5 16.7 20 21"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"
                />
            </template>

            <template v-else-if="name === 'menu'">
                <path
                    d="M4 6H20M4 12H20M4 18H20"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"
                />
            </template>

            <path
                v-else-if="name === 'home'"
                d="M4 11L12 4L20 11V20H4V11Z"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linejoin="round"
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
