## Why

Améliorer le flux de création des devis (quotations) en affichant l'adresse de livraison des destinations, optimiser la gestion des frais d'expédition en filtrant les pays configurés, et vérifier la fiabilité de la logique de routage Chine/Dubai dans le popup de création de demande d'approvisionnement.

## What Changes

1. **Ajout de l'adresse de livraison dans le formulaire de création de devis** : Afficher l'adresse de livraison (`address`, `label_address`) de chaque destination dans la page `admin/quotations/create.blade.php` (section "Destinations & Quantités") pour que l'admin voie l'adresse complète lors de la création du devis.

2. **Filtre des pays configurés dans la liste des frais d'expédition** : Ajouter un bouton filtre dans le composant Livewire `ShippingFeesTable` qui permet de basculer entre l'affichage de tous les pays et l'affichage des pays qui ont au moins un `ShippingFeeItem` avec `price_per_kg` renseigné (pour éviter les lignes vides).

3. **Vérification et correction de la logique Chine/Dubai dans le popup "Choose Shipping Routing"** :
   - Analyser la méthode `getRatesForPopup()` dans `ShippingFeeController` et la logique Alpine.js `sourcingRequestForm` dans `client/sourcing-requests/create.blade.php`
   - Vérifier que le routage direct utilise bien les bons champs de prix (`price_per_kg`)
   - Vérifier que le routage indirect (via Dubai) utilise correctement `price_per_kg_china_to_dubai` + `price_per_kg_dubai_to_africa`
   - S'assurer que la sélection de route affecte correctement le `sourcing_location` soumis
   - Apporter les corrections nécessaires si des incohérences sont trouvées

## Capabilities

### New Capabilities
- `quotation-shipping-address`: Affichage de l'adresse de livraison dans le formulaire de création de devis admin
- `shipping-fees-filter`: Filtre d'affichage des pays avec/sans frais d'expédition configurés dans le tableau admin

### Modified Capabilities
- `sourcing-routing-popup`: Vérification et correction de la logique de routage Chine/Dubai (direct/indirect) dans le popup de création de demande d'approvisionnement client

## Impact

- **Vue** : `resources/views/admin/quotations/create.blade.php` — ajout de l'affichage de l'adresse
- **Vue** : `resources/views/livewire/admin/shipping-fees-table.blade.php` — ajout du bouton filtre
- **Livewire** : `app/Livewire/Admin/ShippingFeesTable.php` — ajout de la propriété `$showConfiguredOnly` et logique de filtrage
- **Contrôleur** : `app/Http/Controllers/Client/ShippingFeeController.php` — vérification de la méthode `getRatesForPopup()`
- **Vue** : `resources/views/client/sourcing-requests/create.blade.php` — vérification de la logique Alpine.js `sourcingRequestForm`
- **Modèle** : `app/Models/SourcingRequestDestination.php` — déjà en place, pas de changement nécessaire
- **Migration** : Aucune migration nécessaire (les champs `address` et `label_address` existent déjà dans `sourcing_request_destinations`)
