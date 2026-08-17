## Why

Sur la page d'édition d'une quotation (`/admin/quotations/{id}/edit`), cliquer sur une photo de qualité (low/medium/good) ne fait rien. Le partial `quality-options.blade.php` dispatch un événement Alpine `open-viewer`, mais la page édition n'a pas de listener `@open-viewer.window` ni de modal correspondante — contrairement à la page création qui fonctionne correctement.

## What Changes

- Ajouter le modal viewer (`@open-viewer.window` listener + template) à la page `edit.blade.php`, identique à celui déjà présent dans `create.blade.php`.
- Aucune modification du partial `quality-options.blade.php` nécessaire — il fonctionne déjà correctement avec le dispatch d'événements.

## Capabilities

### New Capabilities

- `quality-photo-zoom-edit`: Ajout du modal de zoom photo pour les images de qualité sur la page d'édition des quotations.

### Modified Capabilities

<!-- Aucune capacité existante n'est modifiée -->

## Impact

- **Fichier modifié**: `resources/views/admin/quotations/edit.blade.php` — ajout du bloc modal viewer (~18 lignes) avant `@push('scripts')`.
- **Aucun impact** sur les autres pages (create, client show) — le partial partagé n'est pas touché.
- **Aucune dépendance** ajoutée — utilise Alpine.js déjà inclus dans le layout.
