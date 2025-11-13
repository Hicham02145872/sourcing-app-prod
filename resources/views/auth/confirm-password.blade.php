<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2 text-center">
            Confirm password
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-center">
            Please verify your identity to continue
        </p>
    </div>

    <!-- Security Notice -->
    <div class="mb-8 p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-xl">
        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-center">
            This is a secure area. Please confirm your password before accessing this section.
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

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
                autofocus
                class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Confirm
        </button>
    </form>

    <!-- Divider -->
    <div class="my-8 flex items-center">
        <div class="flex-1 border-t border-slate-200 dark:border-slate-700"></div>
    </div>

    <!-- Cancel -->
    <p class="text-center text-sm text-slate-600 dark:text-slate-400">
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
            Cancel
        </a>
    </p>
</x-guest-layout>