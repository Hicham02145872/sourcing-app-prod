## 1. Filtrer les items indirects selon le transport dans `getRatesForPopup()`

- [x] 1.1 Dans `app/Http/Controllers/Client/ShippingFeeController.php`, ligne 147, ajouter `$transportType === 'air' &&` à la condition de chargement des items indirects

## 2. Vérification

- [ ] 2.1 Tester : transport = Air → les options Direct (air_direct) et Indirect (air_indirect) s'affichent (test manuel navigateur requis)
- [ ] 2.2 Tester : transport = Sea → seule l'option Direct (sea) s'affiche, l'option Indirect est cachée (test manuel navigateur requis)
- [ ] 2.3 Tester : pays avec `is_direct = true` → pas d'option indirecte (déjà géré existant — pas de changement nécessaire)
