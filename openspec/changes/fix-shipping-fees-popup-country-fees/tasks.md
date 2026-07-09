## 1. Modifier `confirmRoutingPopup()` pour afficher le popup récapitulatif

- [x] 1.1 Dans `resources/views/client/sourcing-requests/create.blade.php`, modifier `confirmRoutingPopup()` pour :
  - Fermer le popup de routage (`showRoutingPopup = false`)
  - Définir `sourcing_location` sur la route sélectionnée (inchangé)
  - Ouvrir le popup récapitulatif (`showFeeModal = true`) avec `loadingFees = true`
  - Collecter tous les blocs de destination et leurs quantités
  - Pour chaque destination, appeler `getFeeUrl(countryId, transport, sourcing)` en parallèle via `Promise.all()`
  - Remplir `feeDestinations` avec les résultats (pays, transport, sourcing, quantité, items, currency, unit, arrival_time)
  - Ne PAS soumettre le formulaire (la soumission sera faite depuis le popup récapitulatif)

## 2. Adapter le popup récapitulatif pour le nouveau flux

- [x] 2.1 Vérifier que le template du popup récapitulatif (lignes 549-668) affiche correctement toutes les informations :
  - Nom du pays et drapeau
  - Mode de transport et source (China/Dubai)
  - Quantité commandée
  - Tableau des items (item_style, price_per_kg, delay, estimated total)
  - Délai d'arrivée estimé

- [x] 2.2 Vérifier que `confirmSubmit()` soumet bien le formulaire via `submitFormDirectly()` (déjà correct)

- [x] 2.3 Synchroniser : le `sourcing_location` déjà défini dans `confirmRoutingPopup()` doit être conservé lors de la soumission finale

## 3. Vérification finale

- [ ] 3.1 Tester avec 1 destination : le popup récapitulatif s'affiche après le routage, les frais du pays sont visibles, la soumission fonctionne (test manuel navigateur requis)
- [ ] 3.2 Tester avec 2+ destinations : chaque pays affiche ses propres frais, les quantités sont correctes (test manuel navigateur requis)
- [ ] 3.3 Tester avec un pays sans frais configurés : le message "No shipping rates available" s'affiche, la soumission est toujours possible (test manuel navigateur requis)
- [ ] 3.4 Tester avec `shipping_fees_popup` désactivé : le flux sans popup fonctionne toujours (soumission directe) — déjà garanti par le code : si `feePopupEnabled` est `false`, `submitForm()` soumet directement sans popup (ligne 901-903 existant inchangé)
