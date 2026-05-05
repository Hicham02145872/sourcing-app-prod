# Conception d'Intégration pour le Suivi Automatique des Colis (Tracking)

Ce document décrit l'approche technique pour intégrer une fonctionnalité de suivi automatique des colis dans l'application, en utilisant les webhooks des transporteurs.

## 1. Vue d'ensemble

L'objectif est de mettre à jour automatiquement le statut d'une `SourcingOrder` lorsqu'un transporteur nous notifie d'un changement de statut via un webhook.

L'intégration s'appuiera sur les composants existants de l'application :

-   Le modèle `SourcingOrder` qui contient déjà les champs `tracking_number` et `tracking_carrier`.
-   Le système d'événements et de notifications pour informer les utilisateurs des mises à jour.
-   Le système de files d'attente (queues) pour le traitement asynchrone des notifications.

## 2. Base de Données

Aucune modification n'est requise sur la table `sourcing_orders`.

Pour garder un historique des statuts, nous allons créer une nouvelle table `tracking_history`.

### Nouvelle Migration

Créer une nouvelle migration pour la table `tracking_history`:

```bash
php artisan make:migration create_tracking_history_table
```

Contenu de la migration :

```php
// database/migrations/YYYY_MM_DD_HHMMSS_create_tracking_history_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sourcing_order_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->text('description')->nullable();
            $table->timestamp('event_timestamp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_history');
    }
};
```

## 3. Route pour le Webhook

Nous allons créer un nouveau fichier de routes pour l'API afin de gérer les webhooks.

### 3.1. Créer le fichier `routes/api.php`

```php
// routes/api.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\ShippingController;

Route::post('/webhooks/shipping', [ShippingController::class, 'handle'])
    ->name('webhooks.shipping');
```

### 3.2. Enregistrer les routes API

Modifier `bootstrap/app.php` pour enregistrer le nouveau fichier de routes :

```php
// bootstrap/app.php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php', // Ajouter cette ligne
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

## 4. Contrôleur pour le Webhook

Nous allons créer un nouveau contrôleur pour gérer la logique de réception des webhooks.

### 4.1. Créer le contrôleur

```bash
php artisan make:controller Webhook/ShippingController
```

### 4.2. Implémenter la logique du contrôleur

Le contrôleur aura pour responsabilités :
-   **Sécuriser** le webhook.
-   **Valider** et **parser** la requête.
-   **Mettre à jour** la commande (`SourcingOrder`).
-   **Enregistrer** l'historique du statut.
-   **Déclencher** un événement pour notifier l'utilisateur.

```php
// app/Http/Controllers/Webhook/ShippingController.php
<?php

namespace App\Http\Controllers\Webhook;

use App\Events\SourcingOrderStatusChanged;
use App\Models\SourcingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShippingController
{
    public function handle(Request $request)
    {
        // 1. Sécuriser le Webhook (exemple avec un secret)
        $secret = env('SHIPPING_PROVIDER_WEBHOOK_SECRET');
        $signature = $request->header('X-Shipping-Signature');

        if (!$this->isValidSignature($signature, $request->getContent(), $secret)) {
            Log::warning('Invalid webhook signature received.');
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        // 2. Parser la charge utile (payload)
        $payload = $request->json()->all();
        $trackingNumber = $payload['tracking_number'] ?? null;
        $status = $payload['status'] ?? null;
        $description = $payload['description'] ?? 'Status updated by webhook.';
        $event_timestamp = $payload['timestamp'] ?? now();

        if (!$trackingNumber || !$status) {
            return response()->json(['message' => 'Missing tracking_number or status.'], 422);
        }

        // 3. Trouver et mettre à jour la commande
        $order = SourcingOrder::where('tracking_number', $trackingNumber)->first();

        if (!$order) {
            Log::info("Webhook received for unknown tracking number: {$trackingNumber}");
            return response()->json(['message' => 'Order not found.'], 200); // Répondre 200 pour éviter les re-essais
        }

        // 4. Mettre à jour le statut et enregistrer l'historique
        $order->status = $this->mapProviderStatusToAppStatus($status);

        $order->trackingHistories()->create([
            'status' => $status,
            'description' => $description,
            'event_timestamp' => $event_timestamp,
        ]);
        
        $order->save();

        // 5. Déclencher l'événement de notification
        SourcingOrderStatusChanged::dispatch($order);

        return response()->json(['message' => 'Webhook processed successfully.']);
    }

    private function isValidSignature(string $signature, string $payload, string $secret): bool
    {
        // Implémenter la logique de validation de la signature du fournisseur
        // Exemple : hachage HMAC
        $hash = hash_hmac('sha256', $payload, $secret);
        return hash_equals($hash, $signature);
    }
    
    private function mapProviderStatusToAppStatus(string $providerStatus): string
    {
        // Mapper le statut du transporteur aux statuts de l'application
        // Exemple : 'in_transit' -> 'out_for_delivery'
        // À adapter en fonction des statuts du transporteur
        $statusMapping = [
            'in_transit' => 'out_for_delivery',
            'delivered' => 'delivered',
            // ... autres mappages
        ];

        return $statusMapping[strtolower($providerStatus)] ?? 'shipped';
    }
}
```

## 5. Modèle `TrackingHistory`

Il faut créer le modèle `TrackingHistory` et définir la relation avec `SourcingOrder`.

### 5.1. Créer le modèle

```bash
php artisan make:model TrackingHistory
```

### 5.2. Définir les relations

Ajouter la relation dans le modèle `SourcingOrder` :

```php
// app/Models/SourcingOrder.php
public function trackingHistories()
{
    return $this->hasMany(TrackingHistory::class);
}
```

Ajouter la relation dans le modèle `TrackingHistory` :

```php
// app/Models/TrackingHistory.php
protected $fillable = ['sourcing_order_id', 'status', 'description', 'event_timestamp'];

public function sourcingOrder()
{
    return $this->belongsTo(SourcingOrder::class);
}
```

## 6. Notifications

L'événement `SourcingOrderStatusChanged` est déjà géré par le listener `SendSourcingOrderStatusUpdatedNotification`, qui envoie des notifications via plusieurs canaux (email, FCM, etc.). Aucune modification n'est nécessaire ici, le système existant sera automatiquement utilisé.

## 7. Sécurité

Le webhook doit être sécurisé pour s'assurer que les requêtes proviennent bien du transporteur. La méthode `isValidSignature` dans le contrôleur doit être implémentée en fonction de la documentation du transporteur (généralement via un secret partagé et un hash HMAC).

Il faudra ajouter la variable d'environnement `SHIPPING_PROVIDER_WEBHOOK_SECRET` dans les fichiers `.env` et `env.production`.

## 8. Interface Utilisateur (UI)

Pour afficher l'historique du suivi à l'utilisateur, il faudra modifier la vue qui affiche les détails d'une `SourcingOrder`. On pourra y ajouter une section qui itère sur `$order->trackingHistories`.

Exemple (à adapter dans le fichier de vue Blade approprié) :

```html
<h3>Historique du suivi</h3>
<ul>
    @foreach($order->trackingHistories->sortByDesc('event_timestamp') as $history)
        <li>
            <strong>{{ $history->status }}</strong> - {{ $history->description }}
            <small>({{ $history->event_timestamp->format('d/m/Y H:i') }})</small>
        </li>
    @endforeach
</ul>
```

## 9. Déploiement

Pour que le traitement des notifications fonctionne en production, un "queue worker" doit être lancé et supervisé (par exemple avec Supervisor) sur le serveur.



```bash
php artisan queue:work
```
