// Nom du cache (changer la version force la mise à jour)
const CACHE_NAME = 'nedcore-cache-v2';

// Fichiers essentiels de l'application (la "coquille")
// On met UNIQUEMENT les fichiers qui existent VRAIMENT.
const urlsToCache = [
  '/loginPointe',               // Page de connexion
  '/manifest.json',       // Le manifest
  '/assets/css/style.css',// Votre CSS principal
  '/assets/img/authentication/mobile.png' // Votre logo
  // NOTE: Ajoutez ici d'autres fichiers JS/CSS si vous en avez
];

// 1. Installation du Service Worker
self.addEventListener('install', event => {
  console.log('Service Worker: Installation v2...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Service Worker: Mise en cache des fichiers de la coquille');
        // On utilise addAll, mais avec une liste de fichiers correcte
        return cache.addAll(urlsToCache);
      })
      .then(() => self.skipWaiting())
  );
});

// 2. Activation et nettoyage des anciens caches
self.addEventListener('activate', event => {
  console.log('Service Worker: Activation v2...');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('Service Worker: Nettoyage de l\'ancien cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// 3. Interception des requêtes (Stratégie "Network falling back to cache")
// C'est beaucoup plus robuste pour une application dynamique comme Laravel.
self.addEventListener('fetch', event => {
  // On ne met pas en cache les requêtes autres que GET (ex: POST pour le login)
  if (event.request.method !== 'GET') {
    return;
  }
  
  event.respondWith(
    // D'abord, on essaie d'aller sur le réseau
    fetch(event.request)
      .then(networkResponse => {
        // Si ça marche, on met la réponse en cache pour une utilisation future
        // et on la retourne au navigateur.
        const responseToCache = networkResponse.clone();
        caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, responseToCache);
        });
        return networkResponse;
      })
      .catch(() => {
        // Si le réseau échoue (mode hors-ligne), on cherche dans le cache.
        console.log('Service Worker: Réseau échoué, recherche dans le cache...');
        return caches.match(event.request);
      })
  );
});


// public/sw.js
self.addEventListener('push', (event) => {
  const payload = event.data ? event.data.json() : {};
  const title = payload.title || 'Notification';
  const options = {
    body: payload.body || '',
    icon: payload.icon || '/assets/img/authentication/mobile.png',
    badge: payload.badge || '/assets/img/authentication/mobile.png',
    data: { url: payload.url || '/notifications' }
  };
  event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  const url = event.notification.data?.url || '/notifications';
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientsArr) => {
        for (const client of clientsArr) {
          if ('focus' in client && client.url.includes(self.location.origin)) {
            return client.focus();
          }
        }
        return clients.openWindow(url);
      })
  );
});
