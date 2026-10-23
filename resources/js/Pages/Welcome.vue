<script setup lang="ts">
import { ref } from 'vue';
import UiButton from '../Components/UI/UiButton.vue';
import UiInput from '../Components/UI/UiInput.vue';
import UiBadge from '../Components/UI/UiBadge.vue';
import UiModal from '../Components/UI/UiModal.vue';
import UiDrawer from '../Components/UI/UiDrawer.vue';
import UiIcon from '../Components/UI/UiIcon.vue';
import type { DrawerPosition } from '../Components/UI/UiDrawer.vue';
import ProductCard from '../Components/Shop/ProductCard.vue';
import Hero from '../Components/Home/Hero.vue';
import ProductSlider from '../Components/Home/ProductSlider.vue';
import GiftCardPromo from '../Components/Home/GiftCardPromo.vue';
import type { HeroSlide } from '../Components/Home/Hero.vue';
import type { Product } from '../types/product';
import SiteLayout from '../Layouts/SiteLayout.vue';
import type { NavItem, FooterColumn } from '../types/nav';

const textValue = ref('');
const emailValue = ref('');
const passwordValue = ref('');
const telValue = ref('');

const isModalOpen = ref(false);
const drawerPosition = ref<DrawerPosition>('right');
const isDrawerOpen = ref(false);

function openDrawer(position: DrawerPosition): void {
    drawerPosition.value = position;
    isDrawerOpen.value = true;
}

/*
 * Mock data for the Welcome demo page only — no catalog, no API.
 */
const placeholderImage =
    'data:image/svg+xml;utf8,' +
    encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400">'
        + '<rect width="400" height="400" fill="#e3e3e0"/>'
        + '</svg>',
    );

function heroPlaceholder(fill: string, width = 260, height = 360): string {
    return 'data:image/svg+xml;utf8,' + encodeURIComponent(
        `<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}">`
        + `<rect width="${width}" height="${height}" fill="${fill}"/>`
        + '</svg>',
    );
}

/*
 * No campaign/banner backend exists yet -- these slides are mock
 * content for the Hero component, same as the rest of this page.
 */
const heroSlides: HeroSlide[] = [
    {
        id: 1,
        title: 'Vizaje Lab',
        subtitle: 'Уход за кожей с доказанной эффективностью',
        ctaLabel: 'Перейти к бренду',
        ctaUrl: '/catalog',
        images: [heroPlaceholder('#e7ddf0'), heroPlaceholder('#d9c9ec'), heroPlaceholder('#cbb3e8')],
        background: 'linear-gradient(135deg, #f1e9fa 0%, #e4d4f5 100%)',
    },
    {
        id: 2,
        title: 'Nica Beauty',
        subtitle: 'Новая коллекция декоративной косметики',
        ctaLabel: 'Перейти к бренду',
        ctaUrl: '/catalog',
        images: [heroPlaceholder('#fbe3e0'), heroPlaceholder('#f6c9c3')],
        background: 'linear-gradient(135deg, #fdeeec 0%, #fad9d4 100%)',
    },
];

/*
 * No homepage sections/merchandising backend exists yet -- these
 * three rows are mock products for the ProductSlider component,
 * standing in for "discounts" / "new arrivals" / "hits" until that
 * exists.
 */
function sliderProduct(overrides: Partial<Product> & Pick<Product, 'id' | 'slug' | 'title' | 'brand' | 'price'>): Product {
    return {
        image: placeholderImage,
        inStock: true,
        ...overrides,
    };
}

const discountProducts: Product[] = [
    sliderProduct({
        id: 101,
        slug: 'matte-lipstick-no-12',
        title: 'Matte Lipstick No. 12',
        brand: 'Nica Beauty',
        category: 'Makeup',
        price: 180,
        oldPrice: 240,
        salePercent: 25,
        isSale: true,
    }),
    sliderProduct({
        id: 102,
        slug: 'eyeshadow-palette',
        title: 'Eyeshadow Palette',
        brand: 'Nica Beauty',
        category: 'Makeup',
        price: 240,
        oldPrice: 300,
        salePercent: 20,
        isSale: true,
    }),
    sliderProduct({
        id: 103,
        slug: 'setting-spray',
        title: 'Setting Spray',
        brand: 'Nica Beauty',
        category: 'Makeup',
        price: 168,
        oldPrice: 210,
        salePercent: 20,
        isSale: true,
    }),
    sliderProduct({
        id: 104,
        slug: 'hand-cream',
        title: 'Hand Cream',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        price: 76,
        oldPrice: 95,
        salePercent: 20,
        isSale: true,
    }),
];

