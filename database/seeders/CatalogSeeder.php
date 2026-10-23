<?php

namespace Database\Seeders;

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
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * A handful of brands/categories/products for exercising the
     * catalog page locally — not production data, no legacy import.
     */
    public function run(): void
    {
        $skincare = Category::create([
            'title' => ['ro' => 'Îngrijirea pielii', 'ru' => 'Уход за кожей'],
            'slug' => ['ro' => 'ingrijirea-pielii', 'ru' => 'uhod-za-kozhey'],
            'depth_level' => 1,
            'is_top_menu' => true,
        ]);

        $makeup = Category::create([
            'title' => ['ro' => 'Machiaj', 'ru' => 'Макияж'],
            'slug' => ['ro' => 'machiaj', 'ru' => 'makiyazh'],
            'depth_level' => 1,
            'is_top_menu' => true,
        ]);

        $perfume = Category::create([
            'title' => ['ro' => 'Parfumuri', 'ru' => 'Парфюмерия'],
            'slug' => ['ro' => 'parfumuri', 'ru' => 'parfyumeriya'],
            'depth_level' => 1,
        ]);

        $vizajeLab = Brand::create([
            'title' => 'Vizaje Lab',
            'slug' => 'vizaje-lab',
        ]);

        $nicaBeauty = Brand::create([
            'title' => 'Nica Beauty',
            'slug' => 'nica-beauty',
        ]);

        $vizajeParfum = Brand::create([
            'title' => 'Vizaje Parfum',
            'slug' => 'vizaje-parfum',
        ]);

        $faceCream = Product::create([
            'category_id' => $skincare->id,
            'brand_id' => $vizajeLab->id,
            'sku' => 'VN-SKU-0001',
            'title' => ['ro' => 'Cremă hidratantă', 'ru' => 'Увлажняющий крем'],
            'slug' => ['ro' => 'crema-hidratanta', 'ru' => 'uvlazhnyayushchiy-krem'],
            'price' => 420,
            'stock' => 24,
            'is_new' => true,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $faceCream->id,
            'path' => 'products/crema-hidratanta/main.jpg',
        ]);

        $lipstick = Product::create([
            'category_id' => $makeup->id,
            'brand_id' => $nicaBeauty->id,
            'sku' => 'VN-SKU-0002',
            'title' => ['ro' => 'Ruj mat', 'ru' => 'Матовая помада'],
            'slug' => ['ro' => 'ruj-mat', 'ru' => 'matovaya-pomada'],
            'price' => 240,
            'discount_price' => 180,
            'stock' => 15,
            'is_best_selling' => true,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $lipstick->id,
            'path' => 'products/ruj-mat/main.jpg',
            'sort_order' => 1,
        ]);

        ProductImage::create([
            'product_id' => $lipstick->id,
            'path' => 'products/ruj-mat/alt.jpg',
            'sort_order' => 2,
        ]);

        $redVariant = ProductVariant::create([
            'product_id' => $lipstick->id,
            'sku' => 'VN-SKU-0002-RED',
            'title' => ['ro' => 'Roșu clasic', 'ru' => 'Классический красный'],
            'color_hex_list' => ['#C21807'],
            'price' => 180,
            'stock' => 8,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        ProductVariantImage::create([
            'product_variant_id' => $redVariant->id,
            'path' => 'products/ruj-mat/red.jpg',
        ]);

        ProductVariant::create([
            'product_id' => $lipstick->id,
            'sku' => 'VN-SKU-0002-NUDE',
            'title' => ['ro' => 'Nude', 'ru' => 'Нюд'],
            'color_hex_list' => ['#C9947A'],
            'price' => 180,
            'stock' => 0,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Feature values for the product detail page's "Characteristics" block.
        $makeupFeatureType = FeatureType::create(['name' => 'Makeup']);

        $finishFeature = Feature::create([
            'feature_type_id' => $makeupFeatureType->id,
            'name' => ['ro' => 'Finisaj', 'ru' => 'Финиш'],
            'input_type' => 'select',
        ]);

        $matteFinish = FeatureValue::create([
            'feature_id' => $finishFeature->id,
            'name' => ['ro' => 'Mat', 'ru' => 'Матовый'],
        ]);

        $skinTypeFeature = Feature::create([
            'feature_type_id' => $makeupFeatureType->id,
            'name' => ['ro' => 'Tip piele', 'ru' => 'Тип кожи'],
            'input_type' => 'select',
        ]);

        $allSkinTypes = FeatureValue::create([
            'feature_id' => $skinTypeFeature->id,
            'name' => ['ro' => 'Toate tipurile', 'ru' => 'Все типы'],
        ]);

        ProductFeatureValue::create([
            'product_id' => $lipstick->id,
            'feature_value_id' => $matteFinish->id,
        ]);

        ProductFeatureValue::create([
            'product_id' => $lipstick->id,
            'feature_value_id' => $allSkinTypes->id,
        ]);

        $perfumeProduct = Product::create([
            'category_id' => $perfume->id,
            'brand_id' => $vizajeParfum->id,
            'sku' => 'VN-SKU-0003',
            'title' => ['ro' => 'Apă de parfum 50ml', 'ru' => 'Парфюмерная вода 50мл'],
            'slug' => ['ro' => 'apa-de-parfum-50ml', 'ru' => 'parfyumernaya-voda-50ml'],
            'price' => 890,
            'stock' => 0,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $perfumeProduct->id,
            'path' => 'products/apa-de-parfum-50ml/main.jpg',
        ]);

        $bodyLotion = Product::create([
            'category_id' => $skincare->id,
            'brand_id' => $vizajeLab->id,
            'sku' => 'VN-SKU-0004',
            'title' => ['ro' => 'Loțiune de corp', 'ru' => 'Лосьон для тела'],
            'slug' => ['ro' => 'lotiune-de-corp', 'ru' => 'losyon-dlya-tela'],
            'price' => 150,
            'stock' => 30,
            'is_new' => true,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $bodyLotion->id,
            'path' => 'products/lotiune-de-corp/main.jpg',
        ]);

        $vitaminSerum = Product::create([
            'category_id' => $skincare->id,
            'brand_id' => $vizajeLab->id,
            'sku' => 'VN-SKU-0006',
            'title' => ['ro' => 'Ser cu vitamina C', 'ru' => 'Сыворотка с витамином C'],
            'slug' => ['ro' => 'ser-vitamina-c', 'ru' => 'syvorotka-vitamin-c'],
            'price' => 340,
            'stock' => 18,
            'is_new' => true,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $vitaminSerum->id,
            'path' => 'products/ser-vitamina-c/main.jpg',
        ]);

        $eyeshadow = Product::create([
            'category_id' => $makeup->id,
            'brand_id' => $nicaBeauty->id,
            'sku' => 'VN-SKU-0007',
            'title' => ['ro' => 'Paletă farduri', 'ru' => 'Палетка теней'],
            'slug' => ['ro' => 'paleta-farduri', 'ru' => 'paletka-teney'],
            'price' => 300,
            'discount_price' => 240,
            'stock' => 11,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $eyeshadow->id,
            'path' => 'products/paleta-farduri/main.jpg',
        ]);

        ProductFeatureValue::create([
            'product_id' => $eyeshadow->id,
            'feature_value_id' => $matteFinish->id,
        ]);

        $cologne = Product::create([
            'category_id' => $perfume->id,
            'brand_id' => $vizajeParfum->id,
            'sku' => 'VN-SKU-0008',
            'title' => ['ro' => 'Apă de colonie bărbați', 'ru' => 'Мужской одеколон'],
            'slug' => ['ro' => 'apa-de-colonie-barbati', 'ru' => 'muzhskoy-odekolon'],
            'price' => 650,
            'stock' => 0,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $cologne->id,
            'path' => 'products/apa-de-colonie-barbati/main.jpg',
        ]);

        $handCream = Product::create([
            'category_id' => $skincare->id,
            'brand_id' => $vizajeLab->id,
            'sku' => 'VN-SKU-0009',
            'title' => ['ro' => 'Cremă de mâini', 'ru' => 'Крем для рук'],
            'slug' => ['ro' => 'crema-de-maini', 'ru' => 'krem-dlya-ruk'],
            'price' => 95,
            'stock' => 40,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $handCream->id,
            'path' => 'products/crema-de-maini/main.jpg',
        ]);

        $settingSpray = Product::create([
            'category_id' => $makeup->id,
            'brand_id' => $nicaBeauty->id,
            'sku' => 'VN-SKU-0010',
            'title' => ['ro' => 'Spray de fixare', 'ru' => 'Фиксирующий спрей'],
            'slug' => ['ro' => 'spray-de-fixare', 'ru' => 'fiksiruyushchiy-sprey'],
            'price' => 210,
            'stock' => 20,
            'is_new' => true,
            'is_published' => true,
        ]);

        ProductImage::create([
            'product_id' => $settingSpray->id,
            'path' => 'products/spray-de-fixare/main.jpg',
        ]);

        // Related products for the product detail page's "You may also
        // like" block.
        ProductRelation::create([
            'product_id' => $lipstick->id,
            'related_product_id' => $eyeshadow->id,
            'type' => 'related',
        ]);

        ProductRelation::create([
            'product_id' => $lipstick->id,
            'related_product_id' => $settingSpray->id,
            'type' => 'related',
        ]);

        ProductRelation::create([
            'product_id' => $faceCream->id,
            'related_product_id' => $vitaminSerum->id,
            'type' => 'related',
        ]);

        ProductRelation::create([
            'product_id' => $faceCream->id,
            'related_product_id' => $handCream->id,
            'type' => 'related',
        ]);

        // Draft product, kept unpublished to exercise the catalog's
        // is_published filter.
        Product::create([
            'category_id' => $makeup->id,
            'brand_id' => $nicaBeauty->id,
            'sku' => 'VN-SKU-0005',
            'title' => ['ro' => 'Fond de ten (draft)', 'ru' => 'Тональный крем (черновик)'],
            'slug' => ['ro' => 'fond-de-ten-draft', 'ru' => 'tonalnyy-krem-draft'],
            'price' => 310,
            'stock' => 0,
            'is_published' => false,
        ]);
    }
}
