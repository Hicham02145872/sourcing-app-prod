## Why

Quand un client crée une demande d'approvisionnement (sourcing request), le popup "Shipping Fees Summary" (le deuxième modal) est censé afficher un récapitulatif détaillé des frais d'expédition par pays de destination. Cependant, ce popup n'est jamais déclenché dans le flux actuel — le code passe directement du popup de routage (Choose Shipping Routing) à la soumission du formulaire sans passer par l'écran récapitulatif des frais par pays. Les frais de country (par pays) que l'admin a configurés dans le panneau d'administration ne sont donc jamais visibles par le client avant la soumission.

## What Changes

1. **Activation du popup "Shipping Fees Summary" dans le flux de création** : Modifier la méthode `confirmRoutingPopup()` dans le composant Alpine.js `sourcingRequestForm` pour afficher le popup `showFeeModal` après la sélection du routage, au lieu de soumettre directement le formulaire.

2. **Récupération des frais pour toutes les destinations** : Dans le popup récapitulatif, pour chaque destination (pays), appeler l'API `getShippingFee()` pour récupérer les frais correspondant au transport et au sourcing sélectionnés. Remplir le tableau `feeDestinations` avec les données de chaque pays.

3. **Affichage des frais par pays dans le tableau récapitulatif** : Chaque destination affiche :
   - Le nom du pays et son indicatif (drapeau)
   - Le mode de transport (Air/Sea) et la source (China/Dubai)
   - La quantité commandée
   - Le tableau des items (item_style, price_per_kg, delay, estimated total)
   - Le délai d'arrivée estimé

4. **Soumission finale** : Le bouton "Confirm & Submit" du popup récapitulatif déclenche `confirmSubmit()` qui soumet le formulaire via `submitFormDirectly()`.

## Capabilities

### New Capabilities
- `shipping-fees-summary-popup`: Activation et implémentation du popup récapitulatif des frais d'expédition par pays de destination dans le flux de création de demande d'approvisionnement

### Modified Capabilities
- `sourcing-routing-popup`: Modification du flux pour insérer le popup récapitulatif entre la sélection du routage et la soumission finale

## Impact

- **Vue** : `resources/views/client/sourcing-requests/create.blade.php` — Modification de la méthode `confirmRoutingPopup()` et de la logique `showFeeModal` pour récupérer et afficher les frais par destination
- **Contrôleur** : `app/Http/Controllers/Client/ShippingFeeController.php` — La méthode `getShippingFee()` existe déjà et sera utilisée pour récupérer les frais par pays/transport/sourcing (aucun changement nécessaire)
- **Modèle** : Aucun changement nécessaire
- **Migration** : Aucune migration nécessaire
