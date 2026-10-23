<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiInput from '../../Components/UI/UiInput.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import type { NavItem, FooterColumn } from '../../types/nav';

interface Props {
    status?: string | null;
}

defineProps<Props>();

const form = useForm({
    email: '',
});

function submit(): void {
    form.post('/forgot-password');
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
        <div class="auth-page">
            <h1 class="auth-page__title">Reset your password</h1>

            <p class="auth-page__hint">
                Enter your email and we'll send you a password reset link.
            </p>

            <p v-if="status" class="auth-page__status">{{ status }}</p>

            <form class="auth-page__form" @submit.prevent="submit">
                <UiInput
                    id="forgot-password-email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    autocomplete="email"
                    required
                    :error="form.errors.email"
                />

                <UiButton
                    type="submit"
                    variant="primary"
                    :disabled="form.processing"
                    class="auth-page__submit"
                >
                    Email password reset link
                </UiButton>
            </form>

            <div class="auth-page__links">
                <Link href="/login">Back to log in</Link>
            </div>
        </div>
    </SiteLayout>
</template>

<style scoped>
.auth-page {
    max-width: 24rem;
    margin: 0 auto;
    padding: 3rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.auth-page__title {
    font-size: 1.5rem;
    font-weight: 600;
}

.auth-page__hint {
    font-size: 0.875rem;
    color: #706f6c;
}

.auth-page__status {
    font-size: 0.875rem;
    color: #1f7a4d;
}

.auth-page__form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.auth-page__submit {
    margin-top: 0.5rem;
}

.auth-page__links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.auth-page__links a {
    color: #1b1b18;
}
</style>
