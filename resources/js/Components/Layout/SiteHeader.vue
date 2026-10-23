<script setup lang="ts">
import UiButton from '../UI/UiButton.vue';
import UiIcon from '../UI/UiIcon.vue';
import UiBadge from '../UI/UiBadge.vue';
import type { NavItem } from '../../types/nav';

interface Props {
    logoUrl?: string;
    homeUrl?: string;
    navItems?: NavItem[];
    announcement?: string;
    cartCount?: number;
    wishlistCount?: number;
    isAuthenticated?: boolean;
}

withDefaults(defineProps<Props>(), {
    logoUrl: undefined,
    homeUrl: '/',
    navItems: () => [],
    announcement: undefined,
    cartCount: 0,
    wishlistCount: 0,
    isAuthenticated: false,
});

const emit = defineEmits<{
    'open-search': [];
    'open-cart': [];
    'open-profile': [];
    'open-menu': [];
}>();
</script>

<template>
    <header class="site-header">
        <div v-if="announcement" class="site-header__announcement">
            {{ announcement }}
        </div>

        <!-- Desktop variant -->
        <div class="site-header__desktop">
            <div class="site-header__row">
                <a :href="homeUrl" class="site-header__logo">
                    <img v-if="logoUrl" :src="logoUrl" alt="Logo">
                    <span v-else>Vizaje Nica</span>
                </a>

                <nav v-if="navItems.length" class="site-header__nav">
                    <a
                        v-for="item in navItems"
                        :key="item.url"
                        :href="item.url"
                        class="site-header__nav-link"
                    >
                        {{ item.label }}
                    </a>
                </nav>

                <div class="site-header__actions">
                    <UiButton
                        variant="icon"
                        aria-label="Search"
                        @click="emit('open-search')"
                    >
                        <UiIcon name="search" />
                    </UiButton>

                    <UiButton
                        variant="icon"
                        class="site-header__action"
                        aria-label="Wishlist and profile"
                        @click="emit('open-profile')"
                    >
                        <UiIcon :name="isAuthenticated ? 'user' : 'heart'" />
                        <span v-if="wishlistCount > 0" class="site-header__count">
                            <UiBadge variant="custom">{{ wishlistCount }}</UiBadge>
                        </span>
                    </UiButton>

                    <UiButton
                        variant="icon"
                        class="site-header__action"
                        aria-label="Cart"
                        @click="emit('open-cart')"
                    >
                        <UiIcon name="cart" />
                        <span v-if="cartCount > 0" class="site-header__count">
                            <UiBadge variant="custom">{{ cartCount }}</UiBadge>
                        </span>
                    </UiButton>
                </div>
            </div>
        </div>

        <!-- Mobile variant -->
        <div class="site-header__mobile">
            <UiButton
                variant="icon"
                aria-label="Menu"
                @click="emit('open-menu')"
            >
                <UiIcon name="menu" />
            </UiButton>

            <a :href="homeUrl" class="site-header__logo site-header__logo--mobile">
                <img v-if="logoUrl" :src="logoUrl" alt="Logo">
                <span v-else>VN</span>
            </a>

            <UiButton
                variant="icon"
                aria-label="Search"
                @click="emit('open-search')"
            >
                <UiIcon name="search" />
            </UiButton>
        </div>
    </header>
</template>

<style scoped>
.site-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background-color: #fff;
    border-bottom: 1px solid #ededeb;
}

.site-header__announcement {
    text-align: center;
    font-size: 0.8125rem;
    padding: 0.5rem 1rem;
    background-color: #1b1b18;
    color: #fff;
}

.site-header__desktop {
    display: none;
}

.site-header__row {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 0.75rem 1.5rem;
}

.site-header__logo {
    display: inline-flex;
    align-items: center;
    font-weight: 600;
    font-size: 1.125rem;
    color: #1b1b18;
    text-decoration: none;
    flex-shrink: 0;
}

.site-header__logo img {
    height: 2rem;
}

.site-header__nav {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    flex: 1;
}

.site-header__nav-link {
    font-size: 0.9375rem;
    color: #1b1b18;
    text-decoration: none;
}

.site-header__nav-link:hover {
    text-decoration: underline;
}

.site-header__actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: auto;
}

.site-header__action {
    position: relative;
}

.site-header__count {
    position: absolute;
    top: -0.25rem;
    right: -0.25rem;
}

.site-header__mobile {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.625rem 1rem;
}

.site-header__logo--mobile {
    font-size: 1rem;
}

@media (min-width: 64rem) {
    .site-header__desktop {
        display: block;
    }

    .site-header__mobile {
        display: none;
    }
}
</style>
