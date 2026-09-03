<?php

namespace App\Http\Requests\Auth;

use App\Rules\RecaptchaRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'\-]+$/u', function ($attribute, $value, $fail) {
                $lower = mb_strtolower($value);

                $urlPatterns = ['http', 'www.', 'tinyurl', '.org', '.ru', '.com/', '.net', '.io', 'graph.org', 'bit.ly', 't.co', 'rb.gy', 'cutt.ly', 'shorturl'];
                foreach ($urlPatterns as $pattern) {
                    if (str_contains($lower, $pattern)) {
                        $fail(__('validation.name_no_urls'));
                        return;
                    }
                }

                $spamKeywords = ['btc', 'coinbase', 'transfer', 'crypto', 'prize', 'подарок', 'приз', 'бесплатно', 'заработай', 'выиграй', 'uberant', 'claim', 'bonus', 'investment', 'forex', 'binary', 'trading signal'];
                foreach ($spamKeywords as $keyword) {
                    if (str_contains($lower, $keyword)) {
                        $fail(__('validation.name_no_spam'));
                        return;
                    }
                }
            }],
            'website' => ['max:0'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone_country_iso' => ['required', 'string', 'size:2', \Illuminate\Validation\Rule::in(array_keys(config('phone_dial_codes')))],
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()->min(8)],
            'g-recaptcha-response' => config('services.captcha.secret') ? ['required', new RecaptchaRule] : [],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('Full name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('Full name'), 'max' => 100]),
            'name.regex' => __('validation.name_invalid_chars'),
            'email.required' => __('validation.required', ['attribute' => __('Email address')]),
            'email.email' => __('validation.email'),
            'email.unique' => __('validation.unique', ['attribute' => __('Email address')]),
            'phone.required' => __('validation.required', ['attribute' => __('Phone number')]),
            'password.required' => __('validation.required', ['attribute' => __('Password')]),
            'password.confirmed' => __('validation.confirmed', ['attribute' => __('Password')]),
            'g-recaptcha-response.required' => __('validation.captcha_required'),
        ];
    }
}

