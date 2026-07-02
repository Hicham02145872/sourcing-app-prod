## Context

La page `client/sourcing-requests/show.blade.php` affiche le devis après qu'il a été envoyé par l'admin. Actuellement :
- Les moyens de paiement (PaymentMethod) ne sont pas affichés : le client ne voit pas comment effectuer le paiement (RIB, IBAN, SWIFT, etc.)
- Les images des options de qualité (`low`, `medium`, `good`) sont affichées en petit format `object-cover` sans possibilité de cliquer pour agrandir.
- Un composant `x-photo-viewer` existe déjà et une modale JS `openMediaModal` est présente mais non utilisée pour les images qualité.
- Le controller `SourcingRequestController@show` passe déjà `$paymentMethods` à la vue mais la section n'est pas rendue.

## Goals / Non-Goals

**Goals:**
- Afficher les moyens de paiement actifs (PaymentMethod) avec leurs détails (nom, logo, coordonnées bancaires) sur la page détail sourcing request côté client
- Permettre au client d'expand/collapse les détails de chaque moyen de paiement
- Rendre les images des options de qualité cliquables pour ouverture en plein écran via la modale existante

**Non-Goals:**
- Ne pas modifier le flux de paiement existant
- Ne pas ajouter de nouveau modèle ou migration
- Ne pas toucher à la page admin

## Decisions

### D1. Réutilisation du modal `openMediaModal` existant
**Décision :** Utiliser la fonction `openMediaModal(src, type)` déjà existante dans `show.blade.php` pour afficher les images qualité en plein écran.
**Alternative :** Utiliser le composant `x-photo-viewer` — nécessite de convertir le rendu, plus complexe.
**Justification :** La modale JS existe déjà et fonctionne ; il suffit d'ajouter `onclick="openMediaModal(this.src, 'image')"` sur les `<img>` des quality options.

### D2. Section moyens de paiement toujours visible
**Décision :** Ajouter une section "Available Payment Methods" qui s'affiche dès qu'il y a des `PaymentMethod` actives, sans condition de statut de devis. Le client peut consulter les coordonnées bancaires à tout moment.
**Réutilisation :** Même pattern que la section existante sur `sourcing-orders/show.blade.php` (accordéon avec logo, nom, détails expandables).
**Données à afficher :** Nom, logo, détails (clés/valeurs du champ JSON `details`).
**Justification :** Le controller passe déjà `$paymentMethods` ; il suffit d'ajouter le rendu dans la vue.

## Risks / Trade-offs

| Risque | Mitigation |
|---|---|
| Aucun moyen de paiement actif | Utiliser une condition `@if($paymentMethods->isNotEmpty())` |
| Les détails JSON peuvent être vides | Vérifier avec `@if(!empty($paymentMethod->details))` |
