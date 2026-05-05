# Guide d'Intégration des Webhooks AfterShip

Ce document fournit un guide étape par étape pour intégrer les webhooks d'AfterShip dans votre application Laravel afin de mettre à jour automatiquement les statuts des commandes.

## Étape 1 : Obtenir votre Secret de Webhook AfterShip

1.  Connectez-vous à votre compte AfterShip.
2.  Allez dans la section **Settings > Webhooks**.
3.  Cliquez sur "Add webhook". Vous y trouverez un champ "Webhook secret".
4.  Copiez cette clé secrète. Nous l'utiliserons à l'étape 5.

## Étape 2 : Créer la Route pour le Webhook
oin d'une URL dans notre application que nous fournirons à AfterShip.

Nous avons bes
### 2.1. Créer le fichier `routes/api.php`

Si ce fichier n'existe pas, créez-le et ajoutez le code suivant :

```php
// routes/api.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\ShippingController;

// Cette route recevra les notifications de webhook d'AfterShip
Route::post('/webhooks/aftership', [ShippingController::class, 'handleAfterShip'])
    ->name('webhooks.aftership');
```

### 2.2. Enregistrer la route API

Ouvrez le fichier `bootstrap/app.php` et assurez-vous que le fichier de routes `api.php` est bien enregistré.

```php
// bootstrap/app.php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Assurez-vous que cette ligne est présente
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ...
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
```

Votre URL de webhook sera : `https://votredomaine.com/api/webhooks/aftership`.

## Étape 3 : Créer le Contrôleur

Exécutez la commande suivante pour créer le contrôleur qui gérera les requêtes du webhook :

```bash
php artisan make:controller Webhook/ShippingController
```

## Étape 4 : Implémenter la Logique du Contrôleur

Ouvrez le fichier `app/Http/Controllers/Webhook/ShippingController.php` et remplacez son contenu par le code suivant. Ce code est spécifiquement conçu pour les webhooks AfterShip.

```php
<?php

namespace App\Http\Controllers\Webhook;

use App\Events\SourcingOrderStatusChanged;
use App\Models\SourcingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ShippingController extends Controller
{
    /**
     * Gère les notifications de webhook entrantes d'AfterShip.
     */
    public function handleAfterShip(Request $request)
    {
        // 1. Valider la signature du webhook pour la sécurité
        $secret = env('AFTERSHIP_WEBHOOK_SECRET');
        $signature = $request->header('aftership-hmac-sha256');

        if (!$this->isValidSignature($signature, $request->getContent(), $secret)) {
            Log::warning('Webhook AfterShip : signature invalide.');
            return response()->json(['message' => 'Signature Invalide'], 403);
        }

        // 2. Parser la charge utile (payload) d'AfterShip
        $payload = $request->json('msg');

        // AfterShip peut envoyer des données sans tracking_number lors des tests, on ignore
        if (empty($payload['tracking_number'])) {
            return response()->json(['message' => 'Numéro de suivi manquant.']);
        }

        $trackingNumber = $payload['tracking_number'];
        $statusTag = $payload['tag']; // ex: "InTransit", "Delivered"
        
        // 3. Trouver la commande correspondante dans votre base de données
        $order = SourcingOrder::where('tracking_number', $trackingNumber)->first();

        if (!$order) {
            Log::info("Webhook AfterShip reçu pour un numéro de suivi inconnu : {$trackingNumber}");
            // Répondre 200 pour qu'AfterShip ne renvoie pas la même notification
            return response()->json(['message' => 'Commande non trouvée.']);
        }
        
        // 4. Mapper le statut d'AfterShip à vos statuts internes
        $newStatus = $this->mapAfterShipStatus($statusTag);
        
        // Mettre à jour le statut de la commande
        $order->status = $newStatus;
        $order->save();

        // Optionnel : enregistrer dans l'historique (si vous avez créé la table)
        // $order->trackingHistories()->create([...]);

        // 5. Déclencher un événement pour notifier l'utilisateur
        // Votre système de notification existant prendra le relais
        SourcingOrderStatusChanged::dispatch($order);

        Log::info("Statut de la commande {$order->id} mis à jour à '{$newStatus}' via le webhook AfterShip.");

        return response()->json(['message' => 'Webhook traité avec succès.']);
    }

    /**
     * Valide la signature HMAC-SHA256 d'AfterShip.
     */
    private function isValidSignature(?string $signature, string $payload, ?string $secret): bool
    {
        if (empty($secret) || empty($signature)) {
            return false;
        }

        // AfterShip encode la signature en base64
        $expectedSignature = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Mappe les statuts d'AfterShip ('tag') à vos statuts d'application.
     */
    private function mapAfterShipStatus(string $aftershipTag): string
    {
        $statusMap = [
            'Pending' => 'pending', // ou 'processing'
            'InfoReceived' => 'shipped',
            'InTransit' => 'shipped',
            'OutForDelivery' => 'out_for_delivery',
            'AttemptFail' => 'delivery_failed',
            'Delivered' => 'delivered',
            'Exception' => 'exception',
            'Expired' => 'expired',
        ];

        // Retourne le statut mappé, ou le statut par défaut 'shipped' si inconnu
        return $statusMap[$aftershipTag] ?? 'shipped';
    }
}
```

