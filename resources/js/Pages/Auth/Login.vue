<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiInput from '../../Components/UI/UiInput.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import { siteNavItems, siteFooterColumns, siteAnnouncement } from '../../constants/navigation';

interface Props {
    status?: string | null;
}

defineProps<Props>();

const form = useForm({
    email: '',
    password: '',
});

function submit(): void {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}

</script>

<template>
    <SiteLayout :nav-items="siteNavItems" :footer-columns="siteFooterColumns" :announcement="siteAnnouncement">
        <div class="auth-page">
            <h1 class="auth-page__title">Log in</h1>

            <p v-if="status" class="auth-page__status">{{ status }}</p>

            <form class="auth-page__form" @submit.prevent="submit">
                <UiInput
                    id="login-email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    autocomplete="email"
                    required
                    :error="form.errors.email"
                />

                <UiInput
                    id="login-password"
                    v-model="form.password"
                    type="password"
                    label="Password"
                    autocomplete="current-password"
                    required
                    :error="form.errors.password"
                />

                <UiButton
                    type="submit"
                    variant="primary"
                    :disabled="form.processing"
                    class="auth-page__submit"
                >
                    Log in
                </UiButton>
            </form>

            <div class="auth-page__links">
                <Link href="/forgot-password">Forgot your password?</Link>
                <Link href="/register">Create an account</Link>
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
