<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register', ['locale' => 'eng']));

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register', ['locale' => 'eng']), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone_country_iso' => 'MA',
            'phone' => '612345678',
            'password' => 'Password1!x',
            'password_confirmation' => 'Password1!x',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', ['locale' => 'eng']));
    }
}
