/*
 * Service Worker — IUFP (G_universite)
 * Stratégie :
 *   - Navigations (pages)  : réseau d'abord → cache → page hors-ligne.
 *   - Assets (/assets/...) : cache d'abord + rafraîchissement réseau en arrière-plan.
 *   - Le reste            : réseau, repli cache.
 * Les requêtes non-GET (POST de formulaires) ne sont JAMAIS interceptées.
 */
var CACHE = 'gu-cache-v7';
var BASE = new URL('./', self.location).href;          // .../public/
var OFFLINE = new URL('offline.html', self.location).href;

// Coquille minimale mise en cache à l'installation.
var PRECACHE = [
  OFFLINE,
  BASE + 'manifest.webmanifest',
  BASE + 'assets/css/theme.css',
  BASE + 'assets/css/gu-components.css',
  BASE + 'assets/css/gu-shell.css',
  BASE + 'assets/images/logo1.png',
  BASE + 'assets/images/pwa/icon-192.png?v=5',
  BASE + 'assets/images/pwa/icon-512.png?v=5'
];

self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open(CACHE).then(function (cache) {
      // add() individuel : un asset manquant ne fait pas échouer toute l'installation.
      return Promise.all(PRECACHE.map(function (url) {
        return cache.add(url).catch(function () { /* ignore */ });
      }));
    }).then(function () { return self.skipWaiting(); })
  );
});

self.addEventListener('activate', function (event) {
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(keys.map(function (k) {
        if (k !== CACHE) { return caches.delete(k); }
      }));
    }).then(function () { return self.clients.claim(); })
  );
});

self.addEventListener('fetch', function (event) {
  var req = event.request;

  // On ne gère que le GET (les POST/formulaires passent directement au réseau).
  if (req.method !== 'GET') { return; }

  var url = new URL(req.url);

  // On ne gère que le même domaine (les CDN externes vont au réseau).
  if (url.origin !== self.location.origin) { return; }

  // Pages (navigation) : réseau d'abord, repli cache puis page hors-ligne.
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req).catch(function () {
        return caches.match(req).then(function (cached) {
          return cached || caches.match(OFFLINE);
        });
      })
    );
    return;
  }

  // Assets statiques : cache d'abord, mise à jour réseau en arrière-plan.
  if (url.pathname.indexOf('/assets/') !== -1) {
    event.respondWith(
      caches.match(req).then(function (cached) {
        var network = fetch(req).then(function (res) {
          if (res && res.status === 200 && res.type === 'basic') {
            var copy = res.clone();
            caches.open(CACHE).then(function (cache) { cache.put(req, copy); });
          }
          return res;
        }).catch(function () { return cached; });
        return cached || network;
      })
    );
    return;
  }

  // Par défaut : réseau, repli cache.
  event.respondWith(
    fetch(req).catch(function () { return caches.match(req); })
  );
});
