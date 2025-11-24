{{-- components/layout/notification-list.blade.php --}}
<div x-show="!loading && !error && notifications && notifications.length > 0" 
     class="max-h-[480px] overflow-y-auto custom-scrollbar">
    <template x-for="notification in notifications" :key="notification.id">
        <div @click="markAsRead(notification.id)" 
             class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-all duration-200 cursor-pointer group"
             :class="{ 'bg-[#EF7722]/5 dark:bg-[#EF7722]/10': !notification.read_at }">
            <div class="flex gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200 shadow-sm border border-[#EBEBEB] dark:border-slate-600"
                             :class="{
                                'bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20': notification.type === 'info',
                                'bg-[#10b981]/10 dark:bg-[#10b981]/20': notification.type === 'success',
                                'bg-[#FAA533]/10 dark:bg-[#FAA533]/20': notification.type === 'warning',
                                'bg-red-100 dark:bg-red-900/20': notification.type === 'error'
                             }">
                                                         <svg class="h-5 w-5"
                                                             :class="{
                                                                'text-[#0BA6DF] dark:text-[#0BA6DF]': notification.type === 'info',
                                                                'text-[#10b981] dark:text-[#10b981]': notification.type === 'success',
                                                                'text-[#FAA533] dark:text-[#FAA533]': notification.type === 'warning',
                                                                'text-red-600 dark:text-red-400': notification.type === 'error'
                                                             }"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(notification.type)">
                                                        </svg>                        </div>
                        <span x-show="!notification.read_at" 
                              class="absolute -top-1 -right-1 w-3 h-3 bg-[#EF7722] dark:bg-[#FAA533] rounded-full border-2 border-white dark:border-slate-800 animate-pulse"></span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-[#EF7722] dark:group-hover:text-[#FAA533] transition-colors" 
                            x-text="notification.title"></h4>
                        <button @click.stop="deleteNotification(notification.id)" 
                                class="opacity-0 group-hover:opacity-100 p-1 text-slate-400 hover:text-red-600 dark:hover:text-red-400 rounded transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-2 line-clamp-2" 
                       x-text="notification.body"></p>
                    <div class="flex items-center gap-2 text-xs text-[#EF7722] dark:text-[#FAA533] font-medium">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-text="formatDate(notification.created_at)"></span>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #EF7722;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #FAA533;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #FAA533;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #EF7722;
}
</style>