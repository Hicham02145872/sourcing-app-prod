<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class PasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the password is a string and is hashed correctly.
     *
     * @return void
     */
    public function test_password_is_string_and_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $this->assertIsString($user->password);
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
