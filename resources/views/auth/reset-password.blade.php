<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2">
            {{ __('Set new password') }}
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            {{ __('Create a strong password for your account') }}
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email (Read-only) -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('Email address') }}
            </label>
            <input id="email" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}"
                required 
                autofocus 
                autocomplete="username"
                readonly
                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-500 dark:text-slate-400 cursor-not-allowed" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- New Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                {{ __('New password') }}
            </label>
            <input id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="new-password"
                placeholder="{{ __('Enter new password') }}"
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
                placeholder="{{ __('Re-enter new password') }}"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 mt-8">
            {{ __('Reset password') }}
        </button>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center">
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
    </div>

    <!-- Back to Login -->
    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
        <a href="{{ route('login') }}" class="font-medium text-[#EF7722] hover:text-[#FAA533] transition-colors">
            {{ __('Back to sign in') }}
        </a>
    </p>
</x-guest-layout>