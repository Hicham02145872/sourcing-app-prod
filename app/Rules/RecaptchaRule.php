<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RecaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.captcha.secret');
        $sitekey = config('services.captcha.sitekey');

        // La clé reCAPTCHA n'est pas configurée : ne pas bloquer l'inscription.
        if (empty($secret)) {
            Log::debug('[reCAPTCHA] skipped: no NOCAPTCHA_SECRET configured');

            return;
        }

        // Si le captcha a déjà été validé avec succès lors d'une soumission
        // précédente (ex: erreur sur un autre champ), ne pas re-vérifier.
        // Évite le problème de token consommé réutilisé => invalid-input-response.
        if (Session::get('recaptcha_verified') === true) {
            Log::debug('[reCAPTCHA] already verified in this session, skipping');

            return;
        }

        if (empty($value)) {
            Log::warning('[reCAPTCHA] empty token received on registration');

            $fail(__('validation.captcha_failed'));

            return;
        }

        // Diagnostic essential (visible en production) : le couple clé/secret utilisé
        Log::info('[reCAPTCHA] registering attempt', [
            'sitekey_prefix' => $sitekey ? substr($sitekey, 0, 6).'...' : '(none)',
            'secret_prefix' => substr($secret, 0, 6).'...',
            'token_length' => strlen($value),
            'token_preview' => substr($value, 0, 12).'...'.substr($value, -8),
        ]);

        try {
            $response = Http::timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $value,
            ]);

            $result = $response->json();

            // Logger en niveau warning les infos utiles pour diagnostiquer invalid-input-response
            if (! ($result['success'] ?? false)) {
                Log::warning('[reCAPTCHA] verification failed', [
                    'error_codes' => $result['error-codes'] ?? [],
                    'sitekey_prefix' => $sitekey ? substr($sitekey, 0, 6).'...' : '(none)',
                    'secret_prefix' => substr($secret, 0, 6).'...',
                    'token_length' => strlen($value),
                    'ip' => request()->ip(),
                ]);
            } else {
                // Token valide : le mémoriser pour cette session.
                Session::put('recaptcha_verified', true);

                Log::debug('[reCAPTCHA] verified successfully, marked session', [
                    'score' => $result['score'] ?? null,
                    'action' => $result['action'] ?? null,
                ]);
            }

            Log::debug('[reCAPTCHA] siteverify response received', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'error_codes' => $result['error-codes'] ?? [],
            ]);

            if (! ($result['success'] ?? false)) {
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


