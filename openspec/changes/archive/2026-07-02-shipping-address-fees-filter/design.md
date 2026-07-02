## Context

Le projet utilise Laravel 12 + Livewire 3 + Alpine.js. Les adresses de livraison sont déjà stockées dans `sourcing_request_destinations.address` et `label_address` mais ne sont pas affichées dans le formulaire de création de devis admin. La liste des frais d'expédition affiche tous les pays y compris ceux sans aucune configuration. Le popup de routage Chine/Dubai utilise `getRatesForPopup()` avec une logique de séparation direct/indirect qui doit être vérifiée.

## Goals / Non-Goals

**Goals:**
- Afficher l'adresse de livraison complète dans la section "Destinations & Quantities" du formulaire de création de devis
- Ajouter un bouton filtre dans le tableau des frais d'expédition pour ne montrer que les pays avec des `price_per_kg` configurés
- Vérifier et corriger la logique de routage Chine/Dubai (direct/indirect) dans le popup de création de demande d'approvisionnement

**Non-Goals:**
- Ne pas modifier le modèle de données ni ajouter de migration (les champs existent déjà)
- Ne pas changer le comportement du flux côté client (sourcing request), seulement vérifier/corriger la logique existante
- Ne pas ajouter de nouvelles fonctionnalités de filtrage avancé ou d'export

## Decisions

### D1. Affichage de l'adresse dans la vue create.blade.php
**Décision :** Ajouter une colonne "Address" dans le tableau des destinations de `admin/quotations/create.blade.php` et utiliser les champs `$dest->address` et `$dest->label_address` déjà disponibles via la relation `$sourcingRequest->destinations`.
**Alternative envisagée :** Créer un partial séparé — rejeté car l'info s'intègre naturellement dans le tableau existant.
**Justification :** Aucune migration nécessaire, les données sont déjà chargées via la relation Eloquent.

### D2. Filtre des pays configurés dans ShippingFeesTable
**Décision :** Ajouter une propriété Livewire `$showConfiguredOnly = false` avec une méthode `toggleConfigured()` qui modifie la requête pour ne retourner que les pays ayant `shippingFee.items` avec `price_per_kg IS NOT NULL`.
**Alternative envisagée :** Filtre côté JavaScript — rejeté car Livewire permet un filtrage côté serveur plus robuste et compatible avec la pagination existante.
**Justification :** Approche simple, performante, et cohérente avec l'architecture Livewire existante.

### D3. Vérification de la logique Chine/Dubai
**Décision :** Analyser la méthode `getRatesForPopup()` dans `ShippingFeeController` et la logique Alpine.js `sourcingRequestForm`. Le routage direct utilise `price_per_kg` avec filtrage des styles UAE. Le routage indirect combine les items `train` et les items UAE-hub du transport sélectionné, mais utilise encore `price_per_kg` au lieu de la somme `price_per_kg_china_to_dubai + price_per_kg_dubai_to_africa`. Si c'est le cas, corriger pour utiliser les champs split pour le routage indirect.
**Justification :** Les colonnes `price_per_kg_china_to_dubai` et `price_per_kg_dubai_to_africa` ont été ajoutées via migration `2026_06_29_155833_add_split_indirect_cost_columns_to_shipping_fee_items_table` mais le popup doit être vérifié pour confirmer qu'il les utilise correctement.

## Risks / Trade-offs

| Risque | Mitigation |
|---|---|
| Le popup utilise `price_per_kg` au lieu de `price_per_kg_china_to_dubai + price_per_kg_dubai_to_africa` pour l'indirect | Vérifier et corriger dans `getRatesForPopup()` pour assembler le prix total indirect comme somme des deux champs split |
| La sélection du routing dans le popup écrase le champ `sourcing_location` du formulaire | Vérifier que `confirmRoutingPopup()` définit correctement le select Tom Select — déjà fait mais à tester |
| Le filtre "configured only" ralentit si beaucoup de pays avec jointures | Utiliser `whereHas` avec une sous-requête optimisée ; la table `shipping_fee_items` a déjà un index |
