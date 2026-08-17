## 1. Modal Viewer Implementation

- [x] 1.1 Ajouter le bloc modal viewer (Alpine.js `@open-viewer.window` listener + `x-teleport` template) dans `edit.blade.php` avant `@push('scripts')` (~ligne 562), en copiant le code de `create.blade.php` (lignes 286-303)

## 2. Verification

- [x] 2.1 Vérifier que le zoom fonctionne pour les images de qualité existantes (low, medium, good) sur la page d'édition
- [x] 2.2 Vérifier que le zoom fonctionne pour les nouvelles images de qualité ajoutées pendant l'édition
- [x] 2.3 Vérifier que la fermeture du modal fonctionne (bouton X, touche Escape, clic sur le fond)
