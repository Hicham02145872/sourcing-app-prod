<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class LoginAfterVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function a_verified_user_can_log_in_after_logout(): void
    {
        // Arrange: Create a user and mark their email as verified
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Ensure password is hashed
        ]);

        // Act 1: Log in the user
        $loginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        // Assert 1: User is logged in
        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        // Act 2: Log out the user
        $logoutResponse = $this->actingAs($user)->post('/logout', ['_token' => csrf_token()]);

        // Assert 2: User is logged out
        $logoutResponse->assertRedirect('/');
        $this->assertGuest();

        // Act 3: Attempt to log in again with the same credentials
        $reloginResponse = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

        // Assert 3: User can log in again
        $reloginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
