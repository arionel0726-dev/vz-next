<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import CatalogToolbar from '../../Components/Shop/CatalogToolbar.vue';
import ProductGrid from '../../Components/Shop/ProductGrid.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import type { Product } from '../../types/product';
import type {
    CatalogFilters,
    CatalogPagination,
    CatalogViewMode,
} from '../../types/catalog';
import type { NavItem, FooterColumn } from '../../types/nav';

interface Props {
    products: Product[];
    pagination: CatalogPagination;
    filters: CatalogFilters;
}

const props = defineProps<Props>();

const sortOptions = [
    { value: 'popular', label: 'Popular' },
    { value: 'newest', label: 'Newest' },
    { value: 'price_asc', label: 'Price: low to high' },
    { value: 'price_desc', label: 'Price: high to low' },
];

// Display-only, not a server filter — kept purely client-side.
const viewMode = ref<CatalogViewMode>('grid');

/**
 * Rebuilds the current filter state as URL query params, so a
 * single-field change (sort, page, ...) never drops the others —
 * this is the "sync filters with the query string" contract the
 * controller/CatalogIndexRequest expect.
 */
function buildQuery(
    overrides: Record<string, string | number | boolean | undefined> = {},
): Record<string, string | number> {
    const base: Record<string, string | number | boolean | undefined> = {
        search: props.filters.search ?? undefined,
        brand: props.filters.brand ?? undefined,
        category: props.filters.category ?? undefined,
        in_stock: props.filters.inStock || undefined,
        is_new: props.filters.isNew || undefined,
        on_sale: props.filters.onSale || undefined,
        sort: props.filters.sort,
    };

    const merged = { ...base, ...overrides };

    const query: Record<string, string | number> = {};

    for (const [key, value] of Object.entries(merged)) {
        if (value === undefined || value === null || value === false) {
            continue;
        }

        query[key] = value === true ? 1 : value;
    }

    return query;
}

function visitCatalog(overrides: Record<string, string | number | boolean | undefined>): void {
    router.get('/catalog', buildQuery(overrides), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function handleSortChange(sort: string): void {
    visitCatalog({ sort, page: undefined });
}

function goToPage(page: number): void {
    visitCatalog({ page });
}

function handleAddToCart(product: Product): void {
    console.log('add-to-cart', product);
}

function handleOpen(product: Product): void {
    console.log('open', product);
}

function handleToggleWishlist(product: Product): void {
    console.log('toggle-wishlist', product);
}

function handleOpenFilters(): void {
    console.log('open-filters');
}

const navItems: NavItem[] = [
    { label: 'Catalog', url: '/catalog' },
    { label: 'Brands', url: '#' },
    { label: 'Offers', url: '#' },
    { label: 'Contacts', url: '#' },
];

const footerColumns: FooterColumn[] = [
    {
        title: 'Company',
        links: [
            { label: 'About', url: '#' },
            { label: 'Contacts', url: '#' },
        ],
    },
    {
        title: 'Help',
        links: [
            { label: 'Delivery', url: '#' },
            { label: 'Returns', url: '#' },
        ],
    },
];
</script>

<template>
    <SiteLayout
        :nav-items="navItems"
        :footer-columns="footerColumns"
        announcement="Free delivery from 500 MDL"
        :cart-count="2"
        :wishlist-count="1"
    >
        <div class="catalog-page">
            <h1 class="catalog-page__title">Catalog</h1>

            <CatalogToolbar
                :product-count="props.pagination.total"
                :view-mode="viewMode"
                :sort-value="props.filters.sort"
                :sort-options="sortOptions"
                @open-filters="handleOpenFilters"
                @update:view-mode="viewMode = $event"
                @update:sort-value="handleSortChange"
            />

            <ProductGrid
                :products="props.products"
                :view-mode="viewMode"
                @add-to-cart="handleAddToCart"
                @open="handleOpen"
                @toggle-wishlist="handleToggleWishlist"
            />

            <div
                v-if="props.pagination.lastPage > 1"
                class="catalog-page__pagination"
            >
                <UiButton
                    variant="outline"
                    :disabled="props.pagination.currentPage <= 1"
                    @click="goToPage(props.pagination.currentPage - 1)"
                >
                    Previous
                </UiButton>

                <span class="catalog-page__pagination-label">
                    Page {{ props.pagination.currentPage }} of {{ props.pagination.lastPage }}
                </span>

                <UiButton
                    variant="outline"
                    :disabled="props.pagination.currentPage >= props.pagination.lastPage"
                    @click="goToPage(props.pagination.currentPage + 1)"
                >
                    Next
                </UiButton>
            </div>
        </div>
    </SiteLayout>
</template>

<style scoped>
.catalog-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 1.5rem 1.5rem 3rem;
}

.catalog-page__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.catalog-page__pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-top: 2rem;
}

.catalog-page__pagination-label {
    font-size: 0.875rem;
    color: #706f6c;
}
</style>
