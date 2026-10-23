<script setup lang="ts">
import { ref } from 'vue';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import UiButton from '../../Components/UI/UiButton.vue';
import CatalogToolbar from '../../Components/Shop/CatalogToolbar.vue';
import ProductGrid from '../../Components/Shop/ProductGrid.vue';
import ProductGallery from '../../Components/Shop/ProductGallery.vue';
import ProductInfo from '../../Components/Shop/ProductInfo.vue';
import ProductVariantSelector from '../../Components/Shop/ProductVariantSelector.vue';
import ProductFeatures from '../../Components/Shop/ProductFeatures.vue';
import RelatedProducts from '../../Components/Shop/RelatedProducts.vue';
import CartItem from '../../Components/Shop/CartItem.vue';
import CartSummary from '../../Components/Shop/CartSummary.vue';
import CartEmpty from '../../Components/Shop/CartEmpty.vue';
import type { Product, ProductVariantDetail, ProductFeatureGroup } from '../../types/product';
import type { CatalogViewMode, SortOption } from '../../types/catalog';
import type { CartItem as CartItemType, CartTotals } from '../../types/cart';
import type { NavItem, FooterColumn } from '../../types/nav';

/*
 * This is a standalone visual test page — every component here runs
 * on mock data only, nothing is wired to the real catalog/cart APIs.
 * It exists purely so all the built components can be eyeballed
 * together in one place.
 */

const placeholderImage = (fill: string) => 'data:image/svg+xml;utf8,' + encodeURIComponent(
    `<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400">`
    + `<rect width="400" height="400" fill="${fill}"/>`
    + `</svg>`,
);

const img1 = placeholderImage('%23e3e3e0');
const img2 = placeholderImage('%23d8d4ea');
const img3 = placeholderImage('%23ead8d4');

/* ---- 1. CatalogToolbar + ProductGrid ---- */

const mockProducts = ref<Product[]>([
    {
        id: 1,
        slug: 'hydrating-face-cream',
        title: 'Hydrating Face Cream',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        image: img1,
        price: 420,
        bonusAmount: 42,
        isNew: true,
        inStock: true,
    },
    {
        id: 2,
        slug: 'matte-lipstick-no-12',
        title: 'Matte Lipstick No. 12',
        brand: 'Nica Beauty',
        category: 'Makeup',
        image: img2,
        price: 180,
        oldPrice: 240,
        salePercent: 25,
        isSale: true,
        isHit: true,
        inStock: true,
    },
    {
        id: 3,
        slug: 'eau-de-parfum-50ml',
        title: 'Eau de Parfum 50ml',
        brand: 'Vizaje Parfum',
        category: 'Perfume',
        image: img3,
        price: 890,
        inStock: false,
    },
]);

const sortOptions: SortOption[] = [
    { value: 'popular', label: 'Popular' },
    { value: 'newest', label: 'Newest' },
    { value: 'price_asc', label: 'Price: low to high' },
    { value: 'price_desc', label: 'Price: high to low' },
];

const viewMode = ref<CatalogViewMode>('grid');
const sortValue = ref('popular');

function handleProductEvent(label: string, product: Product): void {
    console.log(label, product);
}

/* ---- 2. ProductGallery + ProductInfo + ProductVariantSelector + ProductFeatures ---- */

const galleryImages = [img2, img1, img3];

const mockVariants: ProductVariantDetail[] = [
    { id: 1, label: 'Red', price: 180, oldPrice: 240, inStock: true, colorHex: ['#C21807'] },
    { id: 2, label: 'Nude', price: 180, oldPrice: 240, inStock: false, colorHex: ['#C9947A'] },
];

const selectedVariantId = ref<number | string>(mockVariants[0].id);

const mockFeatures: ProductFeatureGroup[] = [
    { feature: 'Finish', values: ['Matte'] },
    { feature: 'Skin type', values: ['All types'] },
];

/* ---- 3. RelatedProducts ---- */

const relatedProducts = mockProducts.value;

/* ---- 4. CartItem + CartSummary + CartEmpty ---- */

const mockCartItems = ref<CartItemType[]>([
    {
        id: '2:1',
        product: { id: 2, slug: 'matte-lipstick-no-12', title: 'Matte Lipstick No. 12', brand: 'Nica Beauty', image: img2 },
        variant: { id: 1, label: 'Red' },
        quantity: 1,
        unitPrice: 180,
        oldPrice: 240,
        subtotal: 240,
        total: 180,
        stock: 8,
    },
    {
        id: '1:0',
        product: { id: 1, slug: 'hydrating-face-cream', title: 'Hydrating Face Cream', brand: 'Vizaje Lab', image: img1 },
        variant: null,
        quantity: 2,
        unitPrice: 420,
        oldPrice: null,
        subtotal: 840,
        total: 840,
        stock: 24,
    },
]);

