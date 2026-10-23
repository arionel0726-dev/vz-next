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
.ui-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    border-radius: 0.5rem;
    border: 1px solid transparent;
    font-size: 0.9375rem;
    font-weight: 500;
    line-height: 1.2;
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
    background-color: #1b1b18;
    border-color: #1b1b18;
    color: #fff;
}

.ui-button--primary:not(:disabled):hover {
    background-color: #000;
}

.ui-button--secondary {
    background-color: #f1f1ef;
    border-color: #f1f1ef;
    color: #1b1b18;
}

.ui-button--secondary:not(:disabled):hover {
    background-color: #e3e3e0;
}

.ui-button--ghost {
    background-color: transparent;
    border-color: transparent;
    color: #1b1b18;
}

.ui-button--ghost:not(:disabled):hover {
    background-color: #f1f1ef;
}

.ui-button--outline {
    background-color: transparent;
    border-color: #1b1b18;
    color: #1b1b18;
}

.ui-button--outline:not(:disabled):hover {
    background-color: #1b1b18;
    color: #fff;
}

.ui-button--danger {
    background-color: #c53030;
    border-color: #c53030;
    color: #fff;
}

.ui-button--danger:not(:disabled):hover {
    background-color: #9b2626;
}

.ui-button--icon {
    padding: 0.5rem;
    border-radius: 999px;
    background-color: transparent;
    border-color: transparent;
    color: #1b1b18;
}

.ui-button--icon:not(:disabled):hover {
    background-color: #f1f1ef;
}

.ui-button__label {
    display: inline-flex;
    align-items: center;
}
</style>
