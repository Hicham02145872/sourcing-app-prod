## Why

Dans le popup "Choose Shipping Routing" de la création de demande d'approvisionnement, l'option "Indirect Shipping" (via Dubai) s'affiche même quand le transport sélectionné est "Sea". Or, le routage indirect (Chine → Dubai → pays de destination) est toujours aérien — il n'a pas de sens en transport maritime. Cela crée de la confusion pour le client qui voit une option indirecte avec des frais aériens alors qu'il a choisi le transport maritime.

## What Changes

1. **Filtrer les items indirects selon le transport** : Dans `getRatesForPopup()`, ne charger les items indirects (`air_indirect`/`train`) que si le transport demandé est `air`. Si le transport est `sea`, retourner un tableau vide pour `indirect.items`.

2. **Ajuster les `directTransportTypes` pour le legacy** : Déjà corrigé dans le change précédent — les items `air_direct` et `air` sont filtrés ensemble pour la route directe, et `air_indirect` et `train` pour la route indirecte.

## Capabilities

### Modified Capabilities
- `sourcing-routing-popup`: Correction du filtrage des options de routage selon le transport sélectionné (air/sea)

## Impact

- **Contrôleur** : `app/Http/Controllers/Client/ShippingFeeController.php` — méthode `getRatesForPopup()` : ajouter condition `$transportType === 'air'` pour le chargement des items indirects
- **Vue** : Aucun changement nécessaire — l'affichage conditionnel `x-show="ratesData?.indirect?.items?.length > 0"` existe déjà
