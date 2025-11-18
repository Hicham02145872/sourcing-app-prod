{{-- Notifications List --}}
<div x-show="!loading && !error && notifications && notifications.length > 0" 
     class="max-h-[480px] overflow-y-auto custom-scrollbar">
    <template x-for="notification in notifications" :key="notification.id">
        <div @click="markAsRead(notification.id)" 
             class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-all duration-200 cursor-pointer group"
             :class="{ 'bg-blue-50/30 dark:bg-blue-900/5': !notification.read_at }">
            <div class="flex gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                             :class="{
                                'bg-blue-100 dark:bg-blue-900/30': notification.type === 'info',
                                'bg-green-100 dark:bg-green-900/30': notification.type === 'success',
                                'bg-yellow-100 dark:bg-yellow-900/30': notification.type === 'warning',
                                'bg-red-100 dark:bg-red-900/30': notification.type === 'error'
                             }">
                            <svg class="h-5 w-5" 
                                 :class="{
                                    'text-blue-600 dark:text-blue-400': notification.type === 'info',
                                    'text-green-600 dark:text-green-400': notification.type === 'success',
                                    'text-yellow-600 dark:text-yellow-400': notification.type === 'warning',
                                    'text-red-600 dark:text-red-400': notification.type === 'error'
                                 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="notification.type === 'info'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path x-show="notification.type === 'success'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path x-show="notification.type === 'warning'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                <path x-show="notification.type === 'error'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span x-show="!notification.read_at" 
                              class="absolute -top-1 -right-1 w-3 h-3 bg-blue-600 dark:bg-blue-500 rounded-full border-2 border-white dark:border-gray-800 animate-pulse"></span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors" 
                            x-text="notification.title"></h4>
                        <button @click.stop="deleteNotification(notification.id)" 
                                class="opacity-0 group-hover:opacity-100 p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2" 
                       x-text="notification.body"></p>
                    <div class="flex items-center gap-2 text-xs text-blue-600 dark:text-blue-400">
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
    background: #3b82f6;
    border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #2563eb;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #60a5fa;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #3b82f6;
}
</style>