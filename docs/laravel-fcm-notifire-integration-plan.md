# Plan d'Intégration Laravel FCM NotiFire

## Vue d'Ensemble

Laravel FCM NotiFire est une solution puissante pour envoyer des notifications push via Firebase Cloud Messaging (FCM) dans votre application Laravel. Cette intégration permettra d'envoyer des notifications en temps réel aux clients et administrateurs de votre application de sourcing.

---

## Avantages pour Votre Application

### 1. **Notifications Push en Temps Réel**
- Les clients reçoivent instantanément des notifications sur leurs appareils mobiles/web
- Alertes immédiates pour les changements de statut des commandes
- Notifications pour les nouvelles quotations, paiements, et expéditions

### 2. **Engagement Utilisateur Amélioré**
- Taux d'ouverture plus élevé que les emails (70% vs 20%)
- Les utilisateurs restent informés même sans ouvrir l'application
- Réduction du temps de réponse aux actions importantes

### 3. **Multi-Plateforme**
- Support Android, iOS, et Web (PWA)
- Une seule API pour toutes les plateformes
- Gestion centralisée des notifications

### 4. **Personnalisation Avancée**
- Notifications ciblées par rôle (client, admin, super-admin)
- Messages personnalisés avec données dynamiques
- Actions directes depuis les notifications (deep linking)

### 5. **Fiabilité et Scalabilité**
- Infrastructure Firebase robuste et éprouvée
- Gestion automatique des tokens expirés
- Support de millions de notifications simultanées

### 6. **Intégration avec Votre Système Existant**
- Compatible avec votre système de notifications Laravel actuel
- Fonctionne en parallèle avec les notifications database/email
- Réutilisation de vos classes de notifications existantes

---

## Prérequis

