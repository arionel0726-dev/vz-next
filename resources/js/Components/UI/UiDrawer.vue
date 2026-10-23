<script setup lang="ts">
import { watch } from 'vue';

export type DrawerPosition = 'left' | 'right' | 'bottom';

interface Props {
    modelValue: boolean;
    position?: DrawerPosition;
    closeOnOverlay?: boolean;
    ariaLabel?: string;
}

const props = withDefaults(defineProps<Props>(), {
    position: 'right',
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
        <Transition :name="`ui-drawer-slide-${position}`">
            <div
                v-if="modelValue"
                class="ui-drawer"
                :class="`ui-drawer--${position}`"
                @keydown="handleKeydown"
            >
                <div class="ui-drawer__overlay" @click="handleOverlayClick" />

                <aside
                    class="ui-drawer__panel"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="ariaLabel"
                    tabindex="-1"
                >
                    <header v-if="$slots.header" class="ui-drawer__header">
                        <slot name="header" />

                        <button
                            type="button"
                            class="ui-drawer__close"
                            aria-label="Close"
                            @click="close"
                        >
                            <slot name="close-icon">×</slot>
                        </button>
                    </header>

                    <div class="ui-drawer__body">
                        <slot />
                    </div>

                    <footer v-if="$slots.footer" class="ui-drawer__footer">
                        <slot name="footer" />
                    </footer>
                </aside>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.ui-drawer {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
}

.ui-drawer__overlay {
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.45);
}

.ui-drawer__panel {
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    overflow-y: auto;
}

.ui-drawer--left .ui-drawer__panel,
.ui-drawer--right .ui-drawer__panel {
    height: 100%;
    width: min(26rem, 100%);
    padding: 1.25rem;
}

.ui-drawer--left {
    justify-content: flex-start;
}

.ui-drawer--right {
    justify-content: flex-end;
}

.ui-drawer--bottom {
    align-items: flex-end;
}

.ui-drawer--bottom .ui-drawer__panel {
    width: 100%;
    max-height: min(85vh, 100%);
    border-radius: 1rem 1rem 0 0;
    padding: 1.25rem;
}

.ui-drawer__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.ui-drawer__close {
    border: none;
    background: transparent;
    font-size: 1.25rem;
    line-height: 1;
    cursor: pointer;
    color: #1b1b18;
}

.ui-drawer__body {
    flex: 1;
}

.ui-drawer__footer {
    margin-top: 1.25rem;
}

.ui-drawer-slide-left-enter-active,
.ui-drawer-slide-left-leave-active,
.ui-drawer-slide-right-enter-active,
.ui-drawer-slide-right-leave-active,
.ui-drawer-slide-bottom-enter-active,
.ui-drawer-slide-bottom-leave-active {
    transition: opacity 0.2s ease;
}

.ui-drawer-slide-left-enter-active .ui-drawer__panel,
.ui-drawer-slide-left-leave-active .ui-drawer__panel {
    transition: transform 0.2s ease;
}

.ui-drawer-slide-right-enter-active .ui-drawer__panel,
.ui-drawer-slide-right-leave-active .ui-drawer__panel {
    transition: transform 0.2s ease;
}

.ui-drawer-slide-bottom-enter-active .ui-drawer__panel,
.ui-drawer-slide-bottom-leave-active .ui-drawer__panel {
    transition: transform 0.2s ease;
}

.ui-drawer-slide-left-enter-from,
.ui-drawer-slide-left-leave-to {
    opacity: 0;
}

.ui-drawer-slide-left-enter-from .ui-drawer__panel,
.ui-drawer-slide-left-leave-to .ui-drawer__panel {
    transform: translateX(-100%);
}

.ui-drawer-slide-right-enter-from,
.ui-drawer-slide-right-leave-to {
    opacity: 0;
}

.ui-drawer-slide-right-enter-from .ui-drawer__panel,
.ui-drawer-slide-right-leave-to .ui-drawer__panel {
    transform: translateX(100%);
}

.ui-drawer-slide-bottom-enter-from,
.ui-drawer-slide-bottom-leave-to {
    opacity: 0;
}

.ui-drawer-slide-bottom-enter-from .ui-drawer__panel,
.ui-drawer-slide-bottom-leave-to .ui-drawer__panel {
    transform: translateY(100%);
}
</style>
