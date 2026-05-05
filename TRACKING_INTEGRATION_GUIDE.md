# Rapport Technique : Intégration du Suivi de Colis par Analyse Réseau (Scraping)

## 1. Introduction

### 1.1. Objectif de l'intégration
L'objectif est d'automatiser la mise à jour du statut des `SourcingOrder` en cours d'expédition en récupérant les informations de suivi directement depuis les sites web des transporteurs. Cette approche permet de notifier le client proactivement des étapes clés de la livraison (ex: "En transit", "Bloqué en douane", "En cours de livraison") sans intervention manuelle d'un administrateur.

### 1.2. Contexte
Notre application gère des commandes via le modèle `SourcingOrder`, qui possède un champ `tracking_number` et `tracking_carrier`. Actuellement, la mise à jour du statut est manuelle. L'automatisation de ce processus améliorera l'expérience client en tirant parti du système de notifications (`SourcingOrderStatusChanged`) déjà en place.

Cette méthode se base sur l'analyse des requêtes **XHR/Fetch** (visibles dans l'onglet "Réseau" des outils de développement du navigateur) pour simuler un appel que le site du transporteur fait pour afficher les statuts.

---

## 2. Transporteurs et Faisabilité

Récupérer le statut via l'analyse réseau est une technique de "web scraping". Sa faisabilité dépend de la complexité du site du transporteur.

| Transporteur (Exemple) | Faisabilité / Difficulté | Notes |
| :--- | :--- | :--- |
| **ColisExemple Express** | `Facile` | Le site utilise une simple requête GET avec le numéro de suivi dans l'URL. |
| **RapidPost** | `Moyenne` | Requête POST avec un jeton CSRF temporaire qu'il faut récupérer au préalable. |
| **GlobalShip** | `Difficile` | L'API interne est protégée par un système d'authentification complexe (ex: OAuth2) et des headers dynamiques. |
| **Transporteur Local** | `Variable` | Souvent plus simples et moins protégés, mais peuvent changer sans préavis. |

**Limitation Majeure :** Cette méthode est **instable**. Une simple mise à jour du site du transporteur (changement d'URL, de format JSON, ajout d'une protection) peut casser l'intégration. **L'utilisation d'une API officielle, si elle existe, est toujours préférable.**

---

## 3. Analyse de la Requête Réseau

### 3.1. Comment identifier la bonne requête ?
1.  Ouvrez le site du transporteur dans votre navigateur (ex: `https://colisexemple.com/suivi`).
2.  Ouvrez les **Outils de Développement** (touche `F12` ou `Ctrl+Shift+I`).
3.  Allez dans l'onglet **Réseau (Network)**.
4.  Cliquez sur le filtre **XHR** ou **Fetch**.
5.  Entrez un numéro de suivi valide sur la page et lancez la recherche.
6.  Observez les nouvelles requêtes qui apparaissent. Cherchez une requête dont le nom semble pertinent (ex: `tracking`, `status`, `trace`).
7.  Cliquez sur cette requête et allez dans l'onglet **Aperçu (Preview)** ou **Réponse (Response)** pour inspecter le JSON retourné.

**Exemple de requête identifiée :**
*   **URL:** `https://api.colisexemple.com/v2/tracking`
*   **Méthode:** `POST`
*   **Payload (Corps de la requête) :**
    ```json
    {
      "trackingNumber": "CXE123456789FR",
      "language": "fr"
    }
    ```

### 3.2. Exemple de Payload JSON de Réponse
Voici à quoi pourrait ressembler la réponse JSON du serveur du transporteur :
```json
{
  "trackingNumber": "CXE123456789FR",
  "origin": "Shenzhen, CN",
  "destination": "Paris, FR",
  "status": "out_for_delivery",
  "statusLabel": "En cours de livraison",
  "events": [
    {
      "timestamp": "2025-12-15T10:00:00Z",
      "location": "Centre de tri, Paris",
      "label": "En cours de livraison"
    },
    {
      "timestamp": "2025-12-14T22:15:00Z",
      "location": "Centre de tri, Paris",
      "label": "Arrivée au centre de tri local"
    },
    {
      "timestamp": "2025-12-12T08:30:00Z",
      "location": "Aéroport CDG, FR",
      "label": "Dédouanement terminé"
    },
    {
      "timestamp": "2025-12-10T17:00:00Z",
      "location": "Shenzhen, CN",
      "label": "Colis expédié"
    }
  ]
}
```

---

## 4. Architecture de l'Intégration Laravel

Le processus s'articule autour d'une tâche planifiée (Cron) qui déclenche des jobs pour chaque commande.

**Workflow textuel :**
1.  `Tâche Planifiée (Cron)`: Une commande `php artisan tracking:check-statuses` s'exécute toutes les 4 heures.
2.  `Commande (Command)`: La commande sélectionne toutes les `SourcingOrder` ayant un statut "en transit" (ex: `in_transit_china`, `arrival_uae`, etc.) et un `tracking_number`.
3.  `Dispatch Job`: Pour chaque commande, elle déclenche un job `UpdateTrackingStatusJob`. Le job est ajouté à une file d'attente (queue) pour ne pas bloquer l'exécution.
4.  `Job`: Le job `UpdateTrackingStatusJob` exécute la logique principale :
    *   Il appelle un service `TrackingService`.
    *   `TrackingService`: Ce service contient la logique pour un transporteur spécifique. Il construit et exécute la requête HTTP (avec Guzzle) vers l'URL identifiée à l'étape 3.
    *   Le service "parse" la réponse JSON et extrait le statut le plus récent.
