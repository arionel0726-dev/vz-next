<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiInput from '../../Components/UI/UiInput.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import { siteNavItems, siteFooterColumns } from '../../constants/navigation';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}

</script>

<template>
    <SiteLayout :nav-items="siteNavItems" :footer-columns="siteFooterColumns">
        <div class="auth-page">
            <h1 class="auth-page__title">Create an account</h1>

            <form class="auth-page__form" @submit.prevent="submit">
                <UiInput
                    id="register-name"
                    v-model="form.name"
                    type="text"
                    label="Name"
                    autocomplete="name"
                    required
                    :error="form.errors.name"
                />

                <UiInput
                    id="register-email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    autocomplete="email"
                    required
                    :error="form.errors.email"
                />

                <UiInput
                    id="register-password"
                    v-model="form.password"
                    type="password"
                    label="Password"
                    autocomplete="new-password"
                    required
                    :error="form.errors.password"
                />

                <UiInput
                    id="register-password-confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    label="Confirm password"
                    autocomplete="new-password"
                    required
                    :error="form.errors.password_confirmation"
                />

                <UiButton
                    type="submit"
                    variant="primary"
                    :disabled="form.processing"
                    class="auth-page__submit"
                >
                    Register
                </UiButton>
            </form>

            <div class="auth-page__links">
                <Link href="/login">Already have an account? Log in</Link>
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
