<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2">
            Reset password
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Enter your email to receive a reset link
        </p>
    </div>

    <!-- Info Box -->
    <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-xl">
        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
            We'll send you an email with instructions to reset your password. Please check your inbox and spam folder.
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
                Email address
            </label>
            <input id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}"
                required 
                autofocus
                placeholder="name@company.com"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Send reset link
        </button>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center">
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
        <span class="px-4 text-xs text-slate-400 dark:text-slate-500 font-medium">OR</span>
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
    </div>

    <!-- Back to Login -->
    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
        Remember your password?
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
            Back to sign in
        </a>
    </p>
</x-guest-layout>