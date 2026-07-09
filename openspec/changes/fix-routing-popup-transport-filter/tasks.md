## 1. Filtrer les items indirects selon le transport dans `getRatesForPopup()`

- [x] 1.1 Dans `app/Http/Controllers/Client/ShippingFeeController.php`, ligne 147, ajouter `$transportType === 'air' &&` à la condition de chargement des items indirects

## 2. Respecter la valeur pré-sélectionnée de `sourcing_location` dans le popup

- [x] 2.1 Dans `submitForm()`, remplacer `this.selectedRoute = 'china'` par la valeur existante de `sourcing_location` (fallback à 'china')

## 3. Vérification

- [ ] 3.1 Tester : transport = Air → les options Direct (air_direct) et Indirect (air_indirect) s'affichent (test manuel navigateur requis)
- [ ] 3.2 Tester : transport = Sea → seule l'option Direct (sea) s'affiche, l'option Indirect est cachée (test manuel navigateur requis)
- [ ] 3.3 Tester : pays avec `is_direct = true` → pas d'option indirecte (déjà géré existant — pas de changement nécessaire)
- [ ] 3.4 Tester : `sourcing_location` = Dubai pré-sélectionné → le popup s'ouvre avec Dubai coché par défaut
