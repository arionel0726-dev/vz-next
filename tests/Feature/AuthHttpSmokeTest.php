<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Minimal smoke tests: the login and account pages respond and
 * render the right component. Detailed behavior is covered in
 * AuthenticationTest and AccountPageTest.
 */
class AuthHttpSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_responds(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));
    }

    public function test_account_page_responds_for_an_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/account')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Account/Index'));
    }
}
