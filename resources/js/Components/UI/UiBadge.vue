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
/*
 * Gold Apple reference: status tags are flat, sharp-cornered labels
 * on the product photo, not soft pastel pills — "new"/"hit" are the
 * reference's own game-color tags; "sale" is the one commercial
 * accent (magenta), reserved for the discount signal only.
 */
.ui-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.5rem;
    border-radius: 0;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1.4;
    white-space: nowrap;
}

.ui-badge--new {
    background-color: var(--color-status-new);
    color: var(--color-ink);
}

.ui-badge--sale {
    background-color: var(--color-accent-magenta);
    color: var(--color-canvas);
}

.ui-badge--hit {
    background-color: var(--color-status-hit);
    color: var(--color-ink);
}

.ui-badge--bonus {
    background-color: var(--color-surface-muted);
    color: var(--color-ink);
}

.ui-badge--custom {
    background-color: var(--color-ink);
    color: var(--color-canvas);
}
</style>
