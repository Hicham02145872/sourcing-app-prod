# Synchronisation Google Sheet vers App (Reverse Sync)

## 1. Le Concept : "Polling" (Vérification Périodique)

Pour mettre à jour l'application Laravel lorsque un administrateur change un statut dans le Google Sheet, nous allons utiliser une méthode de **Polling** (sondage).

Contrairement à un Webhook (qui nécessite que Google notifie notre serveur, complexe à sécuriser et configurer localement), le **Polling** consiste à créer une commande planifiée (Cron Job) qui va :
1. Lire le Google Sheet toutes les X minutes.
2. Comparer les données du Sheet avec la base de données.
3. Mettre à jour l'App si un changement est détecté.

## 2. Implémentation Technique

### Étape 1 : Lire les données (`GoogleSheetService`)

Ajouter une méthode pour récupérer toutes les lignes du fichier.

```php
// App/Services/GoogleSheetService.php

public function fetchAllRows()
{
    $range = $this->sheetName . '!A:Z'; // Lire toute la feuille
    $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
    return $response->getValues();
}
```

### Étape 2 : Créer la Commande de Synchro

Créer une commande `php artisan make:command SyncSheetStatus`.

```php
// App/Console/Commands/SyncSheetStatus.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetService;
use App\Models\SourcingOrder;

class SyncSheetStatus extends Command
{
    protected $signature = 'sourcing:sync-sheet-status';
    protected $description = 'Met à jour les statuts des commandes depuis Google Sheets';

    public function handle(GoogleSheetService $sheetService)
    {
        $rows = $sheetService->fetchAllRows();
        
        // On suppose que la ligne 1 contient les en-têtes
        $headers = array_shift($rows); 
        
        // Mapping des colonnes (A adapter selon vos colonnes réelles)
        // Ex: Order ID est index 0, Statut est index 7
        $idIndex = 0; 
        $statusIndex = 7;

        foreach ($rows as $row) {
            $orderId = $row[$idIndex] ?? null;
            $sheetStatus = $row[$statusIndex] ?? null;

            if ($orderId && $sheetStatus) {
                $order = SourcingOrder::find($orderId);

                if ($order && $order->status !== $sheetStatus) {
                    // Normalisation du statut (ex: "payé" -> "paid")
                    // Il faudra une fonction de mapping ici si les textes diffèrent
                    $internalStatus = $this->mapStatus($sheetStatus);

                    if ($internalStatus) {
                        $order->update(['status' => $internalStatus]);
                        $this->info("Commande #{$orderId} mise à jour : {$internalStatus}");
                    }
                }
            }
        }
    }

    private function mapStatus($sheetStatus)
    {
        // Exemple simple
        $map = [
            'Payé' => 'paid',
            'Expédié' => 'shipped',
            'Livré' => 'delivered',
        ];
        return $map[$sheetStatus] ?? null;
    }
}
```

### Étape 3 : Automatisation (Scheduler)

Dans `routes/console.php` (Laravel 11) ou `app/Console/Kernel.php` :

```php
use Illuminate\Support\Facades\Schedule;

// Exécuter toutes les heures
Schedule::command('sourcing:sync-sheet-status')->hourly();
```

## 3. Points d'Attention

1.  **Correspondance Exacte** : Le texte dans le Google Sheet doit correspondre exactement à ce que l'application attend (ou utiliser une fonction `mapStatus` robuste).
2.  **Performance** : Si le fichier contient 10 000 lignes, lire tout le fichier peut être long. Il faudra peut-être limiter la lecture aux 100 dernières lignes.
3.  **Conflits** : Si quelqu'un modifie l'app ET le sheet en même temps, la dernière synchro gagnera. C'est généralement acceptable pour ce cas d'usage.

## 4. Alternative : Webhook via Apps Script

Si vous avez besoin d'une mise à jour **instantanée** (pas toutes les heures), vous pouvez utiliser Google Apps Script.

### Le Principe
Google Sheets ne peut pas appeler une URL nativement. On ajoute un petit script dans le Sheet qui se déclenche à chaque modification (`onEdit`) et envoie les données à votre site Laravel.

### Code Apps Script

Dans votre Google Sheet : **Extensions > Apps Script**.

```javascript
function onEdit(e) {
  var sheet = e.source.getActiveSheet();
  var range = e.range;
  var row = range.getRow();
  
  // Vérifier qu'on est sur la bonne feuille et pas sur les en-têtes
  if (sheet.getName() !== "sourcing" || row < 2) return;

  var statusColumnIndex = 8; // Colonne H (Statut)
  
  // Si la modification est bien sur la colonne Statut
  if (range.getColumn() === statusColumnIndex) {
    var orderId = sheet.getRange(row, 1).getValue(); // Assumons que Order ID est Col A
    var newStatus = e.value;
    
    // Envoyer au serveur Laravel
    var payload = {
      order_id: orderId,
      status: newStatus
    };
    
    var options = {
      'method' : 'post',
      'contentType': 'application/json',
      'payload' : JSON.stringify(payload)
    };
    
    // Remplacez par votre URL réelle (Doit être publique !)
    UrlFetchApp.fetch('https://mon-app-laravel.com/api/webhooks/google-sheet', options);
  }
}
```

### Côté Laravel

Il faut créer une route API qui reçoit ce POST.

```php
// routes/api.php
Route::post('/webhooks/google-sheet', [WebhookController::class, 'handleSheetUpdate']);
```

Cette méthode est plus "réactive" mais demande que votre serveur soit accessible depuis Internet (pas possible en local sans tunnel type Ngrok).

