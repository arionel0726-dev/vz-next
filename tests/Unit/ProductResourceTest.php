<?php

namespace Tests\Unit;

use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_maps_a_product_to_the_frontend_shape(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Îngrijire', 'ru' => 'Уход'],
            'slug' => ['ro' => 'ingrijire'],
        ]);

        $brand = Brand::create([
            'title' => 'Vizaje Lab',
            'slug' => 'vizaje-lab',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'SKU-RESOURCE-1',
            'title' => ['ro' => 'Cremă', 'ru' => 'Крем'],
            'slug' => ['ro' => 'crema', 'ru' => 'krem'],
            'price' => 300,
            'discount_price' => 240,
            'stock' => 10,
            'is_new' => true,
            'is_best_selling' => true,
        ]);

        ProductImage::create(['product_id' => $product->id, 'path' => 'a.jpg', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $product->id, 'path' => 'b.jpg', 'sort_order' => 2]);

        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-RESOURCE-1-V1',
            'title' => ['ro' => 'Volum mare'],
            'price' => 320,
            'stock' => 4,
        ]);

        $product->load(['brand', 'category', 'images', 'variants']);

        $array = (new ProductResource($product))->resolve();

        $this->assertSame($product->id, $array['id']);
        $this->assertSame('crema', $array['slug']);
        $this->assertSame('Cremă', $array['title']);
        $this->assertSame('Vizaje Lab', $array['brand']);
        $this->assertSame('Îngrijire', $array['category']);
        $this->assertSame('a.jpg', $array['image']);
        $this->assertSame(['a.jpg', 'b.jpg'], $array['gallery']);
        $this->assertSame(240.0, $array['price']);
        $this->assertSame(300.0, $array['oldPrice']);
        $this->assertTrue($array['isSale']);
        $this->assertSame(20, $array['salePercent']);
        $this->assertTrue($array['isNew']);
        $this->assertTrue($array['isHit']);
        $this->assertTrue($array['inStock']);
        $this->assertTrue($array['hasVariants']);
        $this->assertCount(1, $array['variants']);
        $this->assertSame('Volum mare', $array['variants'][0]['label']);
        $this->assertSame(320.0, $array['variants'][0]['price']);
        $this->assertTrue($array['variants'][0]['inStock']);
    }

    public function test_it_reports_no_discount_when_discount_price_is_not_lower(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Parfumuri'],
            'slug' => ['ro' => 'parfumuri'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'SKU-RESOURCE-2',
            'title' => ['ro' => 'Apă de parfum'],
            'slug' => ['ro' => 'apa-de-parfum'],
            'price' => 500,
            'stock' => 0,
        ]);

        $product->load(['brand', 'category', 'images', 'variants']);

        $array = (new ProductResource($product))->resolve();

        $this->assertSame(500.0, $array['price']);
        $this->assertNull($array['oldPrice']);
        $this->assertFalse($array['isSale']);
        $this->assertNull($array['salePercent']);
        $this->assertFalse($array['inStock']);
        $this->assertFalse($array['hasVariants']);
        $this->assertSame([], $array['variants']);
    }
}
