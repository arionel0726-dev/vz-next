export interface Product {
    id: number | string;
    title: string;
    brand?: string;
    image: string;
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
}
