<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2">
            {{ __('Reset password') }}
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            {{ __('Enter your email to receive a reset link') }}
        </p>
    </div>

    <!-- Info Box -->
    <div class="mb-8 p-4 bg-[#EF7722]/10 border border-[#EF7722]/20 rounded-xl">
        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
            {{ __('We\'ll send you an email with instructions to reset your password. Please check your inbox and spam folder.') }}
        </p>
    </div>

    <!-- Status Message -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

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
                autofocus
                placeholder="{{ __('name@company.com') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2">
            {{ __('Send reset link') }}
        </button>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center">
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
        <span class="px-4 text-xs text-slate-400 dark:text-slate-500 font-medium">{{ __('OR') }}</span>
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
    </div>

    <!-- Back to Login -->
    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
        {{ __('Remember your password?') }}
        <a href="{{ route('login') }}" class="font-medium text-[#EF7722] hover:text-[#FAA533] transition-colors">
            {{ __('Back to sign in') }}
        </a>
    </p>
</x-guest-layout>