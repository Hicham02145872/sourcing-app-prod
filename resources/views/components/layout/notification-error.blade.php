{{-- components/layout/notification-error.blade.php --}}
<div x-show="error && !loading" class="p-8">
    <div class="flex flex-col items-center justify-center gap-4 text-center">
        <div class="p-4 bg-red-100 dark:bg-red-900/20 rounded-full">
            <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">{{ __('Loading Error') }}</h4>
            <p class="text-xs text-slate-600 dark:text-slate-400" x-text="error"></p>
        </div>
        <button @click="fetchNotifications" 
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 shadow-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            {{ __('Retry') }}
        </button>
    </div>
</div>