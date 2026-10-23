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
    /** Time-limited deal flag — rendered as an icon-only badge. */
    isFlash?: boolean;
    /** "Comes with a free gift" flag. */
    isGift?: boolean;
    /** Exclusive to this store. */
    isExclusive?: boolean;
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
    /** Swatch color(s) — two values render as a split/bicolor swatch. */
    colorHex?: string[] | null;
}

export interface ProductVariantDetail {
    id: number | string;
    label?: string;
    price: number;
    oldPrice?: number;
    inStock: boolean;
    colorHex?: string[];
    volume?: string | null;
    image?: string | null;
}

export interface ProductFeatureGroup {
    feature: string;
    values: string[];
}

export interface ProductDetail {
    id: number | string;
    slug: string;
    title: string;
    brand?: string;
    category?: string;
    image: string;
    gallery: string[];
    description?: string | null;
    text?: string | null;
    price: number;
    oldPrice?: number;
    bonusAmount?: number;
    currency?: string;
    isNew?: boolean;
    isSale?: boolean;
    salePercent?: number;
    isHit?: boolean;
    inStock: boolean;
    hasVariants: boolean;
    variants: ProductVariantDetail[];
    featureValues: ProductFeatureGroup[];
    relatedProducts: Product[];
}
