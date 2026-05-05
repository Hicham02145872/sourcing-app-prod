# Guide Détaillé : Implémentation d'une Progressive Web App (PWA) avec Laravel

## 1. Introduction et Concepts Clés

Ce guide détaille la procédure pour transformer votre application web Laravel en une **Progressive Web App (PWA)** installable. Nous nous appuierons sur le package `silviolleite/laravel-pwa` pour simplifier le processus.

Une PWA repose sur trois piliers techniques que le package va nous aider à gérer :
1.  **Le Manifeste (`manifest.json`)** : Un fichier JSON qui décrit votre application (nom, icônes, couleurs, etc.) au système d'exploitation du téléphone.
2.  **Le Service Worker** : Un script JavaScript qui s'exécute en arrière-plan. Il est essentiel pour la gestion du cache (fonctionnement hors-ligne) et les notifications push.
3.  **Les Icônes** : Un jeu d'icônes de différentes tailles pour s'adapter à tous les appareils.

**Prérequis fondamental :** Votre site doit être servi en **HTTPS**. C'est une condition non négociable pour le fonctionnement des PWA.

---

## 2. Étape 1 : Installation du Package

Ouvrez votre terminal à la racine de votre projet Laravel et exécutez la commande suivante pour installer le package :
```bash
composer require silviolleite/laravel-pwa
```

---

## 3. Étape 2 : Publication et Personnalisation du Manifeste

Le manifeste est le "passeport" de votre PWA. Nous allons d'abord publier le fichier de configuration du package pour le personnaliser.

1.  **Publier la configuration :**
    ```bash
    php artisan vendor:publish --provider="LaravelPWA\Providers\LaravelPWAServiceProvider"
    ```
    Cette commande crée un nouveau fichier de configuration : `config/laravelpwa.php`.

2.  **Personnaliser `config/laravelpwa.php` :**
    Ouvrez ce fichier et modifiez les informations pour qu'elles correspondent à votre application. Voici les champs les plus importants :
    ```php
    'name' => 'Sourcing App', // Nom complet de l'application
    'short_name' => 'Sourcing', // Nom court affiché sous l'icône

    'start_url' => '/', // Page de démarrage de l'application

    'background_color' => '#ffffff', // Couleur de l'écran de chargement (splash screen)
    'theme_color' => '#000000', // Couleur de la barre d'état du téléphone

    'display' => 'standalone', // 'standalone' pour une sensation d'application native
    'orientation' => 'portrait', // 'portrait', 'landscape' ou 'any'
    ```

---

## 4. Étape 3 : Génération et Personnalisation des Icônes et du Service Worker

Maintenant que la configuration de base est prête, nous allons laisser le package générer tous les fichiers nécessaires.

1.  **Générer les fichiers PWA :**
    ```bash
    php artisan laravel-pwa:publish
    ```
2.  **Qu'est-ce que cette commande a fait ?**
    *   Elle a créé un jeu complet d'icônes dans `public/images/icons/`. Ces icônes sont utilisées pour l'écran d'accueil, l'écran de chargement, etc.
    *   Elle a créé le fichier `public/serviceworker.js`.
    *   Elle a créé une vue pour la page hors-ligne : `resources/views/vendor/laravelpwa/offline.blade.php`.

3.  **Action requise : Personnaliser les Icônes**
    *   Vous **devez** remplacer les icônes générées par les vôtres.
    *   Préparez votre logo principal (ex: 512x512 pixels) et utilisez un outil en ligne ("PWA icon generator") pour générer toutes les tailles nécessaires.
    *   Remplacez les fichiers dans `public/images/icons/` en conservant les mêmes noms et dimensions.

---

## 5. Étape 4 : Comprendre et Utiliser le Service Worker

Le Service Worker est le cerveau de votre PWA. Par défaut, celui fourni par le package a un objectif principal : **le fonctionnement hors-ligne**.

