const cacheName = 'momentus-v1';

self.addEventListener('install', event => {
    console.log('Service Worker installing.');
});

self.addEventListener('activate', event => {
    console.log('Service Worker activating.');

    event.waitUntil(
        caches
            .keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cache => {
                        if (cache !== cacheName) {
                            console.log('Service Worker deleting cache.');
                            return caches.delete(cache);
                        }
                    })
                )
            })
    );
});

self.addEventListener('fetch', event => {
    console.log('Service Worker Fetching.');

    if (!event.request.url.startsWith('http')) {
        return
    }

    event.respondWith(
        fetch(event.request)
            .then(response => {
                const responseToCache = response.clone();

                caches
                    .open(cacheName)
                    .then(cache => {
                        cache.put(event.request, responseToCache);
                    });

                return response;
            })
            .catch(error => caches.match(event.request).then(response => response))
    );
});
