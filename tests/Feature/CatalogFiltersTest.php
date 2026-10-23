<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CatalogFiltersTest extends TestCase
{
    use RefreshDatabase;

    private Category $skincare;

    private Category $makeup;

    private Brand $vizajeLab;

    private Brand $nicaBeauty;

    protected function setUp(): void
    {
        parent::setUp();

        $this->skincare = Category::create([
            'title' => ['ro' => 'Îngrijire'],
            'slug' => ['ro' => 'ingrijire'],
        ]);

        $this->makeup = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        $this->vizajeLab = Brand::create([
            'title' => 'Vizaje Lab',
            'slug' => 'vizaje-lab',
        ]);

        $this->nicaBeauty = Brand::create([
            'title' => 'Nica Beauty',
            'slug' => 'nica-beauty',
        ]);

        $this->createProduct([
            'category_id' => $this->skincare->id,
            'brand_id' => $this->vizajeLab->id,
            'sku' => 'FILTER-SKU-CREAM',
            'title' => ['ro' => 'Cremă hidratantă'],
            'slug' => ['ro' => 'crema-hidratanta'],
            'price' => 420,
            'stock' => 10,
            'is_new' => true,
            'is_published' => true,
        ], now()->subDays(5));

        $this->createProduct([
            'category_id' => $this->makeup->id,
            'brand_id' => $this->nicaBeauty->id,
            'sku' => 'FILTER-SKU-LIPSTICK',
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat'],
            'price' => 240,
            'discount_price' => 180,
            'stock' => 5,
            'is_best_selling' => true,
            'is_published' => true,
        ], now()->subDays(1));

        $this->createProduct([
            'category_id' => $this->makeup->id,
            'brand_id' => $this->nicaBeauty->id,
            'sku' => 'FILTER-SKU-OOS',
            'title' => ['ro' => 'Fond de ten'],
            'slug' => ['ro' => 'fond-de-ten'],
            'price' => 300,
            'stock' => 0,
            'is_published' => true,
        ], now()->subDays(10));
    }

    /**
     * Product::create() always stamps created_at to "now" (Eloquent
     * overwrites it during the insert regardless of mass-assigned
     * value), so a specific created_at for sort-order tests has to be
     * set after creation with timestamps temporarily disabled.
     */
    private function createProduct(array $attributes, \Illuminate\Support\Carbon $createdAt): Product
    {
        $product = Product::create($attributes);

        $product->timestamps = false;
        $product->created_at = $createdAt;
        $product->save();

        return $product;
    }

    public function test_search_matches_title_sku_and_brand(): void
    {
        $this->get('/catalog?search=ruj')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.slug', 'ruj-mat')
            );

        $this->get('/catalog?search=FILTER-SKU-CREAM')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.slug', 'crema-hidratanta')
            );

        $this->get('/catalog?search=Vizaje+Lab')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.brand', 'Vizaje Lab')
            );
    }

    public function test_filter_by_brand(): void
    {
        $this->get('/catalog?brand='.$this->nicaBeauty->id)
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 2)
                ->where('filters.brand', $this->nicaBeauty->id)
            );
    }

    public function test_filter_by_category(): void
    {
        $this->get('/catalog?category='.$this->skincare->id)
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.category', 'Îngrijire')
            );
    }

    public function test_filter_in_stock_only(): void
    {
        $this->get('/catalog?in_stock=1')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 2)
                ->where('filters.inStock', true)
            );
    }

    public function test_filter_is_new(): void
    {
        $this->get('/catalog?is_new=1')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.slug', 'crema-hidratanta')
            );
    }

    public function test_filter_on_sale(): void
    {
        $this->get('/catalog?on_sale=1')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 1)
                ->where('products.0.slug', 'ruj-mat')
                ->where('products.0.isSale', true)
            );
    }

    public function test_sort_price_asc_orders_by_effective_price(): void
    {
        $this->get('/catalog?sort=price_asc')
            ->assertInertia(fn (Assert $page) => $page
                ->has('products', 3)
                // ruj-mat's effective price is 180 (discounted), lowest of the three.
                ->where('products.0.slug', 'ruj-mat')
                ->where('products.1.slug', 'fond-de-ten')
                ->where('products.2.slug', 'crema-hidratanta')
            );
    }

    public function test_sort_price_desc_orders_by_effective_price(): void
    {
        $this->get('/catalog?sort=price_desc')
            ->assertInertia(fn (Assert $page) => $page
                ->where('products.0.slug', 'crema-hidratanta')
                ->where('products.2.slug', 'ruj-mat')
            );
    }

    public function test_sort_newest_orders_by_created_at(): void
    {
        $this->get('/catalog?sort=newest')
            ->assertInertia(fn (Assert $page) => $page
                ->where('products.0.slug', 'ruj-mat')
                ->where('products.1.slug', 'crema-hidratanta')
                ->where('products.2.slug', 'fond-de-ten')
            );
    }

    public function test_sort_popular_is_the_default_and_favors_best_sellers(): void
    {
        $this->get('/catalog')
            ->assertInertia(fn (Assert $page) => $page
                ->where('filters.sort', 'popular')
                ->where('products.0.slug', 'ruj-mat')
            );
    }

    public function test_pagination_props_reflect_total_matching_products(): void
    {
        $this->get('/catalog')
            ->assertInertia(fn (Assert $page) => $page
                ->where('pagination.total', 3)
                ->where('pagination.currentPage', 1)
                ->where('pagination.lastPage', 1)
            );
    }

    public function test_invalid_sort_value_is_rejected(): void
    {
        $this->get('/catalog?sort=not-a-real-sort')
            ->assertInvalid(['sort']);
    }
}
