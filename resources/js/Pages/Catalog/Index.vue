<script setup lang="ts">
import { ref } from 'vue';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import CatalogToolbar from '../../Components/Shop/CatalogToolbar.vue';
import ProductGrid from '../../Components/Shop/ProductGrid.vue';
import type { Product } from '../../types/product';
import type { CatalogViewMode, SortOption } from '../../types/catalog';
import type { NavItem, FooterColumn } from '../../types/nav';

/*
 * Mock catalog data for this page only — no database, no API,
 * no filtering logic. A real catalog route/controller is a
 * separate, later task.
 */
const placeholderImage =
    'data:image/svg+xml;utf8,' +
    encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400">'
        + '<rect width="400" height="400" fill="%23e3e3e0"/>'
        + '</svg>',
    );

const mockProducts = ref<Product[]>([
    {
        id: 1,
        title: 'Hydrating Face Cream',
        brand: 'Vizaje Lab',
        image: placeholderImage,
        price: 420,
        bonusAmount: 42,
        isNew: true,
        inStock: true,
    },
    {
        id: 2,
        title: 'Matte Lipstick No. 12',
        brand: 'Nica Beauty',
        image: placeholderImage,
        price: 180,
        oldPrice: 240,
        salePercent: 25,
        isSale: true,
        isHit: true,
        inStock: true,
    },
    {
        id: 3,
        title: 'Eau de Parfum 50ml',
        brand: 'Vizaje Parfum',
        image: placeholderImage,
        price: 890,
        isLiked: true,
        inStock: false,
    },
    {
        id: 4,
        title: 'Nourishing Hair Mask',
        brand: 'Vizaje Lab',
        image: placeholderImage,
        price: 260,
        bonusAmount: 26,
        inStock: true,
    },
    {
        id: 5,
        title: 'Vitamin C Serum',
        brand: 'Nica Beauty',
        image: placeholderImage,
        price: 340,
        oldPrice: 400,
        salePercent: 15,
        isSale: true,
        inStock: true,
    },
    {
        id: 6,
        title: 'Body Lotion 200ml',
        brand: 'Vizaje Lab',
        image: placeholderImage,
        price: 150,
        isNew: true,
        inStock: true,
    },
]);

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
    const target = mockProducts.value.find((item) => item.id === product.id);

    if (target) {
        target.isLiked = !target.isLiked;
    }
}

function handleOpenFilters(): void {
    console.log('open-filters');
}

const mockNavItems: NavItem[] = [
    { label: 'Catalog', url: '/catalog' },
    { label: 'Brands', url: '#' },
    { label: 'Offers', url: '#' },
    { label: 'Contacts', url: '#' },
];

const mockFooterColumns: FooterColumn[] = [
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
        :nav-items="mockNavItems"
        :footer-columns="mockFooterColumns"
        announcement="Free delivery from 500 MDL"
        :cart-count="2"
        :wishlist-count="1"
    >
        <div class="catalog-page">
            <h1 class="catalog-page__title">Catalog</h1>

            <CatalogToolbar
                :product-count="mockProducts.length"
                :view-mode="viewMode"
                :sort-value="sortValue"
                :sort-options="sortOptions"
                @open-filters="handleOpenFilters"
                @update:view-mode="viewMode = $event"
                @update:sort-value="sortValue = $event"
            />

            <ProductGrid
                :products="mockProducts"
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
