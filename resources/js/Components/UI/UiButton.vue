<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

export type ButtonVariant =
    | 'primary'
    | 'secondary'
    | 'ghost'
    | 'outline'
    | 'danger'
    | 'icon';

export type ButtonType = 'button' | 'submit' | 'reset';

interface Props {
    variant?: ButtonVariant;
    type?: ButtonType;
    disabled?: boolean;
    loading?: boolean;
    ariaLabel?: string;
    /** When set, renders as an Inertia Link instead of a <button>. */
    href?: string;
}

withDefaults(defineProps<Props>(), {
    variant: 'primary',
    type: 'button',
    disabled: false,
    loading: false,
    ariaLabel: undefined,
    href: undefined,
});

const emit = defineEmits<{
    click: [event: MouseEvent];
}>();

function handleClick(event: MouseEvent): void {
    emit('click', event);
}
</script>

<template>
    <Link
        v-if="href"
        class="ui-button"
        :class="[`ui-button--${variant}`, { 'is-loading': loading }]"
        :href="href"
        :aria-label="ariaLabel"
        @click="handleClick"
    >
        <slot name="icon-left" />
        <span v-if="$slots.default" class="ui-button__label">
            <slot />
        </span>
        <slot name="icon-right" />
    </Link>

    <button
        v-else
        class="ui-button"
        :class="[`ui-button--${variant}`, { 'is-loading': loading }]"
        :type="type"
        :disabled="disabled || loading"
        :aria-label="ariaLabel"
        @click="handleClick"
    >
        <slot name="icon-left" />
        <span v-if="$slots.default" class="ui-button__label">
            <slot />
        </span>
        <slot name="icon-right" />
    </button>
</template>

<style scoped>
/*
 * Gold Apple reference: commercial/clickable geometry is always a
 * sharp 0px corner (never a "soft" mid radius) — only category/swatch
 * circles get border-radius: 50%, never a button. The primary CTA is
 * always pure black with uppercase, letter-spaced text; color is
 * reserved for badges, never for button fills.
 */
.ui-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.8125rem 0.9375rem;
    border-radius: 0;
    border: 1px solid transparent;
    font-size: 0.6875rem;
    font-weight: 500;
    line-height: 1.2;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease, opacity 0.15s ease;
}

.ui-button:disabled {
    cursor: not-allowed;
    opacity: 0.55;
}

.ui-button.is-loading {
    cursor: progress;
}

.ui-button--primary {
    background-color: var(--color-ink);
    border-color: var(--color-ink);
    color: var(--color-canvas);
}

.ui-button--primary:not(:disabled):hover {
    background-color: var(--color-ink-soft);
}

.ui-button--secondary {
    background-color: var(--color-surface-muted);
    border-color: var(--color-surface-muted);
    color: var(--color-ink);
}

.ui-button--secondary:not(:disabled):hover {
    background-color: var(--color-border);
}

.ui-button--ghost {
    background-color: transparent;
    border-color: transparent;
    color: var(--color-ink);
}

.ui-button--ghost:not(:disabled):hover {
    background-color: var(--color-surface-muted);
}

.ui-button--outline {
    background-color: transparent;
    border-color: var(--color-ink);
    color: var(--color-ink);
}

.ui-button--outline:not(:disabled):hover {
    background-color: var(--color-ink);
    color: var(--color-canvas);
}

.ui-button--danger {
    background-color: var(--color-danger);
    border-color: var(--color-danger);
    color: var(--color-canvas);
}

.ui-button--danger:not(:disabled):hover {
    background-color: #9b2626;
}

/* Square tap target, not a circle — the reference reserves circles
   for category/swatch icons, not interactive buttons. */
.ui-button--icon {
    padding: 0;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0;
    background-color: transparent;
    border-color: transparent;
    color: var(--color-ink);
    text-transform: none;
    letter-spacing: normal;
}

.ui-button--icon:not(:disabled):hover {
    background-color: var(--color-surface-muted);
}

.ui-button__label {
    display: inline-flex;
    align-items: center;
}
</style>