- **Stratégie de Cache :** Le Service Worker intercepte les requêtes réseau. Si l'utilisateur est en ligne, il charge le contenu depuis le serveur. Si l'utilisateur est hors-ligne, il essaie de servir les pages et les ressources (CSS, JS) depuis le cache.
- **Page Hors-Ligne :** Si une page demandée n'est pas dans le cache et que l'utilisateur est hors-ligne, la PWA affichera le contenu de la vue `offline.blade.php`. Vous pouvez (et devriez) personnaliser cette page pour afficher un message convivial.
- **Personnalisation Avancée :** Pour des besoins plus complexes (ex: mettre en cache des images spécifiques, des données d'API), vous pouvez modifier directement le fichier `public/serviceworker.js`.

---

## 6. Étape 5 : Intégration dans votre Layout Principal

Pour que le navigateur reconnaisse votre site comme une PWA, vous devez lier le manifeste et le service worker dans votre page HTML. Le package offre une directive Blade qui s'en occupe pour vous.

Ouvrez votre fichier de layout principal (souvent `resources/views/layouts/app.blade.php`) et ajoutez la directive `@laravelPWA` dans la balise `<head>` :

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- ... autres balises meta ... -->

    @laravelPWA

    <!-- ... vos styles CSS, etc. ... -->
</head>
<body>
    <!-- ... contenu de votre page ... -->
</body>
</html>
```
Cette simple ligne va générer toutes les balises `meta` et `link` nécessaires.

---

## 7. Étape 7 : Intégration des Notifications Push (Type WhatsApp)

L'une des fonctionnalités les plus puissantes des PWA est la capacité à envoyer des notifications push qui apparaissent sur l'écran de l'utilisateur même lorsque l'application est fermée. Voici un guide détaillé pour les implémenter.

L'architecture se divise en 3 parties :
1.  **Backend (Laravel)** : Prépare et envoie les notifications.
2.  **Frontend (JavaScript Client)** : Demande la permission à l'utilisateur et l'abonne.
3.  **Service Worker** : Reçoit le push et affiche la notification.

### 7.1. Partie 1 : Modifications du Backend

#### A. Installation de la librairie Web-Push
Nous avons besoin d'une librairie pour gérer le protocole Web Push. La plus standard est `web-push/web-push`.
```bash
composer require web-push/web-push
```

#### B. Génération des Clés VAPID
VAPID (Voluntary Application Server Identification) est un standard qui permet au serveur d'application de s'identifier de manière sécurisée auprès des services de push (Google, Apple, etc.).

1.  Générez vos clés via la librairie :
    ```bash
    php vendor/bin/web-push generate-keys
    ```
2.  Ajoutez les clés générées à votre fichier `.env` :
    ```
    VAPID_PUBLIC_KEY=BG...
    VAPID_PRIVATE_KEY=...
    ```

#### C. Stockage des Abonnements
Chaque appareil/navigateur qu'un utilisateur abonne génère un "abonnement" unique (un objet JSON). Nous devons le stocker.

1.  **Créer une migration** pour ajouter une colonne à la table `users` :
    ```bash
    php artisan make:migration add_push_subscriptions_to_users_table --table=users
    ```
2.  **Modifier la migration** : Nous utilisons un champ `JSON` pour stocker une liste d'abonnements (un utilisateur peut avoir plusieurs appareils).
    ```php
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('push_subscriptions')->nullable()->after('fcm_token');
        });
    }
    ```
3.  Exécutez la migration : `php artisan migrate`.

#### D. Créer une Route pour gérer l'Abonnement
Ajoutez deux routes dans `routes/web.php` (protégées par le middleware `auth`) pour que le frontend puisse envoyer et supprimer l'abonnement.
```php
Route::middleware('auth')->group(function () {
    // ...
    Route::post('/push-subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
    Route::delete('/push-subscriptions', [App\Http\Controllers\PushSubscriptionController::class, 'destroy'])->name('push-subscriptions.destroy');
});
```
Vous devrez créer le `PushSubscriptionController` correspondant pour gérer la logique de sauvegarde et de suppression.

### 7.2. Partie 2 : Modifications du Frontend (JavaScript)

Créez un nouveau fichier JS (ex: `resources/js/push-manager.js`) et intégrez-le dans votre layout.

```javascript
// push-manager.js

document.addEventListener('DOMContentLoaded', () => {
    const pushButton = document.getElementById('enable-push-notifications');
    if (pushButton) {
        pushButton.addEventListener('click', askForNotificationPermission);
    }
});

async function askForNotificationPermission() {
    if (!('Notification' in window) || !('ServiceWorkerRegistration' in window)) {
        alert("Votre navigateur ne supporte pas les notifications push.");
        return;
    }

    const permission = await Notification.requestPermission();
    if (permission === 'granted') {
        console.log('Permission accordée.');
        subscribeUserToPush();
    }
}

async function subscribeUserToPush() {
    const serviceWorkerRegistration = await navigator.serviceWorker.ready;
    const existingSubscription = await serviceWorkerRegistration.pushManager.getSubscription();

    if (existingSubscription) {
        console.log('Utilisateur déjà abonné.');
        // Peut-être juste vérifier que le backend a bien l'info
        return;
    }

    const vapidPublicKey = 'METTRE_VOTRE_VAPID_PUBLIC_KEY_ICI';
    const subscription = await serviceWorkerRegistration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
    });

    // Envoyer l'abonnement au backend
    await sendSubscriptionToBackend(subscription);
}

