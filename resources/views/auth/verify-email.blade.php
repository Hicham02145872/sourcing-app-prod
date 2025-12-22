<x-guest-layout>
    <!-- Header -->
    <div class="mb-10">
        <div class="w-14 h-14 bg-[#EF7722]/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-7 h-7 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-semibold text-slate-900 dark:text-white mb-2 text-center">
            Verify your email
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-center">
            Check your inbox for the verification link
        </p>
    </div>

    <!-- Info Message -->
    <div class="mb-8 p-4 bg-[#EF7722]/10 border border-[#EF7722]/20 rounded-xl">
        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed text-center">
            We've sent a verification link to your email address. Click the link to activate your account.
        </p>
    </div>

    <!-- Success Status -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-8 p-4 bg-emerald-100 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-900/30 rounded-xl">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">
                    Verification link sent successfully!
                </p>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="space-y-4" 
         x-data="{ countdown: {{ session('status') == 'verification-link-sent' ? 60 : 0 }} }" 
         x-init="if (countdown > 0) { const timer = setInterval(() => { countdown--; if (countdown <= 0) clearInterval(timer); }, 1000); }">
        <!-- Resend Button -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    x-bind:disabled="countdown > 0"
                    class="w-full py-3.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed transform active:scale-[0.98]">
                <span x-show="countdown === 0">{{ __('Resend verification email') }}</span>
                <span x-show="countdown > 0" x-cloak class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Resend available in') }} <span x-text="countdown"></span>s
                </span>
            </button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                {{ __('Log out') }}
            </button>
        </form>
    </div>

    <!-- Help Text -->
    <div class="mt-8 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
        <p class="text-xs text-slate-600 dark:text-slate-400 text-center leading-relaxed">
            <strong>Didn't receive the email?</strong><br/>
            Check your spam folder or click resend above
        </p>
    </div>
</x-guest-layout>