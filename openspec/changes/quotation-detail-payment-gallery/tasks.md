## 1. Images des options de qualité en plein écran

- [x] 1.1 Ajouter `onclick="openMediaModal(this.src, 'image')"` sur les `<img>` des options de qualité (`low`, `medium`, `good`) dans `resources/views/client/sourcing-requests/show.blade.php`

## 2. Moyens de paiement sur la page détail sourcing request

- [x] 2.1 Ajouter une section "Available Payment Methods" dans `resources/views/client/sourcing-requests/show.blade.php` qui liste les `$paymentMethods` (déjà passées par le controller)
  - Afficher le logo (si existant) et le nom de chaque moyen de paiement
  - Rendre les détails expandables/collapsables via un accordéon (Alpine.js `x-data`, `x-show`)
  - Afficher chaque détail comme une paire clé/valeur dans un tableau
  - Même design que la section existante sur `sourcing-orders/show.blade.php`
- [x] 2.2 Conditionner l'affichage à `@if($paymentMethods->isNotEmpty())`

## 3. Vérification finale

- [x] 3.1 Vérifier que les images qualité s'ouvrent en plein écran au clic
- [x] 3.2 Vérifier que la section moyens de paiement s'affiche avec les détails bancaires
