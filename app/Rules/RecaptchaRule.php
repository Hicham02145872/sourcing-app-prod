<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.captcha.secret');
        $sitekey = config('services.captcha.sitekey');

        Log::debug('[reCAPTCHA] validate started', [
            'secret_configured' => ! empty($secret),
            'sitekey_configured' => ! empty($sitekey),
            'value_present' => ! empty($value),
            'value_length' => is_string($value) ? strlen($value) : null,
        ]);

        if (empty($secret)) {
            Log::debug('[reCAPTCHA] skipped: no NOCAPTCHA_SECRET configured');

            return;
        }

        if (empty($value)) {
            Log::warning('[reCAPTCHA] empty token received on registration');

            $fail(__('validation.captcha_failed'));

            return;
        }

        try {
            $response = Http::timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            Log::debug('[reCAPTCHA] siteverify response received', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'error_codes' => $result['error-codes'] ?? [],
            ]);

            if (! ($result['success'] ?? false)) {
                Log::warning('[reCAPTCHA] verification failed', [
                    'error_codes' => $result['error-codes'] ?? [],
                    'ip' => request()->ip(),
                ]);

                $fail(__('validation.captcha_failed'));
            }
        } catch (\Throwable $e) {
            Log::error('[reCAPTCHA] exception during siteverify', [
                'message' => $e->getMessage(),
            ]);

            $fail(__('validation.captcha_failed'));
        }
    }
}

