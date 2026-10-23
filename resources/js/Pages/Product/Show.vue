<script setup lang="ts">
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import SiteLayout from '../../Layouts/SiteLayout.vue';
import ProductGallery from '../../Components/Shop/ProductGallery.vue';
import ProductInfo from '../../Components/Shop/ProductInfo.vue';
import ProductVariantSelector from '../../Components/Shop/ProductVariantSelector.vue';
import ProductFeatures from '../../Components/Shop/ProductFeatures.vue';
import RelatedProducts from '../../Components/Shop/RelatedProducts.vue';
import type { Product, ProductDetail } from '../../types/product';
import type { NavItem, FooterColumn } from '../../types/nav';

interface Props {
    product: ProductDetail;
}

const props = defineProps<Props>();

const selectedVariantId = ref<number | string | null>(
    props.product.hasVariants && props.product.variants.length > 0
        ? props.product.variants[0].id
        : null,
);

const selectedVariant = computed(() => props.product.variants
    .find((variant) => variant.id === selectedVariantId.value) ?? null);

const displayPrice = computed(() => selectedVariant.value?.price ?? props.product.price);

const displayOldPrice = computed(() => selectedVariant.value
    ? selectedVariant.value.oldPrice
    : props.product.oldPrice);

const displayInStock = computed(() => selectedVariant.value
    ? selectedVariant.value.inStock
    : props.product.inStock);

const displayIsSale = computed(() => (displayOldPrice.value ?? 0) > displayPrice.value);

const displaySalePercent = computed(() => {
    if (!displayIsSale.value || !displayOldPrice.value) {
        return undefined;
    }

    return Math.round(
        ((displayOldPrice.value - displayPrice.value) / displayOldPrice.value) * 100,
    );
});

const galleryImages = computed(() => {
    const variantImage = selectedVariant.value?.image;

    if (variantImage) {
        return [variantImage, ...props.product.gallery.filter((src) => src !== variantImage)];
    }

    return props.product.gallery;
});

function handleAddToCart(): void {
    console.log('add-to-cart', {
        productId: props.product.id,
        variantId: selectedVariantId.value,
    });
}

function handleRelatedAddToCart(product: Product): void {
    console.log('add-to-cart', product);
}

function handleRelatedOpen(product: Product): void {
    router.visit(`/product/${product.slug}`);
}

function handleRelatedToggleWishlist(product: Product): void {
    console.log('toggle-wishlist', product);
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
        <div class="product-page">
            <div class="product-page__main">
                <ProductGallery
                    :images="galleryImages"
                    :alt="product.title"
                />

                <div class="product-page__details">
                    <ProductInfo
                        :title="product.title"
                        :brand="product.brand"
                        :category="product.category"
                        :is-new="product.isNew"
                        :is-sale="displayIsSale"
                        :sale-percent="displaySalePercent"
                        :is-hit="product.isHit"
                        :price="displayPrice"
                        :old-price="displayOldPrice"
                        :bonus-amount="product.bonusAmount"
                        :currency="product.currency"
                        :in-stock="displayInStock"
                        @add-to-cart="handleAddToCart"
                    />

                    <ProductVariantSelector
                        v-if="product.hasVariants"
                        v-model="selectedVariantId"
                        :variants="product.variants"
                    />

                    <p v-if="product.description" class="product-page__description">
                        {{ product.description }}
                    </p>
                </div>
            </div>

            <ProductFeatures :features="product.featureValues" />

            <RelatedProducts
                :products="product.relatedProducts"
                @add-to-cart="handleRelatedAddToCart"
                @open="handleRelatedOpen"
                @toggle-wishlist="handleRelatedToggleWishlist"
            />
        </div>
    </SiteLayout>
</template>

<style scoped>
.product-page {
    max-width: 72rem;
    margin: 0 auto;
    padding: 1.5rem 1.5rem 3rem;
}

.product-page__main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

.product-page__details {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.product-page__description {
    font-size: 0.9375rem;
    color: #4a4a47;
    line-height: 1.6;
}

@media (min-width: 64rem) {
    .product-page__main {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    }
}
</style>