function sendSubscriptionToBackend(subscription) {
    return fetch('/push-subscriptions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(subscription),
    });
}

// Fonction utilitaire pour convertir la clé VAPID
function urlBase64ToUint8Array(base64String) {
    // ... (code standard trouvable en ligne)
}
```

### 7.3. Partie 3 : Réception dans le Service Worker

Modifiez le fichier `public/serviceworker.js` généré par le package PWA.

```javascript
// Dans public/serviceworker.js

// ... (en bas du fichier)

self.addEventListener('push', function (event) {
    if (!event.data) {
        return;
    }
    const data = event.data.json();
    
    const title = data.title || "Nouvelle Notification";
    const options = {
        body: data.body,
        icon: data.icon || '/images/icons/icon-192x192.png',
        badge: '/images/icons/icon-96x96.png', // Icône pour la barre de statut Android
        data: {
            url: data.url // URL à ouvrir au clic
        }
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close(); // Ferme la notification
    
    // Ouvre la fenêtre de l'application à l'URL spécifiée ou à la page d'accueil
    const urlToOpen = event.notification.data.url || '/';
    event.waitUntil(
        clients.openWindow(urlToOpen)
    );
});
```

### 7.4. Partie 4 : Envoyer une Notification depuis Laravel

Créons un Listener pour l'événement `SourcingOrderStatusChanged` qui enverra une notification push.

```php
// Exemple: app/Listeners/SendPushNotificationOnStatusChange.php

namespace App\Listeners;

use App\Events\SourcingOrderStatusChanged;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendPushNotificationOnStatusChange
{
    public function handle(SourcingOrderStatusChanged $event)
    {
        $user = $event->sourcingOrder->user;
        $subscriptions = $user->push_subscriptions ?? [];

        if (empty($subscriptions)) {
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => 'mailto:your-email@example.com',
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        $webPush = new WebPush($auth);

        $notificationPayload = [
            'title' => 'Mise à jour de votre commande',
            'body' => 'Le statut de votre commande #' . $event->sourcingOrder->id . ' est maintenant : ' . $event->sourcingOrder->status,
            'icon' => asset('images/icons/icon-192x192.png'),
            'url' => route('client.sourcing-orders.show', $event->sourcingOrder),
        ];

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create(json_decode($sub, true));
            $webPush->queueNotification($subscription, json_encode($notificationPayload));
        }

        foreach ($webPush->flush() as $report) {
            // Gérer les rapports d'envoi (ex: supprimer les abonnements expirés)
        }
    }
}
```
N'oubliez pas d'enregistrer ce nouveau Listener dans votre `EventServiceProvider`.

---

## 8. Étape 8 : Test et Déploiement

1.  **Tester en Local :** Lancez votre serveur (`php artisan serve`).
2.  **Utiliser les Outils de Développement du Navigateur :**
    *   Dans Chrome ou Firefox, ouvrez les Outils de Développement (`F12`).
    *   Allez dans l'onglet **Application** (ou "Storage" sur Firefox).
    *   Dans le menu de gauche, vous trouverez :
        - **Manifest** : Pour vérifier que votre manifeste est bien lu et que les informations sont correctes.
        - **Service Workers** : Pour voir si votre service worker est activé et en cours d'exécution. Vous pouvez le forcer à se mettre à jour ou le désenregistrer pour vos tests.
        - **Storage** : Pour vider le cache si nécessaire.
3.  **Simuler l'Installation :** Sur la version de bureau de Chrome, une icône "Installer" apparaît généralement dans la barre d'adresse si la PWA est correctement configurée. Sur mobile, le navigateur proposera l'ajout à l'écran d'accueil.
4.  **Déploiement :** Une fois que tout fonctionne en local, déployez votre code sur votre serveur de production. Assurez-vous que le site est bien accessible en **HTTPS**.

---

## 9. Conclusion

En suivant ces étapes, vous aurez transformé votre application Laravel en une PWA fonctionnelle. L'utilisateur pourra l'installer sur son téléphone et l'utiliser comme une application native, avec un investissement en développement très faible. C'est la solution parfaite pour étendre la portée de votre application web vers le mobile de manière fluide et moderne.
