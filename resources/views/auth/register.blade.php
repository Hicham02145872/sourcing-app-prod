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
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

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

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Phone number') }}
            </label>
            <input id="phone"
                type="text" 
                name="phone" 
                value="{{ old('phone') }}"
                required 
                autocomplete="tel"
                placeholder="{{ __('+1 (555) 000-0000') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
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
            {{ __('Sign in') }}
        </a>
    </p>
</x-guest-layout>