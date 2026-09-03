<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2">
            {{ __('Create your account') }}
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            {{ __('Get started with SmartSource today') }}
        </p>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerForm">
        @csrf

        <!-- Honeypot (hidden from humans, visible to bots) -->
        <div style="position:absolute;left:-9999px;" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
        </div>

        <!-- reCAPTCHA v3 token -->
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">

        <!-- Full Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Full name') }}
            </label>
            <input id="name"
                type="text" 
                name="name" 
                value="{{ old('name') }}"
                required 
                autofocus 
                autocomplete="name"
                placeholder="{{ __('John Doe') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Email address') }}
            </label>
            <input id="email"
                type="email" 
                name="email" 
                value="{{ old('email') }}"
                required 
                autocomplete="username"
                placeholder="{{ __('name@company.com') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone (country code + national number) -->
        <div>
            <span class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Phone number') }}
            </span>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-stretch">
                <div class="sm:w-[min(100%,14rem)] shrink-0">
                    <label for="phone_country_iso" class="sr-only">{{ __('Country code') }}</label>
                    <select id="phone_country_iso"
                            name="phone_country_iso"
                            required
                            autocomplete="tel-country-code"
                            class="w-full px-3 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all">
                        @foreach($phoneCountries as $row)
                            <option value="{{ $row['iso'] }}" @selected(old('phone_country_iso', $defaultPhoneIso) === $row['iso'])>
                                {{ $row['name'] }} (+{{ $row['dial'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-0 flex-1">
                    <label for="phone" class="sr-only">{{ __('National phone number') }}</label>
                    <input id="phone"
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        required
                        inputmode="tel"
                        autocomplete="tel-national"
                        placeholder="{{ __('Phone national placeholder') }}"
                        class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
                </div>
            </div>
            <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                {{ __('Enter your number without repeating the country code.') }}
            </p>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <x-input-error :messages="$errors->get('phone_country_iso')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Password') }}
            </label>
            <input id="password"
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="{{ __('Create a strong password') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 leading-relaxed" role="note">
                {{ __('Password requirements hint') }}
            </p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Confirm password') }}
            </label>
            <input id="password_confirmation"
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="{{ __('Re-enter your password') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div class="flex items-start pt-1">
            <input type="checkbox" 
                   id="terms" 
                   name="terms" 
                   required
                   class="mt-1 w-4 h-4 text-[#EF7722] border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-0">
            <label for="terms" class="ml-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                {{ __('I agree to the') }} <a href="#" class="text-[#EF7722] hover:text-[#FAA533] font-medium">{{ __('Terms of Service') }}</a> {{ __('and') }} <a href="#" class="text-[#EF7722] hover:text-[#FAA533] font-medium">{{ __('Privacy Policy') }}</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 mt-6">
            {{ __('Create account') }}
        </button>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center">
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
        <span class="px-4 text-xs text-slate-400 dark:text-slate-500 font-medium">{{ __('OR') }}</span>
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
    </div>

    <!-- Login Link -->
    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="font-medium text-[#EF7722] hover:text-[#FAA533] transition-colors">
            {{ __('Sign In') }}
        </a>
    </p>

    {{-- reCAPTCHA v3 --}}
    @if(config('services.captcha.sitekey'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.captcha.sitekey') }}" async defer></script>
    <script>
        (function() {
            var sitekey = @json(config('services.captcha.sitekey'));
            var form = document.getElementById('registerForm');
            if (!form) { return; }

            function logCaptcha(msg) {
                if (window.console) console.log('[reCAPTCHA]', msg);
            }

            var fakeSubmit = false;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (fakeSubmit) { return; }

                logCaptcha('submit intercepted, requesting token...');

                if (typeof grecaptcha === 'undefined') {
                    logCaptcha('ERROR: grecaptcha not loaded yet');
                    // Fallback: still submit so the user is not stuck
                    fakeSubmit = true;
                    form.submit();
                    return;
                }

                grecaptcha.ready(function() {
                    logCaptcha('executing reCAPTCHA v3...');
                    grecaptcha.execute(sitekey, {action: 'register'}).then(function(token) {
                        logCaptcha('token acquired (length=' + token.length + ')');
                        document.getElementById('g-recaptcha-response').value = token;
                        fakeSubmit = true;
                        form.submit();
                    }).catch(function(err) {
                        logCaptcha('ERROR acquiring token: ' + (err && err.message || err));
                        // Fallback so the user is not stuck
                        fakeSubmit = true;
                        form.submit();
                    });
                });
            });
        })();
    </script>
    @else
    <script>
        // reCAPTCHA nicht konfiguriert: nur diagnostischer Log
        (function() {
            if (window.console) console.log('[reCAPTCHA] sitekey not configured - captcha disabled');
        })();
    </script>
    @endif
</x-guest-layout>