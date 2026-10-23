<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogIndexRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class CatalogController extends Controller
{
    private const PER_PAGE = 6;

    /**
     * Matches ProductResource's own discount logic: the price to sort
     * by is the discount price when it's a real, lower discount, the
     * base price otherwise.
     */
    private const EFFECTIVE_PRICE_SQL = '
        CASE
            WHEN discount_price IS NOT NULL AND discount_price > 0 AND discount_price < price
                THEN discount_price
            ELSE price
        END
    ';

    public function index(CatalogIndexRequest $request): Response
    {
        $filters = $request->filters();

        $query = Product::query()
            ->with(['brand', 'category', 'images', 'variants'])
            ->where('is_published', true);

        $this->applySearch($query, $filters['search']);
        $this->applyBrand($query, $filters['brand']);
        $this->applyCategory($query, $filters['category']);
        $this->applyInStock($query, $filters['inStock']);
        $this->applyIsNew($query, $filters['isNew']);
        $this->applyOnSale($query, $filters['onSale']);
        $this->applySort($query, $filters['sort']);

        $paginator = $query
            ->paginate(self::PER_PAGE)
            ->withQueryString()
            ->through(fn (Product $product) => (new ProductResource($product))->resolve());

        return Inertia::render('Catalog/Index', [
            'products' => $paginator->items(),
            'pagination' => [
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'filters' => $filters,
        ]);
    }

    /**
     * Simple substring match across title (all locales, since it's
     * stored as JSON text), SKU, and brand name. Not a full-text
     * engine — good enough for this stage of the catalog.
     */
    private function applySearch(Builder $query, ?string $search): void
    {
        if ($search === null) {
            return;
        }

        $query->where(function (Builder $inner) use ($search) {
            $inner->where('products.title', 'like', "%{$search}%")
                ->orWhere('products.sku', 'like', "%{$search}%")
                ->orWhereHas('brand', function (Builder $brandQuery) use ($search) {
                    $brandQuery->where('title', 'like', "%{$search}%");
                });
        });
    }

    private function applyBrand(Builder $query, ?int $brandId): void
    {
        if ($brandId !== null) {
            $query->where('brand_id', $brandId);
        }
    }

    private function applyCategory(Builder $query, ?int $categoryId): void
    {
        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }
    }

    private function applyInStock(Builder $query, bool $inStock): void
    {
        if ($inStock) {
            $query->where('stock', '>', 0);
        }
    }

    private function applyIsNew(Builder $query, bool $isNew): void
    {
        if ($isNew) {
            $query->where('is_new', true);
        }
    }

    private function applyOnSale(Builder $query, bool $onSale): void
    {
        if ($onSale) {
            $query->whereNotNull('discount_price')
                ->where('discount_price', '>', 0)
                ->whereColumn('discount_price', '<', 'price');
        }
    }

    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            CatalogIndexRequest::SORT_NEWEST => $query
                ->orderByDesc('created_at')
                ->orderByDesc('id'),

            CatalogIndexRequest::SORT_PRICE_ASC => $query
                ->orderByRaw(self::EFFECTIVE_PRICE_SQL.' ASC')
                ->orderByDesc('id'),

            CatalogIndexRequest::SORT_PRICE_DESC => $query
                ->orderByRaw(self::EFFECTIVE_PRICE_SQL.' DESC')
                ->orderByDesc('id'),

            default => $query
                ->orderByDesc('is_best_selling')
                ->orderBy('sort_order')
                ->orderByDesc('id'),
        };
    }
}
