<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.captcha.secret');

        if (empty($secret)) {
            return;
        }

        try {
            $response = Http::timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            if (! $result['success'] ?? false) {
                $fail(__('validation.captcha_failed'));
            }
        } catch (\Throwable) {
            $fail(__('validation.captcha_failed'));
        }
    }
}
