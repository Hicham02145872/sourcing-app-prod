## Why

Le statut `in_transit_china` existe déjà côté `SourcingOrder` (transitions, masquage client, tracking virtuel), mais la phase « en transit depuis la Chine » n'est pas alimentée manuellement avec photo/tracking par l'admin, et n'est pas visible côté client ni reportée sur la demande.

## What Changes

- Ajout du statut `in_transit_china` à `SourcingRequest::STATUSES` + autorisation de transition correspondante (admin, depuis les statuts acceptés/suivis — exemple : `accepted` → `in_transit_china`), sans toucher aux transitions existantes des `SourcingOrder`.
- Nouvelle route admin `POST /admin/requests/{id}/mark-in-transit` (et action Livewire associée) : passe la demande et l'ordre lié en `in_transit_china`, enregistre `china_tracking_number` et une photo de colis (`package_label_photo_path`, jpg/png, ≤ 5 Mo, stockage public).
- Affichage client du statut avec la photo et le numéro de tracking chinois quand la phase est active (le masquage actuel côté `SourcingOrder` est conservé tel quel).
- Affichage Super Admin du statut `in_transit_china` avec photo et tracking.
- Nouvelle colonne `china_tracking_number` sur `sourcing_orders` (nullable, string) et `package_label_photo_path` sur la table adéquate (demande ou ordre, à confirmer à l'exploration — défaut : `sourcing_orders`).

## Capabilities

### New Capabilities
- `in-transit-china-phase`: alimentation manuelle de la phase « in transit China » (statut, tracking chinois, photo colis), visualisation client/super admin.

### Modified Capabilities
<!-- Aucune spec existante concernée -->

## Impact

- Migration additive (colonnes nullable) ; `SourcingRequest::STATUSES` + `canTransitionTo()` ; observer éventuel pour l'ordre lié.
- Nouveau contrôleur route admin + action Livewire ; upload photo validé (mime, taille).
- Rendu client + super admin (blade) avec traduction du statut.
- Réutilise le pattern `status_timestamps` existant ; aucun ALTER ENUM.