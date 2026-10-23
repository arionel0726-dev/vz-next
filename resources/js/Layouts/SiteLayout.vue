<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import SiteHeader from '../Components/Layout/SiteHeader.vue';
import SiteFooter from '../Components/Layout/SiteFooter.vue';
import MobileBottomMenu from '../Components/Layout/MobileBottomMenu.vue';
import type { NavItem, FooterColumn } from '../types/nav';

interface Props {
    navItems?: NavItem[];
    footerColumns?: FooterColumn[];
    socialLinks?: NavItem[];
    announcement?: string;
    footerDescription?: string;
    copyright?: string;
    logoUrl?: string;
    homeUrl?: string;
    wishlistCount?: number;
}

withDefaults(defineProps<Props>(), {
    navItems: () => [],
    footerColumns: () => [],
    socialLinks: () => [],
    announcement: undefined,
    footerDescription: undefined,
    copyright: undefined,
    logoUrl: undefined,
    homeUrl: '/',
    wishlistCount: 0,
});

/*
 * The cart item count and the authenticated user are shared on every
 * Inertia response (see HandleInertiaRequests::share), so both stay
 * correct across pages without each page having to fetch or pass
 * them.
 */
const cartCount = computed(() => (usePage().props.cartCount as number | undefined) ?? 0);

interface SharedAuthUser {
    id: number;
    name: string;
}

const isAuthenticated = computed(() => {
    const auth = usePage().props.auth as { user: SharedAuthUser | null } | undefined;

    return !!auth?.user;
});

const emit = defineEmits<{
    'open-search': [];
    'open-menu': [];
}>();
</script>

<template>
    <div class="site-layout">
        <SiteHeader
            :logo-url="logoUrl"
            :home-url="homeUrl"
            :nav-items="navItems"
            :announcement="announcement"
            :cart-count="cartCount"
            :wishlist-count="wishlistCount"
            :is-authenticated="isAuthenticated"
            @open-search="emit('open-search')"
            @open-menu="emit('open-menu')"
        />

        <main class="site-layout__content">
            <slot />
        </main>

        <SiteFooter
            :home-url="homeUrl"
            :logo-url="logoUrl"
            :description="footerDescription"
            :columns="footerColumns"
            :social-links="socialLinks"
            :copyright="copyright"
        />

        <MobileBottomMenu
            :home-url="homeUrl"
            :cart-count="cartCount"
            :wishlist-count="wishlistCount"
            :is-authenticated="isAuthenticated"
            @open-search="emit('open-search')"
            @open-menu="emit('open-menu')"
        />
    </div>
</template>

<style scoped>
.site-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.site-layout__content {
    flex: 1;
    padding-bottom: 4.5rem;
}

@media (min-width: 64rem) {
    .site-layout__content {
        padding-bottom: 0;
    }
}
</style>
