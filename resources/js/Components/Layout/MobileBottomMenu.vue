<script setup lang="ts">
import UiIcon from '../UI/UiIcon.vue';
import UiBadge from '../UI/UiBadge.vue';
import type { IconName } from '../UI/UiIcon.vue';

interface Props {
    homeUrl?: string;
    cartCount?: number;
    wishlistCount?: number;
}

withDefaults(defineProps<Props>(), {
    homeUrl: '/',
    cartCount: 0,
    wishlistCount: 0,
});

const emit = defineEmits<{
    'open-search': [];
    'open-cart': [];
    'open-profile': [];
    'open-menu': [];
}>();

interface BottomMenuAction {
    key: 'menu' | 'search' | 'cart' | 'profile';
    label: string;
    icon: IconName;
}

const actions: BottomMenuAction[] = [
    { key: 'menu', label: 'Menu', icon: 'menu' },
    { key: 'search', label: 'Search', icon: 'search' },
    { key: 'cart', label: 'Cart', icon: 'cart' },
    { key: 'profile', label: 'Profile', icon: 'user' },
];

function handleAction(key: BottomMenuAction['key']): void {
    if (key === 'search') {
        emit('open-search');
    } else if (key === 'cart') {
        emit('open-cart');
    } else if (key === 'profile') {
        emit('open-profile');
    } else {
        emit('open-menu');
    }
}
</script>

<template>
    <nav class="mobile-bottom-menu" aria-label="Primary">
        <a :href="homeUrl" class="mobile-bottom-menu__item">
            <UiIcon name="home" />
            <span class="mobile-bottom-menu__label">Home</span>
        </a>

        <button
            v-for="action in actions"
            :key="action.key"
            type="button"
            class="mobile-bottom-menu__item"
            @click="handleAction(action.key)"
        >
            <span class="mobile-bottom-menu__icon">
                <UiIcon :name="action.icon" />

                <span
                    v-if="action.key === 'cart' && cartCount > 0"
                    class="mobile-bottom-menu__count"
                >
                    <UiBadge variant="custom">{{ cartCount }}</UiBadge>
                </span>

                <span
                    v-else-if="action.key === 'profile' && wishlistCount > 0"
                    class="mobile-bottom-menu__count"
                >
                    <UiBadge variant="custom">{{ wishlistCount }}</UiBadge>
                </span>
            </span>

            <span class="mobile-bottom-menu__label">{{ action.label }}</span>
        </button>
    </nav>
</template>

<style scoped>
.mobile-bottom-menu {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-around;
    background-color: #fff;
    border-top: 1px solid #ededeb;
    padding: 0.375rem 0.5rem calc(0.375rem + env(safe-area-inset-bottom));
}

.mobile-bottom-menu__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.125rem;
    border: none;
    background: transparent;
    color: #1b1b18;
    text-decoration: none;
    font-size: 0.6875rem;
    padding: 0.25rem 0.5rem;
    cursor: pointer;
}

.mobile-bottom-menu__icon {
    position: relative;
    display: inline-flex;
}

.mobile-bottom-menu__count {
    position: absolute;
    top: -0.375rem;
    right: -0.5rem;
}

.mobile-bottom-menu__label {
    line-height: 1;
}

@media (min-width: 64rem) {
    .mobile-bottom-menu {
        display: none;
    }
}
</style>
