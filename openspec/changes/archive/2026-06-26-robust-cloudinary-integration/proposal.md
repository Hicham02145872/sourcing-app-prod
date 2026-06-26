## Why

Cloudinary est intégré mais pas robuste : pas de migration exécutée, pas de nettoyage à la suppression, `PaymentMethodController` le contourne, et la config production est vide. On prend le risque de fichiers orphelins, de perte de données, et d'un fallback silencieux vers le disque local (non scalable).

## What Changes

- Migrer toutes les images locales existantes vers Cloudinary via la commande `images:migrate-to-cloudinary`
- Ajouter la suppression des images Cloudinary lors de la suppression des modèles (éviter les orphelins)
- Corriger `PaymentMethodController` pour utiliser `ImageProcessingService` (comme tous les autres)
- Configurer `CLOUDINARY_URL` dans les fichiers `.env.production` et `env.production`
- Améliorer la gestion d'erreurs et logging dans le pipeline d'upload
- Stocker les Public IDs Cloudinary en base plutôt que les URLs complètes (pour exploiter les transformations)
- Garantir que le fallback local reste fonctionnel si Cloudinary est indisponible

## Capabilities

### New Capabilities
- `cloudinary-image-migration`: Migration des images locales existantes vers Cloudinary avec rollback possible
- `cloudinary-asset-cleanup`: Suppression automatique des assets Cloudinary à la suppression des modèles
- `cloudinary-transform`: Génération d'URLs transformées (resize, crop) via Public IDs plutôt qu'URLs brutes

### Modified Capabilities
<!-- No existing specs to modify -->

## Impact

- **Models**: `SourcingRequest`, `Quotation`, `QuotationMedia`, `SourcingOrder`, `SourcingOrderMedia`, `PaymentMethod`, `User`, `RefundRequest` — ajout/suppression de champs Public ID
- **Controllers**: `PaymentMethodController` — refactor upload
- **Service**: `ImageProcessingService` — meilleure gestion d'erreurs, double stockage Public ID + URL
- **Helper**: `media_url()` — support des transformations optionnelles
- **Config**: `.env.production`, `env.production` — ajout `CLOUDINARY_URL`
- **Dépendances**: Aucune nouvelle dépendance
