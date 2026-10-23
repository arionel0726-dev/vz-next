<script setup lang="ts">
export type BadgeVariant = 'new' | 'sale' | 'hit' | 'bonus' | 'custom';

interface Props {
    variant?: BadgeVariant;
}

withDefaults(defineProps<Props>(), {
    variant: 'custom',
});
</script>

<template>
    <span class="ui-badge" :class="`ui-badge--${variant}`">
        <slot />
    </span>
</template>

<style scoped>
.ui-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.5rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1.4;
    white-space: nowrap;
}

.ui-badge--new {
    background-color: #e3f3ea;
    color: #1f7a4d;
}

.ui-badge--sale {
    background-color: #fde8e8;
    color: #c53030;
}

.ui-badge--hit {
    background-color: #fef3df;
    color: #a3690b;
}

.ui-badge--bonus {
    background-color: #e8ecfd;
    color: #38469e;
}

.ui-badge--custom {
    background-color: #f1f1ef;
    color: #1b1b18;
}
</style>
