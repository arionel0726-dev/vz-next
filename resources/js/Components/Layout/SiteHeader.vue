<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
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
    'open-menu': [];
}>();

function isActiveNavUrl(url: string): boolean {
    return usePage().url === url || usePage().url.startsWith(`${url}/`);
}

/**
 * Visual-only label for now: vz-next has no multi-language routing
 * and no store/city selector backend yet, so this is a static
 * region/locale + city label, not an interactive dropdown -- no
 * click handler, so it never implies functionality that isn't there.
 * Wire it up for real once /ru, /ro routes and a stores feature
 * exist.
 */
const regionLabel = 'MD · RU';
const cityLabel = 'Chișinău';

/*
 * Desktop-only scroll behavior: the announcement strip hides once the
 * page scrolls down, and the logo shrinks once scrolled past the top
 * (legacy header spec, "Main row" / "Announcement strip"). Mobile
 * header isn't touched by this — it has its own, unrelated layout.
 */
const isScrolled = ref(false);
const isScrollingDown = ref(false);
let lastScrollY = 0;

function handleScroll(): void {
    const currentY = window.scrollY;
    isScrolled.value = currentY > 4;
    isScrollingDown.value = currentY > lastScrollY && currentY > 80;
    lastScrollY = currentY;
}

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <header class="site-header" :class="{ 'is-scrolled': isScrolled }">
        <div
            v-if="announcement"
            class="site-header__announcement"
            :class="{ 'is-hidden': isScrollingDown }"
        >
            <div class="site-header__container">
                {{ announcement }}
            </div>
        </div>

        <!-- Desktop variant -->
        <div class="site-header__desktop">
            <div class="site-header__main site-header__container">
                <div class="site-header__locale">
                    <span class="site-header__locale-region">{{ regionLabel }}</span>
                    <span class="site-header__locale-divider" aria-hidden="true" />
                    <span class="site-header__locale-city">
                        <UiIcon name="chevron-down" :size="14" />
                        {{ cityLabel }}
                    </span>
                </div>

                <a :href="homeUrl" class="site-header__logo">
                    <img v-if="logoUrl" :src="logoUrl" alt="Logo">
                    <span v-else>Vizaje Nica</span>
                </a>

                <div class="site-header__actions">
                    <UiButton
                        variant="icon"
                        class="site-header__action"
                        aria-label="Search"
                        @click="emit('open-search')"
                    >
                        <UiIcon name="search" :size="21" />
                    </UiButton>

                    <!-- No wishlist backend yet (see legacy spec: legacy
                         itself only keeps it in a cookie, not a real
                         table) -- the slot is here for layout parity,
                         not wired to anything yet. -->
                    <UiButton
                        variant="icon"
                        class="site-header__action"
                        aria-label="Wishlist"
                    >
                        <UiIcon name="heart" :size="21" />
                        <span v-if="wishlistCount > 0" class="site-header__count">
                            <UiBadge variant="custom">{{ wishlistCount }}</UiBadge>
                        </span>
                    </UiButton>

                    <UiButton
                        variant="icon"
                        class="site-header__action"
                        :aria-label="isAuthenticated ? 'Account' : 'Log in'"
                        :href="isAuthenticated ? '/account' : '/login'"
                    >
                        <UiIcon name="user" :size="21" />
                    </UiButton>

                    <UiButton
                        variant="icon"
                        class="site-header__action"
                        aria-label="Cart"
                        href="/cart"
                    >
                        <UiIcon name="cart" :size="21" />
                        <span v-if="cartCount > 0" class="site-header__count">
                            <UiBadge variant="custom">{{ cartCount }}</UiBadge>
                        </span>
                    </UiButton>
                </div>
            </div>

            <nav v-if="navItems.length" class="site-header__nav">
                <div class="site-header__container site-header__nav-list">
                    <a
                        v-for="item in navItems"
                        :key="item.url"
                        :href="item.url"
                        class="site-header__nav-link"
                        :class="{ 'is-active': isActiveNavUrl(item.url) }"
                    >
                        {{ item.label }}
                    </a>
                </div>
            </nav>
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
/*
 * Sizes/grid/typography follow the legacy header spec exactly where
 * given (container max-width, row heights, action/icon/badge sizes,
 * nav typography). Mega-menu, the announcement rotator, the account
 * dropdown and real multi-language routing are deliberately out of
 * this pass -- see the component comments below for each deferred
 * piece. Mobile header/nav is untouched here.
 */
.site-header {
    position: sticky;
    top: 0;
    z-index: 100;
    background-color: var(--color-canvas);
    border-bottom: 1px solid var(--color-border);
}

.site-header__container {
    width: 100%;
    max-width: 104.375rem; /* 1670px */
    margin: 0 auto;
    padding: 0 1.25rem; /* 20px */
}

