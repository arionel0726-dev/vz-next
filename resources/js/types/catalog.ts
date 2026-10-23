export type CatalogViewMode = 'grid' | 'list';

export interface SortOption {
    value: string;
    label: string;
}

export type CatalogSort = 'popular' | 'newest' | 'price_asc' | 'price_desc';

export interface CatalogFilters {
    search: string | null;
    brand: number | null;
    category: number | null;
    inStock: boolean;
    isNew: boolean;
    onSale: boolean;
    sort: CatalogSort;
}

export interface CatalogPagination {
    currentPage: number;
    lastPage: number;
    perPage: number;
    total: number;
}
