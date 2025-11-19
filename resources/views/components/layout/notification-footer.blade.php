{{-- components/layout/notification-footer.blade.php --}}
<div x-show="!loading && !error && notifications.length > 0" 
     class="px-6 py-3 border-t border-[#EBEBEB] dark:border-slate-700 bg-gradient-to-r from-[#EF7722]/5 to-[#FAA533]/5 dark:from-[#EF7722]/10 dark:to-[#FAA533]/10">
    <div class="flex items-center justify-between">
        <button @click="clearAll" 
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 rounded px-2 py-1">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            {{ __('Clear All') }}
        </button>
        <a href="/notifications" 
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#EF7722] hover:text-[#FAA533] dark:text-[#FAA533] dark:hover:text-[#EF7722] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-1 rounded px-2 py-1">
            {{ __('View All') }}
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>