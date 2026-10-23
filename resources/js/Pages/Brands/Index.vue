<script setup lang="ts">
import { computed, ref } from 'vue';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiIcon from '../../Components/UI/UiIcon.vue';
import { siteNavItems, siteFooterColumns, siteAnnouncement } from '../../constants/navigation';

/*
 * Test data only, per explicit instruction -- there's a real Brand
 * model/table already, but it only has the handful of brands the
 * catalog seeder created, nowhere near enough for an A-Z directory.
 * Swap this for a real BrandController once brand data exists at
 * scale.
 */
const mockBrands: string[] = [
    '111SKIN', '23.5°N', '3INA', '7DAYS', '7th Heaven', '9 wishes',
    'A-Derma', 'ABERCROMBIE&FITCH', 'ABIB', 'Aceology', 'Amouage', 'AMOUROUD', 'AMUSE', 'Angelo Kah\'el',
    'Bioderma', 'Byredo', 'Bobbi Brown',
    'Chanel', 'Clarins', 'Clinique',
    'Dior', 'Dr. Jart+',
    'Elemis', 'Estée Lauder',
    'Fenty Beauty', 'Floral Street',
    'Givenchy', 'Guerlain',
    'Huda Beauty',
    'Isa Knox',
    'Jo Malone London',
    'Kiehl\'s',
    'Lancôme', 'La Mer', 'L\'Occitane',
    'Maison Crivelli', 'MAC', 'Murad',
    'Nica Beauty', 'NARS',
    'Origins',
    'Paula\'s Choice',
    'Rare Beauty',
    'Shiseido', 'Sisley',
    'The Ordinary', 'Tom Ford',
    'Urban Decay',
    'Vizaje Lab', 'Vizaje Parfum',
    'YSL Beauty',
    'Zoeva',
];

const letters = ['0–9', ...Array.from({ length: 26 }, (_, i) => String.fromCharCode(65 + i))];

const searchQuery = ref('');

const filteredBrands = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();

    if (!query) {
        return mockBrands;
    }

    return mockBrands.filter((name) => name.toLowerCase().includes(query));
});

function letterFor(name: string): string {
    const first = name.charAt(0).toUpperCase();

    return /[0-9]/.test(first) ? '0–9' : first;
}

const groupedBrands = computed(() => {
    const groups = new Map<string, string[]>();

    for (const name of filteredBrands.value) {
        const letter = letterFor(name);
        const list = groups.get(letter) ?? [];
        list.push(name);
        groups.set(letter, list);
    }

    for (const list of groups.values()) {
        list.sort((a, b) => a.localeCompare(b));
    }

    return groups;
});

function hasBrands(letter: string): boolean {
    return (groupedBrands.value.get(letter)?.length ?? 0) > 0;
}

const visibleLetters = computed(() => letters.filter((letter) => hasBrands(letter)));

function scrollToLetter(letter: string): void {
    document.getElementById(`brand-letter-${letter}`)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>

<template>
    <SiteLayout
        :nav-items="siteNavItems"
        :footer-columns="siteFooterColumns"
        :announcement="siteAnnouncement"
    >
        <div class="brands-page">
            <h1 class="brands-page__title">Brands</h1>

            <nav class="brands-page__alphabet" aria-label="Jump to letter">
                <button
                    v-for="letter in letters"
                    :key="letter"
                    type="button"
                    class="brands-page__letter-link"
                    :disabled="!hasBrands(letter)"
                    @click="scrollToLetter(letter)"
                >
                    {{ letter }}
                </button>
            </nav>

            <div class="brands-page__layout">
                <aside class="brands-page__sidebar">
                    <div class="brands-page__search">
                        <input
                            v-model="searchQuery"
                            type="text"
                            class="brands-page__search-input"
                            placeholder="Найти бренды"
                            aria-label="Search brands"
                        >
                        <UiIcon name="search" :size="22" />
                    </div>

                    <div class="brands-page__favorites">
                        <h2 class="brands-page__favorites-title">Избранные</h2>

                        <!-- No brand-favoriting backend yet -- always
                             empty for now, same reasoning as the
                             wishlist action slot in the header. -->
                        <p class="brands-page__favorites-empty">
                            Пока здесь ничего нет. Добавляйте новые бренды в своё избранное.
                        </p>
                    </div>
                </aside>

                <div class="brands-page__groups">
                    <p v-if="filteredBrands.length === 0" class="brands-page__no-results">
                        Ничего не найдено.
                    </p>

                    <section
                        v-for="letter in visibleLetters"
                        :id="`brand-letter-${letter}`"
                        :key="letter"
                        class="brands-page__group"
                    >
                        <h2 class="brands-page__group-title">{{ letter }}</h2>

                        <div class="brands-page__group-list">
                            <span
                                v-for="name in groupedBrands.get(letter)"
                                :key="name"
                                class="brands-page__brand"
                            >
                                {{ name }}
                            </span>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<style scoped>
.brands-page {
    max-width: 90rem;
    margin: 0 auto;
    padding: 1.5rem 1.5rem 4rem;
}

.brands-page__title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
}

.brands-page__alphabet {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--color-border);
}

.brands-page__letter-link {
    border: none;
    background: transparent;
    padding: 0;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-ink);
    cursor: pointer;
}

.brands-page__letter-link:disabled {
    color: var(--color-border);
    cursor: default;
}

.brands-page__letter-link:not(:disabled):hover {
    color: var(--color-muted);
}

.brands-page__layout {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}

.brands-page__sidebar {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}

.brands-page__search {
    display: flex;
    align-items: center;
    gap: 1rem;
    border-bottom: 1px solid var(--color-ink);
    padding-bottom: 0.75rem;
    color: var(--color-muted);
}

.brands-page__search-input {
    flex: 1;
    min-width: 0;
    border: none;
    outline: none;
    background: transparent;
    font-size: 1.5rem;
    font-weight: 500;
    color: var(--color-ink);
}

.brands-page__search-input::placeholder {
    color: var(--color-border);
}

.brands-page__favorites-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.brands-page__favorites-empty {
    font-size: 0.875rem;
    color: var(--color-muted);
    max-width: 16rem;
}

.brands-page__groups {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}

.brands-page__group-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.brands-page__group-list {
    display: grid;
    grid-template-columns: 1fr;
    column-gap: 2rem;
    row-gap: 0.75rem;
}

.brands-page__brand {
    font-size: 0.9375rem;
    color: var(--color-ink);
}

.brands-page__no-results {
    color: var(--color-muted);
}

@media (min-width: 40rem) {
    .brands-page__group-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 64rem) {
    .brands-page__layout {
        flex-direction: row;
        gap: 4rem;
    }

    .brands-page__sidebar {
        width: 18rem;
        flex-shrink: 0;
    }

    .brands-page__groups {
        flex: 1;
    }

    .brands-page__group-list {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
</style>
