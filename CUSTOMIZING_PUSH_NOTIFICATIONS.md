# Guide Complémentaire : Créer des Notifications Push Riches (Type WhatsApp)

## 1. Objectif : Aller au-delà de la Simple Notification

Vous souhaitez que vos notifications soient aussi engageantes que celles des applications natives. Une notification de type WhatsApp n'est pas juste un titre et un texte ; elle inclut une icône, parfois une image, elle fait vibrer le téléphone et propose des actions rapides.

Nous pouvons recréer la majorité de ces fonctionnalités en utilisant les options avancées de l'API Web Push.

---

## 2. Anatomie d'une Notification Riche

Lorsque notre Service Worker affiche une notification, il utilise la fonction `showNotification(title, options)`. Le secret réside dans l'objet `options`.

Voici les options les plus importantes pour une expérience riche :

- **`body`** : Le texte principal de la notification (le contenu du message).
- **`icon`** : Une petite icône, généralement le logo de votre application (similaire au petit logo WhatsApp).
- **`image`** : Une grande image affichée à l'intérieur de la notification. Idéal pour montrer une photo du produit.
- **`badge`** : Une petite icône monochrome utilisée par Android dans la barre de statut en haut de l'écran.
- **`vibrate`** : Permet de faire vibrer le téléphone. On peut définir une séquence (vibrer, pause, vibrer). Par exemple : `[200, 100, 200]`.
- **`tag`** : Un identifiant pour la notification. Si vous envoyez une nouvelle notification avec le même `tag`, elle remplacera l'ancienne au lieu de s'empiler. Très utile pour les mises à jour de statut.
- **`actions`** : **La fonctionnalité la plus importante pour l'interaction.** Permet d'ajouter des boutons cliquables directement dans la notification.

---

## 3. Étape 1 : Améliorer le Payload envoyé par Laravel

Pour utiliser ces options, notre backend doit envoyer un "payload" (charge utile) plus riche au service de push.

Modifions le Listener `SendPushNotificationOnStatusChange.php` (créé dans le guide précédent) pour inclure ces nouvelles données.

```php
// Exemple amélioré dans app/Listeners/SendPushNotificationOnStatusChange.php

public function handle(SourcingOrderStatusChanged $event)
{
    // ... (code pour récupérer $user, $subscriptions, et initialiser $webPush) ...

    $order = $event->sourcingOrder;
    $statusLabel = $this->getStatusLabel($order->status); // Méthode pour traduire le statut

    // Supposons que vous ayez une image pour le produit
    $productImageUrl = $order->quotation->sourcingRequest->image_url ?? asset('images/default-product.png');

    $notificationPayload = [
        'title' => 'Statut : ' . $statusLabel,
        'body' => 'Votre commande #' . $order->id . ' pour "' . $order->quotation->sourcingRequest->product_name . '"',
        'icon' => asset('images/icons/icon-192x192.png'),
        'image' => $productImageUrl, // Grande image dans la notification
        'badge' => asset('images/icons/badge-monochrome.png'), // Icône pour la barre de statut
        
        // Données non visibles, mais utiles pour gérer le clic
        'data' => [
            'url' => route('client.sourcing-orders.show', $order)
        ],
        
        // Actions interactives
        'actions' => [
            [
                'action' => 'view_order', // un identifiant pour l'action
                'title' => 'Voir la Commande'
            ],
            [
                'action' => 'track_package',
                'title' => 'Suivre le Colis'
            ]
        ]
    ];

    // ... (code pour envoyer la notification avec $webPush->queueNotification) ...
}

private function getStatusLabel(string $status): string
{
    // ... Logique pour convertir 'in_transit_china' en 'En Transit (Chine)' ...
    return ucfirst(str_replace('_', ' ', $status));
}
```

---

## 4. Étape 2 : Mettre à jour le Service Worker pour Gérer les Notifications Riches

Maintenant, le service worker doit être capable d'interpréter ce payload plus riche.

Modifiez le fichier `public/serviceworker.js` (ou `firebase-messaging-sw.js` si vous utilisez uniquement Firebase pour la réception).

```javascript
// public/serviceworker.js OU firebase-messaging-sw.js

// Écouteur d'événement PUSH
self.addEventListener('push', function (event) {
    if (!event.data) return;
    const data = event.data.json();

    const title = data.title || "Nouvelle Notification";
    const options = {
        body: data.body,
        icon: data.icon || '/images/icons/icon-192x192.png',
        badge: data.badge || '/images/icons/badge-monochrome.png',
        image: data.image, // Afficher la grande image
        vibrate: [200, 100, 200], // Faire vibrer le téléphone
        tag: 'sourcing-order-update-' + data.data.order_id, // Regrouper les notifications par commande
        data: {
            url: data.data.url // URL à ouvrir en cas de clic
        },
        actions: data.actions // Ajouter les boutons !
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

// Écouteur de CLIC sur la notification
self.addEventListener('notificationclick', function (event) {
    event.notification.close(); // Toujours fermer la notification après un clic

    // Gérer les clics sur les boutons d'action
    if (event.action === 'view_order') {
        // Ouvre l'URL de la commande
        clients.openWindow(event.notification.data.url);
    } else if (event.action === 'track_package') {
        // Redirige vers une page de suivi externe, par exemple
        clients.openWindow('https://un-site-de-suivi.com/?track=' + event.notification.data.tracking_number);
    } else {
        // Comportement par défaut : si on clique sur le corps de la notif
        clients.openWindow(event.notification.data.url);
    }
}, false);
```

---

## 5. Limites par Rapport à une Application 100% Native

Même avec ces améliorations, il y a de légères différences avec une application native :
- **Sons personnalisés** : Le son de la notification est généralement celui par défaut du système d'exploitation de l'utilisateur. Il est très difficile d'imposer un son personnalisé de manière fiable.
- **Badge sur l'icône de l'application** : Le petit point rouge sur l'icône de l'application (`App Badge`) est géré par une API séparée (la Badging API). C'est une amélioration possible, mais qui demande une étape d'implémentation supplémentaire.
- **Groupement des notifications** : Le comportement exact du groupement (`tag`) peut varier légèrement selon la version d'Android et le fabricant du téléphone.

## Conclusion

En enrichissant le payload envoyé par votre backend et en utilisant les options avancées dans votre service worker, vous pouvez créer une expérience de notification push très proche de celle d'une application native comme WhatsApp, avec des images, des vibrations et des boutons d'action qui améliorent considérablement l'engagement de l'utilisateur.
