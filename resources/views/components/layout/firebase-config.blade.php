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

    let existingNotificationSourcingRequestIds = new Set();

    async function fetchExistingNotifications() {
        try {
            const response = await fetch('{{ route("notifications.index") }}', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                data.forEach(notification => {
                    if (notification.data && notification.data.sourcing_request_id) {
                        const srId = String(notification.data.sourcing_request_id); // Ensure string type
                        existingNotificationSourcingRequestIds.add(srId);
                        console.log(`DEBUG: Added SR ID ${srId} (type: ${typeof srId}) from DB to Set.`);
                    }
                });
                console.log("✅ Existing notification IDs fetched:", existingNotificationSourcingRequestIds);
            }
        } catch (error) {
            console.error("❌ Error fetching existing notifications:", error);
        }
    }

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
        
        const sourcingRequestId = String(payload.data?.sourcing_request_id); // Ensure string type for comparison
        console.log(`DEBUG: FCM Payload SR ID: ${sourcingRequestId} (type: ${typeof sourcingRequestId})`);
        console.log(`DEBUG: existingNotificationSourcingRequestIds contains ${sourcingRequestId}? ${existingNotificationSourcingRequestIds.has(sourcingRequestId)}`);

        // Only display if it's not already in the database notifications
        if (sourcingRequestId && existingNotificationSourcingRequestIds.has(sourcingRequestId)) {
            console.log(`ℹ️ Notification for SR ID ${sourcingRequestId} already exists in DB, skipping display.`);
            return;
        }

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

</script>