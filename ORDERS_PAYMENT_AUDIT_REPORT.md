
# Rapport d'Audit - Gestion des Commandes et Paiements

## Introduction

Ce document détaille l'analyse des fonctionnalités critiques liées au cycle de vie d'une commande, de l'acceptation d'un devis jusqu'au paiement. L'accent est mis sur la sécurité des données de paiement, la fiabilité des processus et la robustesse de la logique métier.

---

## Problème Critique n°1 : Sécurité et Confidentialité des Preuves de Paiement

C'est le problème le plus grave de cette section. La manière dont les preuves de paiement sont stockées expose potentiellement des données sensibles.

### Le Risque

Les preuves de paiement (reçus, captures d'écran de virement) peuvent contenir des informations personnelles et financières. Actuellement, elles sont stockées dans le dossier `public`, ce qui signifie que **toute personne connaissant l'URL exacte du fichier peut y accéder**, sans être authentifiée. C'est une fuite de données potentielle.

### Analyse

Dans `Client\SourcingOrderController@uploadProofOfPayment`, la ligne `$path = $request->file('proof_of_payment')->store('proofs_of_payment', 'public');` utilise le disque `public`. Votre configuration `filesystems.php` montre que ce disque est lié au dossier `public/storage`, qui est accessible publiquement via le web.

### Solution Recommandée

Stocker ces fichiers sur un disque privé et ne les rendre accessibles qu'aux administrateurs authentifiés.

1.  **Utilisez un disque privé** : Votre disque `local` est déjà configuré par défaut pour être privé (`storage/app/private`). Il suffit de l'utiliser lors du stockage.

    ```php
    // Dans Client\SourcingOrderController.php
    // ...
    // AVANT
    // $path = $request->file('proof_of_payment')->store('proofs_of_payment', 'public');
    // APRÈS
    $path = $request->file('proof_of_payment')->store('proofs_of_payment', 'local');
    // ...
    ```

2.  **Créez une route sécurisée pour le téléchargement** : L'administrateur ne pourra plus cliquer sur un lien direct. Il faut créer une nouvelle méthode dans `Admin\SourcingOrderController` qui se chargera de retourner le fichier de manière sécurisée.

    **a. Définir la route** dans `routes/web.php` (à l'intérieur du groupe `admin`) :

    ```php
    Route::get('sourcing-orders/{sourcingOrder}/download-proof', [AdminSourcingOrderController::class, 'downloadProof'])->name('sourcing-orders.download-proof');
    ```

    **b. Créer la méthode dans le contrôleur** `Admin\SourcingOrderController.php` :

    ```php
    public function downloadProof(SourcingOrder $sourcingOrder)
    {
        // Idéalement, vérifiez l'autorisation avec une Policy.
        // $this->authorize('viewProof', $sourcingOrder);

        if (!$sourcingOrder->proof_of_payment_path) {
            abort(404, 'No proof of payment file found.');
        }

        // Retourne le fichier en téléchargement sans révéler son emplacement réel sur le serveur.
        return Storage::disk('local')->download($sourcingOrder->proof_of_payment_path);
    }
    ```

---

## Problème Majeur n°2 : Manque de Fiabilité lors de la Création de Commande

L'acceptation d'un devis déclenche plusieurs actions critiques en base de données qui ne sont pas protégées contre les erreurs ou les actions concurrentes.

### Le Risque

1.  **Données Incohérentes** : Dans `Client\QuotationController@accept`, vous créez une commande, mettez à jour le devis, ET mettez à jour la demande de sourcing. Si une erreur survient au milieu de ces opérations, la base de données devient incohérente (ex: la commande est créée mais le statut du devis n'est pas mis à jour).
2.  **Race Condition** : Un utilisateur qui double-clique sur le bouton "Accepter" pourrait potentiellement déclencher le processus deux fois, créant ainsi deux commandes pour le même devis.

### Analyse

La méthode `accept` effectue 3 écritures en base de données (`SourcingOrder::create`, `quotation->update`, `sourcingRequest->update`) sans être atomique (c'est-à-dire sans être dans une transaction).

### Solution Recommandée

1.  **Utilisez une Transaction de Base de Données** pour garantir que toutes les opérations réussissent ou échouent ensemble.
2.  **Verrouillez la ligne** du devis (`lockForUpdate`) pour empêcher les "race conditions".

```php
// Dans Client\QuotationController.php
use Illuminate\Support\Facades\DB;

public function accept(Request $request, Quotation $quotation): RedirectResponse
{
    // ... vérification du propriétaire ...

    try {
        $sourcingOrder = DB::transaction(function () use ($quotation) {
            // Verrouille la ligne du devis pour éviter que deux requêtes l'acceptent en même temps.
            $q = Quotation::where('id', $quotation->id)->lockForUpdate()->firstOrFail();

            // Vérifie si le devis n'a pas déjà été traité entre-temps.
            if ($q->status !== 'sent') { // ou 'pending', selon votre logique
                // Lance une exception pour annuler la transaction et gérer l'erreur.
                throw new \Exception('This quotation has already been processed.');
            }

            // 1. Crée la commande
            $order = SourcingOrder::create([
                'user_id' => auth()->user()->id,
                'quotation_id' => $q->id,
                'total_amount' => $q->amount,
                'status' => 'pending_payment',
            ]);

            // 2. Met à jour le devis
            $q->update(['status' => 'accepted']);

            // 3. Met à jour la demande de sourcing
            $q->sourcingRequest->update(['status' => 'accepted']);

            // ... logique de notification (idéalement via des événements) ...

            return $order; // Retourne la commande créée
        });

        // ... logique d'envoi d'e-mail (qui devrait être dans un Listener) ...

        return redirect()->route('client.sourcing-orders.show', $sourcingOrder)->with('status', 'Quotation accepted!');

    } catch (\Exception $e) {
        // Gère l'erreur (ex: devis déjà traité)
        return redirect()->back()->withErrors(['generic' => $e->getMessage()]);
    }
}
```

---

## Recommandation n°3 : Logique de Transition des Statuts (State Machine)

### Le Problème

Un administrateur peut changer le statut d'une commande vers n'importe quel autre statut, sans contrainte, via le formulaire d'édition.

### Le Risque

Une erreur humaine est possible. Un admin pourrait accidentellement faire passer une commande "expédiée" (`shipped`) à "en attente de paiement" (`pending_payment`), ce qui n'a pas de sens et pourrait déclencher des logiques inattendues (comme un rappel de paiement).

### Solution Recommandée

Implémentez une logique de "state machine" simple dans votre modèle `SourcingOrder` pour définir les transitions autorisées.

1.  **Ajoutez une méthode de validation dans `SourcingOrder.php`** :

    ```php
    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = [
            'pending_payment' => ['paid', 'cancelled'],
            'paid' => ['shipped', 'on_hold', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => ['completed'],
            'on_hold' => ['paid', 'cancelled'],
            // Une commande annulée ou complétée ne peut plus changer de statut
            'cancelled' => [],
            'completed' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }
    ```

2.  **Utilisez cette méthode dans le contrôleur admin** :

    ```php
    // Dans Admin\SourcingOrderController.php
    public function updateStatus(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(SourcingOrder::STATUSES)],
        ]);

        if (!$sourcingOrder->canTransitionTo($validated['status'])) {
            return back()->withErrors(['status' => 'Invalid status transition from '' . $sourcingOrder->status . '' to '' . $validated['status'] . ''.']);
        }

        $sourcingOrder->update(['status' => $validated['status']]);

        // ... notifications ...

        return back()->with('status', 'Sourcing order status updated successfully!');
    }
    ```

---

## Rappel Important

N'oubliez pas d'appliquer les **Policies d'Autorisation** (décrites dans le premier rapport d'audit) à tous les contrôleurs de cette section (`QuotationController`, `SourcingOrderController`) pour vous assurer qu'un utilisateur ne peut accéder qu'à ses propres devis et commandes. C'est une étape de sécurité fondamentale.
