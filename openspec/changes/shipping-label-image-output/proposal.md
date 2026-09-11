## Why

Les étiquettes d'expédition sont aujourd'hui générées en PDF uniquement (barryvdh/laravel-dompdf). L'owner souhaite un rendu image (PNG, 300 DPI) de la même étiquette pour une impression de qualité et une diffusion facilitée, sans renoncer au PDF existant.

## What Changes

- Nouveau service `App\Services\ShippingLabelImageService` rendant l'étiquette en PNG via GD natif (aucune dépendance externe), calqué visuellement sur la vue `admin/sourcing-orders/shipping-label.blade.php` actuelle.
- Rendu à 300 DPI sur une toile A4 (2480 × 3508 px) avec polices TrueType DejaVu présentes dans le vendor (coexistants avec dompdf).
- Endpoints : le paramètre de requête `?format=png` sur les routes d'étiquette admin et client (`showShippingLabel`, `showShippingLabelForDestination`) retourne l'image PNG; le format par défaut reste le PDF.
- Gating par feature flag `label_image_output` (reconvention `FeatureFlagService`) + config `fsb.label` (dpi 300, chemins de police).
- Aucune migration, aucun changement de schéma; le PDF et le flux d'auto-fill d'adresse (`label_address ?: address` dans la vue) sont conservés.
- **Non-breaking** : comportement par défaut inchangé.

## Capabilities

### New Capabilities
- `shipping-label-image-output`: rendition PNG 300 DPI de l'étiquette d'expédition, cohérente avec la vue PDF existante et contrôlée par feature flag.

### Modified Capabilities
<!-- Aucune spec existante concernée -->

## Impact

- `app/Services/ShippingLabelImageService.php` : rendu GD (imagecreatetruecolor, imagettftext, wrapping, bordure de tableau, logo).
- Polices : `vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf` + `DejaVuSans-Bold.ttf` ; logo `public/images/logo.png`.
- `app/Http/Controllers/Admin/SourcingOrderController.php` + `app/Http/Controllers/Client/SourcingOrderController.php` : branche `format=png` sur les méthodes étiquette.
- `config/fsb.php` (section `label`) ; flag `FeatureFlagService` `label_image_output`.
- Tests Feature/unit : header PNG, dimensions DPI, contenu, wrapping, fallback flag off, non-régression PDF.