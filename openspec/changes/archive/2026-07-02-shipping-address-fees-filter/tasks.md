## 1. Adresse de livraison dans le formulaire de création de devis

- [x] 1.1 Ajouter une colonne "Address" dans le tableau des destinations de `admin/quotations/create.blade.php` affichant `$dest->address` et `$dest->label_address`

## 2. Filtre des pays configurés dans la liste des frais d'expédition

- [x] 2.1 Ajouter la propriété `$showConfiguredOnly = false` et une méthode `toggleConfigured()` dans `app/Livewire/Admin/ShippingFeesTable.php`
- [x] 2.2 Modifier la méthode `render()` pour filtrer les pays via `whereHas('shippingFee.items', fn($q) => $q->whereNotNull('price_per_kg'))` quand `$showConfiguredOnly` est true
- [x] 2.3 Ajouter un bouton filtre "Configured Only" dans `resources/views/livewire/admin/shipping-fees-table.blade.php` avec un indicateur visuel d'état actif/inactif

## 3. Vérification et correction de la logique Chine/Dubai dans le popup de routage

- [x] 3.1 Analyser `getRatesForPopup()` dans `app/Http/Controllers/Client/ShippingFeeController.php` : vérifier que les prix indirects utilisent `price_per_kg_china_to_dubai + price_per_kg_dubai_to_africa` (somme) et non seulement `price_per_kg` — **confirmé : utilisait seulement `price_per_kg`**
- [x] 3.2 Corriger `getRatesForPopup()` si nécessaire pour assembler le prix indirect total à partir des deux champs split — **corrigé via `calculateIndirectPrice()`**
- [x] 3.3 Vérifier la logique Alpine.js `sourcingRequestForm` dans `resources/views/client/sourcing-requests/create.blade.php` : confirmer que `confirmRoutingPopup()` définit correctement `sourcing_location` sur la valeur sélectionnée — **confirmé correct**
- [x] 3.4 Vérifier que le calcul des prix dans le popup (section des items directs/indirects) correspond aux champs de prix corrects selon la route choisie — **direct: `price_per_kg`, indirect: somme split fields avec fallback**
- [x] 3.5 Appliquer les corrections nécessaires si des incohérences sont trouvées dans la logique du popup — **ajouté indicateur visuel du split pricing (CN→DXB + DXB→pays)**

## 4. Vérification finale

- [x] 4.1 Tester l'affichage de l'adresse dans la page de création de devis
- [x] 4.2 Tester le filtre des pays configurés (activer/désactiver, combiné avec recherche)
- [x] 4.3 Tester le popup de routage Chine/Dubai avec différents pays configurés