const mockCartTotals = ref<CartTotals>({
    itemsCount: 3,
    subtotal: 1080,
    discountTotal: 60,
    total: 1020,
});

const showEmptyCart = ref(false);

function handleCartUpdateQuantity(key: string, quantity: number): void {
    const item = mockCartItems.value.find((entry) => entry.id === key);

    if (item) {
        item.quantity = quantity;
        item.total = item.unitPrice * quantity;
        item.subtotal = (item.oldPrice ?? item.unitPrice) * quantity;
    }
}

function handleCartRemove(key: string): void {
    mockCartItems.value = mockCartItems.value.filter((entry) => entry.id !== key);
}

/* ---- Layout ---- */

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
    <SiteLayout
        :nav-items="navItems"
        :footer-columns="footerColumns"
        announcement="Component test page — mock data only"
    >
        <div class="test-page">
            <h1 class="test-page__title">Component test page</h1>
            <p class="test-page__subtitle">
                Every section below runs on mock data — nothing here touches the real catalog or cart.
            </p>

            <section class="test-page__section">
                <h2>CatalogToolbar + ProductGrid</h2>

                <CatalogToolbar
                    :product-count="mockProducts.length"
                    :view-mode="viewMode"
                    :sort-value="sortValue"
                    :sort-options="sortOptions"
                    @open-filters="() => console.log('open-filters')"
                    @update:view-mode="viewMode = $event"
                    @update:sort-value="sortValue = $event"
                />

                <ProductGrid
                    :products="mockProducts"
                    :view-mode="viewMode"
                    @add-to-cart="(p) => handleProductEvent('add-to-cart', p)"
                    @open="(p) => handleProductEvent('open', p)"
                    @toggle-wishlist="(p) => handleProductEvent('toggle-wishlist', p)"
                />
            </section>

            <section class="test-page__section">
                <h2>ProductGallery + ProductInfo + ProductVariantSelector</h2>

                <div class="test-page__product-detail">
                    <ProductGallery :images="galleryImages" alt="Matte Lipstick" />

                    <div class="test-page__product-info">
                        <ProductInfo
                            title="Matte Lipstick No. 12"
                            brand="Nica Beauty"
                            category="Makeup"
                            is-sale
                            :sale-percent="25"
                            is-hit
                            :price="180"
                            :old-price="240"
                            in-stock
                            @add-to-cart="() => console.log('add-to-cart (test page)')"
                        />

                        <ProductVariantSelector
                            v-model="selectedVariantId"
                            :variants="mockVariants"
                        />
                    </div>
                </div>

                <ProductFeatures :features="mockFeatures" />
            </section>

            <section class="test-page__section">
                <h2>RelatedProducts</h2>

                <RelatedProducts
                    :products="relatedProducts"
                    @add-to-cart="(p) => handleProductEvent('add-to-cart', p)"
                    @open="(p) => handleProductEvent('open', p)"
                    @toggle-wishlist="(p) => handleProductEvent('toggle-wishlist', p)"
                />
            </section>

            <section class="test-page__section">
                <h2>CartItem + CartSummary + CartEmpty</h2>

                <UiButton variant="outline" @click="showEmptyCart = !showEmptyCart">
                    {{ showEmptyCart ? 'Show mock items' : 'Show empty state' }}
                </UiButton>

                <CartEmpty v-if="showEmptyCart" />

                <div v-else class="test-page__cart">
                    <div class="test-page__cart-items">
                        <CartItem
                            v-for="item in mockCartItems"
                            :key="item.id"
                            :item="item"
                            @update-quantity="handleCartUpdateQuantity"
                            @remove="handleCartRemove"
                        />
                    </div>

                    <CartSummary :totals="mockCartTotals" />
                </div>
            </section>
        </div>
    </SiteLayout>
</template>

<style scoped>
.test-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 2rem 1.5rem 4rem;
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
}

.test-page__title {
    font-size: 1.75rem;
    font-weight: 600;
}

.test-page__subtitle {
    color: #706f6c;
    margin-top: 0.25rem;
}

.test-page__section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid #ededeb;
}

.test-page__section h2 {
    font-size: 1.125rem;
    font-weight: 600;
}

.test-page__product-detail {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

.test-page__product-info {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.test-page__cart {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

@media (min-width: 64rem) {
    .test-page__product-detail {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    }

    .test-page__cart {
        grid-template-columns: minmax(0, 1fr) 20rem;
    }
}
</style>
