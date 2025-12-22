{{-- components/layout/notification-footer.blade.php --}}
<div x-show="!loading && !error && notifications.length > 0" 
     class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900">
    <div class="flex items-center justify-between">
        <button @click="clearAll" 
                class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-rose-500 transition-colors duration-200">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            {{ __('Clear All') }}
        </button>
        <a href="/notifications" 
            class="text-xs font-bold text-[#EF7722] hover:text-[#FAA533] transition-colors flex items-center gap-1">
            {{ __('View full activity') }}
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>
</div>