5.  `Mise à jour du Modèle`: Si le statut récupéré est différent du statut actuel de la `SourcingOrder`, le job met à jour le champ `status` de la commande.
6.  `Notification`: La mise à jour du modèle déclenche l'événement `SourcingOrderStatusChanged`, et le système de notification existant se charge d'envoyer un email/push au client.
7.  **(Optionnel) Historique**: On peut stocker chaque événement de tracking dans une nouvelle table (`tracking_events`) pour offrir un historique complet au client.

---

## 5. Exemple de Code Laravel

### 5.1. Service de Suivi (`app/Services/TrackingService.php`)
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\SourcingOrder;

class TrackingService
{
    // Exemple pour "ColisExemple Express"
    public function getColisExempleStatus(SourcingOrder $order): ?string
    {
        $url = 'https://api.colisexemple.com/v2/tracking';

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->post($url, [
                'trackingNumber' => $order->tracking_number,
                'language' => 'fr'
            ]);

            if (!$response->successful()) {
                // Gérer les erreurs HTTP (4xx, 5xx)
                return null;
            }

            $data = $response->json();
            
            // Extraire le statut depuis la réponse JSON
            // La clé 'status' peut varier selon le transporteur
            return $data['status'] ?? null;

        } catch (\Exception $e) {
            // Gérer les erreurs de connexion, timeout, etc.
            Log::error('Failed to get tracking status for order ' . $order->id, ['error' => $e->getMessage()]);
            return null;
        }
    }
}
```

### 5.2. Tâche planifiée (`app/Console/Commands/CheckTrackingStatuses.php`)
```php
<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SourcingOrder;
use App\Jobs\UpdateTrackingStatusJob;

class CheckTrackingStatuses extends Command
{
    protected $signature = 'tracking:check-statuses';
    protected $description = 'Dispatch jobs to check tracking statuses for in-transit orders.';

    public function handle()
    {
        $inTransitStatuses = [
            'shipment_preparing', 'in_transit_china', 'arrival_uae', 
            'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country',
            'customs_clearance_destination_country', 'out_for_delivery'
        ];

        $orders = SourcingOrder::whereIn('status', $inTransitStatuses)
                                ->whereNotNull('tracking_number')
                                ->get();

        foreach ($orders as $order) {
            UpdateTrackingStatusJob::dispatch($order);
        }

        $this->info('Dispatched ' . $orders->count() . ' tracking jobs.');
    }
}
```

### 5.3. Job (`app/Jobs/UpdateTrackingStatusJob.php`)
```php
<?php
namespace App\Jobs;

// ... imports ...
use App\Services\TrackingService;
use App\Models\SourcingOrder;

class UpdateTrackingStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(SourcingOrder $order)
    {
        $this->order = $order;
    }

    public function handle(TrackingService $trackingService)
    {
        $newStatus = null;

        // Logique pour appeler le bon service selon le transporteur
        if ($this->order->tracking_carrier === 'ColisExemple') {
            $newStatus = $trackingService->getColisExempleStatus($this->order);
        }
        // ... ajouter d'autres transporteurs ici ...

        if ($newStatus && $this->order->status !== $newStatus) {
            // Valider que la transition est possible
            if ($this->order->canTransitionTo($newStatus)) {
                $this->order->update(['status' => $newStatus]);
                // L'événement SourcingOrderStatusChanged est déclenché par le modèle
            }
        }
    }
}
```
### 5.4. Planification dans `app/Console/Kernel.php`
```php
protected function schedule(Schedule $schedule)
{
    // Exécute la commande toutes les 4 heures
    $schedule->command('tracking:check-statuses')->everyFourHours();
}
```

---

## 6. Sécurité et Bonnes Pratiques

*   **Fréquence Raisonnable**: Ne planifiez pas la tâche trop fréquemment. Une exécution toutes les 2 à 6 heures est généralement suffisante et évite de surcharger le serveur du transporteur, ce qui pourrait mener à un blocage de votre IP.
*   **User-Agent**: Utilisez toujours un `User-Agent` réaliste dans vos requêtes HTTP pour imiter un vrai navigateur.
*   **Gestion des Erreurs**: Le code doit être robuste. Utilisez des blocs `try/catch` pour gérer les pannes réseau, les changements de format JSON, ou les blocages d'IP. Logguez systématiquement les erreurs pour pouvoir les analyser.
*   **Délais d'attente (Timeouts)**: Configurez des délais d'attente (timeouts) raisonnables sur vos requêtes HTTP pour éviter que vos jobs ne restent bloqués indéfiniment.
*   **Maintenance**: Soyez conscient que cette intégration demandera une maintenance régulière. Prévoyez des alertes si un grand nombre de requêtes échouent, signe que le site du transporteur a probablement changé.

---

## 7. Conclusion

L'intégration du suivi par analyse réseau est une solution puissante mais fragile pour automatiser les mises à jour de statut lorsque aucune API officielle n'est disponible. Elle demande une architecture soignée basée sur des tâches planifiées et des jobs en file d'attente pour être robuste et performante.

**Recommandation finale :** Considérez cette approche comme une solution temporaire ou de dernier recours. Si le volume de commandes devient important, il est fortement recommandé d'investir dans l'intégration d'API de suivi officielles ou de services tiers spécialisés (ex: AfterShip, Shippo) qui agrègent les données de multiples transporteurs de manière fiable.
