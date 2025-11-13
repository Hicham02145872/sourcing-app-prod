<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2">
            Welcome back
        </h1>
        <p class="text-slate-500 dark:text-slate-400">
            Sign in to continue to your account
        </p>
    </div>

    <!-- Status Message -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                autocomplete="username"
                placeholder="name@company.com"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Password
            </label>
            <input id="password"
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="Enter your password"
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer group">
                <input type="checkbox"
                       name="remember"
                       class="w-4 h-4 text-blue-600 border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 transition-all">
                <span class="ml-2 text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">
                    Remember me
                </span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Sign in
        </button>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center">
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
        <span class="px-4 text-xs text-slate-400 dark:text-slate-500 font-medium">OR</span>
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
    </div>

    <!-- Register Link -->
    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
            Create account
        </a>
    </p>
</x-guest-layout>