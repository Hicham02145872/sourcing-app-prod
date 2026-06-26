## Context

L'app utilise Cloudinary en mode hybride : `ImageProcessingService` tente Cloudinary avec fallback local. La plupart des vues rendent via `media_url()` qui supporte les deux sources. Mais plusieurs failles :

- **Migration jamais exécutée** : toutes les images en base sont des chemins locaux
- **Suppression non gérée** : seuls `SourcingRequest`, `SourcingOrder`, `SourcingOrderMedia` nettoient le disque local — aucun ne nettoie Cloudinary
- **Contournement** : `PaymentMethodController` fait du `store('public')` directement
- **Config production vide** : `CLOUDINARY_URL` absent des fichiers `.env.production` et `env.production`
- **Pas de transformations** : les URLs stockées sont des `secure_url` brutes, impossibles à redimensionner
- **Migration incomplète** : la commande `images:migrate-to-cloudinary` ne couvre pas `refund_proof_path` ni `RefundRequest`

## Goals / Non-Goals

**Goals:**
- Migrer toutes les images locales existantes vers Cloudinary
- Nettoyer les assets Cloudinary à la suppression des modèles
- Unifier tous les uploads via `ImageProcessingService` (fin du contournement)
- Permettre les transformations d'images (resize, crop, quality) via les Public IDs
- Assurer un fallback local fiable si Cloudinary est indisponible
- Configurer Cloudinary pour la production

**Non-Goals:**
- Remplacer le system de stockage local — il reste le fallback
- Migration vers Spatie Media Library ou autre lib
- Optimisation vidéo via Cloudinary
- CDN multi-région

## Decisions

### 1. Stockage : Public ID + URL complète en base
Ajouter une colonne `cloudinary_public_id` nullable sur chaque modèle porteur d'image. La colonne existante (`product_image`, `file_path`, etc.) continue de stocker l'URL complète ou le chemin local.

**Pourquoi pas seulement le Public ID ?**
- Le fallback local stocke des chemins, pas des Public IDs
- La lecture directe de l'URL est plus rapide (pas de reconstruction)
- Les notifications existantes utilisent déjà `media_url()` sur ces champs

### 2. ImageProcessingService retourne un DTO
Au lieu d'un `string`, `compressAndStore()` retournera un objet `ImageResult` avec `{ path: string, publicId: string|null }`.

### 3. Suppression via events + job queue
Les model events `deleting` existants seront étendus pour appeler Cloudinary. Mais pour ne pas ralentir les requêtes HTTP, la suppression Cloudinary sera dispatchée dans un job `DeleteCloudinaryAsset`.

### 4. Transformations via `media_url()` avec paramètres optionnels
```php
media_url($path, ['width' => 300, 'height' => 300, 'crop' => 'fill'])
```
Si c'est une URL Cloudinary, on extrait/reconstruit avec les transformations.
Si c'est un chemin local, on ignore les transformations.

### 5. Migration commande améliorée
- Ajouter le support de `refund_proof_path` (SourcingOrder) et `RefundRequest`
- Mode `--dry-run` pour voir ce qui serait migré
- Mode `--rollback` pour restaurer les chemins locaux (basé sur un log JSON des migrations)
- `--batch-size` pour traiter par lots

## Risks / Trade-offs

| Risk | Mitigation |
|------|-----------|
| **Rate limiting Cloudinary** pendant la migration massive | Ajouter `--batch-size` + `--delay` entre les lots |
| **Échec de suppression Cloudinary** (réseau, auth) | Le job retry 3× avec backoff. Si toujours KO, log + alert |
| **Public ID déjà existant** | Utiliser des préfixes de folder + `asset_folder` plutôt que des IDs custom |
| **Régression : fallback local non testé** | Ajouter des tests unitaires pour `ImageProcessingService` avec Cloudinary déconnecté |
| **URLs stockées changent de format** | `media_url()` est déjà compatible. Les anciennes URLs Cloudinary (sans Public ID) continuent de fonctionner |
