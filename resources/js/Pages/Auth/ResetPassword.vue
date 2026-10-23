<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiInput from '../../Components/UI/UiInput.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import { siteNavItems, siteFooterColumns } from '../../constants/navigation';

interface Props {
    email: string;
    token: string;
}

const props = defineProps<Props>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

function submit(): void {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}

</script>

<template>
    <SiteLayout :nav-items="siteNavItems" :footer-columns="siteFooterColumns">
        <div class="auth-page">
            <h1 class="auth-page__title">Set a new password</h1>

            <form class="auth-page__form" @submit.prevent="submit">
                <UiInput
                    id="reset-password-email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    autocomplete="email"
                    required
                    :error="form.errors.email"
                />

                <UiInput
                    id="reset-password-password"
                    v-model="form.password"
                    type="password"
                    label="New password"
                    autocomplete="new-password"
                    required
                    :error="form.errors.password"
                />

                <UiInput
                    id="reset-password-password-confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    label="Confirm new password"
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
                    Reset password
                </UiButton>
            </form>
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
</style>
