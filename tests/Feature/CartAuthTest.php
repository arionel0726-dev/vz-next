<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * The cart lives in the session (App\Cart\CartService). Laravel's
 * session regenerate on login carries the existing payload forward,
 * so the guest cart is already "the" cart once authenticated — see
 * App\Listeners\MergeGuestCartOnLogin for why there's no separate
 * merge step to write.
 */
class CartAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_remains_available_before_login(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'AUTH-CART-SKU-1',
            'title' => ['ro' => 'Ruj mat'],
            'slug' => ['ro' => 'ruj-mat'],
            'price' => 200,
            'stock' => 10,
            'is_published' => true,
        ]);

        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.quantity', 2)
        );

        $this->assertGuest();
    }

    public function test_guest_cart_merges_into_the_user_cart_after_login(): void
    {
        $category = Category::create([
            'title' => ['ro' => 'Machiaj'],
            'slug' => ['ro' => 'machiaj'],
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'AUTH-CART-SKU-2',
            'title' => ['ro' => 'Cremă hidratantă'],
            'slug' => ['ro' => 'crema-hidratanta'],
            'price' => 300,
            'stock' => 10,
            'is_published' => true,
        ]);

        $user = User::factory()->create();

        // Add to cart as a guest, before logging in.
        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 3]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/account');

        $this->assertAuthenticatedAs($user);

        // The cart the guest built survives the login transition.
        $this->get('/cart')->assertInertia(fn (Assert $page) => $page
            ->has('items', 1)
            ->where('items.0.quantity', 3)
            ->where('totals.itemsCount', 3)
        );
    }
}
