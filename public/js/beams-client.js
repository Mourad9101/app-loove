// Vérifier que toutes les dépendances sont disponibles
if (typeof PusherPushNotifications === 'undefined') {
    console.warn('Pusher Beams SDK non chargé');
} else if (!PUSHER_NOTIF_INSTANCE_ID) {
    console.warn('Instance ID Pusher non configuré');
} else if (!USER_ID) {
    console.warn('Utilisateur non connecté');
} else {
    // Enregistrement du service worker
    if ('serviceWorker' in navigator) {
        // Récupérer l'URL de base du site
        const baseUrl = window.location.origin + '/Evergem3';
        
        navigator.serviceWorker.register(`${baseUrl}/service-worker.js`)
            .then((registration) => {
                console.log('Service Worker enregistré avec succès:', registration.scope);
                initializeBeams();
            })
            .catch((error) => {
                console.error('Erreur lors de l\'enregistrement du Service Worker:', error);
            });
    } else {
        console.warn('Service Workers non supportés');
        initializeBeams();
    }
}

// Fonction d'initialisation de Beams
function initializeBeams() {
    try {
        const beamsClient = new PusherPushNotifications.Client({
            instanceId: 'd7745148-a3be-4dfc-91b4-fc00b3c477f2',
        });

        beamsClient.start()
            .then(() => beamsClient.addDeviceInterest('hello'))
            .then(() => console.log('Successfully registered and subscribed!'))
            .catch(console.error);
    } catch (error) {
        console.error('Error initializing Beams:', error);
    }
} 