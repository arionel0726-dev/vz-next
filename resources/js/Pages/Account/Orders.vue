<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiIcon from '../../Components/UI/UiIcon.vue';
import type { NavItem, FooterColumn } from '../../types/nav';

const navItems: NavItem[] = [
    { label: 'Catalog', url: '/catalog' },
    { label: 'Cart', url: '/cart' },
];

const footerColumns: FooterColumn[] = [
    {
        title: 'Company',
        links: [{ label: 'About', url: '#' }],
    },
];
</script>

<template>
    <SiteLayout :nav-items="navItems" :footer-columns="footerColumns">
        <div class="account-page">
            <h1 class="account-page__title">My account</h1>

            <nav class="account-page__nav">
                <Link href="/account" class="account-page__nav-link">
                    Account details
                </Link>

                <Link href="/account/orders" class="account-page__nav-link is-active">
                    Order history
                </Link>
            </nav>

            <div class="account-page__empty">
                <UiIcon name="cart" :size="36" />
                <h2 class="account-page__empty-title">No orders yet</h2>
                <p class="account-page__empty-text">
                    This is a placeholder — order history will appear here once checkout exists.
                </p>
            </div>
        </div>
    </SiteLayout>
</template>

<style scoped>
.account-page {
    max-width: 28rem;
    margin: 0 auto;
    padding: 2rem 1.5rem 3rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.account-page__title {
    font-size: 1.5rem;
    font-weight: 600;
}

.account-page__nav {
    display: flex;
    gap: 1rem;
    border-bottom: 1px solid #ededeb;
    padding-bottom: 0.75rem;
}

.account-page__nav-link {
    font-size: 0.875rem;
    color: #706f6c;
}

.account-page__nav-link.is-active {
    color: #1b1b18;
    font-weight: 500;
}

.account-page__empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 3rem 1rem;
    text-align: center;
    color: #706f6c;
}

.account-page__empty-title {
    font-size: 1.0625rem;
    font-weight: 600;
    color: #1b1b18;
}

.account-page__empty-text {
    font-size: 0.875rem;
    max-width: 20rem;
}
</style>
