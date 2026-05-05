# Gestion des Conflits d'Assignation (Pessimistic Locking)

## 1. Le Problème : Data Race (Conditions de Course)

Dans un environnement où plusieurs administrateurs (Super Admins) travaillent simultanément, il existe un risque que deux personnes tentent d'assigner la même demande de sourcing (`SourcingRequest`) à deux administrateurs différents **au même moment exact**.

**Scénario typique :**
1. **Admin A** ouvre la page d'une demande (ID: 100). Elle est "Non assignée".
2. **Admin B** ouvre la même page (ID: 100). Elle est "Non assignée".
3. **Admin A** clique sur "Assigner à Michel".
4. **Admin B** clique sur "Assigner à Sarah".
5. Sans protection, la base de données effectuera les deux mises à jour l'une après l'autre. La dernière requête écrasera la première, et l'Admin A pensera avoir assigné le dossier alors que c'est l'Admin B qui "gagne" silencieusement.

## 2. La Solution : Verrouillage Pessimiste (`lockForUpdate`)

Pour empêcher cela, nous utilisons le **Verrouillage Pessimiste** (Pessimistic Locking) offert par Laravel et SQL.

### Comment ça marche ?
Lorsqu'on démarre une transaction pour assigner un dossier, on dit à la base de données :
*"Sélectionne cette ligne et **verrouille-la** (empêche toute autre modification) jusqu'à ce que j'ai fini."*

Si un autre admin essaie de modifier la même ligne pendant ce temps, sa requête devra attendre que la première transaction soit terminée (ou échouera si le délai est trop long), garantissant la cohérence des données.

## 3. Implémentation Code

Voici comment sécuriser la méthode `assign` dans votre `AdminSourcingRequestController`.

### Code Actuel (Vulnérable)
```php
public function assign(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
{
    // ... validation ...
    
    // ⚠️ RISQUE : Rien n'empêche une autre modification ici avant l'update
    $sourcingRequest->update([
        'assigned_to_admin_id' => $validated['admin_id'],
        'assigned_at' => now()
    ]);

    return back()->with('success', 'Dossier assigné avec succès.');
}
```

### Code Sécurisé (Recommandé)
Nous utilisons `DB::transaction` combiné avec `lockForUpdate()`.

```php
use Illuminate\Support\Facades\DB;

public function assign(Request $request, $id): RedirectResponse
{
    if (!auth()->user()->isSuperAdmin()) {
        abort(403, 'Action non autorisée.');
    }

    $validated = $request->validate([
        'admin_id' => 'required|exists:users,id'
    ]);

    try {
        DB::transaction(function () use ($id, $validated) {
            // 1. On récupère la requête en la VERROUILLANT
            // Toute autre transaction devra attendre ici
            $sourcingRequest = SourcingRequest::where('id', $id)
                ->lockForUpdate() 
                ->firstOrFail();

            // 2. Vérification supplémentaire (Optionnelle mais recommandée)
            // On vérifie si elle n'a pas été assignée entre temps
            if ($sourcingRequest->assigned_to_admin_id !== null) {
                // Option A : On bloque l'écrasement
                throw new \Exception("Ce dossier vient d'être assigné à quelqu'un d'autre !");
                
                // Option B : On continue et on écrase (mais on est sûr de l'ordre)
            }

            // 3. Mise à jour atomique
            $sourcingRequest->update([
                'assigned_to_admin_id' => $validated['admin_id'],
                'assigned_at' => now()
            ]);
            
            // Sync avec la commande si nécessaire
             if ($sourcingRequest->order) {
                // On pourrait aussi verrouiller l'order ici si nécessaire
                $sourcingRequest->order->update([
                    'assigned_to_admin_id' => $validated['admin_id']
                ]);
            }
        });

    } catch (\Exception $e) {
        return back()->with('error', 'Erreur lors de l\'assignation : ' . $e->getMessage());
    }

    return back()->with('success', 'Dossier assigné avec succès de manière sécurisée.');
}
```

## 4. Points Clés à Retenir

1.  **Utiliser une Transaction** : `DB::transaction(...)` garantit que toutes les opérations (assigner la request + assigner l'order) réussissent ou échouent ensemble.
2.  **`lockForUpdate()`** : Cette méthode ajoute `FOR UPDATE` à la requête SQL (`SELECT * FROM sourcing_requests WHERE id = ? FOR UPDATE`). C'est la clé du verrouillage.
3.  **Gestion des Exceptions** : Toujours envelopper dans un `try/catch` pour gérer les cas où le verrou ne peut pas être obtenu ou si une règle métier est violée.

Cette approche garantit que même avec 3 Super Admins cliquant exactement au même moment, les requêtes seront traitées les unes après les autres, sans corruption de données.
