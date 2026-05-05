# Plan d'Intégration Webhook 17TRACK

## Vue d'Ensemble

Le webhook 17TRACK permet de recevoir des notifications en temps réel lorsque le statut d'un colis change, au lieu d'interroger constamment l'API (polling). C'est plus efficace et plus rapide.

---

## 📋 Prérequis

1. ✅ Compte 17TRACK avec accès API
2. ✅ Clé API active : `95F2075A936FA19ED4DAF54BE2E6508D` (déjà configurée)
3. ✅ Serveur accessible publiquement (HTTPS recommandé)
4. ✅ Laravel Queue fonctionnel

---

## Architecture du Webhook

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│  17TRACK    │         │  Votre App   │         │   Client    │
│   Server    │────────▶│  /webhooks/  │────────▶│             │
│             │  POST   │   17track    │  Notif  │  Email/FCM  │
└─────────────┘         └──────────────┘         └─────────────┘
                              │
                              ▼
                        ┌──────────────┐
                        │  Database    │
                        │  + Queue     │
                        └──────────────┘
```

---

## Étape 1 : Créer la Route Webhook

### Fichier : `routes/web.php`

```php
// Ajouter cette route AVANT les routes authentifiées
Route::post('/webhooks/17track', [App\Http\Controllers\WebhookController::class, 'handle17Track'])
    ->name('webhooks.17track')
    ->withoutMiddleware([
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ]);
```

**Important** : Le webhook doit être accessible sans authentification et sans CSRF token.

---

## Étape 2 : Créer le Controller

### Commande Laravel

```bash
php artisan make:controller WebhookController
```

### Fichier : `app/Http/Controllers/WebhookController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\SourcingOrder;
use App\Models\TrackingStatusHistory;
use App\Notifications\TrackingStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle incoming webhook from 17TRACK.
     */
    public function handle17Track(Request $request)
    {
        // Log la requête pour debug
        Log::info('17TRACK Webhook received', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        try {
            // Valider la signature (sécurité)
            if (!$this->verify17TrackSignature($request)) {
                Log::warning('17TRACK Webhook: Invalid signature');
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            // Extraire les données
            $payload = $request->json()->all();
            $event = $payload['event'] ?? null;
            $data = $payload['data'] ?? [];

            // Traiter selon le type d'événement
            switch ($event) {
                case 'TRACKING_UPDATED':
                    $this->handleTrackingUpdate($data);
                    break;
                    
                case 'TRACKING_DELIVERED':
                    $this->handleDelivery($data);
                    break;
                    
                case 'TRACKING_EXCEPTION':
                    $this->handleException($data);
                    break;
                    
                default:
                    Log::info("17TRACK Webhook: Unknown event type: {$event}");
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('17TRACK Webhook Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    /**
     * Vérifier la signature 17TRACK (sécurité).
     */
    private function verify17TrackSignature(Request $request): bool
    {
        $signature = $request->header('X-17TRACK-Signature');
        
        if (!$signature) {
            return false;
        }

        $payload = $request->getContent();
        $secret = config('services.17track.webhook_secret'); // À configurer
        
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Gérer la mise à jour de statut.
     */
    private function handleTrackingUpdate(array $data): void
    {
        $trackingNumber = $data['number'] ?? null;
        $newStatus = $data['track']['latest_status']['status'] ?? null;
        $location = $data['track']['latest_event']['location'] ?? null;
        $statusDate = $data['track']['latest_event']['time_iso'] ?? now();

        if (!$trackingNumber) {
            Log::warning('17TRACK Webhook: Missing tracking number');
            return;
        }

        // Trouver la commande
        $order = SourcingOrder::where('tracking_number', $trackingNumber)->first();

        if (!$order) {
            Log::warning("17TRACK Webhook: Order not found for tracking: {$trackingNumber}");
            return;
        }

        // Vérifier si le statut a vraiment changé
        $previousStatus = $order->tracking_status;
        
        if ($previousStatus === $newStatus) {
            Log::info("17TRACK Webhook: Status unchanged for {$trackingNumber}");
            return;
        }

        // Sauvegarder dans l'historique
        TrackingStatusHistory::create([
            'sourcing_order_id' => $order->id,
            'tracking_number' => $trackingNumber,
            'status' => $newStatus,
            'location' => $location,
            'status_date' => $statusDate,
            'raw_data' => json_encode($data),
        ]);

        // Mettre à jour la commande
        $order->update([
            'tracking_status' => $newStatus,
            'tracking_location' => $location,
        ]);

        // Envoyer notification au client
        $client = $order->user;
        if ($client) {
            $client->notify(new TrackingStatusUpdated($order, $previousStatus, $newStatus));
        }

        Log::info("17TRACK Webhook: Status updated for {$trackingNumber}: {$previousStatus} → {$newStatus}");
    }

    /**
     * Gérer la livraison.
     */
    private function handleDelivery(array $data): void
    {
        $trackingNumber = $data['number'] ?? null;
        
        $order = SourcingOrder::where('tracking_number', $trackingNumber)->first();

        if ($order) {
            $order->update([
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);

            // Notification spéciale livraison
            $client = $order->user;
            if ($client) {
                $client->notify(new \App\Notifications\OrderDelivered($order));
            }

            Log::info("17TRACK Webhook: Order delivered: {$trackingNumber}");
        }
    }

    /**
     * Gérer les exceptions (problèmes).
     */
    private function handleException(array $data): void
    {
        $trackingNumber = $data['number'] ?? null;
        $exceptionType = $data['exception_type'] ?? 'unknown';
        
        $order = SourcingOrder::where('tracking_number', $trackingNumber)->first();

        if ($order) {
            // Marquer comme problématique
            $order->update([
                'tracking_status' => "EXCEPTION: {$exceptionType}",
                'needs_attention' => true,
            ]);

            // Notifier les admins
            $admins = \App\Models\User::where('role', 'super_admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\TrackingException($order, $exceptionType));
            }

            Log::warning("17TRACK Webhook: Exception on {$trackingNumber}: {$exceptionType}");
        }
    }
}
```

---

## Étape 3 : Créer la Table `tracking_status_history`

### Migration

```bash
php artisan make:migration create_tracking_status_history_table
```

### Fichier : `database/migrations/xxxx_create_tracking_status_history_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sourcing_order_id')->constrained('sourcing_orders')->onDelete('cascade');
            $table->string('tracking_number');
            $table->string('status');
            $table->string('location')->nullable();
            $table->timestamp('status_date')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();

            $table->index(['sourcing_order_id', 'created_at']);
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_status_history');
    }
};
```

### Exécuter la migration

```bash
php artisan migrate
```

---

## Étape 4 : Créer le Model

### Commande

```bash
php artisan make:model TrackingStatusHistory
```

### Fichier : `app/Models/TrackingStatusHistory.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingStatusHistory extends Model
{
    protected $table = 'tracking_status_history';

    protected $fillable = [
        'sourcing_order_id',
        'tracking_number',
        'status',
        'location',
        'status_date',
        'raw_data',
    ];

    protected $casts = [
        'status_date' => 'datetime',
        'raw_data' => 'array',
    ];

    public function sourcingOrder(): BelongsTo
    {
        return $this->belongsTo(SourcingOrder::class);
    }
}
```

---

## Étape 5 : Ajouter Colonnes à `sourcing_orders`

### Migration

```bash
php artisan make:migration add_tracking_columns_to_sourcing_orders_table
```

```php
public function up(): void
{
    Schema::table('sourcing_orders', function (Blueprint $table) {
        $table->string('tracking_status')->nullable()->after('tracking_carrier');
        $table->string('tracking_location')->nullable()->after('tracking_status');
        $table->boolean('needs_attention')->default(false)->after('tracking_location');
    });
}
```

```bash
php artisan migrate
```

---

## Étape 6 : Créer la Notification

### Commande

```bash
php artisan make:notification TrackingStatusUpdated
```

### Fichier : `app/Notifications/TrackingStatusUpdated.php`

```php
<?php

namespace App\Notifications;

use App\Models\SourcingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrackingStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SourcingOrder $order,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['mail', 'database'];
        
        if ($notifiable->fcm_token) {
            $channels[] = 'fcm';
        }
        
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("📦 Mise à jour de votre colis #{$this->order->id}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Votre colis a été mis à jour :")
            ->line("**Ancien statut** : {$this->oldStatus}")
            ->line("**Nouveau statut** : {$this->newStatus}")
            ->line("**Numéro de suivi** : {$this->order->tracking_number}")
            ->action('Suivre mon colis', route('client.tracking.index', ['number' => $this->order->tracking_number]))
            ->line("Merci de votre confiance !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'sourcing_order_id' => $this->order->id,
            'tracking_number' => $this->order->tracking_number,
            'title' => '📦 Mise à jour de livraison',
            'body' => "Votre colis est maintenant : {$this->newStatus}",
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'type' => 'tracking_update',
        ];
    }

    public function toFcm(object $notifiable): array
    {
        return [
            'title' => '📦 Mise à jour de livraison',
            'body' => "Votre colis est maintenant : {$this->newStatus}",
            'data' => [
                'click_action' => 'VIEW_TRACKING',
                'tracking_number' => $this->order->tracking_number,
                'order_id' => (string) $this->order->id,
            ],
        ];
    }
}
```

---

## Étape 7 : Configuration

### Fichier : `config/services.php`

```php
return [
    // ... autres services

    '17track' => [
        'api_key' => env('SEVENTEEN_TRACK_API_KEY', '95F2075A936FA19ED4DAF54BE2E6508D'),
        'webhook_secret' => env('SEVENTEEN_TRACK_WEBHOOK_SECRET'),
        'base_url' => 'https://api.17track.net/track/v2.2',
    ],
];
```

### Fichier : `.env`

```env
SEVENTEEN_TRACK_API_KEY=95F2075A936FA19ED4DAF54BE2E6508D
SEVENTEEN_TRACK_WEBHOOK_SECRET=votre_secret_genere_sur_17track
```

---

## Étape 8 : Configuration sur 17TRACK

### Se connecter au Dashboard 17TRACK

1. Aller sur https://www.17track.net/en/apiuser
2. Section **Webhook Settings**
3. **Callback URL** : `https://votredomaine.com/webhooks/17track`
4. **Events** à activer :
   - ☑️ Status Updated
   - ☑️ Delivered
   - ☑️ Exception
5. **Secret Key** : Générer et copier dans `.env`
6. Cliquer sur **Save**

---

## Étape 9 : Tests

### Test Local avec Ngrok

Si votre serveur n'est pas public, utilisez ngrok :

```bash
ngrok http 8000
```

Copier l'URL HTTPS (ex: `https://abc123.ngrok.io`) et l'utiliser comme callback URL.

### Test avec Postman

```bash
POST https://votredomaine.com/webhooks/17track
Content-Type: application/json
X-17TRACK-Signature: <calculer_la_signature>

{
  "event": "TRACKING_UPDATED",
  "data": {
    "number": "ME49508327",
    "track": {
      "latest_status": {
        "status": "In Transit"
      },
      "latest_event": {
        "location": "Dubai, UAE",
        "time_iso": "2025-12-21T16:00:00Z"
      }
    }
  }
}
```

### Vérifier les Logs

```bash
tail -f storage/logs/laravel.log
```

Vous devriez voir :
```
[2025-12-21 16:00:00] local.INFO: 17TRACK Webhook received
[2025-12-21 16:00:00] local.INFO: 17TRACK Webhook: Status updated for ME49508327: Pending → In Transit
```

---

## Étape 10 : Monitoring & Sécurité

### Ajouter un Rate Limiter

```php
// routes/web.php
Route::post('/webhooks/17track', [WebhookController::class, 'handle17Track'])
    ->middleware('throttle:60,1') // Max 60 requêtes par minute
    ->name('webhooks.17track');
```

### Logger toutes les tentatives

```php
// Dans WebhookController
Log::channel('webhooks')->info('17TRACK webhook', $request->all());
```

### Créer un channel de logs dédié

```php
// config/logging.php
'channels' => [
    'webhooks' => [
        'driver' => 'daily',
        'path' => storage_path('logs/webhooks.log'),
        'level' => 'debug',
        'days' => 30,
    ],
],
```

---

## Checklist de Déploiement

- [ ] Migration `tracking_status_history` exécutée
- [ ] Migration colonnes `sourcing_orders` exécutée
- [ ] Model `TrackingStatusHistory` créé
- [ ] Controller `WebhookController` créé
- [ ] Notification `TrackingStatusUpdated` créée
- [ ] Route webhook ajoutée
- [ ] Configuration `.env` mise à jour
- [ ] Webhook configuré sur 17TRACK
- [ ] Tests effectués (ngrok ou production)
- [ ] Logs vérifiés
- [ ] Queue worker actif (`php artisan queue:work`)

---

## Avantages du Webhook vs Polling

| Aspect | Webhook (Push) | Polling (Pull) |
|--------|---------------|----------------|
| **Temps réel** | ✅ Instantané | ❌ Délai (1h entre checks) |
| **Quota API** | ✅ Économique | ❌ 1 call par tracking/heure |
| **Performance serveur** | ✅ Faible | ❌ Cron job constant |
| **Complexité** | 🟡 Setup initial | 🟢 Simple |
| **Fiabilité** | ✅ Garantie 17TRACK | 🟡 Si cron fail |

---

## Troubleshooting

### Webhook ne reçoit rien

1. Vérifier que l'URL est publique (pas `localhost`)
2. Vérifier les logs 17TRACK
3. Tester avec `curl` :
```bash
curl -X POST https://votredomaine.com/webhooks/17track \
  -H "Content-Type: application/json" \
  -d '{"event":"test"}'
```

### Signature invalide

- Vérifier que `SEVENTEEN_TRACK_WEBHOOK_SECRET` est correct
- S'assurer que le payload n'est pas modifié avant vérification

### Notifications pas envoyées

- Vérifier que la queue tourne : `php artisan queue:work`
- Vérifier les failed jobs : `php artisan queue:failed`

---

## Prochaines Étapes

Une fois le webhook fonctionnel :

1. ✅ Désactiver ou réduire le polling (si existant)
2. ✅ Ajouter un dashboard admin pour voir l'historique
3. ✅ Implémenter les alertes d'anomalies
4. ✅ Ajouter l'ETA basé sur l'historique

---

## Conclusion

Le webhook 17TRACK transformera votre système de tracking en solution **temps réel** avec notifications instantanées. L'investissement initial est rapidement rentabilisé par :
- 🚀 Meilleure expérience client
- 💰 Réduction des coûts API
- ⚡ Performance améliorée

**Temps d'implémentation estimé** : 4-6 heures
