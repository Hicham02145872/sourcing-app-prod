{{-- components/layout/notification-header.blade.php --}}
<div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">{{ __('Notifications') }}</h3>
            <template x-if="unreadCount > 0">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EF7722]/10 text-[#EF7722] dark:bg-[#EF7722]/20 tracking-wider uppercase">
                    <span x-text="unreadCount"></span> {{ __('new') }}
                </span>
            </template>
        </div>
        <button @click="markAllAsRead" 
                x-show="unreadCount > 0"
                class="text-xs font-semibold text-slate-500 hover:text-[#EF7722] transition-colors duration-200 focus:outline-none flex items-center gap-1.5 group">
            <svg class="w-4 h-4 text-slate-400 group-hover:text-[#EF7722] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ __('Mark all as read') }}
        </button>
    </div>
</div>
