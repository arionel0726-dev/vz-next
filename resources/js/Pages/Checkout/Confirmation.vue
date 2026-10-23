<script setup lang="ts">
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiBadge from '../../Components/UI/UiBadge.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import type { NavItem, FooterColumn } from '../../types/nav';

interface OrderItem {
    productName: string;
    productSku: string;
    unitPrice: number;
    quantity: number;
    subtotal: number;
}

interface OrderDetails {
    orderNumber: string;
    status: string;
    subtotal: number;
    total: number;
    contactName: string;
    contactEmail: string;
    contactPhone: string;
    deliveryAddress: string;
    notes: string | null;
    createdAt: string;
    items: OrderItem[];
}

interface Props {
    order: OrderDetails;
}

defineProps<Props>();

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
        <div class="confirmation-page">
            <h1 class="confirmation-page__title">Thank you for your order!</h1>

            <p class="confirmation-page__subtitle">
                Order <strong>{{ order.orderNumber }}</strong>
                <UiBadge variant="custom">{{ order.status }}</UiBadge>
            </p>

            <section class="confirmation-page__section">
                <h2 class="confirmation-page__section-title">Items</h2>

                <ul class="confirmation-page__items">
                    <li v-for="item in order.items" :key="item.productSku" class="confirmation-page__item">
                        <span class="confirmation-page__item-name">
                            {{ item.productName }} &times; {{ item.quantity }}
                        </span>
                        <span class="confirmation-page__item-total">{{ item.subtotal.toFixed(2) }} MDL</span>
                    </li>
                </ul>

                <div class="confirmation-page__total">
                    <span>Total</span>
                    <strong>{{ order.total.toFixed(2) }} MDL</strong>
                </div>
            </section>

            <section class="confirmation-page__section">
                <h2 class="confirmation-page__section-title">Delivery &amp; contact details</h2>

                <dl class="confirmation-page__details">
                    <dt>Name</dt>
                    <dd>{{ order.contactName }}</dd>

                    <dt>Email</dt>
                    <dd>{{ order.contactEmail }}</dd>

                    <dt>Phone</dt>
                    <dd>{{ order.contactPhone }}</dd>

                    <dt>Address</dt>
                    <dd>{{ order.deliveryAddress }}</dd>

                    <template v-if="order.notes">
                        <dt>Notes</dt>
                        <dd>{{ order.notes }}</dd>
                    </template>
                </dl>
            </section>

            <UiButton href="/catalog" variant="ghost" class="confirmation-page__continue">
                Continue shopping
            </UiButton>
        </div>
    </SiteLayout>
</template>

<style scoped>
.confirmation-page {
    max-width: 40rem;
    margin: 0 auto;
    padding: 2rem 1.5rem 3rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.confirmation-page__title {
    font-size: 1.5rem;
    font-weight: 600;
}

.confirmation-page__subtitle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4a4a47;
}

.confirmation-page__section-title {
    font-size: 1.0625rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.confirmation-page__items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0 0 0.75rem;
}

.confirmation-page__item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    font-size: 0.875rem;
    color: #4a4a47;
}

.confirmation-page__item-name {
    flex: 1;
}

.confirmation-page__total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding-top: 0.75rem;
    border-top: 1px solid #ededeb;
    font-size: 1rem;
}

.confirmation-page__details {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 0.375rem 1rem;
    font-size: 0.875rem;
}

.confirmation-page__details dt {
    color: #706f6c;
}

.confirmation-page__details dd {
    color: #1b1b18;
    margin: 0;
}

.confirmation-page__continue {
    align-self: flex-start;
}
</style>
