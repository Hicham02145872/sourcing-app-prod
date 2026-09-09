## 1. Génération du FSB (SharedIdService)

- [x] 1.1 Mettre à jour `SharedIdService::format()` pour générer `FSB` + 6 chiffres (ex. `FSB000005`) et `parse()` pour accepter `FSB\d{6}` (nouveau) et `SB\d{5}` (legacy).
- [x] 1.2 Ajouter/modifier un test unitaire `SharedIdServiceTest` : format FSB, séquence ×5, parsing FSB + SB legacy, `currentCounter`.

## 2. Migrations (largeur des colonnes)

- [x] 2.1 Créer une migration élargissant `sourcing_requests.shared_id` à `string(9)` (garde nullable + unique).
- [x] 2.2 Créer une migration élargissant `sourcing_orders.shared_id` à `string(9)` (garde nullable + unique).
- [x] 2.3 Exécuter et vérifier les migrations sur la base de test (`php artisan migrate --env testing` ou équivalent stack).

## 3. SourcingRequest — référence FSB

- [x] 3.1 Vérifier qu'aucun littéral `SB` (labels, textes) n'est codé en dur sur les surfaces demande (dashboard, listes, show, bulk-payment, handling) ; corriger si besoin pour afficher `reference_id`.
- [x] 3.2 Confirmer que `reference_id` de `SourcingRequest` retourne bien le `shared_id` (désormais FSB) — pas de changement attendu, test de couverture.

## 4. SourcingOrder — FSB hérité (accesseurs + résolution)

- [x] 4.1 Modifier `getFsbTrackingNumberAttribute()` : retourner `shared_id` si format `FSB\d{6}`, sinon FSB dérivé de l'id (legacy).
- [x] 4.2 Modifier `getFsbTrackingNumberForDestinationIndex()` : base numérique du shared_id FSB + index pour les nouvelles commandes, encodage `id+k` legacy sinon.
- [x] 4.3 Modifier `resolveFsbNumberToOrderAndDestinationIndex()` : étape 1 par `shared_id` FSB exact, étape 2 multi-dest par base du shared_id + index, sinon chemin legacy (id / id+k).
- [x] 4.4 Vérifier `getReferenceIdAttribute()` délègue toujours à `fsb_tracking_number` (automatique) — pas de changement requis.
- [x] 4.5 Audit : vérifier qu'aucune autre construction `'FSB'.id` n'existe hors accesseurs (notifications, workflow admin, DevDashboard, caches `tracking:{fsb}`).

## 5. Suivi (tracking)

- [ ] 5.1 Vérifier que `UnifiedTrackingService` résout un FSB hérité via la nouvelle résolution du modèle (intégration uniquement, pas de changement de logique).
- [ ] 5.2 Vérifier le statut virtuel (`VirtualTrackingStatusService`) pour un FSB hérité sans numéro transporteur réel.
- [ ] 5.3 Ajouter un test feature : saisir le FSB d'une nouvelle commande (hérité) dans le suivi → statut virtuel/réel correct ; et un FSB légacy → chemin actuel conservé.

## 6. Refunds

- [ ] 6.1 Remplacer `display_id` par `reference_id` dans `client/refund-requests/create.blade.php` (l.42) et `index.blade.php` (l.129).
- [ ] 6.2 Remplacer `display_id` par `reference_id` dans `client/refund-requests/show.blade.php` (l.41).
- [ ] 6.3 Audit des vues refunds admin (`admin/refund-requests/*`) et autres affichages `display_id` d'une commande dans le contexte client/admin.

## 7. Recherche admin

- [ ] 7.1 Vérifier que la recherche par `shared_id` couvre les deux formats (SB legacy et FSB) sans changement de requête.
- [ ] 7.2 Ajouter un test feature : recherche par `FSB000005` trouve la nouvelle commande ; recherche par `SB00015` trouve l'ancienne.

## 8. Tests & E2E

- [ ] 8.1 Mettre à jour `tests/Feature/SharedIdGenerationTest.php` (SR → FSB, ordre hérite du même FSB, legacy intact).
- [ ] 8.2 Mettre à jour `tests/Feature/QuotationAcceptCreatesOrderTest.php` (partage `shared_id` = FSB).
- [ ] 8.3 Mettre à jour `tests/Feature/OrderTrackingFsbResolutionTest.php` (résolution par `shared_id` FSB en priorité).
- [ ] 8.4 Adapter `tests/e2e/client-sourcing-request.spec.ts` : référence attendue `/FSB0\d{5,}/` (TC-SR-03) au lieu de `/SB0\d{4,}/`.
- [ ] 8.5 Vérifier `tests/e2e/admin-dashboard.spec.ts` et `admin-sourcing-request.spec.ts` : les recherches par numéro restent cohérentes avec la base persistée (SB legacy ou FSB) ; adapter le terme recherché si besoin.
- [ ] 8.6 Lancer la suite unitaire/feature (`php artisan test --env testing` ciblée) et les specs e2e impacts (QT/SR/admin) ; tout vert.

## 9. Lint & finalisation

- [ ] 9.1 Lint PHP (Pint/phpstan selon le projet) et lint des specs e2e (tsc/playwright) sur les fichiers modifiés.
- [ ] 9.2 Résumé de mise en production (changement de code + migrations) et checklist de rollback.