const newProducts: Product[] = [
    sliderProduct({
        id: 201,
        slug: 'hydrating-face-cream',
        title: 'Hydrating Face Cream',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        price: 420,
        bonusAmount: 42,
        isNew: true,
    }),
    sliderProduct({
        id: 202,
        slug: 'vitamin-c-serum',
        title: 'Vitamin C Serum',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        price: 340,
        isNew: true,
    }),
    sliderProduct({
        id: 203,
        slug: 'body-lotion',
        title: 'Body Lotion',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        price: 150,
        isNew: true,
    }),
    sliderProduct({
        id: 204,
        slug: 'cologne-for-men',
        title: 'Cologne for Men',
        brand: 'Vizaje Parfum',
        category: 'Perfume',
        price: 650,
        isNew: true,
        isFlash: true,
    }),
];

const hitProducts: Product[] = [
    sliderProduct({
        id: 301,
        slug: 'matte-lipstick-no-12-hit',
        title: 'Matte Lipstick No. 12',
        brand: 'Nica Beauty',
        category: 'Makeup',
        price: 240,
        isHit: true,
        variants: [
            { id: 1, label: 'Red', price: 240, inStock: true, colorHex: ['#C21807'] },
            { id: 2, label: 'Nude', price: 240, inStock: true, colorHex: ['#C9947A'] },
        ],
    }),
    sliderProduct({
        id: 302,
        slug: 'eau-de-parfum-50ml',
        title: 'Eau de Parfum 50ml',
        brand: 'Vizaje Parfum',
        category: 'Perfume',
        price: 890,
        isHit: true,
        isExclusive: true,
    }),
    sliderProduct({
        id: 303,
        slug: 'hydrating-face-cream-hit',
        title: 'Hydrating Face Cream',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        price: 420,
        bonusAmount: 42,
        isHit: true,
    }),
    sliderProduct({
        id: 304,
        slug: 'vitamin-c-serum-hit',
        title: 'Vitamin C Serum',
        brand: 'Vizaje Lab',
        category: 'Skincare',
        price: 340,
        isHit: true,
        isGift: true,
    }),
];

const mockProducts = ref<Product[]>([
    {
        id: 1,
        slug: 'hydrating-face-cream',
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
        slug: 'matte-lipstick-no-12',
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
        slug: 'eau-de-parfum-50ml',
        title: 'Eau de Parfum 50ml',
        brand: 'Vizaje Parfum',
        image: placeholderImage,
        price: 890,
        isLiked: true,
        inStock: false,
    },
]);

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

/*
 * Mock layout data for the Welcome demo page only.
 */
