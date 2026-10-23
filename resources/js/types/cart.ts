export interface CartItemProductSummary {
    id: number | string;
    slug: string;
    title: string;
    brand?: string | null;
    image?: string | null;
}

export interface CartItemVariantSummary {
    id: number | string;
    label?: string;
}

export interface CartItem {
    id: string;
    product: CartItemProductSummary;
    variant: CartItemVariantSummary | null;
    quantity: number;
    unitPrice: number;
    oldPrice?: number | null;
    subtotal: number;
    total: number;
    stock: number;
}

export interface CartTotals {
    itemsCount: number;
    subtotal: number;
    discountTotal: number;
    total: number;
}
