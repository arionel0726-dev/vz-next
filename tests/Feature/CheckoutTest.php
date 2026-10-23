<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function makeProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'sku' => 'CHK-SKU-'.uniqid(),
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat-'.uniqid()],
            'price' => 150,
            'stock' => 10,
            'is_published' => true,
        ], $overrides));
    }

    private function checkoutPayload(array $overrides = []): array
    {
        return array_merge([
            'contact_name' => 'Ana Popescu',
            'contact_email' => 'ana@example.com',
            'contact_phone' => '069111222',
            'delivery_address' => 'Chisinau, str. Test 1, ap. 2',
            'notes' => 'Leave at the door.',
        ], $overrides);
    }

    public function test_an_unauthenticated_user_is_redirected_to_login_from_checkout(): void
    {
        $this->get('/checkout')->assertRedirect('/login');
    }

    public function test_an_unauthenticated_user_cannot_submit_checkout(): void
    {
        $this->post('/checkout', $this->checkoutPayload())->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_open_checkout(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($user)
            ->get('/checkout')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Checkout/Index')
                ->has('items', 1)
                ->where('contact.email', $user->email)
            );
    }

    public function test_checkout_redirects_to_cart_when_empty(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/checkout')->assertRedirect('/cart');
    }

    public function test_an_order_is_created_from_the_cart(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(['price' => 150, 'stock' => 10]);

        $this->actingAs($user)->post('/cart/items', ['product_id' => $product->id, 'quantity' => 3]);

        $response = $this->actingAs($user)->post('/checkout', $this->checkoutPayload());

        $this->assertDatabaseCount('orders', 1);

        $order = Order::first();
        $response->assertRedirect("/checkout/{$order->id}");

        $this->assertSame($user->id, $order->user_id);
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertEquals(450.0, (float) $order->subtotal);
        $this->assertEquals(450.0, (float) $order->total);
        $this->assertSame('Ana Popescu', $order->contact_name);
        $this->assertSame('Chisinau, str. Test 1, ap. 2', $order->delivery_address);

        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_the_cart_is_cleared_after_order_creation(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $this->actingAs($user)->post('/cart/items', ['product_id' => $product->id, 'quantity' => 1]);
        $this->actingAs($user)->post('/checkout', $this->checkoutPayload());

        $this->actingAs($user)
            ->get('/cart')
            ->assertInertia(fn (Assert $page) => $page->has('items', 0));
    }

    public function test_product_snapshots_are_saved_on_the_order_item(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct([
            'sku' => 'SNAP-SKU-1',
            'title' => ['ro' => 'Ruj mat roz'],
            'price' => 200,
            'discount_price' => 150,
        ]);

        $this->actingAs($user)->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);
        $this->actingAs($user)->post('/checkout', $this->checkoutPayload());

        $order = Order::first();
        $item = $order->items->first();

        $this->assertSame('SNAP-SKU-1', $item->product_sku);
        $this->assertSame('Ruj mat roz', $item->product_name);
        $this->assertEquals(150.0, (float) $item->unit_price);
        $this->assertSame(2, $item->quantity);
        $this->assertEquals(300.0, (float) $item->subtotal);

        // The snapshot must survive the product being renamed later.
        $product->update(['title' => ['ro' => 'Nume schimbat'], 'sku' => 'RENAMED-SKU']);
        $item->refresh();

        $this->assertSame('Ruj mat roz', $item->product_name);
        $this->assertSame('SNAP-SKU-1', $item->product_sku);
    }

    public function test_checkout_is_rejected_when_stock_is_insufficient_at_order_time(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(['stock' => 5]);

        $this->actingAs($user)->post('/cart/items', ['product_id' => $product->id, 'quantity' => 5]);

        // Stock drops to zero after the item was added to the cart but
        // before checkout is submitted (e.g. sold out by another order).
        $product->update(['stock' => 0]);

        $response = $this->actingAs($user)->post('/checkout', $this->checkoutPayload());

        $response->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        // The cart survives a rejected checkout.
        $this->actingAs($user)
            ->get('/cart')
            ->assertInertia(fn (Assert $page) => $page->has('items', 1));
    }

    public function test_order_creation_is_atomic_when_one_item_fails_stock_validation(): void
    {
        $user = User::factory()->create();
        $available = $this->makeProduct(['stock' => 10]);
        $outOfStock = $this->makeProduct(['stock' => 2]);

        $this->actingAs($user)->post('/cart/items', ['product_id' => $available->id, 'quantity' => 1]);
        $this->actingAs($user)->post('/cart/items', ['product_id' => $outOfStock->id, 'quantity' => 2]);

        // Only the second product sells out before checkout runs.
        $outOfStock->update(['stock' => 0]);

        $this->actingAs($user)->post('/checkout', $this->checkoutPayload())
            ->assertSessionHasErrors('cart');

        // Neither the order nor any order item was persisted, and the
        // first product's stock was not decremented either.
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(10, $available->refresh()->stock);
    }

    public function test_stock_is_decremented_after_a_successful_order(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct(['stock' => 10]);

        $this->actingAs($user)->post('/cart/items', ['product_id' => $product->id, 'quantity' => 4]);
        $this->actingAs($user)->post('/checkout', $this->checkoutPayload());

        $this->assertSame(6, $product->refresh()->stock);
    }
}
