// Import Firebase libraries
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Initialize Firebase with your config
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

// Handle background messages when app is closed
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message:', payload);
  
  // Check if notification object exists
  if (!payload.notification && !payload.data) {
    console.warn('Payload has no notification or data');
    return;
  }

  // Extract notification data with fallbacks
  // If no notification object, create one from data
  const notificationTitle = payload.notification?.title || 
                           payload.data?.title || 
                           'Nouvelle notification';
  
  const notificationBody = payload.notification?.body || 
                          payload.data?.body || 
                          '';
  
  const notificationIcon = payload.notification?.icon || 
                          payload.data?.icon || 
                          '/firebase-logo.png';
  
  const notificationOptions = {
    body: notificationBody,
    icon: notificationIcon,
    badge: '/badge-72x72.png',
    tag: payload.data?.notification_id || 'notification-default',
    data: payload.data || {},
    requireInteraction: false
  };

  // Show the notification
  self.registration.showNotification(notificationTitle, notificationOptions)
    .catch(error => console.error('Error showing notification:', error));
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
  console.log('[firebase-messaging-sw.js] Notification clicked:', event);
  
  event.notification.close();
  
  // Get the URL to open from notification data
  const urlToOpen = event.notification.data?.click_action || '/';
  
  // Validate URL is from same origin
  let targetUrl = urlToOpen;
  try {
    const url = new URL(urlToOpen, self.location.origin);
    targetUrl = url.toString();
  } catch (error) {
    console.warn('Invalid URL in click_action:', urlToOpen);
    targetUrl = '/';
  }

  // Try to focus existing window, otherwise open new one
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        // Look for a window with matching origin
        for (let client of clientList) {
          if (new URL(client.url).origin === new URL(targetUrl).origin && 'focus' in client) {
            return client.focus();
          }
        }
        // If no matching window, open new one
        if (clients.openWindow) {
          return clients.openWindow(targetUrl);
        }
      })
      .catch(error => console.error('Error handling notification click:', error))
  );
});

// Handle notification close
self.addEventListener('notificationclose', (event) => {
  console.log('[firebase-messaging-sw.js] Notification closed:', event);
  // You can track analytics here if needed
});

console.log('[firebase-messaging-sw.js] Service Worker loaded successfully');