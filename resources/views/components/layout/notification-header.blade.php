{{-- Notification Header --}}
<div class="px-6 py-4 border-b border-blue-100 dark:border-blue-900/30 bg-gradient-to-r from-blue-50 to-blue-100/50 dark:from-blue-900/20 dark:to-blue-800/10">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-600 rounded-lg shadow-sm">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100">{{ __('Notifications') }}</h3>
                <p class="text-xs text-blue-600 dark:text-blue-400" x-show="unreadCount > 0">
                    <span x-text="unreadCount"></span> {{ __('new') }}<span x-show="unreadCount > 1">{{ __('s') }}</span>
                </p>
            </div>
        </div>
        <button @click="markAllAsRead" 
                x-show="unreadCount > 0"
                class="px-3 py-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
            {{ __('Mark All as Read') }}
        </button>
    </div>
</div>