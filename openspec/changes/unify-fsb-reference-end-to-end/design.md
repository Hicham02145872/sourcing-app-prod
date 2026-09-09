## Context

Deux système de numérotation coexistent aujourd'hui :

- `SourcingRequest.shared_id` (colonne, générée par `SharedIdService` via la table `sb_id_sequence`, incrément de 5) : format `SB` + 5 chiffres (ex. `SB00015`). C'est la référence affichée des demandes (`reference_id`).
- `SourcingOrder` : référence affichée = `fsb_tracking_number`, un alias **virtuel** dérivé de l'id (`'FSB' + str_pad(id, 6)`), résolu par le moteur de suivi. `shared_id` (SB) est copié de la demande vers la commande pour la recherche interne uniquement.

Le changement `unify-order-reference-and-tracking` (archivé) a rendu les **commandes** affichées/traçables en FSB (dérivé de l'id), tout en laissant les demandes en SB. Objectif de cette évolution : **étendre le FSB à la demande** et faire porter à la commande **le même numéro FSB que sa demande** (pas un FSB différent dérivé de l'id), pour les refunds et le suivi — en gardant les enregistrements `SB****` existants fonctionnels.

Contraintes :
- Séquence inchangée (pas de 5) pour ne pas casser les numéros existants.
- Rétro-compatibilité : les `SB****` existants continuent de s'afficher, se retrouvent par recherche, et leurs commandes continuent d'être traçables.
- Le `tracking_number` réel (numéro transporteur) n'est jamais touché.

## Goals / Non-Goals

**Goals:**
- Un `shared_id` au format FSB (`FSB` + 6 chiffres) généré à la création de chaque nouvelle demande, depuis la même séquence ×5.
- La commande hérite du FSB de sa demande ; `reference_id` et `fsb_tracking_number` retournent ce même numéro.
- La page de suivi résout ce FSB vers la commande (statut virtuel ou réel).
- Les pages refunds affichent le FSB de la commande.
- La recherche admin trouve indifféremment `SB****` (légacy) et `FSB****` (nouveaux).
- Les enregistrements légacy (`SB****`, ou commandes sans shared_id) conservent exactement leur comportement actuel.

