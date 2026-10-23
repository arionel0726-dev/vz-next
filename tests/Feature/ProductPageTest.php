<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Feature;
use App\Models\FeatureType;
use App\Models\FeatureValue;
use App\Models\Product;
use App\Models\ProductFeatureValue;
use App\Models\ProductImage;
use App\Models\ProductRelation;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_published_product_page_opens(): void
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
            'sku' => 'PAGE-SKU-1',
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat'],
            'price' => 200,
            'stock' => 5,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'path' => 'products/ruj-mat/main.jpg',
        ]);

        $response = $this->get('/product/ruj-mat');

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Product/Show')
            ->where('product.id', $product->id)
            ->where('product.slug', 'ruj-mat')
            ->where('product.title', 'Ruj mat')
            ->where('product.brand', 'Nica Beauty')
            ->where('product.category', 'Machiaj')
        );
    }

    public function test_an_unpublished_product_returns_404(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        Product::create([
            'category_id' => $category->id,
            'sku' => 'PAGE-SKU-2',
            'title' => ['ro' => 'Fond de ten'],
            'slug' => ['ro' => 'fond-de-ten'],
            'price' => 300,
            'is_published' => false,
        ]);

        $this->get('/product/fond-de-ten')->assertNotFound();
    }

    public function test_an_unknown_slug_returns_404(): void
    {
        $this->get('/product/this-slug-does-not-exist')->assertNotFound();
    }

    public function test_variants_and_images_are_passed_to_inertia_props(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'PAGE-SKU-3',
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat-variabil'],
            'price' => 240,
            'discount_price' => 180,
            'stock' => 10,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'path' => 'a.jpg',
            'sort_order' => 1,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'path' => 'b.jpg',
            'sort_order' => 2,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'PAGE-SKU-3-RED',
            'title' => ['ro' => 'Roșu'],
            'color_hex_list' => ['#C21807'],
            'price' => 180,
            'stock' => 4,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'PAGE-SKU-3-NUDE',
            'title' => ['ro' => 'Nude'],
            'color_hex_list' => ['#C9947A'],
            'price' => 180,
            'stock' => 0,
        ]);

        $featureType = FeatureType::create(['name' => 'Makeup']);

        $feature = Feature::create([
            'feature_type_id' => $featureType->id,
            'name' => ['ro' => 'Finisaj'],
        ]);

        $featureValue = FeatureValue::create([
            'feature_id' => $feature->id,
            'name' => ['ro' => 'Mat'],
        ]);

        ProductFeatureValue::create([
            'product_id' => $product->id,
            'feature_value_id' => $featureValue->id,
        ]);

        $relatedProduct = Product::create([
            'category_id' => $category->id,
            'sku' => 'PAGE-SKU-4',
            'title' => ['ro' => 'Paletă farduri'],
            'slug' => ['ro' => 'paleta-farduri'],
            'price' => 300,
            'stock' => 5,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $relatedProduct->id,
            'path' => 'related.jpg',
        ]);

        ProductRelation::create([
            'product_id' => $product->id,
            'related_product_id' => $relatedProduct->id,
            'type' => 'related',
        ]);

        $response = $this->get('/product/ruj-mat-variabil');

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Product/Show')
            ->where('product.gallery', ['a.jpg', 'b.jpg'])
            ->where('product.hasVariants', true)
            ->has('product.variants', 2)
            ->where('product.variants.0.price', 180)
            ->where('product.variants.1.inStock', false)
            ->has('product.featureValues', 1)
            ->where('product.featureValues.0.feature', 'Finisaj')
            ->where('product.featureValues.0.values', ['Mat'])
            ->has('product.relatedProducts', 1)
            ->where('product.relatedProducts.0.slug', 'paleta-farduri')
        );
    }
}
