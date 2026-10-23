<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CatalogPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_page_renders_published_products_from_the_database(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        $brand = Brand::create([
            'title' => 'Nica Beauty',
            'slug' => 'nica-beauty',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'TEST-SKU-1',
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat'],
            'price' => 200,
            'discount_price' => 150,
            'stock' => 5,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'path' => 'products/ruj-mat/main.jpg',
        ]);

        $response = $this->get('/catalog');

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Catalog/Index')
            ->has('products', 1)
            ->where('products.0.id', $product->id)
            ->where('products.0.slug', 'ruj-mat')
            ->where('products.0.title', 'Ruj mat')
            ->where('products.0.brand', 'Nica Beauty')
            ->where('products.0.price', 150)
            ->where('products.0.oldPrice', 200)
            ->where('products.0.isSale', true)
        );
    }

    public function test_catalog_page_excludes_unpublished_products(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Parfumuri'],
            'slug' => ['ro' => 'parfumuri'],
        ]);

        Product::create([
            'category_id' => $category->id,
            'sku' => 'TEST-SKU-2',
            'title' => ['ro' => 'Draft produs'],
            'slug' => ['ro' => 'draft-produs'],
            'price' => 100,
            'is_published' => false,
        ]);

        $response = $this->get('/catalog');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Catalog/Index')
            ->has('products', 0)
        );
    }
}
