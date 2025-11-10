<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flag-icons@6.6.2/css/flag-icons.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.csp.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInRight {
            from { 
                opacity: 0; 
                transform: translateX(100%); 
            }
            to { 
                opacity: 1; 
                transform: translateX(0); 
            }
        }
        
        @keyframes slideOutRight {
            from { 
                opacity: 1; 
                transform: translateX(0); 
            }
            to { 
                opacity: 0; 
                transform: translateX(100%); 
            }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.7; }
            100% { transform: scale(0.95); opacity: 1; }
        }
        
        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
        
        .animate-slide-down { animation: slideDown 0.3s ease-out; }
        .animate-pulse-ring { animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-slide-in { animation: slideInRight 0.3s ease-out; }
        .animate-slide-out { animation: slideOutRight 0.3s ease-in; }
        .animate-progress { animation: progress 5s linear forwards; }
        
        .notification-item:hover { transform: translateX(-2px); }
        .notification-item { transition: all 0.2s ease; }
        
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <!-- Alpine.js Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('notificationCenter', () => ({
                open: false,
                notifications: [],
                unreadCount: 0,
                loading: false,
                error: null,
                lastFetch: null,
                fetchInterval: null,
                filter: 'all',
                searchQuery: '',

                init() {
                    this.fetchNotifications();
                    
                    window.addEventListener('notification-received', (event) => {
                        console.log('🔔 Nouvelle notification détectée');
                        setTimeout(() => this.fetchNotifications(true), 800);
                        this.showToast(event.detail);
                    });

                    this.setupPolling();

                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.stopPolling();
                        } else {
                            this.setupPolling();
                            this.fetchNotifications(true);
                        }
                    });
                },

                setupPolling() {
                    if (!this.fetchInterval) {
                        this.fetchInterval = setInterval(() => {
                            if (!document.hidden) {
                                this.fetchNotifications(true);
                            }
                        }, 30000);
                    }
                },

                stopPolling() {
                    if (this.fetchInterval) {
                        clearInterval(this.fetchInterval);
                        this.fetchInterval = null;
                    }
                },

                async fetchNotifications(skipCache = false) {
                    const now = Date.now();
                    if (!skipCache && this.lastFetch && (now - this.lastFetch) < 5000) {
                        return;
                    }

                    this.loading = true;
                    this.error = null;

                    try {
                        const response = await fetch('{{ route("notifications.index") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);

                        const data = await response.json();
                        
                        if (data.notifications) {
                            this.notifications = data.notifications;
                            this.unreadCount = data.unread_count || 0;
                        } else if (Array.isArray(data)) {
                            this.notifications = data;
                            this.unreadCount = data.filter(n => !n.read_at).length;
                        } else {
                            this.notifications = [];
                            this.unreadCount = 0;
                        }
                        
                        this.lastFetch = now;
                        console.log(`✅ ${this.notifications.length} notifications chargées`);

                    } catch (err) {
                        console.error("❌ Erreur chargement notifications:", err);
                        this.error = "Impossible de charger les notifications";
                    } finally {
                        this.loading = false;
                    }
                },

                get filteredNotifications() {
                    let filtered = this.notifications;
                    
                    if (this.filter === 'unread') {
                        filtered = filtered.filter(n => !n.read_at);
                    } else if (this.filter === 'read') {
                        filtered = filtered.filter(n => n.read_at);
                    }
                    
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        filtered = filtered.filter(n => 
                            (n.title || '').toLowerCase().includes(query) || 
                            (n.body || '').toLowerCase().includes(query)
                        );
                    }
                    
                    return filtered;
                },

                async markAsRead(id, clickAction = null) {
                    try {
                        const response = await fetch(`/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);

                        const notif = this.notifications.find(n => n.id === id);
                        if (notif && !notif.read_at) {
                            notif.read_at = new Date().toISOString();
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        }

                        if (clickAction && clickAction !== '#') {
                            window.location.href = clickAction;
                        }

                    } catch (err) {
                        console.error(`❌ Erreur marquage notification:`, err);
                    }
                },

                async markAllAsRead() {
                    if (this.unreadCount === 0) return;

                    try {
                        const response = await fetch('/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            this.notifications.forEach(n => {
                                if (!n.read_at) n.read_at = new Date().toISOString();
                            });
                            this.unreadCount = 0;
                            this.showSuccessToast('Toutes les notifications ont été marquées comme lues');
                        }
                    } catch (err) {
                        console.error('❌ Erreur marquage toutes notifications:', err);
                    }
                },

                async deleteNotification(id, event) {
                    event.stopPropagation();
                    event.preventDefault();

                    try {
                        const response = await fetch(`/notifications/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            const notif = this.notifications.find(n => n.id === id);
                            if (notif && !notif.read_at) {
                                this.unreadCount = Math.max(0, this.unreadCount - 1);
                            }
                            this.notifications = this.notifications.filter(n => n.id !== id);
                        }
                    } catch (err) {
                        console.error('❌ Erreur suppression notification:', err);
                    }
                },

                async clearAll() {
                    if (this.notifications.length === 0) return;

                    if (!confirm('Êtes-vous sûr de vouloir supprimer toutes les notifications ?')) {
                        return;
                    }

                    try {
                        const response = await fetch('/notifications/clear-all', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            this.notifications = [];
                            this.unreadCount = 0;
                            this.showSuccessToast('Toutes les notifications ont été supprimées');
                        }
                    } catch (err) {
                        console.error('❌ Erreur suppression toutes notifications:', err);
                    }
                },

                getNotificationIcon(type) {
                    const icons = {
                        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
                        error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        default: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'
                    };
                    return icons[type] || icons.default;
                },

                getNotificationColor(type) {
                    const colors = {
                        success: { bg: 'bg-green-100', text: 'text-green-600', ring: 'ring-green-500' },
                        warning: { bg: 'bg-yellow-100', text: 'text-yellow-600', ring: 'ring-yellow-500' },
                        error: { bg: 'bg-red-100', text: 'text-red-600', ring: 'ring-red-500' },
                        info: { bg: 'bg-blue-100', text: 'text-blue-600', ring: 'ring-blue-500' },
                        default: { bg: 'bg-indigo-100', text: 'text-indigo-600', ring: 'ring-indigo-500' }
                    };
                    return colors[type] || colors.default;
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    const now = new Date();
                    const diffMs = now - date;
                    const diffMins = Math.floor(diffMs / 60000);
                    const diffHours = Math.floor(diffMs / 3600000);
                    const diffDays = Math.floor(diffMs / 86400000);

                    if (diffMins < 1) return 'À l\'instant';
                    if (diffMins < 60) return `Il y a ${diffMins} min`;
                    if (diffHours < 24) return `Il y a ${diffHours}h`;
                    if (diffDays === 1) return 'Hier';
                    if (diffDays < 7) return `Il y a ${diffDays}j`;
                    return date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
                },

                showToast(notification) {
                    window.dispatchEvent(new CustomEvent('show-success-toast', { 
                        detail: notification.notification?.title || 'Nouvelle notification' 
                    }));
                },

                showSuccessToast(message) {
                    window.dispatchEvent(new CustomEvent('show-success-toast', { detail: message }));
                },

                showErrorToast(message) {
                    window.dispatchEvent(new CustomEvent('show-error-toast', { detail: message }));
                },

                destroy() {
                    this.stopPolling();
                }
            }));
        });
    </script>

    <!-- Firebase Push Notifications -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key', 'AIzaSyBgVdeNSCKN1Dhmxpm_pKchE_AfmRF6w_E') }}",
            authDomain: "{{ config('services.firebase.auth_domain', 'sourcing-app-1a786.firebaseapp.com') }}",
            projectId: "{{ config('services.firebase.project_id', 'sourcing-app-1a786') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket', 'sourcing-app-1a786.appspot.com') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id', '930979287595') }}",
            appId: "{{ config('services.firebase.app_id', '1:930979287595:web:3b2710de5de42c3e90d06e') }}"
        };

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        async function requestPermissionAndGetToken() {
            try {
                if (!('Notification' in window)) {
                    console.warn('🚫 Notifications non supportées');
                    return;
                }

                const permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    const currentToken = await getToken(messaging, {
                        vapidKey: "{{ config('services.firebase.vapid_key', 'BPQOA9LTMjO8gw3dVtLIYQce5giuHlV77aRoRU8MK4EnT6ZN3iIoEip3j2xvdAXwqpCa9ggFmAAE-rEC--MKuGg') }}"
                    });
                    
                    if (currentToken) {
                        console.log("✅ FCM Token obtenu");
                        await sendTokenToServer(currentToken);
                    }
                }
            } catch (err) {
                console.error("❌ Erreur Firebase:", err);
            }
        }

        async function sendTokenToServer(token, retries = 3) {
            for (let attempt = 1; attempt <= retries; attempt++) {
                try {
                    const response = await fetch('{{ route("fcm.token.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ fcm_token: token })
                    });

                    if (response.ok) {
                        console.log("✅ Token FCM envoyé");
                        return true;
                    }
                } catch (err) {
                    if (attempt < retries) {
                        await new Promise(resolve => setTimeout(resolve, Math.pow(2, attempt) * 1000));
                    }
                }
            }
            return false;
        }

        onMessage(messaging, (payload) => {
            console.log("🔔 Notification reçue:", payload);
            
            if (payload.notification) {
                new Notification(payload.notification.title, {
                    body: payload.notification.body,
                    icon: payload.notification.icon || '/icon-192x192.png',
                    badge: '/badge-72x72.png',
                    tag: payload.data?.notification_id || 'default',
                });
            }
            
            window.dispatchEvent(new CustomEvent('notification-received', { detail: payload }));
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then((registration) => {
                    console.log("✅ Service Worker enregistré");
                    @auth
                        requestPermissionAndGetToken();
                    @endauth
                })
                .catch(err => console.error("❌ Erreur Service Worker:", err));
        }
    </script>
<script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }" x-cloak>
    <x-sidebar :role="auth()->user()->role ?? 'client'" />

    <header class="sticky top-0 z-40 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl border-b border-gray-200/80 dark:border-gray-700/80 shadow-sm">
        <x-layout.header />
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="lg:hidden p-2 -ml-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="flex-1">
                    
                </div>

                <!-- Dark Mode Toggle -->
                <div x-data="{
                    theme: localStorage.getItem('color-theme') || 'light',
                    toggleTheme() {
                        this.theme = this.theme === 'light' ? 'dark' : 'light';
                        localStorage.setItem('color-theme', this.theme);
                        if (this.theme === 'dark') {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                }" class="relative">
                    <button @click="toggleTheme()" class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group">
                        <svg x-show="theme === 'light'" class="h-6 w-6 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                        <svg x-show="theme === 'dark'" class="h-6 w-6 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-8.66l-.707.707M4.04 4.04l-.707.707M21 12h-1M4 12H3m16.96 7.96l-.707-.707M5.75 5.75l-.707-.707"></path>
                        </svg>
                    </button>
                </div>


                <!-- Language Switcher -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group">
                        <svg class="h-6 w-6 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50" style="display: none;" x-transition>
                        <a href="{{ route('language.switch', 'en') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors @if(app()->getLocale() == 'en') bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-white @endif">
                            <span class="fi fi-gb"></span>
                            <span>English</span>
                        </a>
                        <a href="{{ route('language.switch', 'fr') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors @if(app()->getLocale() == 'fr') bg-indigo-50 dark:bg-gray-700 text-indigo-600 dark:text-white @endif">
                            <span class="fi fi-fr"></span>
                            <span>Français</span>
                        </a>
                    </div>
                </div>

                {{-- Centre de Notifications --}}
                <div class="hidden sm:flex sm:items-center sm:gap-4" x-data="notificationCenter" @show-success-toast.window="showSuccessToast($event.detail)" @show-error-toast.window="showErrorToast($event.detail)">
                    <div class="relative">
                        <button @click="open = !open; if(open) fetchNotifications()" 
                                class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group"
                                :class="{ 'bg-gray-100 dark:bg-gray-700': open }">
                            <svg class="h-6 w-6 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="unreadCount > 0" 
                                  class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full shadow-lg animate-pulse-ring" 
                                  x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
                        </button>

                        {{-- Panneau de Notifications --}}
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-[420px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200/80 dark:border-gray-700 overflow-hidden z-50"
                             style="display: none;">
                            
                            {{-- En-tête Amélioré --}}
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200/80 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                        Notifications
                                    </h3>
                                    <div class="flex items-center gap-2">
                                        <button @click="markAllAsRead()" 
                                                x-show="unreadCount > 0"
                                                class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-gray-700 px-3 py-1.5 rounded-lg transition-all"
                                                title="Tout marquer comme lu">
                                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Tout lire
                                        </button>
                                    </div>
                                </div>

                                {{-- Filtres et Recherche --}}
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <button @click="filter = 'all'" 
                                                :class="filter === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'bg-white/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600'"
                                                class="flex-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-all">
                                            Toutes
                                            <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                                                  :class="filter === 'all' ? 'bg-indigo-100 dark:bg-indigo-500/50 text-indigo-700 dark:text-white' : 'bg-gray-200 dark:bg-gray-500 text-gray-600 dark:text-gray-200'"
                                                  x-text="notifications.length"></span>
                                        </button>
                                        <button @click="filter = 'unread'" 
                                                :class="filter === 'unread' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'bg-white/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600'"
                                                class="flex-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-all">
                                            Non lues
                                            <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                                                  :class="filter === 'unread' ? 'bg-indigo-100 dark:bg-indigo-500/50 text-indigo-700 dark:text-white' : 'bg-gray-200 dark:bg-gray-500 text-gray-600 dark:text-gray-200'"
                                                  x-text="unreadCount"></span>
                                        </button>
                                        <button @click="filter = 'read'" 
                                                :class="filter === 'read' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'bg-white/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600'"
                                                class="flex-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-all">
                                            Lues
                                        </button>
                                    </div>

                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <input type="text" 
                                               x-model="searchQuery"
                                               placeholder="Rechercher une notification..."
                                               class="w-full pl-9 pr-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white">
                                    </div>
                                </div>
                            </div>

                            {{-- Indicateur de Chargement --}}
                            <div x-show="loading && notifications.length === 0" 
                                 class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-500/20 mb-3">
                                    <svg class="animate-spin h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">Chargement des notifications...</p>
                            </div>

                            {{-- Message d'Erreur --}}
                            <div x-show="error" class="mx-4 my-3 p-3 bg-red-50 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-red-500 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-red-700 dark:text-red-300" x-text="error"></p>
                                </div>
                            </div>

                            {{-- Liste des Notifications --}}
                            <div class="max-h-[480px] overflow-y-auto scrollbar-thin" 
                                 x-show="!loading && filteredNotifications.length > 0">
                                <template x-for="(n, index) in filteredNotifications" :key="n.id || index">
                                    <div class="notification-item relative group border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gradient-to-r hover:from-gray-50 hover:to-indigo-50/30 dark:hover:from-gray-700 dark:hover:to-gray-700/50"
                                         :class="{ 'bg-indigo-50/40 dark:bg-indigo-500/10': !n.read_at }">
                                        <a :href="n.click_action || '#'" 
                                           @click.prevent="markAsRead(n.id, n.click_action)"
                                           class="flex items-start gap-3 px-6 py-4">
                                            
                                            {{-- Icône avec Badge --}}
                                            <div class="flex-shrink-0 mt-0.5 relative">
                                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm ring-2 transition-all duration-200"
                                                     :class="[
                                                         getNotificationColor(n.type || 'default').bg,
                                                         getNotificationColor(n.type || 'default').text,
                                                         !n.read_at ? getNotificationColor(n.type || 'default').ring + ' ring-opacity-20' : 'ring-transparent'
                                                     ]">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(n.type || 'default')"></svg>
                                                </div>
                                                <span x-show="!n.read_at" 
                                                      class="absolute -top-1 -right-1 w-3 h-3 bg-indigo-600 rounded-full ring-2 ring-white dark:ring-gray-800 animate-pulse"></span>
                                            </div>

                                            {{-- Contenu --}}
                                            <div class="flex-1 min-w-0 space-y-1">
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-1" 
                                                        :class="{ 'font-bold': !n.read_at }"
                                                        x-text="n.title || 'Notification'"></h4>
                                                    <span class="flex-shrink-0 text-xs text-gray-500 dark:text-gray-400 font-medium" 
                                                          x-text="formatDate(n.created_at)"></span>
                                                </div>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed" 
                                                   x-text="n.body || 'Aucun contenu disponible'"></p>
                                                
                                                {{-- Tags / Catégorie --}}
                                                <div class="flex items-center gap-2 pt-1" x-show="n.category">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                        <span x-text="n.category"></span>
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Bouton Supprimer --}}
                                            <button @click="deleteNotification(n.id, $event)"
                                                    class="flex-shrink-0 opacity-0 group-hover:opacity-100 p-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-all duration-200"
                                                    title="Supprimer">
                                                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </template>
                            </div>

                            {{-- Message Vide --}}
                            <div x-show="!loading && filteredNotifications.length === 0" 
                                 class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    <span x-show="filter === 'all'">Aucune notification</span>
                                    <span x-show="filter === 'unread'">Aucune notification non lue</span>
                                    <span x-show="filter === 'read'">Aucune notification lue</span>
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <span x-show="searchQuery">Essayez une autre recherche</span>
                                    <span x-show="!searchQuery && filter === 'all'">Vous êtes à jour !</span>
                                    <span x-show="!searchQuery && filter === 'unread'">Toutes vos notifications ont été lues</span>
                                </p>
                            </div>

                            {{-- Footer avec Actions --}}
                            <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-6 py-3 flex items-center justify-between"
                                 x-show="notifications.length > 0">
                                <button @click="clearAll()"
                                        class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-500/20 dark:hover:text-red-400 px-3 py-1.5 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Tout supprimer
                                </button>
                                <a href="{{ route('notifications.index') }}" 
                                   class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 dark:hover:text-indigo-400 px-3 py-1.5 rounded-lg transition-all">
                                    Voir tout
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Menu Utilisateur --}}
                <div class="flex items-center gap-3 ml-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-2.5 p-2 pr-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                            <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-white dark:ring-gray-800">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ ucfirst(auth()->user()->role ?? 'Utilisateur') }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" 
                                 :class="{ 'rotate-180': open }" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50"
                             style="display: none;">
                            
                            <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                <span class="inline-flex items-center mt-1.5 px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-500/50 text-indigo-700 dark:text-white">
                                    {{ ucfirst(auth()->user()->role ?? 'Utilisateur') }}
                                </span>
                            </div>
                            
                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Mon Profil
                                </a>
                                
                                <a href="#" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Paramètres
                                </a>
                                
                                <a href="#" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700 dark:hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Aide & Support
                                </a>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/20 transition-colors font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="lg:pl-64 flex flex-col flex-1">
        {{-- Contenu Principal --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        {{-- Footer Moderne --}}
        <footer class="mt-auto border-t bg-white dark:bg-gray-800 dark:border-gray-700">
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Tous droits réservés.</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Confidentialité</a>
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Conditions</a>
                        <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <x-loading-spinner />
    @stack('scripts')
    @if(auth()->user() && auth()->user()->role === 'client')
        <script src="//code.tidio.co/fcoeyvf3lyzubcu375ojfn87yy6zf6l1.js" async></script>
    @endif
</body>
</html>