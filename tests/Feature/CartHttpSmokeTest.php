<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Minimal smoke test: /cart responds and renders the right page,
 * even with nothing in the cart. Detailed cart behavior is covered
 * in CartTest.
 */
class CartHttpSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_responds_with_the_expected_shape(): void
    {
        $this->get('/cart')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Cart/Index')
                ->has('items')
                ->has('totals')
                ->where('totals.itemsCount', 0)
                ->where('totals.total', 0)
            );
    }
}
