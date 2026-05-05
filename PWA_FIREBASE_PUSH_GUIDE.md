# Guide Détaillé : Notifications Push pour votre PWA avec Firebase

## 1. Contexte et Objectif

Vous utilisez déjà Firebase Cloud Messaging (FCM) dans votre application, ce qui est parfait. Notre objectif est de connecter la version Web (PWA) de votre application à cette infrastructure existante.

L'objectif est simple : faire en sorte que le navigateur de l'utilisateur obtienne un **token FCM** et l'envoie à votre backend pour qu'il soit stocké dans la colonne `fcm_token` de la table `users`, exactement comme le ferait une application mobile native.

Nous n'avons **pas besoin** d'installer de nouvelles librairies côté backend (comme `web-push/web-push`) ni de gérer de clés VAPID. Votre backend est déjà prêt.

---

## 2. Étape 1 : Créer le Service Worker pour Firebase

Firebase a besoin de son propre fichier service worker pour gérer la réception des messages en arrière-plan.

1.  **Créez un nouveau fichier** à la racine de votre dossier `public` et nommez-le `firebase-messaging-sw.js`.

2.  **Ajoutez le contenu suivant** dans ce fichier. Ce code initialise Firebase dans le service worker.

    ```javascript
    // public/firebase-messaging-sw.js

    // IMPORTANT: version 8.10.0 est un exemple, utilisez la version que vous avez dans votre app
    importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
    importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

    // Vos clés de configuration Firebase (vous pouvez les trouver dans votre console Firebase)
    // Assurez-vous qu'elles sont identiques à celles utilisées côté client.
    const firebaseConfig = {
        apiKey: "YOUR_API_KEY",
        authDomain: "YOUR_AUTH_DOMAIN",
        projectId: "YOUR_PROJECT_ID",
        storageBucket: "YOUR_STORAGE_BUCKET",
        messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
        appId: "YOUR_APP_ID"
    };

    // Initialise l'application Firebase dans le service worker
    firebase.initializeApp(firebaseConfig);

    // Récupère une instance de Firebase Messaging pour gérer les messages en arrière-plan.
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage((payload) => {
        console.log('[firebase-messaging-sw.js] Received background message ', payload);
        
        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '/images/icons/icon-192x192.png' // ou l'icône depuis le payload
        };

        self.registration.showNotification(notificationTitle, notificationOptions);
    });
    ```
    **Action :** Remplacez les valeurs de `firebaseConfig` par vos propres clés.

---

## 3. Étape 2 : Faire cohabiter les deux Service Workers

Le package PWA a généré son propre service worker (`public/serviceworker.js`). Nous devons simplement lui dire d'importer celui de Firebase.

**Modifiez le fichier `public/serviceworker.js`** et ajoutez la ligne suivante tout en haut du fichier :

```javascript
// public/serviceworker.js

importScripts('/firebase-messaging-sw.js');

// ... reste du code du service worker de la PWA ...
```
Cette instruction garantit que les deux service workers fonctionneront ensemble.

---

## 4. Étape 3 : Demander la Permission et Envoyer le Token

Cette partie gère l'interaction avec l'utilisateur côté client.

1.  **Assurez-vous que le SDK Firebase est chargé** dans votre layout principal (`app.blade.php`).
    ```html
    <!-- Dans app.blade.php, avant votre JS principal -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
    ```

2.  **Créez un script pour gérer la logique d'abonnement.** Vous pouvez ajouter ce code dans un fichier JS existant ou en créer un nouveau (`push-handler.js`).

    ```javascript
    // Initialise Firebase côté client avec les mêmes clés
    const firebaseConfig = {
        apiKey: "YOUR_API_KEY",
        // ... autres clés
    };
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // Fonction pour demander la permission et obtenir le token
    async function subscribeToPushNotifications() {
        try {
            // 1. Demander la permission à l'utilisateur
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                console.log('Permission de notification refusée.');
                return;
            }

            console.log('Permission accordée. Récupération du token...');
            
            // 2. Récupérer le token FCM
            // Le deuxième argument est la clé publique VAPID de votre projet Firebase
            // Vous la trouverez dans Console Firebase > Projet > Paramètres > Cloud Messaging > "Paire de clés pour les applications web"
            const currentToken = await messaging.getToken({ vapidKey: "YOUR_FIREBASE_VAPID_KEY" });

            if (currentToken) {
                console.log('Token FCM obtenu :', currentToken);
                // 3. Envoyer le token au backend
                await sendTokenToServer(currentToken);
            } else {
                console.log('Impossible d\'obtenir le token FCM. Assurez-vous d\'avoir un firebase-messaging-sw.js.');
            }

        } catch (error) {
            console.error('Erreur lors de l\'abonnement aux notifications push :', error);
        }
    }

    // Fonction pour envoyer le token à votre route existante
    async function sendTokenToServer(token) {
        try {
            await fetch('/fcm/token/update', {
                method: 'POST',
                headers': {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ fcm_token: token })
            });
            console.log('Token envoyé au serveur avec succès.');
        } catch (error) {
            console.error('Erreur lors de l\'envoi du token au serveur :', error);
        }
    }

    // Exemple de déclenchement : Lier cette fonction à un bouton dans votre interface de profil utilisateur
    // <button id="enable-notifications-btn">Activer les notifications</button>
    const notificationButton = document.getElementById('enable-notifications-btn');
    if (notificationButton) {
        notificationButton.addEventListener('click', subscribeToPushNotifications);
    }
    ```
    **Action :** Ajoutez un bouton avec l'id `enable-notifications-btn` dans l'interface de votre application (par exemple, dans la page de profil de l'utilisateur) pour lui permettre d'activer les notifications.

---

## 5. Résumé du Flux de Données

1.  L'utilisateur clique sur le bouton "Activer les notifications" dans son profil.
2.  Le script JavaScript demande la permission au navigateur.
3.  Si la permission est accordée, le script demande un **token FCM** à Firebase.
4.  Ce token est envoyé via une requête `POST` à votre route Laravel existante (`/fcm/token/update`).
5.  Votre `UserController` (ou équivalent) sauvegarde ce token dans la colonne `fcm_token` pour l'utilisateur authentifié.
6.  Plus tard, un événement comme `SourcingOrderStatusChanged` est déclenché.
7.  Votre listener (`SendSourcingOrderStatusUpdatedNotification`) voit que l'utilisateur a un `fcm_token` et utilise le `FcmChannel` pour envoyer une notification.
8.  Laravel communique avec les serveurs de Firebase.
9.  Firebase envoie la notification à l'appareil de l'utilisateur.
10. Le service worker `firebase-messaging-sw.js` sur l'appareil de l'utilisateur intercepte le message et affiche la notification à l'écran.

Votre infrastructure existante est parfaitement adaptée. Le seul travail consiste à "brancher" la partie frontend (PWA) pour qu'elle puisse s'enregistrer auprès de Firebase.
