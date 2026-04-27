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
                    
                    if (Array.isArray(data.notifications)) {
                        this.notifications = data.notifications;
                        this.unreadCount = data.unread_count ?? data.count ?? data.notifications.filter(n => !n.read_at).length;
                    } else if (data.notifications && Array.isArray(data.notifications.data)) {
                        // Support paginator payload shape: { notifications: { data: [...] }, count: N }
                        this.notifications = data.notifications.data;
                        this.unreadCount = data.unread_count ?? data.count ?? this.notifications.filter(n => !n.read_at).length;
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
                                    detail: notification.title || 'Nouvelle notification' 
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