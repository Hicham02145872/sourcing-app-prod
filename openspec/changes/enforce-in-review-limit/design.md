## Context

- `SourcingRequestWorkflow::updateStatus()` (app/Livewire/Admin/SourcingRequestWorkflow.php:19) est le point d'entrée unique côté admin pour la revendication (`in_review`, claim) et les changements de statut.
- `SourcingRequest::transitionTo()` gère les transitions et permissions, mais héberge la règle ici casserait les chemins système/e2e : choix d'une **garde applicative additivite**, appelée avant la transition vers `in_review`.
- Convention repo pour les flags : `FeatureFlagService` (table `feature_flags`, middleware `feature`), pas de config PHP. Les valeurs numériques (limite 5) iront dans `config/fsb.php`.

## Goals / Non-Goals

**Goals:**
- Bloquer un admin (hors super_admin) au 6e dossier `in_review` simultané, avec code d'erreur `WORKFLOW_LIMIT_REACHED` et message clair (arabe/fr).
- Couvrir le claim (pending→in_review) ET les changements de statut vers `in_review` depuis un autre statut.
- Règle neutralisable par feature flag ; aucun effet quand désactivée.

**Non-Goals:**
- Ne modifie pas `transitionTo()`, ni les transitions client, ni le module SLA, ni le tracking.
- Pas de rule de priorisation/« revenue » : uniquement le plafond de 5.

## Decisions

- **Garde dédiée** `App\Services\AdminSourcingWorkflowGuard` avec `assertCanStartReview(SourcingRequest $request, User $admin): void` : lève `WorkflowLimitReachedException` si dépassement. Appelée dans `SourcingRequestWorkflow::updateStatus()` à la condition `$status === 'in_review'` et pour l'admin effecteur (super_admin exempté).
  *Alternatives écartées* : vérifier dans `transitionTo()` (risque de casser les flux non-admin existants) ; validation dans la vue seulement (insuffisant, contournable).
- **Décompte** : `SourcingRequest::where('assigned_to_admin_id', $admin->id)->where('status', 'in_review')->count()`, dans une transaction avec `lockForUpdate` sur la demande cible pour éviter les courses en double-claim. Après la transition, le re-décompte reflète le nouvel état (>= 5 → refus, < 5 → OK). L'admin cible est l'acteur qui claim (`auth()->user()` si admin).
- **Feature flag** : clé `workflow_in_review_limit` via `FeatureFlagService::isEnabled('workflow_in_review_limit', $user)` ; valeur limite dans `config/fsb.php` (`workflow.in_review_limit` = 5). Flag absent → visible (convention service) ; on garde donc une config de secours pour la valeur.
- **Réponse** : `WorkflowLimitReachedException` interceptée dans le composant Livewire → toast erreur + `render` inchangé. (Les appels Livewire sont serveur : le retour se matérialise en 422 dans les clients JSON, code dans le champ `code`.)
- **Tests** : Feature — 4 in_review → 5e autorisé ; 5 → 6e refusé (code attendu) ; super_admin au dessus du plafond accepté ; flag off → autorisé.

## Risks / Trade-offs

- Course au claim simultané → transaction `lockForUpdate` sur la demande + re-vérification après lock.
- Détection en double (statut posé par-delà un cas non couvert) → décompte basé sur la ligne déjà en base, la garde est une précondition ; la cohérence finale est garantie par le comptage au moment du claim.
- Déploiement : aucune migration, rollback = suppression de l'appel de garde.