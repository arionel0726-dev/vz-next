export interface Product {
    id: number | string;
    slug: string;
    title: string;
    brand?: string;
    category?: string;
    image: string;
    gallery?: string[];
    url?: string;
    price: number;
    oldPrice?: number;
    bonusAmount?: number;
    currency?: string;
    isNew?: boolean;
    isSale?: boolean;
    salePercent?: number;
    isHit?: boolean;
    isLiked?: boolean;
    inStock?: boolean;
    hasVariants?: boolean;
    variants?: ProductVariantSummary[];
}

export interface ProductVariantSummary {
    id: number | string;
    label?: string;
    price: number;
    oldPrice?: number;
    inStock?: boolean;
}
