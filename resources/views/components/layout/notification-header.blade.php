{{-- components/layout/notification-header.blade.php --}}
<div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 bg-gradient-to-r from-[#EF7722]/5 to-[#FAA533]/5 dark:from-[#EF7722]/10 dark:to-[#FAA533]/10">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-[#EF7722] rounded-lg shadow-sm">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Notifications') }}</h3>
                <p class="text-xs text-[#EF7722] dark:text-[#FAA533] font-medium" x-show="unreadCount > 0">
                    <span x-text="unreadCount" class="font-bold"></span> {{ __('new') }}<span x-show="unreadCount > 1">{{ __('s') }}</span>
                </p>
            </div>
        </div>
        <button @click="markAllAsRead" 
                x-show="unreadCount > 0"
                class="px-3 py-1.5 text-xs font-semibold text-[#EF7722] hover:text-white dark:text-[#FAA533] dark:hover:text-white bg-white hover:bg-[#EF7722] dark:bg-slate-700 dark:hover:bg-[#EF7722] border border-[#EF7722] dark:border-[#FAA533] rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-1 shadow-sm">
            {{ __('Mark All as Read') }}
        </button>
    </div>
</div>