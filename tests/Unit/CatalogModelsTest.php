<?php

namespace Tests\Unit;

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
use App\Models\ProductVariantImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_casts_json_fields_to_arrays(): void
    {
        $brand = Brand::create([
            'title' => 'Vizaje Lab',
            'slug' => 'vizaje-lab',
            'h1_title' => ['ro' => 'Vizaje Lab', 'ru' => 'Vizaje Lab'],
            'is_published' => 1,
            'sort_order' => '5',
        ]);

        $this->assertIsArray($brand->h1_title);
        $this->assertSame('Vizaje Lab', $brand->h1_title['ro']);
        $this->assertTrue($brand->is_published);
        $this->assertSame(5, $brand->sort_order);
    }

    public function test_category_has_parent_and_children_relationship(): void
    {
        $parent = Category::create([
            'title' => ['ro' => 'Cosmetice', 'ru' => 'Косметика'],
            'slug' => ['ro' => 'cosmetice', 'ru' => 'kosmetika'],
        ]);

        $child = Category::create([
            'parent_id' => $parent->id,
            'title' => ['ro' => 'Creme', 'ru' => 'Кремы'],
            'slug' => ['ro' => 'creme', 'ru' => 'kremy'],
            'depth_level' => 2,
        ]);

        $this->assertTrue($child->parent->is($parent));
        $this->assertTrue($parent->children->first()->is($child));
        $this->assertIsArray($child->title);
        $this->assertSame('Кремы', $child->title['ru']);
        $this->assertSame(2, $child->depth_level);
    }

    public function test_product_belongs_to_category_and_brand(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Parfumuri'],
            'slug' => ['ro' => 'parfumuri'],
        ]);

        $brand = Brand::create([
            'title' => 'Vizaje Parfum',
            'slug' => 'vizaje-parfum',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'SKU-001',
            'title' => ['ro' => 'Apa de parfum', 'ru' => 'Парфюмерная вода'],
            'slug' => ['ro' => 'apa-de-parfum'],
            'price' => '420.50',
            'discount_price' => '399.00',
            'stock' => '12',
            'is_new' => 1,
        ]);

        $this->assertTrue($product->category->is($category));
        $this->assertTrue($product->brand->is($brand));
        $this->assertIsArray($product->title);
        $this->assertSame('Парфюмерная вода', $product->title['ru']);
        $this->assertSame('420.50', $product->price);
        $this->assertSame('399.00', $product->discount_price);
        $this->assertSame(12, $product->stock);
        $this->assertTrue($product->is_new);
    }

    public function test_product_has_many_images_and_variants(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'SKU-002',
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat'],
        ]);

        $image = ProductImage::create([
            'product_id' => $product->id,
            'path' => 'products/ruj-mat/1.jpg',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'SKU-002-RED',
            'title' => ['ro' => 'Roșu'],
            'color_hex_list' => ['#FF0000', '#CC0000'],
            'price' => '180.00',
            'stock' => '3',
        ]);

        $variantImage = ProductVariantImage::create([
            'product_variant_id' => $variant->id,
            'path' => 'products/ruj-mat/red-1.jpg',
        ]);

        $this->assertTrue($product->images->first()->is($image));
        $this->assertTrue($product->variants->first()->is($variant));
        $this->assertTrue($variant->product->is($product));
        $this->assertTrue($variant->images->first()->is($variantImage));
        $this->assertIsArray($variant->color_hex_list);
        $this->assertSame(['#FF0000', '#CC0000'], $variant->color_hex_list);
    }

    public function test_product_has_many_to_many_feature_values(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Îngrijire'],
            'slug' => ['ro' => 'ingrijire'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'SKU-003',
            'title' => ['ro' => 'Crema hidratanta'],
            'slug' => ['ro' => 'crema-hidratanta'],
        ]);

        $featureType = FeatureType::create(['name' => 'Cosmetics']);

        $feature = Feature::create([
            'feature_type_id' => $featureType->id,
            'name' => ['ro' => 'Tip piele', 'ru' => 'Тип кожи'],
        ]);

        $featureValue = FeatureValue::create([
            'feature_id' => $feature->id,
            'name' => ['ro' => 'Uscata', 'ru' => 'Сухая'],
        ]);

        ProductFeatureValue::create([
            'product_id' => $product->id,
            'feature_value_id' => $featureValue->id,
        ]);

        $this->assertTrue($product->featureValues->first()->is($featureValue));
        $this->assertTrue($featureValue->products->first()->is($product));
        $this->assertTrue($featureValue->feature->is($feature));
        $this->assertTrue($feature->featureType->is($featureType));
    }

    public function test_product_relations_link_two_products(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Corp'],
            'slug' => ['ro' => 'corp'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'SKU-004',
            'title' => ['ro' => 'Lotiune de corp'],
            'slug' => ['ro' => 'lotiune-de-corp'],
        ]);

        $relatedProduct = Product::create([
            'category_id' => $category->id,
            'sku' => 'SKU-005',
            'title' => ['ro' => 'Gel de dus'],
            'slug' => ['ro' => 'gel-de-dus'],
        ]);

        ProductRelation::create([
            'product_id' => $product->id,
            'related_product_id' => $relatedProduct->id,
            'type' => 'related',
        ]);

        $this->assertTrue($product->relatedProducts->first()->is($relatedProduct));
        $this->assertSame('related', $product->relatedProducts->first()->pivot->type);
    }
}
