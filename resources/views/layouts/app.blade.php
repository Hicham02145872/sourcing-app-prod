<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

    @props([
        'adminSourcingRequestCount' => 0,
        'clientQuotationCount' => 0,
    ])


    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flag-icons@6.6.2/css/flag-icons.min.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                    const type = notification.notification?.type || 'default'; // Default to 'default' type if not specified
                                    const message = notification.notification?.title || 'Nouvelle notification';
                                    if (type === 'error' || type === 'warning') { // Consider 'warning' as an error for toast purposes
                                        window.dispatchEvent(new CustomEvent('show-error-toast', { detail: message }));
                                    } else {
                                        window.dispatchEvent(new CustomEvent('show-success-toast', { detail: message }));
                                    }
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

        // Rendre disponible globalement pour le bouton "Soft Invite"
        window.requestFcmPermission = async function() {
            try {
                if (!('Notification' in window)) {
                    console.warn('🚫 Notifications non supportées');
                    return false;
                }

                console.log("🔔 Demande de permission...");
                const permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    const currentToken = await getToken(messaging, {
                        vapidKey: "{{ config('services.firebase.vapid_key', 'BPQOA9LTMjO8gw3dVtLIYQce5giuHlV77aRoRU8MK4EnT6ZN3iIoEip3j2xvdAXwqpCa9ggFmAAE-rEC--MKuGg') }}"
                    });
                    
                    if (currentToken) {
                        console.log("✅ FCM Token obtenu");
                        const success = await sendTokenToServer(currentToken);
                        if (success) {
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: 'Notifications activées avec succès !' }));
                            return true;
                        }
                    }
                } else if (permission === 'denied') {
                    window.dispatchEvent(new CustomEvent('show-error-toast', { detail: 'Permission refusée. Vous pouvez la réactiver dans les paramètres de votre navigateur.' }));
                }
                return false;
            } catch (err) {
                console.error("❌ Erreur Firebase:", err);
                return false;
            }
        };

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
                // Si l'app est ouverte, on peut aussi afficher une notification native
                // ou simplement laisser Livewire/Alpine gérer le toast via l'événement 'notification-received'
                try {
                    new Notification(payload.notification.title, {
                        body: payload.notification.body,
                        icon: payload.notification.icon || '/icon-192x192.png',
                    });
                } catch (e) {
                    console.warn("Erreur lors de l'affichage de la notification native:", e);
                }
            }
            
            window.dispatchEvent(new CustomEvent('notification-received', { detail: payload }));
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/firebase-messaging-sw.js')
                    .then((registration) => {
                        console.log("✅ Service Worker enregistré");
                        @auth
                            // On ne demande pas automatiquement la permission si elle n'est pas déjà accordée
                            // pour respecter le principe de "Soft Invite".
                            // Mais si elle est déjà accordée, on rafraîchit le token.
                            if (Notification.permission === 'granted') {
                                getToken(messaging, {
                                    vapidKey: "{{ config('services.firebase.vapid_key', 'BPQOA9LTMjO8gw3dVtLIYQce5giuHlV77aRoRU8MK4EnT6ZN3iIoEip3j2xvdAXwqpCa9ggFmAAE-rEC--MKuGg') }}"
                                }).then(token => {
                                    if (token) sendTokenToServer(token);
                                });
                            }
                        @endauth
                    })
                    .catch(err => console.error("❌ Erreur Service Worker:", err));
            });
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
    <x-sidebar :role="auth()->user()?->role ?? 'client'" :adminSourcingRequestCount="$adminSourcingRequestCount ?? 0" :clientQuotationCount="$clientQuotationCount ?? 0" />

    <x-layout.header />

    <div class="lg:ml-64 pt-24 flex flex-col flex-1 min-h-screen">
        <!-- Page Header -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- Contenu Principal --}}
        <main class="flex-1">
            {{ $slot }}
        </main>
        
        {{-- Footer Moderne --}}
        <footer class="mt-auto border-t border-slate-100 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-900/50">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Tous droits réservés.</span>
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

    {{-- Toast Notification Manager --}}
    <div x-data="toastManager()" class="fixed inset-x-0 top-0 flex items-start justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-show="toast.show"
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                    :class="{
                        'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700': toast.type === 'success',
                        'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700': toast.type === 'error'
                    }"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg x-show="toast.type === 'success'" class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <svg x-show="toast.type === 'error'" class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium"
                                    :class="{
                                        'text-green-800 dark:text-green-200': toast.type === 'success',
                                        'text-red-800 dark:text-red-200': toast.type === 'error'
                                    }"
                                    x-text="toast.message"></p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button @click="removeToast(toast.id)"
                                    class="rounded-md inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    :class="{
                                        'bg-green-50 text-green-400 hover:text-green-500 focus:ring-green-500 dark:bg-green-900/20 dark:text-green-300 dark:hover:text-green-400': toast.type === 'success',
                                        'bg-red-50 text-red-400 hover:text-red-500 focus:ring-red-500 dark:bg-red-900/20 dark:text-red-300 dark:hover:text-red-400': toast.type === 'error'
                                    }"
                                >
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('toastManager', () => ({
                toasts: [],
                init() {
                    window.addEventListener('show-success-toast', (event) => {
                        const detail = event.detail;
                        const message = typeof detail === 'string' ? detail : (Array.isArray(detail) ? detail[0] : (detail.detail || detail.message || JSON.stringify(detail)));
                        this.addToast(message, 'success');
                    });
                    window.addEventListener('show-error-toast', (event) => {
                        const detail = event.detail;
                        const message = typeof detail === 'string' ? detail : (Array.isArray(detail) ? detail[0] : (detail.detail || detail.message || JSON.stringify(detail)));
                        this.addToast(message, 'error');
                    });
                    // For status messages coming from Laravel's `with('status', ...)`
                    @if(session('status'))
                        this.addToast("{{ session('status') }}", 'success');
                    @endif
                    @if(session('error'))
                        this.addToast("{{ session('error') }}", 'error');
                    @endif
                },
                addToast(message, type) {
                    const id = Date.now();
                    this.toasts.push({
                        id: id,
                        message: message,
                        type: type,
                        show: true
                    });
                    setTimeout(() => this.removeToast(id), 5000); // Auto-remove after 5 seconds
                },
                removeToast(id) {
                    this.toasts = this.toasts.map(toast => {
                        if (toast.id === id) {
                            toast.show = false; // Trigger leave transition
                        }
                        return toast;
                    });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(toast => toast.id !== id);
                    }, 300); // Remove from DOM after transition
                }
            }));
        });
    </script>


    @livewireScripts
</body>