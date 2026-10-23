<script setup lang="ts">
import { router, useForm, Link } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiInput from '../../Components/UI/UiInput.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import { siteNavItems, siteFooterColumns, siteAnnouncement } from '../../constants/navigation';

interface AccountUser {
    id: number;
    name: string;
    email: string;
    phone: string | null;
}

interface Props {
    user: AccountUser;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone ?? '',
});

function submit(): void {
    form.put('/user/profile-information', {
        preserveScroll: true,
    });
}

function logout(): void {
    router.post('/logout');
}

</script>

<template>
    <SiteLayout :nav-items="siteNavItems" :footer-columns="siteFooterColumns" :announcement="siteAnnouncement">
        <div class="account-page">
            <h1 class="account-page__title">My account</h1>

            <nav class="account-page__nav">
                <Link href="/account" class="account-page__nav-link is-active">
                    Account details
                </Link>

                <Link href="/account/orders" class="account-page__nav-link">
                    Order history
                </Link>
            </nav>

            <form class="account-page__form" @submit.prevent="submit">
                <UiInput
                    id="account-name"
                    v-model="form.name"
                    type="text"
                    label="Name"
                    autocomplete="name"
                    required
                    :error="form.errors.name"
                />

                <UiInput
                    id="account-email"
                    v-model="form.email"
                    type="email"
                    label="Email"
                    autocomplete="email"
                    required
                    :error="form.errors.email"
                />

                <UiInput
                    id="account-phone"
                    v-model="form.phone"
                    type="tel"
                    label="Phone"
                    autocomplete="tel"
                    :error="form.errors.phone"
                />

                <UiButton
                    type="submit"
                    variant="primary"
                    :disabled="form.processing"
                    class="account-page__submit"
                >
                    Save changes
                </UiButton>

                <p v-if="form.recentlySuccessful" class="account-page__saved">
                    Saved.
                </p>
            </form>

            <UiButton variant="ghost" class="account-page__logout" @click="logout">
                Log out
            </UiButton>
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

.account-page__form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.account-page__submit {
    margin-top: 0.5rem;
    align-self: flex-start;
}

.account-page__saved {
    font-size: 0.875rem;
    color: #1f7a4d;
}

.account-page__logout {
    align-self: flex-start;
}
</style>
