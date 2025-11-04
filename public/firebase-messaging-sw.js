importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging.js');

const firebaseConfig = {
    apiKey: "AIzaSyBgVdeNSCKN1Dhmxpm_pKchE_AfmRF6w_E",
    authDomain: "sourcing-app-1a786.firebaseapp.com",
    projectId: "sourcing-app-1a786",
    storageBucket: "sourcing-app-1a786.firebasestorage.app",
    messagingSenderId: "930979287595",
    appId: "1:930979287595:web:3b2710de5de42c3e90d06e"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// Notification quand l'app est fermée
messaging.onBackgroundMessage((payload) => {
    console.log('🔔 Background notification:', payload);
    
    const notificationTitle = payload.notification?.title || 'Nouvelle notification';
    const notificationOptions = {
        body: payload.notification?.body || '',
        icon: payload.notification?.icon || '/icon-192x192.png',
        badge: '/badge-72x72.png',
        tag: payload.data?.notification_id || 'default',
        data: payload.data || {},
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

// Clique sur la notification
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const clickAction = event.notification.data?.click_action;
    
    if (clickAction) {
        clients.matchAll({ type: 'window' }).then((clientList) => {
            for (let client of clientList) {
                if (client.url === clickAction && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(clickAction);
            }
        });
    }
});
