<script setup lang="ts">
import { watch } from 'vue';

interface Props {
    modelValue: boolean;
    closeOnOverlay?: boolean;
    ariaLabel?: string;
}

const props = withDefaults(defineProps<Props>(), {
    closeOnOverlay: true,
    ariaLabel: undefined,
});

const emit = defineEmits<{
    'update:modelValue': [value: boolean];
    close: [];
}>();

function close(): void {
    emit('update:modelValue', false);
    emit('close');
}

function handleOverlayClick(): void {
    if (props.closeOnOverlay) {
        close();
    }
}

function handleKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape' && props.modelValue) {
        close();
    }
}

watch(
    () => props.modelValue,
    (isOpen) => {
        if (typeof document === 'undefined') {
            return;
        }

        document.documentElement.style.overflow = isOpen ? 'hidden' : '';
    },
);
</script>

<template>
    <Teleport to="body">
        <Transition name="ui-modal-fade">
            <div
                v-if="modelValue"
                class="ui-modal"
                @keydown="handleKeydown"
            >
                <div class="ui-modal__overlay" @click="handleOverlayClick" />

                <div
                    class="ui-modal__panel"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="ariaLabel"
                    tabindex="-1"
                >
                    <button
                        type="button"
                        class="ui-modal__close"
                        aria-label="Close"
                        @click="close"
                    >
                        <slot name="close-icon">×</slot>
                    </button>

                    <header v-if="$slots.header" class="ui-modal__header">
                        <slot name="header" />
                    </header>

                    <div class="ui-modal__body">
                        <slot />
                    </div>

                    <footer v-if="$slots.footer" class="ui-modal__footer">
                        <slot name="footer" />
                    </footer>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.ui-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ui-modal__overlay {
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.45);
}

.ui-modal__panel {
    position: relative;
    z-index: 1;
    max-width: 32rem;
    width: calc(100% - 2rem);
    max-height: calc(100% - 2rem);
    overflow-y: auto;
    background-color: #fff;
    border-radius: 0.75rem;
    padding: 1.5rem;
}

.ui-modal__close {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    border: none;
    background: transparent;
    font-size: 1.25rem;
    line-height: 1;
    cursor: pointer;
    color: #1b1b18;
}

.ui-modal__header {
    margin-bottom: 1rem;
}

.ui-modal__footer {
    margin-top: 1.25rem;
}

.ui-modal-fade-enter-active,
.ui-modal-fade-leave-active {
    transition: opacity 0.15s ease;
}

.ui-modal-fade-enter-from,
.ui-modal-fade-leave-to {
    opacity: 0;
}
</style>
