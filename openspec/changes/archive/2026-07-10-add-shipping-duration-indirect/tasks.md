## 1. Database Migration

- [x] 1.1 Créer la migration pour ajouter `china_to_dubai_duration` (varchar, nullable) et `dubai_to_destination_duration` (varchar, nullable) à la table `shipping_fees`
- [ ] 1.2 Exécuter la migration (`php artisan migrate`)

## 2. Model Update

- [x] 2.1 Ajouter `china_to_dubai_duration` et `dubai_to_destination_duration` au tableau `$fillable` du model `ShippingFee`

## 3. Admin - ShippingFeeEdit

- [x] 3.1 Ajouter les propriétés publiques `$china_to_dubai_duration` et `$dubai_to_destination_duration` dans `ShippingFeeEdit.php`
- [x] 3.2 Initialiser ces propriétés dans `mount()` à partir de `$fee->china_to_dubai_duration` et `$fee->dubai_to_destination_duration`
- [x] 3.3 Inclure les nouveaux champs dans le tableau `$data` du méthode `save()`

## 4. Admin - Vue Blade

- [x] 4.1 Modifier `shipping-fee-edit.blade.php` pour afficher deux inputs ("Durée Chine → Dubaï" et "Durée Dubaï → Destination") dans l'onglet air_indirect
- [x] 4.2 Ajouter les `wire:model` correspondants pour les deux nouveaux champs

## 5. Client - ShippingFeeController

- [x] 5.1 Modifier `getRatesForPopup()` pour retourner `china_to_dubai_duration` et `dubai_to_destination_duration` dans l'objet `indirect`
- [x] 5.2 Ajouter le fallback sur `air_indirect_arrival_time` si les durées segmentées sont NULL

## 6. Client - Vues

- [x] 6.1 Modifier `create.blade.php` (popup sourcing) pour afficher les durées segmentées dans l'itinéraire indirect
- [x] 6.2 Modifier `shipping-fees-list.blade.php` pour afficher les durées segmentées dans l'onglet indirect
- [x] 6.3 Ajouter le fallback sur `air_indirect_arrival_time` côté client si les durées sont NULL
