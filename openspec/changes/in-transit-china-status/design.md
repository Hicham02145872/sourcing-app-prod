## Context

- `in_transit_china` existe déjà côté `SourcingOrder` (STATUSES l.66, transitions `shipment_preparing → in_transit_china → arrival_uae`, masquage client `getStatusForClient` l.404, tracking virtuel l.583).
- Côté demande (`SourcingRequest`), le statut n'existe pas : STATUSES (l.41) et `canTransitionTo()` (l.204) doivent être étendus additivement.
- Point d'entrée admin : `SourcingRequestWorkflow::updateStatus()` + `SourcingOrderWorkflow` ; conventions upload : `Storage::disk('public')`.

## Goals / Non-Goals

**Goals:**
- Permettre à l'admin de marquer une demande **et** son ordre lié comme « in transit depuis la Chine » avec tracking chinois (`china_tracking_number`) et photo colis (`package_label_photo_path`).
- Affichage côté client (photo + tracking + statut traduit) et côté super admin.
- Règle strictement additive, aucune modification des transitions existantes de l'ordre.

**Non-Goals:**
- Pas de modification du pipeline de tracking automatique ni des délais virtuels.
- Pas de routage/suivi en temps réel chinois (juste la saisie manuelle).

## Decisions

- **Demande** : ajout de `in_transit_china` à `SourcingRequest::STATUSES` + entrée dans `canTransitionTo()` : `accepted` (admin) → `in_transit_china`, `completed`, `cancelled` ; `in_transit_china` (admin) → `completed`, `cancelled`. `status_timestamps` est déjà géré par le hook modèle (aucun changement). `in_review_limit` ne concerne pas ce statut.
- **Ordre** : la transition de l'ordre doit respecter ses gardes existantes (`canTransitionTo` custom l.237) : on NE force pas `transitionTo` ; l'action met à jour `china_tracking_number` et `package_label_photo_path` systématiquement, et bascule le statut de l'ordre à `in_transit_china` **seulement si autorisé** (sinon on laisse le flux tracking automatique faire).
- **Route/UI** : méthode `markInTransit(SourcingRequest $s)` dans `SourcingRequestWorkflow` (Livewire, cohérent avec l'interface actuelle), bouton admin « Marquer in-transit China » avec upload photo (jpg/png/webp, ≤ 5 Mo) et champ `china_tracking_number`. Enregistrement : `storage/app/public/sourcing/in-transit/{order_id}.{ext}` (disk public, chemin stocké en DB).
- **Client/Super Admin** : le statut de la demande est affiché directement ; on montre la photo + `china_tracking_number` de l'ordre lié (garde : seulement si statut `in_transit_china`). On réutilise lang `in_transit_china` (déjà utilisé par l'ordre) — compléter fr/ar/eng si besoin.
- **Colonnes** : migration additive sur `sourcing_orders` : `china_tracking_number` (string nullable 191), `package_label_photo_path` (string nullable). Aucun ENUM, aucune colonne requise.
- **Tests** : Feature — transition demande acceptée→in_transit_china ; upload photo validé (mime/taille) ; ordre mis à jour avec tracking+photo et statut si autorisé ; affichage client selon statut ; non-régression transitions.

## Risks / Trade-offs

- Ordre déjà avancé (ex. `arrival_uae`) : le statut de l'ordre ne bascule pas (garde), mais les données tracking/photo sont enregistrées → risque de désyncro partielle visualisation client ; mitigation : le client n'affiche la phase que si le statut demande est `in_transit_china`, l'ordre suit son cours.
- Photo requise à l'upload mais flux legacy éventuellement sans photo → `package_label_photo_path` nullable, disparition silencieuse si absente.
- Rollback : retirer la route/colonnes ; aucune donnée migrée.