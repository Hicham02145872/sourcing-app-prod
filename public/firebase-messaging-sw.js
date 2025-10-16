// Give the service worker access to Firebase SDK.
// This is a global script that will be executed in the service worker context.
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Initialize the Firebase app in the service worker by passing the generated config
// from the Firebase console.
const firebaseConfig = {
  apiKey: "AIzaSyBgVdeNSCKN1Dhmxpm_pKchE_AfmRF6w_E",
  authDomain: "sourcing-app-1a786.firebaseapp.com",
  projectId: "sourcing-app-1a786",
  storageBucket: "sourcing-app-1a786.firebasestorage.app",
  messagingSenderId: "930979287595",
  appId: "1:930979287595:web:3b2710de5de42c3e90d06e",
};

firebase.initializeApp(firebaseConfig);

// Retrieve firebase messaging
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/firebase-logo.png'
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});