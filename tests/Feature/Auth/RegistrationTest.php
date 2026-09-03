<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private array $validData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_country_iso' => 'MA',
        'phone' => '612345678',
        'password' => 'Password1!x',
        'password_confirmation' => 'Password1!x',
        'g-recaptcha-response' => 'test-token',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.captcha.secret', '');
    }

    private function postRegistration(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->postJson(route('register', ['locale' => 'eng']), array_merge($this->validData, $overrides));
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register', ['locale' => 'eng']));
        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->postRegistration(['email' => 'new@example.com']);
        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', ['locale' => 'eng']));
    }

    public function test_name_with_http_is_rejected(): void
    {
        $response = $this->postRegistration([
            'name' => 'Visit http://spam.com for BTC',
            'email' => 'bot1@example.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_name_with_graph_org_is_rejected(): void
    {
        $response = $this->postRegistration([
            'name' => '+2.84567197 BTC GET graph.org/abc123',
            'email' => 'bot2@example.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_name_with_tinyurl_is_rejected(): void
    {
        $response = $this->postRegistration([
            'name' => 'Get free money tinyurl.com/xyz',
            'email' => 'bot3@example.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_name_with_btc_keyword_is_rejected(): void
    {
        $response = $this->postRegistration([
            'name' => 'Claim your BTC bonus now',
            'email' => 'bot4@example.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_name_with_special_characters_is_rejected(): void
    {
        $response = $this->postRegistration([
            'name' => 'John Doe <script>alert(1)</script>',
            'email' => 'bot5@example.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_valid_unicode_name_is_accepted(): void
    {
        $response = $this->postRegistration([
            'name' => 'José María Ñoño-Clément',
            'email' => 'unicode@example.com',
        ]);
        $response->assertRedirect(route('verification.notice', ['locale' => 'eng']));
        $this->assertDatabaseHas('users', ['email' => 'unicode@example.com']);
    }

    public function test_honeypot_field_rejects_bot(): void
    {
        $response = $this->postRegistration([
            'name' => 'Real User',
            'email' => 'real@example.com',
            'website' => 'https://spam-bot.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('website');
    }

    public function test_empty_website_field_is_accepted(): void
    {
        $response = $this->postRegistration([
            'name' => 'Legit User',
            'email' => 'legit@example.com',
            'website' => '',
        ]);
        $response->assertRedirect(route('verification.notice', ['locale' => 'eng']));
    }

    public function test_russian_spam_name_is_rejected(): void
    {
        $response = $this->postRegistration([
            'name' => 'Получите бесплатный приз подаrok.com',
            'email' => 'russian@example.com',
        ]);
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }
}
