<script setup lang="ts">
export type BadgeVariant = 'new' | 'sale' | 'hit' | 'bonus' | 'custom' | 'flash' | 'exclusive' | 'gift';

interface Props {
    variant?: BadgeVariant;
    /** Square, icon-only tag (e.g. the flash-deal badge) instead of a text label. */
    iconOnly?: boolean;
}

withDefaults(defineProps<Props>(), {
    variant: 'custom',
    iconOnly: false,
});
</script>

<template>
    <span
        class="ui-badge"
        :class="[`ui-badge--${variant}`, { 'ui-badge--icon-only': iconOnly }]"
    >
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

.ui-badge--flash {
    background-color: var(--color-badge-flash);
    color: var(--color-canvas);
}

.ui-badge--exclusive {
    background-color: var(--color-badge-exclusive);
    color: var(--color-canvas);
}

.ui-badge--gift {
    background-color: var(--color-status-new);
    color: var(--color-ink);
}

.ui-badge--icon-only {
    padding: 0.25rem;
    line-height: 1;
}
</style>
