<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12" x-data="notificationCenterPage({
        initialNotifications: @js($notifications),
        initialUnreadCount: {{ $unreadCount }}
    })">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Notifications Center') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('client.dashboard') }}" class="hover:text-slate-700">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Notifications') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Top Actions -->
                    <div class="flex items-center gap-3">
                        <button @click="markAllAsRead()" 
                                x-show="unreadCount > 0"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-md hover:bg-slate-50 transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Mark All as Read') }}
                        </button>
                        <button @click="clearAll()"
                                x-show="notifications.length > 0"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-md hover:bg-slate-50 transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            {{ __('Clear All') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            
            <!-- Section 1: KPIs / Summary Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Notifications -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-slate-300 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Notifications') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900" x-text="notifications.length">0</h3>
                        </div>
                        <div class="p-1.5 bg-slate-50 rounded text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">Historique complet</div>
                </div>

                <!-- Unread Notifications -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Unread') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900" x-text="unreadCount">0</h3>
                        </div>
                        <div class="p-1.5 bg-orange-50 rounded text-orange-600">
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-orange-600 font-medium">Action requise</div>
                </div>

                <!-- Filters Helper -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Active Filter') }}</p>
                            <h3 class="mt-1 text-base font-bold text-slate-900 capitalize" x-text="filter === 'all' ? 'Toutes' : (filter === 'unread' ? 'Non lues' : 'Lues')">Toutes</h3>
                        </div>
                        <div class="p-1.5 bg-blue-50 rounded text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">Affichage personnalisé</div>
                </div>
            </div>

            <!-- Section 2: Tools (Filters and Search) -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 overflow-hidden">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Filter Tabs -->
                    <div class="inline-flex p-1 bg-slate-50 rounded-lg border border-slate-100">
                        <button @click="filter = 'all'" 
                                :class="filter === 'all' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
                                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">
                            {{ __('All') }}
                        </button>
                        <button @click="filter = 'unread'" 
                                :class="filter === 'unread' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
                                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">
                            {{ __('Unread') }}
                        </button>
                        <button @click="filter = 'read'" 
                                :class="filter === 'read' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'"
                                class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">
                            {{ __('Read') }}
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="{{ __('Rechercher dans les notifications...') }}" 
                               class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-slate-900 focus:border-slate-900 placeholder-slate-400 bg-slate-50/50">
                    </div>
                </div>
            </div>

            <!-- Section 3: Notification List -->
            <div class="space-y-3">
                
                {{-- No results state --}}
                <div x-show="filteredNotifications.length === 0" 
                     class="bg-white rounded-lg border border-dashed border-slate-300 p-12 text-center shadow-sm">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4 border border-slate-100">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">{{ __('Silence radio...') }}</h4>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Aucune notification ne correspond à vos critères.') }}</p>
                </div>

                {{-- The List --}}
                <template x-for="(n, index) in filteredNotifications" :key="n.id || index">
                    <div class="group bg-white rounded-lg border transition-all duration-200 overflow-hidden shadow-sm hover:translate-x-1"
                         :class="n.read_at ? 'border-slate-200' : 'border-orange-200 bg-orange-50/10 ring-1 ring-orange-100 shadow-md'">
                        
                        <div class="flex items-stretch">
                            <!-- Left Accent Color Indicator -->
                            <div class="w-1.5" :class="getNotificationColor(n.type || 'default').accent"></div>
                            
                            <div class="flex-1 flex flex-col md:flex-row items-start md:items-center gap-4 p-4 min-w-0">
                                
                                <!-- Icon Container -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center border transition-transform group-hover:scale-110"
                                         :class="[getNotificationColor(n.type || 'default').bg, getNotificationColor(n.type || 'default').text, getNotificationColor(n.type || 'default').border]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(n.type || 'default')"></svg>
                                    </div>
                                </div>

                                <!-- Body Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                                        <h4 class="text-sm font-bold text-slate-900 truncate" x-text="n.title"></h4>
                                        <span x-show="!n.read_at" class="inline-flex px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-orange-600 text-white uppercase">{{ __('Nouveau') }}</span>
                                        <span x-show="n.category" class="text-[10px] bg-slate-100 text-slate-500 font-bold px-1.5 py-0.5 rounded uppercase tracking-tighter" x-text="n.category"></span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed max-w-4xl" x-text="n.body"></p>
                                    
                                    <div class="mt-2 flex items-center gap-4 text-[10px] text-slate-400 font-medium">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span x-text="formatDate(n.created_at)"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions Container -->
                                <div class="flex items-center gap-2 mt-4 md:mt-0 w-full md:w-auto">
                                    <a x-show="n.click_action" 
                                       :href="n.click_action" 
                                       @click="markAsRead(n.id)"
                                       class="flex-1 md:flex-none inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-md transition-colors shadow-sm">
                                        <span>{{ __('Consulter') }}</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7"/></svg>
                                    </a>
                                    
                                    <div class="flex items-center gap-1 ml-auto">
                                        <button @click="markAsRead(n.id)" 
                                                x-show="!n.read_at"
                                                class="p-2 text-slate-400 hover:text-slate-900 transition-colors"
                                                title="{{ __('Mark as Read') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button @click="deleteNotification(n.id, $event)"
                                                class="p-2 text-slate-400 hover:text-red-600 transition-colors"
                                                title="{{ __('Delete') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Script Section -->
    <script>
        function notificationCenterPage(data) {
            return {
                notifications: data.initialNotifications || [],
                unreadCount: data.initialUnreadCount || 0,
                filter: 'all',
                searchQuery: '',

                get filteredNotifications() {
                    let filtered = this.notifications;
                    if (this.filter === 'unread') {
                        filtered = filtered.filter(n => !n.read_at);
                    } else if (this.filter === 'read') {
                        filtered = filtered.filter(n => n.read_at);
                    }
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(n => n.title.toLowerCase().includes(q) || n.body.toLowerCase().includes(q));
                    }
                    return filtered;
                },

                getNotificationColor(type) {
                    const colors = {
                        success: { bg: 'bg-emerald-50', text: 'text-emerald-600', border: 'border-emerald-100', accent: 'bg-emerald-500' },
                        warning: { bg: 'bg-amber-50', text: 'text-amber-600', border: 'border-amber-100', accent: 'bg-amber-500' },
                        error: { bg: 'bg-red-50', text: 'text-red-600', border: 'border-red-100', accent: 'bg-red-500' },
                        info: { bg: 'bg-blue-50', text: 'text-blue-600', border: 'border-blue-100', accent: 'bg-blue-500' },
                        default: { bg: 'bg-slate-50', text: 'text-slate-600', border: 'border-slate-100', accent: 'bg-slate-400' }
                    };
                    return colors[type] || colors.default;
                },

                getNotificationIcon(type) {
                    const icons = {
                        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        default: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'
                    };
                    return icons[type] || icons.default;
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('fr-FR', {
                        day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'
                    });
                },

                async markAsRead(id) {
                    const notif = this.notifications.find(n => n.id === id);
                    if (notif && !notif.read_at) {
                        try {
                            const response = await fetch(`/notifications/${id}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            });
                            if (response.ok) {
                                notif.read_at = new Date().toISOString();
                                this.unreadCount = Math.max(0, this.unreadCount - 1);
                                window.dispatchEvent(new CustomEvent('notification-refresh')); // Sync with sidebar if needed
                            }
                        } catch (err) { console.error(err); }
                    }
                },

                async markAllAsRead() {
                    try {
                        const response = await fetch('/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (response.ok) {
                            this.notifications.forEach(n => { if (!n.read_at) n.read_at = new Date().toISOString(); });
                            this.unreadCount = 0;
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: "{{ __('Toutes les notifications marquées comme lues.') }}" }));
                        }
                    } catch (err) { console.error(err); }
                },

                async deleteNotification(id, event) {
                    event.stopPropagation();
                    if (!confirm("{{ __('Supprimer cette notification ?') }}")) return;
                    try {
                        const response = await fetch(`/notifications/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const index = this.notifications.findIndex(n => n.id === id);
                            if (!this.notifications[index].read_at) this.unreadCount--;
                            this.notifications.splice(index, 1);
                        }
                    } catch (err) { console.error(err); }
                },

                async clearAll() {
                    if (!confirm("{{ __('Supprimer tout l\'historique ?') }}")) return;
                    try {
                        const response = await fetch('/notifications/clear-all', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });
                        if (response.ok) {
                            this.notifications = [];
                            this.unreadCount = 0;
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: "{{ __('Historique vidé.') }}" }));
                        }
                    } catch (err) { console.error(err); }
                }
            }
        }
    </script>
</x-app-layout>