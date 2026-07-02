## Why

La page de détail de la demande d'approvisionnement (sourcing request) côté client ne montre pas les informations de paiement après acceptation du devis (statut, preuve de paiement, montant payé). De plus, les images des options de qualité ne sont pas cliquables pour une visualisation en plein écran, ce qui empêche le client de bien voir les détails du produit.

## What Changes

1. **Ajout des informations de paiement sur la page détail sourcing request client** : Afficher le statut de paiement (payé/en attente), la preuve de paiement téléchargée, le montant total payé, et les dates clés après que le client a accepté le devis.

2. **Rendre les images des options de qualité cliquables pour plein écran** : Ajouter un gestionnaire de clic sur les images des options de qualité (`low`, `medium`, `good`) dans la page client `sourcing-requests/show.blade.php` pour ouvrir un modal de visualisation plein écran (réutiliser le composant `x-photo-viewer` existant ou la modale `openMediaModal`).

## Capabilities

### New Capabilities
- `quotation-payment-info`: Affichage des informations de paiement (statut, preuve, montant) sur la page de détail de la demande d'approvisionnement côté client après acceptation du devis
- `quality-images-fullscreen`: Visualisation plein écran des images des options de qualité sur la page client

### Modified Capabilities
<!-- Aucune spec existante n'est modifiée -->

## Impact

- **Vue** : `resources/views/client/sourcing-requests/show.blade.php` — ajout section paiement + rendre les images qualité cliquables
- **Modèle** : `app/Models/SourcingOrder.php` — déjà existant avec les champs nécessaires
- **Composant** : `resources/views/components/photo-viewer.blade.php` — déjà existant, à réutiliser
