# Plan d'Intégration de l'API 17TRACK v2.4

Ce document détaille la stratégie d'intégration de l'API 17TRACK dans notre application Laravel "Sourcing App" pour automatiser le suivi des colis.

## 1. Analyse de l'API

L'API 17TRACK fonctionne sur un modèle **Push/Webhook**. Nous ne devons pas interroger l'API constamment pour connaître le statut.
- **Workflow** :
  1. Nous enregistrons un numéro de suivi via l'endpoint `/register`.
  2. 17TRACK surveille le colis.
  3. 17TRACK envoie les mises à jour à notre URL Webhook.
- **Contraintes** :
  - Limite de fréquence : 3 requêtes/seconde pour l'enregistrement.
  - Max 40 numéros par requête.
  - Asynchrone : Le résultat n'est pas immédiat à l'enregistrement.

## 2. Architecture d'Intégration Proposée

### 2.1 Configuration
Ajout des crédentials dans le fichier `.env` et la configuration des services.

**Fichier `.env`** :
```dotenv
17TRACK_API_KEY=votre_clé_api
17TRACK_BASE_URL=https://api.17track.net/track/v1
```

**Fichier `config/services.php`** :
```php
'17track' => [
    'key' => env('17TRACK_API_KEY'),
    'base_url' => env('17TRACK_BASE_URL'),
],
```

### 2.2 Base de Données
Nous devons stocker les informations de suivi et lier les mises à jour aux commandes.

**Table `sourcing_orders` (ou une table dédiée `shipments`)** :
Si nous avons déjà des champs de suivi, nous devons nous assurer d'avoir :
- `tracking_number` (string)
- `carrier_code` (integer/string - code 17TRACK, ex: 3011)
- `tracking_status` (string/integer - ex: 'NotFound', 'InTransit', 'Delivered')
- `last_tracking_update` (timestamp)
- `tracking_details` (json - pour stocker l'historique complet ou les détails structurés)

### 2.3 Service Layer (`App\Services\Tracking\SeventeenTrackService`)
Création d'un service dédié pour interagir avec l'API.

**Méthodes principales** :
- `registerTracking(string $number, int $carrierCode)` : Appelle `/register`.
- `stopTracking(string $number, int $carrierCode)` : Appelle `/deletetrack` (si commande annulée).
- `getCarrierList()` : Pour récupérer/mettre à jour la liste des transporteurs (optionnel, peut être en cache).

### 2.4 Gestion des Webhooks (`App\Http\Controllers\Webhook\SeventeenTrackController`)
Un contrôleur pour recevoir les notifications POST de 17TRACK.

**Route** :
`POST /api/webhooks/17track/updates`

**Logique** :
1. Vérifier la signature (si disponible) ou le token de sécurité.
2. Parser le JSON entrant.
3. Pour chaque événement de suivi reçu :
   - Trouver la commande correspondante via le numéro de suivi.
   - Mettre à jour le statut (`tracking_status`).
   - Mettre à jour les détails (`tracking_details`).
   - Déclencher des événements Laravel (ex: `ShipmentStatusUpdated`) pour notifier le client ou l'admin par email/notification.

### 2.5 Job Queue
Pour éviter de bloquer l'utilisateur lors de la création d'une expédition, l'enregistrement du tracking doit être mis en file d'attente.

- **Job** : `RegisterTrackingNumberJob`
  - Se déclenche quand un admin ajoute un numéro de suivi.
  - Appelle `SeventeenTrackService::registerTracking`.

## 3. Étapes d'Implémentation

### Étape 1 : Configuration
- Ajouter les variables dans `.env`.
- Mettre à jour `config/services.php`.

### Étape 2 : Migration
- Créer une migration pour ajouter/modifier les colonnes dans `sourcing_orders` (ou la table pertinente).
```php
Schema::table('sourcing_orders', function (Blueprint $table) {
    $table->string('carrier_code')->nullable()->after('carrier_name');
    $table->json('tracking_details')->nullable()->after('tracking_number'); // Stocker le dernier payload
    $table->string('tracking_status_code')->nullable()->index(); // Pour filtrage rapide
});
```

### Étape 3 : Développement du Service
- Implémenter `SeventeenTrackService` avec `Illuminate\Support\Facades\Http`.
- Gérer les erreurs (quotas, API down).

### Étape 4 : Développement du Webhook
- Créer la route API (exclure du CRSF si route web, ou utiliser route API).
- Implémenter le contrôleur pour traiter le payload JSON.
- **Important** : Configurer la route Webhook dans la console 17TRACK.

### Étape 5 : UI Admin
- Dans le formulaire d'expédition, ajouter un champ (select) pour choisir le Transporteur (avec les codes 17TRACK).
- Afficher le statut en temps réel sur la page de détail de la commande (basé sur les données stockées en DB).

### Étape 6 : Tests
- Utiliser le quota gratuit (100 requêtes).
- Simuler des webhooks avec Postman pour tester la mise à jour des statuts.

## 4. Questions Ouvertes / À Valider
- Avons-nous déjà une liste de transporteurs en base de données ? Si oui, il faudra mapper nos transporteurs avec ceux de 17TRACK.
- Voulons-nous afficher tout l'historique de suivi au client ou juste le dernier statut ?

Ce plan assure une intégration robuste, "set and forget", où le système se met à jour tout seul après l'enregistrement initial.
