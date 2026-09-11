## 1. Configuration

- [x] 1.1 Add `label` section to `config/fsb.php`: `dpi` (300), `font_path` (vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf), `font_bold_path`, `logo_path` (public/images/logo.png)
- [x] 1.2 Verify `gd` availability in tests (`function_exists('imagecreatetruecolor')`) and on prod deployment step

## 2. Service de rendu

- [x] 2.1 Create `App\Services\ShippingLabelImageService` with A4@300 DPI sizing helper and `render(SourcingOrder $order, ?SourcingRequestDestination $destination): GdImage`
- [x] 2.2 Implement background fill (alpha-blended white), logo rendering, table drawing (2 px borders, label cell fill #f9f9f9, fonts 14/16/18) mirroring `shipping-label.blade.php`
- [x] 2.3 Implement word-wrap helper using `imagettfbbox` for the recipient address
- [x] 2.4 Add `pngBlob()` returning `imagepng()` output and graceful handling of missing logo/font files

## 3. Branchement routes

- [x] 3.1 In `Admin\SourcingOrderController::showShippingLabel` and `showShippingLabelForDestination` support `?format=png` → `image/png` inline response (PDF path unchanged)
- [x] 3.2 In `Client\SourcingOrderController` ship the same `?format=png` support on each label method
- [x] 3.3 Gate the PNG branch with `FeatureFlagService::isEnabled('label_image_output', ...)`; when disabled, fall back to PDF
- [x] 3.4 Verify `route:list` before and after (no route name changes)

## 4. Tests

- [x] 4.1 Unit test: service produces a PNG with `image/png` header signature at 2480 × 3508
- [x] 4.2 Unit test: rendered bytes differ between two different order references
- [x] 4.3 Feature test: `?format=png` returns 200 `image/png` on admin and client label routes
- [x] 4.4 Feature test: per-destination label `?format=png` returns a valid PNG
- [x] 4.5 Feature test: long address word-wraps without exception
- [x] 4.6 Feature test: missing logo does not crash rendering
- [x] 4.7 Feature test: default (no format) still returns PDF; flag `label_image_output` disabled → `?format=png` falls back to PDF