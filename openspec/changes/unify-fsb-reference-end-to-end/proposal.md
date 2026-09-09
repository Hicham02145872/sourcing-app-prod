## Why

Deux systèmes de numérotation coexistent : les **demandes** (`SourcingRequest`) portent un numéro `SB****` (`shared_id`, séquence ×5) tandis que les **commandes** (`SourcingOrder`) affichent un numéro `FSB****` dérivé de l'id. Le client voit un `SB****` sur sa demande, puis un `FSB****` différent sur sa commande, et le suivi attend encore un autre numéro. On veut **un seul numéro FSB** porté par la demande dès sa création, conservé par la commande, utilisé par les refunds et servant de numéro de tracking de bout en bout. Les enregistrements existants (`SB****`) continuent de fonctionner : **seuls les nouveaux** enregistrements reçoivent des numéros `FSB****`.

## What Changes

- **Génération** : `SharedIdService` génère désormais `FSB` + 6 chiffres (ex. `FSB000005`) à partir de la même séquence `sb_id_sequence` (incrément de 5, inchangé). `parse()` accepte toujours l'ancien format `SB****` (légacy).
- **Demandes** : les nouvelles demandes reçoivent un `shared_id` au format FSB ; la référence affichée (`reference_id`) devient donc un numéro FSB — sur le dashboard client, la liste des demandes, l'admin, le bulk payment.
- **Commandes** : la commande hérite du même numéro FSB que sa demande (`shared_id` copié). `reference_id` et `fsb_tracking_number` retournent ce numéro identique → la référence affichée = le numéro à saisir dans le suivi. Pour les commandes légacy (`shared_id` SB ou absent), le comportement actuel (FSB dérivé de l'id) est conservé. **BREAKING** : pour les nouvelles commandes, le FSB n'est plus dérivé de l'id mais hérité de la demande.
- **Suivi** : la résolution FSB (pages tracking, `UnifiedTrackingService`, `VirtualTrackingStatusService`, notifications) retrouve d'abord la commande par `shared_id` (numéro FSB du cycle de vie), puis le chemin légacy.
- **Refunds** : les pages refunds (client + admin) affichent le numéro FSB de la commande au lieu de `display_id` (`#id*5`).
- **Recherche admin** : la recherche par `shared_id` continue de marcher ; elle trouve aussi bien `SB****` (légacy) que `FSB****` (nouveaux).
- **Base de données** : migration pour élargir les colonnes `shared_id` (requests et orders) de VARCHAR(7) à VARCHAR(9). Pas de migration de données.
- **Tests** : tests unitaires/feature/e2e mis à jour (format FSB, compat legacy SB).

## Capabilities

### New Capabilities
- `fsb-unified-reference`: La référence publique d'une demande et de sa commande est un unique numéro FSB, généré à la demande et conservé jusqu'au refund, identique au numéro de tracking à saisir sur la page de suivi ; les enregistrements `SB****` existants continuent de fonctionner.

### Modified Capabilities
<!-- Le capability `client-order-reference-fsb` du changement archivé n'a pas été synchronisé dans openspec/specs ; la présente capability le supersede. -->

## Impact

- `app/Services/SharedIdService.php` : format de génération/parsing (`SB` → `FSB`, compat SB).
- `app/Models/SourcingRequest.php` : accesseur `reference_id` (inchangé, retourne `shared_id` désormais FSB).
- `app/Models/SourcingOrder.php` : `reference_id` / `fsb_tracking_number` / `getFsbTrackingNumberForDestinationIndex` / `resolveFsbNumberToOrderAndDestinationIndex` (priorité `shared_id` FSB, chemin légacy).
- `app/Services/Tracking/UnifiedTrackingService.php` : résolution conservatrice (passe par la résolution du modèle).
- `app/Notifications/FsbTrackingGenerated.php`, `TrackingNumberAdded.php` : contenus (via accesseurs, automatique).
- Vues refunds client : `client/refund-requests/{show,index,create}.blade.php` (`display_id` → `reference_id`). Vues refunds admin : audit similaire.
- Migrations : `alter table sourcing_requests/orders alter shared_id varchar(9)`.
- Tests : `tests/Unit/SharedIdServiceTest.php`, `tests/Feature/SharedIdGenerationTest.php`, `tests/Feature/QuotationAcceptCreatesOrderTest.php`, `tests/Feature/OrderTrackingFsbResolutionTest.php`, specs e2e `client-sourcing-request.spec.ts` (référence SB→FSB), `admin-dashboard.spec.ts`, `admin-sourcing-request.spec.ts` (recherche par réf).
- Colonne `shared_id` : nom conservé (usage interne), seule la largeur change.