<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('images/logo1.png') }}">
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

    <x-layout.header />
    <div class="lg:ml-72 pt-20 flex flex-col flex-1">
        {{-- Contenu Principal --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8 pb-16">
            {{ $slot }}
        </main>

        {{-- Footer Moderne --}}
        <footer class="mt-auto border-t border-slate-200 dark:border-slate-700 p-4 bg-[#EBEBEB] dark:bg-slate-900">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
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