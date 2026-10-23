<script setup lang="ts">
import type { FooterColumn, NavItem } from '../../types/nav';

interface Props {
    homeUrl?: string;
    logoUrl?: string;
    description?: string;
    columns?: FooterColumn[];
    socialLinks?: NavItem[];
    copyright?: string;
}

withDefaults(defineProps<Props>(), {
    homeUrl: '/',
    logoUrl: undefined,
    description: undefined,
    columns: () => [],
    socialLinks: () => [],
    copyright: undefined,
});
</script>

<template>
    <footer class="site-footer">
        <div class="site-footer__main">
            <div class="site-footer__brand">
                <a :href="homeUrl" class="site-footer__logo">
                    <img v-if="logoUrl" :src="logoUrl" alt="Logo">
                    <span v-else>Vizaje Nica</span>
                </a>

                <p v-if="description" class="site-footer__description">
                    {{ description }}
                </p>

                <div v-if="socialLinks.length" class="site-footer__social">
                    <a
                        v-for="social in socialLinks"
                        :key="social.url"
                        :href="social.url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="site-footer__social-link"
                    >
                        {{ social.label }}
                    </a>
                </div>
            </div>

            <div
                v-for="column in columns"
                :key="column.title"
                class="site-footer__column"
            >
                <p class="site-footer__column-title">{{ column.title }}</p>

                <ul class="site-footer__list">
                    <li v-for="link in column.links" :key="link.url">
                        <a :href="link.url" class="site-footer__link">
                            {{ link.label }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p v-if="copyright" class="site-footer__copyright">
                {{ copyright }}
            </p>
        </div>
    </footer>
</template>

<style scoped>
/*
 * Gold Apple reference: the footer is the one deliberately inverted,
 * dark surface on an otherwise all-white site — not another light
 * panel with a hairline border.
 */
.site-footer {
    background-color: var(--color-footer);
    color: var(--color-footer-text);
}

.site-footer__main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    padding: 2.5rem 1.5rem;
    max-width: 72rem;
    margin: 0 auto;
}

.site-footer__logo {
    display: inline-flex;
    font-weight: 500;
    font-size: 1.125rem;
    color: var(--color-footer-text);
    text-decoration: none;
}

.site-footer__logo img {
    height: 2rem;
}

.site-footer__description {
    margin-top: 0.75rem;
    font-size: 0.875rem;
    color: var(--color-footer-muted);
}

.site-footer__social {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

.site-footer__social-link {
    font-size: 0.8125rem;
    color: var(--color-footer-text);
}

.site-footer__column-title {
    font-weight: 500;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
    color: var(--color-footer-text);
}

.site-footer__list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
}

.site-footer__link {
    font-size: 0.875rem;
    color: var(--color-footer-muted);
    text-decoration: none;
}

.site-footer__link:hover {
    color: var(--color-footer-text);
}

.site-footer__bottom {
    border-top: 1px solid var(--color-footer-border);
    padding: 1rem 1.5rem;
    text-align: center;
}

.site-footer__copyright {
    font-size: 0.8125rem;
    color: var(--color-footer-muted);
}

@media (min-width: 64rem) {
    .site-footer__main {
        grid-template-columns: 2fr 1fr 1fr 1fr;
    }
}
</style>
