/* Minimal service worker to avoid missing-file errors.
   Keep this file simple — do NOT force page reloads here. */
self.addEventListener('install', event => {
  self.skipWaiting();
});
self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});
self.addEventListener('fetch', event => {
  // Let the network handle requests by default.
});

// Optional: basic push handler if Firebase expects it (no reloads)
self.addEventListener('push', function(event) {
  let data = {};
  try { data = event.data.json(); } catch (e) {}
  const title = (data && data.notification && data.notification.title) || 'Notification';
  const options = {
    body: (data && data.notification && data.notification.body) || '',
    icon: '/web-app-manifest-192x192.png'
  };
  event.waitUntil(self.registration.showNotification(title, options));
});