.site-header__announcement {
    height: 1.875rem; /* 30px */
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6875rem; /* 11px */
    line-height: 1.4;
    color: #fff;
    /* No asset/screenshot for the legacy gradient+noise texture --
       approximated with a plain blue gradient. */
    background: linear-gradient(135deg, #2f7de0 0%, #1b5fc4 55%, #123f8a 100%);
    transition: height 0.2s ease, opacity 0.2s ease;
}

.site-header__announcement.is-hidden {
    height: 0;
    opacity: 0;
}

.site-header__desktop {
    display: none;
}

.site-header__main {
    display: grid;
    grid-template-columns: minmax(15rem, 1fr) auto minmax(15rem, 1fr); /* 240px */
    align-items: center;
    height: 3.875rem; /* 62px */
    background-color: var(--color-canvas);
}

/* Static region/city label -- see the script comment above
   regionLabel/cityLabel; not an interactive dropdown. */
.site-header__locale {
    justify-self: start;
    display: inline-flex;
    align-items: center;
    gap: 0.625rem; /* 10px */
    width: fit-content;
    font-size: 0.8125rem; /* 13px */
    color: var(--color-ink);
}

.site-header__locale-region {
    font-weight: 500;
}

.site-header__locale-divider {
    width: 1px;
    height: 1rem;
    background-color: var(--color-border);
}

.site-header__locale-city {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--color-muted);
}

.site-header__logo {
    justify-self: center;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 12.875rem; /* 206px */
    height: 3.125rem; /* 50px */
    padding: 0.1875rem 0.5rem; /* 3px 8px */
    text-decoration: none;
    transition: width 0.2s ease;
}

.site-header.is-scrolled .site-header__logo {
    width: 11.5rem; /* 184px */
}

.site-header__logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.site-header__logo span {
    font-weight: 600;
    font-size: 0.9375rem; /* compact mark, not a full wordmark banner */
    letter-spacing: 0.02em;
    color: var(--color-ink);
}

.site-header__actions {
    justify-self: end;
    display: flex;
    align-items: center;
    gap: 0.125rem; /* 2px */
}

.site-header__action {
    position: relative;
}

/* Action buttons/icons are 44x44 / 21x21 per the legacy spec, wider
   than UiButton's default icon-button size -- scoped to this header
   only via :deep(), UiButton itself is unchanged for every other
   caller. */
.site-header__action :deep(.ui-button) {
    width: 2.75rem; /* 44px */
    height: 2.75rem;
}

.site-header__count {
    position: absolute;
    top: -0.0625rem;
    right: -0.0625rem;
}

/*
 * The legacy header's counter badge is a circular capsule, distinct
 * from the flat, sharp-cornered status tags the Gold Apple pass gave
 * UiBadge's "custom" variant elsewhere (product-card badges). A
 * numeric counter over an icon is closer to the "avatar/counter"
 * exception that pass's own rule already allows for circles, so this
 * overrides it locally for the header only.
 */
.site-header__count :deep(.ui-badge) {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 1.0625rem; /* 17px */
    height: 1.0625rem;
    padding: 0 0.1875rem;
    border-radius: 999px;
    border: 2px solid var(--color-canvas);
    background-color: var(--color-ink);
    color: var(--color-canvas);
    font-size: 0.5625rem; /* 9px */
    font-weight: 600;
    line-height: 1;
}

.site-header__nav {
    height: 2.75rem; /* 44px */
    background-color: var(--color-canvas);
    border-bottom: 1px solid #eaeaea;
    display: flex;
    align-items: center;
}

.site-header__nav-list {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: clamp(1.125rem, 2.1vw, 2.25rem); /* clamp(18px, 2.1vw, 36px) */
}

.site-header__nav-link {
    position: relative;
    padding-bottom: 0.1875rem;
    font-size: 0.875rem; /* 14px */
    font-weight: 400;
    color: rgba(17, 17, 17, 0.58);
    text-decoration: none;
    transition: color 0.15s ease, font-size 0.15s ease, font-weight 0.15s ease;
}

.site-header__nav-link:hover,
.site-header__nav-link.is-active {
    font-size: 0.9375rem; /* 15px */
    font-weight: 500;
    color: #111;
}

.site-header__nav-link:hover::after,
.site-header__nav-link.is-active::after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -0.0625rem;
    height: 1px;
    background-color: #111;
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
    .site-header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 950; /* below UiModal/UiDrawer (1000); the legacy
                         value of 999900 would sit above them. */
        border-bottom: none;
    }

    .site-header__desktop {
        display: block;
    }

    .site-header__mobile {
        display: none;
    }
}
</style>
