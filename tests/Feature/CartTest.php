<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);
    }

    private function makeProduct(array $overrides = []): Product
    {
        static $counter = 0;
        $counter++;

        return Product::create(array_merge([
            'category_id' => $this->category->id,
            'sku' => 'CART-SKU-'.$counter,
            'title' => ['ro' => 'Ruj mat '.$counter],
            'slug' => ['ro' => 'ruj-mat-'.$counter],
            'price' => 200,
            'stock' => 10,
            'is_published' => true,
        ], $overrides));
    }

    public function test_adding_a_product_puts_it_in_the_cart(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertRedirect();

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->component('Cart/Index')
            ->has('items', 1)
            ->where('items.0.product.id', $product->id)
            ->where('items.0.quantity', 2)
            ->where('items.0.unitPrice', 200)
            ->where('items.0.total', 400)
            ->where('totals.itemsCount', 2)
            ->where('totals.total', 400)
        );
    }

    public function test_adding_a_product_variant_puts_it_in_the_cart_with_its_own_price(): void
    {
        $product = $this->makeProduct(['price' => 240]);

        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'sku' => 'CART-SKU-VARIANT-1',
            'title' => ['ro' => 'Roșu'],
            'price' => 180,
            'stock' => 5,
            'is_published' => true,
        ]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'quantity' => 1,
        ])->assertRedirect();

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.variant.id', $variant->id)
            ->where('items.0.variant.label', 'Roșu')
            ->where('items.0.unitPrice', 180)
            ->where('items.0.stock', 5)
        );
    }

    public function test_adding_the_same_line_twice_merges_quantity(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);
        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 3]);

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.quantity', 5)
        );
    }

    public function test_updating_quantity_changes_the_line(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 1]);

        $key = $product->id.':0';

        $this->patch("/cart/items/{$key}", ['quantity' => 4])->assertRedirect();

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->where('items.0.quantity', 4)
            ->where('items.0.total', 800)
        );
    }

    public function test_removing_an_item_deletes_the_line(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 1]);

        $key = $product->id.':0';

        $this->delete("/cart/items/{$key}")->assertRedirect();

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 0)
            ->where('totals.itemsCount', 0)
        );
    }

    public function test_clearing_the_cart_removes_all_lines(): void
    {
        $productOne = $this->makeProduct();
        $productTwo = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $productOne->id, 'quantity' => 1]);
        $this->post('/cart/items', ['product_id' => $productTwo->id, 'quantity' => 1]);

        $this->delete('/cart')->assertRedirect();

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 0)
        );
    }

    public function test_quantity_exceeding_stock_is_rejected(): void
    {
        $product = $this->makeProduct(['stock' => 3]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 4,
        ])->assertInvalid(['quantity']);

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 0)
        );
    }

    public function test_updating_quantity_beyond_stock_is_rejected(): void
    {
        $product = $this->makeProduct(['stock' => 3]);

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);

        $key = $product->id.':0';

        $this->patch("/cart/items/{$key}", ['quantity' => 10])
            ->assertInvalid(['quantity']);

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->where('items.0.quantity', 2)
        );
    }

    public function test_unpublished_product_cannot_be_added(): void
    {
        $product = $this->makeProduct(['is_published' => false]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertInvalid(['product_id']);
    }

    public function test_out_of_stock_product_cannot_be_added(): void
    {
        $product = $this->makeProduct(['stock' => 0]);

        $this->post('/cart/items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->assertInvalid(['quantity']);
    }

    public function test_cart_count_is_shared_on_other_pages(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 3]);

        $this->get('/catalog')->assertInertia(fn (Assert $page) => $page
            ->where('cartCount', 3)
        );
    }

    public function test_guest_cart_persists_across_requests_in_the_same_session(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);

        // Browsing away and back doesn't touch the cart — it should
        // still be there, same session, no auth involved.
        $this->get('/catalog');
        $this->get('/');

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.quantity', 2)
        );
    }

    public function test_a_different_guest_session_does_not_see_another_guests_cart(): void
    {
        $product = $this->makeProduct();

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
        );

        // Simulate a brand new guest (fresh session, no cart data).
        $this->flushSession();

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 0)
        );
    }
}
