
self.addEventListener('install', async event => {

});

self.addEventListener('fetch', event => {

});

self.addEventListener('close', event => {
    //For root applications: just change "'./'" to "'/'"
    //Very important having the last forward slash on "new URL('./', location)..."
    event.notification.close();
    event.waitUntil(
        clients.matchAll().then(matchedClients =>
        {
            for (let client of matchedClients)
            {
                if (client.url.indexOf(rootUrl) >= 0)
                {
                    return client.focus();
                }
            }

            return clients.openWindow(rootUrl).then(function (client) { client.focus(); });
        })
    );
})