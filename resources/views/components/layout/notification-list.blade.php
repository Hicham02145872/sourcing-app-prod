<div x-show="!loading && !error && notifications && notifications.length > 0" 
     class="max-h-[520px] overflow-y-auto custom-scrollbar bg-white dark:bg-slate-900">
    <template x-for="notification in notifications" :key="notification.id">
        <div @click="markAsRead(notification.id)" 
             class="relative px-6 py-5 border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-all duration-200 cursor-pointer group flex items-start gap-4">
            
            {{-- Status Pillar (Enterprise Indicator) --}}
            <div x-show="!notification.read_at" 
                 class="absolute left-0 top-0 bottom-0 w-1 bg-[#EF7722]"></div>

            {{-- Icon Section --}}
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center border transition-all duration-200"
                     :class="{
                        'bg-blue-50 border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20': notification.type === 'info',
                        'bg-emerald-50 border-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:border-emerald-500/20': notification.type === 'success',
                        'bg-amber-50 border-amber-100 text-amber-600 dark:bg-amber-500/10 dark:border-amber-500/20': notification.type === 'warning',
                        'bg-rose-50 border-rose-100 text-rose-600 dark:bg-rose-500/10 dark:border-rose-500/20': notification.type === 'error',
                        'bg-slate-50 border-slate-100 text-slate-600 dark:bg-slate-500/10 dark:border-slate-500/20': !notification.type || notification.type === 'default'
                     }">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(notification.type)"></svg>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2 mb-0.5">
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-[#EF7722] transition-colors" 
                        x-text="notification.title"></h4>
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 whitespace-nowrap" 
                          x-text="formatDate(notification.created_at)"></span>
                </div>
                
                <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400 line-clamp-2" 
                   x-text="notification.body"></p>

                {{-- FSB tracking number (when status paid → FSB generated) --}}
                <template x-if="notification.type === 'fsb_tracking_generated' && notification.tracking_number">
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">{{ __('Numéro de suivi') }}:</span>
                        <code class="text-xs font-bold text-[#EF7722] bg-[#EF7722]/10 px-2 py-0.5 rounded border border-[#EF7722]/30" x-text="notification.tracking_number"></code>
                    </div>
                </template>

                {{-- Action Area (Delete) --}}
                <div class="flex items-center justify-end mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button @click.stop="deleteNotification(notification.id)" 
                            class="p-1 text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 rounded-md hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
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
