{{-- components/layout/notification-empty.blade.php --}}
<div x-show="!loading && !error && notifications.length === 0" 
     class="p-12">
    <div class="flex flex-col items-center justify-center gap-4 text-center">
        <div class="p-4 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-full">
            <svg class="h-12 w-12 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
        <div>
            <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ __('No notifications') }}</h4>
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('You are all caught up! No new notifications for now.') }}</p>
        </div>
    </div>
</div>