### 1. Compte Firebase
- [ ] Créer un projet Firebase sur [console.firebase.google.com](https://console.firebase.google.com)
- [ ] Activer Firebase Cloud Messaging (FCM)
- [ ] Télécharger le fichier de configuration `firebase-credentials.json`

### 2. Configuration Serveur
- [ ] PHP >= 8.2
- [ ] Laravel >= 12
- [ ] Extension PHP `gRPC` (optionnel, pour meilleures performances)
- [ ] Extension PHP `curl`

### 3. Configuration Client
- [ ] Application web avec support Service Worker
- [ ] Firebase SDK JavaScript pour le frontend

---

## Plan d'Implémentation

### Phase 1: Installation et Configuration Backend

#### Étape 1.1: Installer le Package
```bash
vendor\bin\sail composer require benwilkins/laravel-fcm-notification
```

#### Étape 1.2: Publier la Configuration
```bash
vendor\bin\sail artisan vendor:publish --provider="LaravelFCM\FCMServiceProvider"
```

#### Étape 1.3: Configurer les Variables d'Environnement
Ajouter dans `.env`:
```env
FCM_SERVER_KEY=your_server_key_here
FCM_SENDER_ID=your_sender_id_here
FCM_PROJECT_ID=your_project_id_here
```

#### Étape 1.4: Stocker les Credentials Firebase
- Placer `firebase-credentials.json` dans `storage/app/firebase/`
- Ajouter le chemin dans `.env`:
```env
FIREBASE_CREDENTIALS=storage/app/firebase/firebase-credentials.json
```

---

### Phase 2: Migration de la Base de Données

#### Étape 2.1: Créer la Table pour les Device Tokens
```bash
vendor\bin\sail artisan make:migration create_user_device_tokens_table
```

**Structure de la migration:**
```php
Schema::create('user_device_tokens', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('device_token')->unique();
    $table->string('device_type')->nullable(); // android, ios, web
    $table->string('device_name')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'device_token']);
});
```

#### Étape 2.2: Exécuter la Migration
```bash
vendor\bin\sail artisan migrate
```

---

### Phase 3: Création des Modèles et Services

#### Étape 3.1: Créer le Modèle UserDeviceToken
```bash
vendor\bin\sail artisan make:model UserDeviceToken
```

**Contenu du modèle:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDeviceToken extends Model
{
    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
        'device_name',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

#### Étape 3.2: Créer le Service FCM
```bash
vendor\bin\sail artisan make:class Services/FCMService
```

**Fonctionnalités du service:**
- Enregistrement des tokens
- Envoi de notifications push
- Gestion des tokens expirés
- Notifications groupées par rôle

---

### Phase 4: Adapter les Notifications Existantes

#### Étape 4.1: Modifier les Classes de Notification
Ajouter le canal FCM aux notifications existantes:

**Exemple avec `QuotationCreated`:**
```php
public function via($notifiable): array
{
    return ['database', 'fcm']; // Ajouter 'fcm'
}

public function toFcm($notifiable): FcmMessage
{
    return (new FcmMessage())
        ->setTitle('Nouvelle Quotation')
        ->setBody("Une quotation a été créée pour votre demande #{$this->quotation->sourcing_request_id}")
        ->setData([
            'quotation_id' => $this->quotation->id,
            'type' => 'quotation_created',
            'action_url' => route('client.quotations.show', $this->quotation),
        ])
        ->setPriority('high')
        ->setSound('default');
}
```

#### Étape 4.2: Notifications à Adapter
- [x] `QuotationCreated`
- [ ] `SourcingRequestStatusChanged`
- [ ] `PaymentReceived`
- [ ] `OrderShipped`
- [ ] `OrderDelivered`
- [ ] Toutes les autres notifications pertinentes

---

### Phase 5: Endpoints API pour les Device Tokens

#### Étape 5.1: Créer le Contrôleur
```bash
vendor\bin\sail artisan make:controller Api/DeviceTokenController
```

**Routes API à créer:**
```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
    Route::delete('/device-tokens/{token}', [DeviceTokenController::class, 'destroy']);
});
```

**Actions du contrôleur:**
- `store()`: Enregistrer un nouveau token
- `destroy()`: Supprimer un token (déconnexion)

---

### Phase 6: Intégration Frontend

#### Étape 6.1: Installer Firebase SDK
```bash
vendor\bin\sail npm install firebase
```

#### Étape 6.2: Créer le Service Worker
Créer `public/firebase-messaging-sw.js`:
```javascript
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_AUTH_DOMAIN",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_STORAGE_BUCKET",
    messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
    appId: "YOUR_APP_ID"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/logo.png',
        data: payload.data
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
```

#### Étape 6.3: Initialiser Firebase dans l'Application
Créer `resources/js/firebase.js`:
```javascript
import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export { messaging, getToken, onMessage };
```

#### Étape 6.4: Demander la Permission et Enregistrer le Token
```javascript
import { messaging, getToken } from './firebase';

async function requestNotificationPermission() {
    try {
        const permission = await Notification.requestPermission();
        
        if (permission === 'granted') {
            const token = await getToken(messaging, {
                vapidKey: 'YOUR_VAPID_KEY'
            });
            
            // Envoyer le token au backend
            await fetch('/api/device-tokens', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify({
                    device_token: token,
                    device_type: 'web',
                    device_name: navigator.userAgent
                })
            });
        }
    } catch (error) {
        console.error('Error getting notification permission:', error);
    }
}
```

---

### Phase 7: Tests et Validation

#### Étape 7.1: Tests Unitaires
```bash
vendor\bin\sail artisan make:test FCMNotificationTest --unit
```

**Tests à créer:**
- Test d'enregistrement de token
- Test d'envoi de notification
- Test de suppression de token expiré
- Test de notification groupée

#### Étape 7.2: Tests Fonctionnels
```bash
vendor\bin\sail artisan make:test DeviceTokenManagementTest
```

**Scénarios à tester:**
- Enregistrement d'un nouveau device token
- Réception de notification après création de quotation
- Suppression de token à la déconnexion
- Gestion des tokens multiples par utilisateur

#### Étape 7.3: Tests Manuels
- [ ] Tester sur navigateur Chrome (desktop)
- [ ] Tester sur navigateur Firefox (desktop)
- [ ] Tester sur Safari (iOS)
- [ ] Tester sur Chrome (Android)
- [ ] Vérifier les notifications en arrière-plan
- [ ] Vérifier les notifications au premier plan
- [ ] Tester le deep linking (clic sur notification)

---

### Phase 8: Optimisations et Fonctionnalités Avancées

#### Étape 8.1: Gestion des Tokens Expirés
Créer une commande artisan pour nettoyer les tokens:
```bash
vendor\bin\sail artisan make:command CleanExpiredDeviceTokens
```

Ajouter au scheduler dans `routes/console.php`:
```php
Schedule::command('tokens:clean-expired')->daily();
```

#### Étape 8.2: Notifications Groupées
Implémenter l'envoi de notifications à plusieurs utilisateurs:
```php
// Envoyer à tous les admins
$admins = User::where('role', 'admin')->get();
Notification::send($admins, new NewSourcingRequest($request));
```

#### Étape 8.3: Notifications Silencieuses
Pour les mises à jour de données sans alerte:
```php
public function toFcm($notifiable): FcmMessage
{
    return (new FcmMessage())
        ->setData(['silent' => true, 'update_type' => 'order_status'])
        ->setPriority('normal')
        ->setContentAvailable(true);
}
```

#### Étape 8.4: Analytics et Suivi
- Tracker le taux d'ouverture des notifications
- Mesurer l'engagement par type de notification
- Analyser les heures optimales d'envoi

---

## Cas d'Usage Spécifiques à Votre Application

### 1. Notifications Client

#### Nouvelle Quotation Reçue
```
Titre: "Nouvelle Quotation Disponible"
Corps: "Votre demande #123 a reçu une quotation de $500"
Action: Ouvrir la page de quotation
```

#### Changement de Statut de Commande
```
Titre: "Mise à Jour de Commande"
Corps: "Votre commande #456 est maintenant 'En Transit'"
Action: Voir le suivi de commande
```

#### Paiement Confirmé
```
Titre: "Paiement Reçu"
Corps: "Votre paiement de $500 a été confirmé"
Action: Voir les détails de paiement
```

### 2. Notifications Admin

#### Nouvelle Demande de Sourcing
```
Titre: "Nouvelle Demande"
Corps: "Client John Doe a créé une demande de sourcing"
Action: Voir la demande et créer une quotation
```

#### Nouveau Paiement Client
```
Titre: "Nouveau Paiement"
Corps: "Paiement de $500 reçu pour la commande #789"
Action: Vérifier et confirmer le paiement
```

#### Commande Prête à Expédier
```
Titre: "Commande Prête"
Corps: "Commande #789 est prête pour l'expédition"
Action: Mettre à jour le tracking
```

### 3. Notifications Super-Admin

#### Rapport Quotidien
```
Titre: "Rapport Quotidien"
Corps: "15 nouvelles commandes, $5000 de revenus aujourd'hui"
Action: Voir le dashboard
```

#### Alerte Système
```
Titre: "Alerte Système"
Corps: "Taux d'échec de paiement élevé détecté"
Action: Voir les logs
```

---

## Configuration de Sécurité

### 1. Validation des Tokens
```php
// Dans DeviceTokenController
public function store(Request $request)
{
    $validated = $request->validate([
        'device_token' => 'required|string|max:255',
        'device_type' => 'required|in:android,ios,web',
        'device_name' => 'nullable|string|max:255',
    ]);
    
    // Vérifier que le token est valide avec Firebase
    // Avant de le stocker
}
```

### 2. Rate Limiting
```php
// Dans routes/api.php
Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    Route::post('/device-tokens', [DeviceTokenController::class, 'store']);
});
```

### 3. Permissions
- Seul le propriétaire peut enregistrer/supprimer ses tokens
- Les admins ne peuvent pas voir les tokens des autres utilisateurs
- Logs d'audit pour les actions sensibles

---

## Monitoring et Maintenance

### 1. Logs
```php
// Logger les envois de notifications
Log::channel('fcm')->info('Notification sent', [
    'user_id' => $user->id,
    'notification_type' => get_class($notification),
    'success' => $result->isSuccess(),
]);
```

### 2. Métriques à Suivre
- Nombre de tokens actifs
- Taux de succès d'envoi
- Taux d'ouverture des notifications
- Tokens expirés/invalides par jour

### 3. Commandes de Maintenance
```bash
# Nettoyer les tokens expirés
vendor\bin\sail artisan tokens:clean-expired

# Tester l'envoi de notification
vendor\bin\sail artisan fcm:test {user_id}

# Statistiques FCM
vendor\bin\sail artisan fcm:stats
```

---

## Timeline d'Implémentation

### Semaine 1: Configuration et Backend
- Jour 1-2: Installation et configuration Firebase
- Jour 3-4: Migrations et modèles
- Jour 5-7: Services et adaptation des notifications

### Semaine 2: Frontend et API
- Jour 1-3: Intégration Firebase SDK
- Jour 4-5: Endpoints API et gestion des tokens
- Jour 6-7: Interface utilisateur pour les permissions

### Semaine 3: Tests et Optimisation
- Jour 1-3: Tests unitaires et fonctionnels
- Jour 4-5: Tests manuels multi-plateformes
- Jour 6-7: Optimisations et corrections

### Semaine 4: Déploiement et Monitoring
- Jour 1-2: Déploiement en pré-production
- Jour 3-4: Tests utilisateurs réels
- Jour 5-6: Ajustements et corrections
- Jour 7: Déploiement en production

---

## Coûts et Ressources

### Firebase Cloud Messaging (Gratuit)
- Notifications illimitées
- Pas de limite de devices
- Support multi-plateforme inclus

### Ressources Serveur
- Stockage minimal (tokens ~1KB chacun)
- Bande passante négligeable
- CPU: impact minimal (async)

### Temps de Développement Estimé
- Backend: 20-25 heures
- Frontend: 15-20 heures
- Tests: 10-15 heures
- **Total: 45-60 heures**

---

## Risques et Mitigation

### Risque 1: Tokens Expirés
**Mitigation:** Système automatique de nettoyage et re-registration

### Risque 2: Notifications Bloquées par le Navigateur
**Mitigation:** Interface claire pour demander la permission, fallback sur email

### Risque 3: Surcharge de Notifications
**Mitigation:** Groupement intelligent, préférences utilisateur, rate limiting

### Risque 4: Compatibilité Navigateur
**Mitigation:** Detection de support, graceful degradation, messages informatifs

---

## Prochaines Étapes

1. **Validation du Plan**
   - Revue avec l'équipe technique
   - Approbation du budget temps
   - Validation des priorités

2. **Création du Projet Firebase**
   - Configurer le projet
   - Obtenir les credentials
   - Configurer les domaines autorisés

3. **Début de l'Implémentation**
   - Phase 1: Backend (Semaine 1)
   - Tests intermédiaires
   - Ajustements si nécessaire

---

## Ressources et Documentation

### Documentation Officielle
- [Firebase Cloud Messaging](https://firebase.google.com/docs/cloud-messaging)
- [Laravel FCM Package](https://github.com/benwilkins/laravel-fcm-notification)
- [Service Workers MDN](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)

### Tutoriels Recommandés
- Firebase FCM avec Laravel
- Push Notifications Best Practices
- Service Worker Implementation Guide

### Support
- Firebase Support: [support.google.com/firebase](https://support.google.com/firebase)
- Laravel Community: [laravel.io](https://laravel.io)
- Stack Overflow: Tag `firebase-cloud-messaging` + `laravel`

---

## Conclusion

L'intégration de Laravel FCM NotiFire transformera l'expérience utilisateur de votre application de sourcing en offrant des notifications push instantanées, fiables et personnalisées. Avec un investissement de 45-60 heures de développement, vous obtiendrez:

✅ Engagement utilisateur accru  
✅ Temps de réponse réduit  
✅ Satisfaction client améliorée  
✅ Système de communication moderne  
✅ Scalabilité pour la croissance future  

Le retour sur investissement est rapide grâce à l'amélioration de la réactivité et de la satisfaction des clients et administrateurs.
