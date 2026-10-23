<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiInput from '../../Components/UI/UiInput.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import CartSummary from '../../Components/Shop/CartSummary.vue';
import type { CartItem, CartTotals } from '../../types/cart';
import type { NavItem, FooterColumn } from '../../types/nav';

interface Contact {
    name: string;
    email: string;
    phone: string | null;
}

interface Props {
    items: CartItem[];
    totals: CartTotals;
    contact: Contact;
}

const props = defineProps<Props>();

const form = useForm({
    contact_name: props.contact.name,
    contact_email: props.contact.email,
    contact_phone: props.contact.phone ?? '',
    delivery_address: '',
    notes: '',
});

function submit(): void {
    form.post('/checkout');
}

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
        <div class="checkout-page">
            <h1 class="checkout-page__title">Checkout</h1>

            <p v-if="form.errors.cart" class="checkout-page__error">
                {{ form.errors.cart }}
            </p>

            <div class="checkout-page__layout">
                <form class="checkout-page__form" @submit.prevent="submit">
                    <h2 class="checkout-page__section-title">Delivery &amp; contact details</h2>

                    <UiInput
                        id="checkout-contact-name"
                        v-model="form.contact_name"
                        type="text"
                        label="Full name"
                        autocomplete="name"
                        required
                        :error="form.errors.contact_name"
                    />

                    <UiInput
                        id="checkout-contact-email"
                        v-model="form.contact_email"
                        type="email"
                        label="Email"
                        autocomplete="email"
                        required
                        :error="form.errors.contact_email"
                    />

                    <UiInput
                        id="checkout-contact-phone"
                        v-model="form.contact_phone"
                        type="tel"
                        label="Phone"
                        autocomplete="tel"
                        required
                        :error="form.errors.contact_phone"
                    />

                    <UiInput
                        id="checkout-delivery-address"
                        v-model="form.delivery_address"
                        type="text"
                        label="Delivery address"
                        placeholder="City, street, house, apartment"
                        autocomplete="street-address"
                        required
                        :error="form.errors.delivery_address"
                    />

                    <UiInput
                        id="checkout-notes"
                        v-model="form.notes"
                        type="text"
                        label="Notes (optional)"
                        placeholder="Delivery instructions, preferred time, etc."
                        :error="form.errors.notes"
                    />

                    <UiButton
                        type="submit"
                        variant="primary"
                        :disabled="form.processing"
                        class="checkout-page__submit"
                    >
                        Place order
                    </UiButton>
                </form>

                <div class="checkout-page__summary">
                    <h2 class="checkout-page__section-title">Order summary</h2>

                    <ul class="checkout-page__items">
                        <li v-for="item in items" :key="item.id" class="checkout-page__item">
                            <span class="checkout-page__item-name">
                                {{ item.product.title }}
                                <span v-if="item.variant?.label"> ({{ item.variant.label }})</span>
                                &times; {{ item.quantity }}
                            </span>
                            <span class="checkout-page__item-total">{{ item.total.toFixed(2) }} MDL</span>
                        </li>
                    </ul>

                    <CartSummary :totals="totals" :show-checkout-button="false" />
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<style scoped>
.checkout-page {
    max-width: 64rem;
    margin: 0 auto;
    padding: 1.5rem 1.5rem 3rem;
}

.checkout-page__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.checkout-page__error {
    color: #c53030;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.checkout-page__layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    align-items: start;
}

.checkout-page__section-title {
    font-size: 1.0625rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.checkout-page__form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkout-page__submit {
    margin-top: 0.5rem;
    align-self: flex-start;
}

.checkout-page__summary {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkout-page__items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
}

.checkout-page__item {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    font-size: 0.875rem;
    color: #4a4a47;
}

.checkout-page__item-name {
    flex: 1;
}

@media (min-width: 64rem) {
    .checkout-page__layout {
        grid-template-columns: minmax(0, 1fr) 20rem;
    }
}
</style>
