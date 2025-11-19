<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        {{ __('Notifications') }}
                    </h2>
                    <p class="mt-1 text-base text-slate-600 dark:text-slate-400">{{ __('View and manage your notifications') }}</p>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-start">
                    <button @click="markAllAsRead()" 
                            x-show="unreadCount > 0"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Mark All as Read') }}
                    </button>
                    <button @click="clearAll()"
                            x-show="notifications.length > 0"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('Clear All') }}
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-[#fffff] dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-5 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Notifications Center') }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Stay updated with your latest activities') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                            <div class="w-2.5 h-2.5 bg-[#EF7722] rounded-full animate-pulse"></div>
                            <span class="text-sm font-bold text-slate-900 dark:text-white" x-text="unreadCount + ' ' + __('Unread')"></span>
                        </div>
                    </div>
                </div>

                {{-- Filters and Search --}}
                <div class="px-6 py-4 bg-white dark:bg-slate-800 border-b border-[#EBEBEB] dark:border-slate-700">
                    <div class="space-y-4">
                        {{-- Filter Tabs --}}
                        <div class="flex items-center gap-2 bg-[#EBEBEB] dark:bg-slate-700 p-1 rounded-lg">
                            <button @click="filter = 'all'" 
                                    :class="filter === 'all' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                    class="flex-1 px-4 py-2 rounded-md text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                {{ __('All') }}
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                      :class="filter === 'all' ? 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533]' : 'bg-[#EBEBEB] dark:bg-slate-600 text-slate-600 dark:text-slate-400'"
                                      x-text="notifications.length"></span>
                            </button>
                            <button @click="filter = 'unread'" 
                                    :class="filter === 'unread' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                    class="flex-1 px-4 py-2 rounded-md text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                {{ __('Unread') }}
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold"
                                      :class="filter === 'unread' ? 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533]' : 'bg-[#EBEBEB] dark:bg-slate-600 text-slate-600 dark:text-slate-400'"
                                      x-text="unreadCount"></span>
                            </button>
                            <button @click="filter = 'read'" 
                                    :class="filter === 'read' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'"
                                    class="flex-1 px-4 py-2 rounded-md text-sm font-semibold transition-all duration-200">
                                {{ __('Read') }}
                            </button>
                        </div>

                        {{-- Search --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   x-model="searchQuery"
                                   placeholder="{{ __('Search notifications...') }}"
                                   class="block w-full pl-10 pr-4 py-2.5 border border-[#EBEBEB] dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-[#EF7722] focus:border-[#EF7722] dark:bg-slate-700 dark:text-white shadow-sm">
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-6" x-data="notificationCenter">
                    {{-- Loading Indicator --}}
                    <div x-show="loading && notifications.length === 0" 
                         class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[#EBEBEB] dark:bg-slate-700 mb-4">
                            <svg class="animate-spin h-6 w-6 text-[#EF7722] dark:text-[#FAA533]" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('Loading notifications') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Please wait while we load your notifications...') }}</p>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="error" 
                         class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-lg">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-red-800 dark:text-red-200" x-text="error"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Notifications List --}}
                    <div class="space-y-3"
                         x-show="!loading && filteredNotifications.length > 0">
                        <template x-for="(n, index) in filteredNotifications" :key="n.id || index">
                            <div class="group relative bg-white dark:bg-slate-800 rounded-xl border border-[#EBEBEB] dark:border-slate-700 hover:border-[#EF7722] dark:hover:border-[#FAA533] transition-all duration-200 hover:shadow-sm"
                                 :class="{ 'bg-[#EF7722]/5 dark:bg-[#EF7722]/10 border-[#EF7722] dark:border-[#FAA533]': !n.read_at }">
                                <a :href="n.click_action || '#'" 
                                   @click.prevent="markAsRead(n.id, n.click_action)"
                                   class="flex items-start gap-4 p-4">
                                    
                                    {{-- Icon with Badge --}}
                                    <div class="flex-shrink-0 relative">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center border-2 transition-all duration-200 group-hover:scale-105 shadow-sm"
                                             :class="[
                                                 getNotificationColor(n.type || 'default').bg,
                                                 getNotificationColor(n.type || 'default').text,
                                                 getNotificationColor(n.type || 'default').border,
                                                 !n.read_at ? 'ring-2 ring-offset-2 ring-[#EF7722]/20 dark:ring-[#FAA533]/20' : ''
                                             ]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(n.type || 'default')"></svg>
                                        </div>
                                        <span x-show="!n.read_at" 
                                              class="absolute -top-1 -right-1 w-3 h-3 bg-[#EF7722] rounded-full border-2 border-white dark:border-slate-800 shadow-sm animate-pulse"></span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0 space-y-2">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-slate-900 dark:text-white line-clamp-1" 
                                                    :class="{ 'font-bold': !n.read_at }"
                                                    x-text="n.title || 'Notification'"></h4>
                                                <p class="text-sm text-slate-600 dark:text-slate-300 line-clamp-2 mt-1" 
                                                   x-text="n.body || 'No content available'"></p>
                                            </div>
                                            <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                                <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap" 
                                                      x-text="formatDate(n.created_at)"></span>
                                                <button @click="deleteNotification(n.id, $event)"
                                                        class="opacity-0 group-hover:opacity-100 p-1.5 rounded-lg hover:bg-[#EBEBEB] dark:hover:bg-slate-700 transition-all duration-200"
                                                        title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        {{-- Tags / Category --}}
                                        <div class="flex items-center gap-2" x-show="n.category">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-[#EBEBEB] dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-[#EBEBEB] dark:border-slate-600">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <span x-text="n.category"></span>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </template>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="!loading && filteredNotifications.length === 0" 
                         class="text-center py-16">
                        <div class="mx-auto w-20 h-20 bg-[#EBEBEB] dark:bg-slate-700 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                            <span x-show="filter === 'all'">{{ __('No notifications yet') }}</span>
                            <span x-show="filter === 'unread'">{{ __('No unread notifications') }}</span>
                            <span x-show="filter === 'read'">{{ __('No read notifications') }}</span>
                        </h3>
                        <p class="text-base text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
                            <span x-show="searchQuery">{{ __('No notifications match your search. Try different keywords.') }}</span>
                            <span x-show="!searchQuery && filter === 'all'">{{ __('You\'re all caught up! New notifications will appear here.') }}</span>
                            <span x-show="!searchQuery && filter === 'unread'">{{ __('All your notifications have been read. Great job staying on top of things!') }}</span>
                            <span x-show="!searchQuery && filter === 'read'">{{ __('You haven\'t read any notifications yet.') }}</span>
                        </p>
                        <div x-show="searchQuery" class="flex justify-center gap-3">
                            <button @click="searchQuery = ''" 
                                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                {{ __('Clear Search') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth transitions for better UX */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Custom scrollbar for notifications */
        .notifications-container {
            scrollbar-width: thin;
            scrollbar-color: #EF7722 transparent;
        }

        .notifications-container::-webkit-scrollbar {
            width: 6px;
        }

        .notifications-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .notifications-container::-webkit-scrollbar-thumb {
            background-color: #EF7722;
            border-radius: 3px;
        }

        .notifications-container::-webkit-scrollbar-thumb:hover {
            background-color: #FAA533;
        }

        /* Dark mode scrollbar */
        .dark .notifications-container::-webkit-scrollbar-thumb {
            background-color: #FAA533;
        }

        .dark .notifications-container::-webkit-scrollbar-thumb:hover {
            background-color: #EF7722;
        }
    </style>

    <script>
        function notificationCenter() {
            return {
                notifications: [],
                loading: true,
                error: null,
                filter: 'all',
                searchQuery: '',
                unreadCount: 0,

                get filteredNotifications() {
                    let filtered = this.notifications;

                    // Apply filter
                    if (this.filter === 'unread') {
                        filtered = filtered.filter(n => !n.read_at);
                    } else if (this.filter === 'read') {
                        filtered = filtered.filter(n => n.read_at);
                    }

                    // Apply search
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(n => 
                            (n.title?.toLowerCase().includes(query) || 
                             n.body?.toLowerCase().includes(query) ||
                             n.category?.toLowerCase().includes(query))
                        );
                    }

                    return filtered;
                },

                getNotificationColor(type) {
                    const colors = {
                        info: {
                            bg: 'bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20',
                            text: 'text-[#0BA6DF] dark:text-[#0BA6DF]',
                            border: 'border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30'
                        },
                        success: {
                            bg: 'bg-green-100 dark:bg-green-900/20',
                            text: 'text-green-600 dark:text-green-400',
                            border: 'border-green-200 dark:border-green-700'
                        },
                        warning: {
                            bg: 'bg-[#FAA533]/10 dark:bg-[#FAA533]/20',
                            text: 'text-[#FAA533] dark:text-[#FAA533]',
                            border: 'border-[#FAA533]/20 dark:border-[#FAA533]/30'
                        },
                        error: {
                            bg: 'bg-red-100 dark:bg-red-900/20',
                            text: 'text-red-600 dark:text-red-400',
                            border: 'border-red-200 dark:border-red-700'
                        },
                        default: {
                            bg: 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20',
                            text: 'text-[#EF7722] dark:text-[#FAA533]',
                            border: 'border-[#EF7722]/20 dark:border-[#FAA533]/30'
                        }
                    };
                    return colors[type] || colors.default;
                },

                getNotificationIcon(type) {
                    const icons = {
                        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        default: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'
                    };
                    return icons[type] || icons.default;
                },

                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                async fetchNotifications() {
                    try {
                        this.loading = true;
                        this.error = null;
                        // Implementation for fetching notifications
                        // this.notifications = await fetch('/api/notifications').then(r => r.json());
                        // this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                    } catch (err) {
                        this.error = 'Failed to load notifications';
                    } finally {
                        this.loading = false;
                    }
                },

                async markAsRead(id, clickAction = null) {
                    try {
                        // Implementation for marking as read
                        // await fetch(`/api/notifications/${id}/read`, { method: 'POST' });
                        
                        if (clickAction) {
                            window.location.href = clickAction;
                        }
                    } catch (err) {
                        console.error('Failed to mark as read:', err);
                    }
                },

                async markAllAsRead() {
                    try {
                        // Implementation for marking all as read
                        // await fetch('/api/notifications/read-all', { method: 'POST' });
                        this.notifications = this.notifications.map(n => ({ ...n, read_at: new Date().toISOString() }));
                        this.unreadCount = 0;
                    } catch (err) {
                        console.error('Failed to mark all as read:', err);
                    }
                },

                async deleteNotification(id, event) {
                    event.stopPropagation();
                    try {
                        // Implementation for deleting notification
                        // await fetch(`/api/notifications/${id}`, { method: 'DELETE' });
                        this.notifications = this.notifications.filter(n => n.id !== id);
                        this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                    } catch (err) {
                        console.error('Failed to delete notification:', err);
                    }
                },

                async clearAll() {
                    try {
                        // Implementation for clearing all notifications
                        // await fetch('/api/notifications/clear-all', { method: 'DELETE' });
                        this.notifications = [];
                        this.unreadCount = 0;
                    } catch (err) {
                        console.error('Failed to clear all notifications:', err);
                    }
                },

                init() {
                    this.fetchNotifications();
                }
            }
        }
    </script>
</x-app-layout>