## Gestion du Numéro de Suivi par l'Administrateur

Pour que le système de suivi AfterShip fonctionne, il est crucial que le numéro de suivi et le transporteur soient correctement associés à chaque `SourcingOrder` dans votre application. Voici l'approche recommandée pour la gestion de cette information par l'administrateur.

### Approche Recommandée : Saisie Manuelle dans le Tableau de Bord Admin

Cette méthode est la plus simple à mettre en œuvre et constitue le point de départ idéal.

1.  **Génération du Numéro de Suivi** : L'administrateur finalise l'expédition d'une `SourcingOrder` avec un transporteur (par exemple, via le site web du transporteur, un logiciel tiers, etc.). Le transporteur lui fournit un numéro de suivi.
2.  **Saisie dans l'Application** :
    *   L'administrateur se connecte au tableau de bord de votre application.
    *   Il navigue vers les détails de la `SourcingOrder` concernée.
    *   Une section (par exemple, un formulaire) dédiée au suivi lui permet de saisir :
        *   Le `tracking_number` (numéro de suivi).
        *   Le `tracking_carrier` (transporteur). Il est recommandé d'utiliser une liste déroulante basée sur les services que vous avez configurés (par exemple, Aramex, FedEx, DHL) pour éviter les erreurs de saisie et correspondre aux noms reconnus par AfterShip.
    *   L'administrateur enregistre ces informations.
3.  **Mise à Jour de la Commande et Notification à AfterShip** :
    *   Votre application met à jour la `SourcingOrder` avec le numéro de suivi et le transporteur.
    *   Idéalement, à ce stade, votre application devrait utiliser l'API d'AfterShip pour **ajouter ce numéro de suivi à votre compte AfterShip**. C'est ce qui indique à AfterShip de commencer à suivre ce colis.

#### Implémentation côté Application

Pour cela, vous devrez ajouter des champs dans l'interface d'administration (votre tableau de bord) pour permettre à l'administrateur de saisir ces informations.

Un exemple de code dans votre contrôleur d'administration pour gérer la mise à jour :

```php
// app/Http/Controllers/Admin/SourcingOrderController.php (exemple)

use App\Models\SourcingOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Assurez-vous d'avoir une classe pour interagir avec l'API AfterShip si vous automatisez l'ajout
// use App\Services\AfterShipService; 

class SourcingOrderController extends Controller
{
    // ... autres méthodes ...

    public function updateTracking(Request $request, SourcingOrder $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
            'tracking_carrier' => 'required|string|max:255', // Doit correspondre aux slugs AfterShip
        ]);

        $order->tracking_number = $request->tracking_number;
        $order->tracking_carrier = $request->tracking_carrier;
        $order->save();

        // Optionnel : Ajouter le suivi à AfterShip via leur API
        // if (class_exists(AfterShipService::class)) {
        
        //     app(AfterShipService::class)->addTracking($order->tracking_number, $order->tracking_carrier);
        // }

        return back()->with('success', 'Numéro de suivi mis à jour.');
    }
}
```

*   **Note sur `tracking_carrier`** : AfterShip utilise des "slugs" spécifiques pour les transporteurs (ex: `dhl-express`, `fedex`). Assurez-vous que les valeurs enregistrées dans votre base de données correspondent à ces slugs AfterShip, ou mettez en place un mécanisme de mappage.

### Autres Approches (pour Information)

*   **Importation en Masse** : Pour un volume élevé, l'administrateur pourrait importer un fichier CSV/Excel contenant les numéros de suivi et les identifiants de commande.
*   **Intégration API Directe** : Pour une automatisation complète, votre application pourrait s'intégrer directement avec l'API de chaque transporteur pour générer et récupérer les numéros de suivi sans intervention manuelle. C'est l'approche la plus complexe à mettre en place.

## Étape 5 : Ajouter le Secret au fichier d'environnement

Ouvrez votre fichier `.env` et ajoutez le secret de webhook AfterShip que vous avez copié à l'étape 1.

```
AFTERSHIP_WEBHOOK_SECRET=votre_secret_copie_depuis_aftership
```

**Important** : N'oubliez pas d'ajouter cette variable aussi dans votre fichier `env.production` ou dans les variables d'environnement de votre serveur de production.

## Étape 6 : Configurer l'Endpoint dans AfterShip

1.  Retournez sur votre tableau de bord AfterShip dans **Settings > Webhooks**.
2.  Dans le champ "Webhook URL", collez l'URL complète de votre route, par exemple : `https://votredomaine.com/api/webhooks/aftership`.
3.  Assurez-vous que le webhook est activé.
4.  Enregistrez.

AfterShip enverra désormais des notifications à votre application à chaque mise à jour de suivi !
