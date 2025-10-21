<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -m-6 p-6 mb-0">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white">
                {{ __('Notifications') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('View and manage your notifications') }}</p>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6" x-data="notificationCenter">
                    {{-- Filters and Search --}}
                    <div class="mb-6 space-y-4">
                        <div class="flex items-center gap-2">
                            <button @click="filter = 'all'" 
                                    :class="filter === 'all' ? 'bg-violet-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600'"
                                    class="flex-1 px-4 py-2 rounded text-sm font-medium transition-colors">
                                {{ __('All') }}
                                <span class="ml-2 px-2 py-0.5 rounded text-xs font-medium"
                                      :class="filter === 'all' ? 'bg-violet-500 text-white' : 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300'"
                                      x-text="notifications.length"></span>
                            </button>
                            <button @click="filter = 'unread'" 
                                    :class="filter === 'unread' ? 'bg-violet-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600'"
                                    class="flex-1 px-4 py-2 rounded text-sm font-medium transition-colors">
                                {{ __('Unread') }}
                                <span class="ml-2 px-2 py-0.5 rounded text-xs font-medium"
                                      :class="filter === 'unread' ? 'bg-violet-500 text-white' : 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300'"
                                      x-text="unreadCount"></span>
                            </button>
                            <button @click="filter = 'read'" 
                                    :class="filter === 'read' ? 'bg-violet-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600'"
                                    class="flex-1 px-4 py-2 rounded text-sm font-medium transition-colors">
                                {{ __('Read') }}
                            </button>
                        </div>

                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   x-model="searchQuery"
                                   placeholder="{{ __('Search notifications...') }}"
                                   class="w-full pl-10 pr-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-sm focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 dark:text-white">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <button @click="markAllAsRead()" 
                                x-show="unreadCount > 0"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded text-white bg-violet-600 hover:bg-violet-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Mark All as Read') }}
                        </button>
                        <button @click="clearAll()"
                                x-show="notifications.length > 0"
                                class="inline-flex items-center gap-2 px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            {{ __('Clear All') }}
                        </button>
                    </div>

                    {{-- Loading Indicator --}}
                    <div x-show="loading && notifications.length === 0" 
                         class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="animate-spin h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ __('Loading notifications...') }}</p>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="error" class="mx-auto my-3 p-3 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded max-w-md">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-red-700 dark:text-red-300" x-text="error"></p>
                        </div>
                    </div>

                    {{-- Notifications List --}}
                    <div class="bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700"
                         x-show="!loading && filteredNotifications.length > 0">
                        <template x-for="(n, index) in filteredNotifications" :key="n.id || index">
                            <div class="notification-item relative group hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                 :class="{ 'bg-violet-50/30 dark:bg-violet-500/10': !n.read_at }">
                                <a :href="n.click_action || '#'" 
                                   @click.prevent="markAsRead(n.id, n.click_action)"
                                   class="flex items-start gap-4 p-4">
                                    
                                    {{-- Icon with Badge --}}
                                    <div class="flex-shrink-0 relative">
                                        <div class="w-10 h-10 rounded flex items-center justify-center border transition-colors"
                                             :class="[
                                                 getNotificationColor(n.type || 'default').bg,
                                                 getNotificationColor(n.type || 'default').text,
                                                 !n.read_at ? getNotificationColor(n.type || 'default').border : 'border-gray-200 dark:border-gray-600'
                                             ]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(n.type || 'default')"></svg>
                                        </div>
                                        <span x-show="!n.read_at" 
                                              class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-violet-600 rounded-full border-2 border-white dark:border-gray-800"></span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0 space-y-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-1" 
                                                :class="{ 'font-bold': !n.read_at }"
                                                x-text="n.title || 'Notification'"></h4>
                                            <span class="flex-shrink-0 text-xs text-gray-500 dark:text-gray-400" 
                                                  x-text="formatDate(n.created_at)"></span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2" 
                                           x-text="n.body || 'No content available'"></p>
                                        
                                        {{-- Tags / Category --}}
                                        <div class="flex items-center gap-2 pt-1" x-show="n.category">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span x-text="n.category"></span>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Delete Button --}}
                                    <button @click="deleteNotification(n.id, $event)"
                                            class="opacity-0 group-hover:opacity-100 p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-all"
                                            title="{{ __('Delete') }}">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </a>
                            </div>
                        </template>
                    </div>

                    {{-- Empty Message --}}
                    <div x-show="!loading && filteredNotifications.length === 0" 
                         class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                            <span x-show="filter === 'all'">{{ __('No notifications yet') }}</span>
                            <span x-show="filter === 'unread'">{{ __('No unread notifications') }}</span>
                            <span x-show="filter === 'read'">{{ __('No read notifications') }}</span>
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <span x-show="searchQuery">{{ __('Try another search') }}</span>
                            <span x-show="!searchQuery && filter === 'all'">{{ __('You\'re all caught up!') }}</span>
                            <span x-show="!searchQuery && filter === 'unread'">{{ __('All your notifications have been read') }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>