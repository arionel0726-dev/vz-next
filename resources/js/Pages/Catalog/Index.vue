<script setup lang="ts">
import { ref } from 'vue';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import CatalogToolbar from '../../Components/Shop/CatalogToolbar.vue';
import ProductGrid from '../../Components/Shop/ProductGrid.vue';
import type { Product } from '../../types/product';
import type { CatalogViewMode, SortOption } from '../../types/catalog';
import type { NavItem, FooterColumn } from '../../types/nav';

interface Props {
    products: Product[];
}

const props = defineProps<Props>();

const sortOptions: SortOption[] = [
    { value: 'popular', label: 'Popular' },
    { value: 'price-asc', label: 'Price: low to high' },
    { value: 'price-desc', label: 'Price: high to low' },
];

const viewMode = ref<CatalogViewMode>('grid');
const sortValue = ref('popular');

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
                :product-count="props.products.length"
                :view-mode="viewMode"
                :sort-value="sortValue"
                :sort-options="sortOptions"
                @open-filters="handleOpenFilters"
                @update:view-mode="viewMode = $event"
                @update:sort-value="sortValue = $event"
            />

            <ProductGrid
                :products="props.products"
                :view-mode="viewMode"
                @add-to-cart="handleAddToCart"
                @open="handleOpen"
                @toggle-wishlist="handleToggleWishlist"
            />
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
</style>
