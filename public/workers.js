let installEvent = null;
let installButton = document.getElementById("install");





window.addEventListener("load", () => {
    navigator.serviceWorker.register("/service-worker.js", { scope : '/worker' })
    .then(registration => {
        console.log("Service Worker is registered", registration);
    })
    .catch(err => {
        console.error("Registration failed:", err);
    });
});

window.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault();
    console.log("Ready to install...");
    installEvent = e;
    
});

setTimeout(cacheLinks, 500);

function cacheLinks() {
    caches.open("pwa").then(function(cache) {
        let linksFound = [];
        document.querySelectorAll("a").forEach(function(a) {
            linksFound.push(a.href);
        });

        cache.addAll(linksFound);
    });
}

if(installButton) {
    console.log({installButton})
    installButton.addEventListener("click", function() {
        console.log('pp')
        installEvent.prompt();
    });
}