**Non-Goals:**
- Ne pas renommer la colonne `shared_id` (nom interne conservé, seule la largeur change).
- Ne pas mettre à jour les données existantes (`SB****` inchangés en base).
- Ne pas rendre une demande « trackable » avant que sa commande existe (le suivi reste un concept d'ordre).
- Ne pas modifier le champ transporteur `tracking_number`.

## Decisions

### D1 — `SharedIdService` génère du FSB, garde le parsing SB

`format()` passe de `'SB'.pad5` à `'FSB'.str_pad($counter, 6, '0')` (ex. `FSB000005`, `FSB000010`, …). La table `sb_id_sequence` est conservée telle quelle (nom inchangé, usage interne). `parse()` accepte les deux formats :

```php
if (preg_match('/^FSB(\d{6})$/', ...)) return (int)$m[1];      // nouveau
if (preg_match('/^SB(\d{5})$/', ...)) return (int)$m[1];       // legacy
```

`currentCounter()` reste fondé sur `sb_id_sequence`. Le séquencement ×5 garantit que les valeurs FSB générées sont toujours des multiples de 5 (voir D3/D4).

### D2 — Migrations : élargir `shared_id` à VARCHAR(9)

`FSB000005` = 9 caractères vs 7 pour `SB00005`. Deux migrations :

- `sourcing_requests.shared_id` : `string('shared_id', 9)->nullable()->unique()` (l'index unique reste).
- `sourcing_orders.shared_id` : idem.

Pas de migration de données. Rollback trivial (re-petit).

### D3 — Le FSB de l'ordre préfère le `shared_id` de la demande, sinon reste dérivé de l'id

Dans `SourcingOrder` :

```php
public function getFsbTrackingNumberAttribute(): string
{
    if (is_string($this->shared_id) && preg_match('/^FSB\d{6}$/', $this->shared_id)) {
        return $this->shared_id;                      // nouveau : hérité de la demande
    }
    return 'FSB'.str_pad($this->id, 6, '0', STR_PAD_LEFT);   // legacy : dérivé de l'id
}
```

`getReferenceIdAttribute()` ne change pas (retourne déjà `fsb_tracking_number`), mais sa valeur devient le FSB hérité pour les nouvelles commandes. Toutes les vues et notifications qui utilisent `reference_id` ou `fsb_tracking_number` basculent automatiquement (page commande, listes, labels, notifications `FsbTrackingGenerated`/`TrackingNumberAdded`, caches `tracking:{fsb}`, workflow admin).

**Alternatives considérées** : (a) retourner `shared_id` directement dans `reference_id` → cassait l'affichage des commandes légacy (SB) qui doivent rester en FSB dérivé de l'id ; (b) stocker un champ `fsb_number` séparé → redondant, `shared_id` joue déjà ce rôle.

### D4 — Encodage par destination sur la base du numéro FSB hérité

`getFsbTrackingNumberForDestinationIndex(int $index)` : pour une commande au FSB hérité `FSB000005`, la destination `k` porte `FSB00000{5+k}` (base numérique du shared_id + index). Cohérence : les numéros générés étant multiples de 5, `base+k` (k ≤ 4) ne peut pas entrer en collision avec un autre numéro généré. Pour le chemin légacy, l'encodage `id + k` actuel est conservé.

### D5 — Résolution FSB : le `shared_id` d'abord, le calcul d'id ensuite

`resolveFsbNumberToOrderAndDestinationIndex()` gagne une première étape par `shared_id` :

1. Normaliser (`FSB000005` → `000005`).
2. Chercher l'ordre dont `shared_id = FSB000005` → `[order, dest 0]` (priorité : les nouveaux).
3. Sinon, pour multi-dest nouveaux : chercher un ordre dont le numérique du `shared_id` FSB + index = `numeric` → `[order, index]` (ex. `FSB000006` → ordre FSB000005, dest 1).
4. Sinon, chemin légacy actuel (ordre `id` = numeric, puis encodage `id+k`).

Cette méthode étant la seule porte d'entrée de `UnifiedTrackingService` (l.183-184, 378-379) et `VirtualTrackingStatusService`, leur logique reste inchangée.

### D6 — Refunds : afficher `reference_id` au lieu de `display_id`

Les vues client `refund-requests/{create,index,show}.blade.php` affichent `#{{ $sourcingOrder->display_id }}` (`id*5`). Remplacer par `{{ $sourcingOrder->reference_id }}` (FSB). Audit identique côté admin (`admin/refund-requests/show.blade.php`) et lieux analogues.

### D7 — Recherche admin : inchangée (repose sur la colonne)

Les requêtes `orWhere('shared_id', 'like', '%'.$search.'%')` (commandes + demandes) fonctionnent déjà pour les deux formats car la colonne stocke les valeurs brutes. Aucun changement de requête ; ajout de tests de couverture.

### D8 — Tests

- `tests/Unit/SharedIdServiceTest.php` : format FSB, parsing FSB + SB legacy.
- `tests/Feature/SharedIdGenerationTest.php` : nouvelle demande → FSB ; ordre → hérite du FSB ; recherche par `FSB000005` et par `SB00015` (legacy).
- `tests/Feature/OrderTrackingFsbResolutionTest.php` : résolution par `shared_id` FSB.
- `tests/Feature/QuotationAcceptCreatesOrderTest.php` : aligner les assertions de partage `shared_id`.
- E2E : `client-sourcing-request.spec.ts` (référence demandée en `/FSB0\d{5,}/`), vérifier `admin-dashboard.spec.ts` / `admin-sourcing-request.spec.ts` (recherche jetée sur un numéro présent en base — ou adaptée).

## Risks / Trade-offs

- [Les numéros FSB générés (`×5`) diffèrent de l'encodage id actuel] → Mitigation : la résolution privilégie le `shared_id` (D5) ; le chemin id reste pour le legacy. Aucun chevauchement de sens avec les FSB `id` existants grâce à la priorité de résolution.
- [Collision FSB multi-destination] → Implausible (destinations ≤ 4) : `base+k` n'atteint jamais un multiple de 5. Documenté en D4.
- [E2E verrouillés sur `SB00005`] → La base persistée contient encore des SB legacy (les recherches passent). En cas de reseed complet, les demandes seedées deviendront FSB → les tests de recherche e2e doivent viser un numéro réellement présent (à adapter dans les tâches).
- [Notifications/caches utilisant `fsb_tracking_number`] → Ils suivent automatiquement l'accesseur (D3) ; vérifier qu'aucun littéral `'FSB'.id` n'existe hors accesseur (audit dans les tâches).
- [Affichage refunds passe de `#215` à `FSB000005`] → Changement voulu (référence publique unifiée) ; les données (`sourcing_order_id`) sont inchangées.

## Migration Plan

1. Migration widening `shared_id` (requests + orders) → déployable seule, sans risque (VARCHAR 7→9).
2. Changements de code (`SharedIdService`, accesseurs `SourcingOrder`, résolution, vues refunds) → commit unique.
3. Tests unitaires/feature/e2e mis à jour dans le même commit.
4. Rollback : revert du commit de code ; la colonne élargie reste (sans effet). Aucune donnée migrée à restaurer.

## Open Questions

- Faut-il rendre le `shared_id` FSB d'une demande sans commande « introuvable » sur la page de suivi (message « numéro inconnu ») plutôt qu'une erreur ? → Comportement par défaut choisi : résolution échoue → message générique existant. À confirmer à l'implémentation.