const mockNavItems: NavItem[] = [
    { label: 'Catalog', url: '#' },
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

const mockSocialLinks: NavItem[] = [
    { label: 'Instagram', url: '#' },
    { label: 'Facebook', url: '#' },
];

/*
 * Layout actions reuse the existing UiModal/UiDrawer demo state —
 * cart and profile now navigate for real (see SiteHeader/
 * MobileBottomMenu), only search/menu are still demo-only here.
 */
function handleOpenSearch(): void {
    isModalOpen.value = true;
}

function handleOpenMenu(): void {
    openDrawer('bottom');
}
</script>

<template>
    <SiteLayout
        :nav-items="mockNavItems"
        :footer-columns="mockFooterColumns"
        :social-links="mockSocialLinks"
        announcement="Free delivery from 500 MDL"
        footer-description="Original cosmetics and perfumery since 1992."
        copyright="© Vizaje Nica, 1992—2026."
        :wishlist-count="1"
        @open-search="handleOpenSearch"
        @open-menu="handleOpenMenu"
    >
    <Hero :slides="heroSlides" />

    <div class="home-sliders">
        <ProductSlider
            title="Скидки"
            :products="discountProducts"
            :show-wishlist="false"
            @add-to-cart="handleAddToCart"
            @open="handleOpen"
        />

        <ProductSlider
            title="Новинки"
            :products="newProducts"
            :show-wishlist="false"
            @add-to-cart="handleAddToCart"
            @open="handleOpen"
        />

        <ProductSlider
            title="Хиты продаж"
            :products="hitProducts"
            :show-wishlist="false"
            @add-to-cart="handleAddToCart"
            @open="handleOpen"
        />
    </div>

    <GiftCardPromo
        title="Подарочные карты"
        subtitle="Кастомизируйте карту для любого повода, а мы доставим её вовремя."
        cta-label="Купить карту"
        cta-url="/catalog"
    />

    <div class="ui-kit-demo">
        <h1 class="ui-kit-demo__title">Vizaje Nica — UI-kit</h1>
        <p class="ui-kit-demo__subtitle">Inertia + Vue 3 работает.</p>

        <section class="ui-kit-demo__section">
            <h2>UiButton</h2>
            <div class="ui-kit-demo__row">
                <UiButton variant="primary">Primary</UiButton>
                <UiButton variant="secondary">Secondary</UiButton>
                <UiButton variant="ghost">Ghost</UiButton>
                <UiButton variant="outline">Outline</UiButton>
                <UiButton variant="danger">Danger</UiButton>
                <UiButton variant="icon" aria-label="Close">
                    <UiIcon name="close" />
                </UiButton>
            </div>
        </section>

        <section class="ui-kit-demo__section">
            <h2>UiBadge</h2>
            <div class="ui-kit-demo__row">
                <UiBadge variant="new">New</UiBadge>
                <UiBadge variant="sale">-20%</UiBadge>
                <UiBadge variant="hit">Hit</UiBadge>
                <UiBadge variant="bonus">+10 bonus</UiBadge>
                <UiBadge variant="custom">Custom</UiBadge>
            </div>
        </section>

        <section class="ui-kit-demo__section">
            <h2>UiInput</h2>
            <div class="ui-kit-demo__column">
                <UiInput
                    id="demo-text"
                    v-model="textValue"
                    type="text"
                    label="Text"
                    placeholder="Text value"
                />

                <UiInput
                    id="demo-email"
                    v-model="emailValue"
                    type="email"
                    label="Email"
                    placeholder="name@email.com"
                />

                <UiInput
                    id="demo-password"
                    v-model="passwordValue"
                    type="password"
                    label="Password"
                />

                <UiInput
                    id="demo-tel"
                    v-model="telValue"
                    type="tel"
                    label="Phone"
                    placeholder="XX XXX XXX"
                >
                    <template #prefix>+373</template>
                </UiInput>
            </div>
        </section>

        <section class="ui-kit-demo__section">
            <h2>UiModal</h2>
            <UiButton variant="primary" @click="isModalOpen = true">
                Open modal
            </UiButton>

            <UiModal v-model="isModalOpen" aria-label="Demo modal">
                <template #header>
                    <h3>Modal title</h3>
                </template>

                <p>Modal body content goes here.</p>

                <template #footer>
                    <UiButton variant="secondary" @click="isModalOpen = false">
                        Close
                    </UiButton>
                </template>
            </UiModal>
        </section>

        <section class="ui-kit-demo__section">
            <h2>UiDrawer</h2>
            <div class="ui-kit-demo__row">
                <UiButton variant="outline" @click="openDrawer('left')">
                    Left drawer
                </UiButton>

                <UiButton variant="outline" @click="openDrawer('right')">
                    Right drawer
                </UiButton>

                <UiButton variant="outline" @click="openDrawer('bottom')">
                    Bottom drawer
                </UiButton>
            </div>

            <UiDrawer
                v-model="isDrawerOpen"
                :position="drawerPosition"
                aria-label="Demo drawer"
            >
                <template #header>
                    <h3>Drawer title</h3>
                </template>

                <p>Drawer body content goes here.</p>

                <template #footer>
                    <UiButton variant="primary" @click="isDrawerOpen = false">
                        Done
                    </UiButton>
                </template>
            </UiDrawer>
        </section>

        <section class="ui-kit-demo__section">
            <h2>ProductCard — grid</h2>
            <div class="ui-kit-demo__grid">
                <ProductCard
                    v-for="product in mockProducts"
                    :key="product.id"
                    :product="product"
                    variant="grid"
                    @add-to-cart="handleAddToCart"
                    @open="handleOpen"
                    @toggle-wishlist="handleToggleWishlist"
                />
            </div>
        </section>

        <section class="ui-kit-demo__section">
            <h2>ProductCard — slider</h2>
            <div class="ui-kit-demo__slider">
                <ProductCard
                    v-for="product in mockProducts"
                    :key="product.id"
                    :product="product"
                    variant="slider"
                    @add-to-cart="handleAddToCart"
                    @open="handleOpen"
                    @toggle-wishlist="handleToggleWishlist"
                />
            </div>
        </section>

        <section class="ui-kit-demo__section">
            <h2>ProductCard — compact</h2>
            <div class="ui-kit-demo__column">
                <ProductCard
                    v-for="product in mockProducts"
                    :key="product.id"
                    :product="product"
                    variant="compact"
                    :show-cart-button="false"
                    @add-to-cart="handleAddToCart"
                    @open="handleOpen"
                    @toggle-wishlist="handleToggleWishlist"
                />
            </div>
        </section>
    </div>
    </SiteLayout>
</template>

<style scoped>
.home-sliders {
    max-width: 90rem;
    margin: 0 auto;
    padding: 3rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 3rem;
}

.ui-kit-demo {
    max-width: 48rem;
    margin: 0 auto;
    padding: 3rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.ui-kit-demo__title {
    font-size: 1.75rem;
    font-weight: 600;
}

.ui-kit-demo__subtitle {
    color: #706f6c;
}

.ui-kit-demo__section h2 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.ui-kit-demo__row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
}

.ui-kit-demo__column {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 24rem;
}

.ui-kit-demo__grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(10rem, 1fr));
    gap: 1rem;
}

.ui-kit-demo__slider {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}
</style>
