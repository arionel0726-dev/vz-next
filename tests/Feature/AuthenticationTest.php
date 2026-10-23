<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ana Popescu',
            'email' => 'ana@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/account');

        $this->assertDatabaseHas('users', [
            'name' => 'Ana Popescu',
            'email' => 'ana@example.com',
        ]);

        $this->assertAuthenticated();
    }

    public function test_registration_requires_a_matching_password_confirmation(): void
    {
        $this->post('/register', [
            'name' => 'Ana Popescu',
            'email' => 'ana@example.com',
            'password' => 'password',
            'password_confirmation' => 'something-else',
        ])->assertInvalid(['password']);

        $this->assertGuest();
    }

    public function test_a_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/account');
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_user_cannot_login_with_an_incorrect_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertInvalid(['email']);

        $this->assertGuest();
    }

    public function test_a_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');

        $this->assertGuest();
    }
}
