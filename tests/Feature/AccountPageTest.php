<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AccountPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_redirected_to_login_from_the_account_page(): void
    {
        $this->get('/account')->assertRedirect('/login');
    }

    public function test_a_guest_is_redirected_to_login_from_the_orders_page(): void
    {
        $this->get('/account/orders')->assertRedirect('/login');
    }

    public function test_an_authenticated_user_can_view_the_account_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Ana Popescu',
            'phone' => '060123456',
        ]);

        $this->actingAs($user)
            ->get('/account')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Account/Index')
                ->where('user.name', 'Ana Popescu')
                ->where('user.email', $user->email)
                ->where('user.phone', '060123456')
            );
    }

    public function test_an_authenticated_user_can_view_the_orders_placeholder_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/account/orders')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Account/Orders')
            );
    }

    public function test_a_user_can_update_their_name_email_and_phone(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'phone' => null,
        ]);

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name' => 'New Name',
                'email' => 'new@example.com',
                'phone' => '069111222',
            ])
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('New Name', $user->name);
        $this->assertSame('new@example.com', $user->email);
        $this->assertSame('069111222', $user->phone);
    }

    public function test_profile_update_requires_a_valid_email(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/user/profile-information', [
                'name' => 'New Name',
                'email' => 'not-an-email',
                'phone' => null,
            ])
            ->assertSessionHasErrors('email', null, 'updateProfileInformation');
    }
}
