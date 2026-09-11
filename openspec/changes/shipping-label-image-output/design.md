## Context

- Étiquette actuelle : vue Blade `admin/sourcing-orders/shipping-label.blade.php` rendue en PDF par `Pdf::loadView()` (`SourcingOrderController::showShippingLabel` l.224, variante par destination l.238, et équivalent client `Client\SourcingOrderController::showShippingLabel`). Layout : logo, tableau (Country / Seller Name / Order ID / Product Name / Quantity / Recipient Address), footer « Contact Us » + WhatsApp.
- L'adresse de destination est d'office pré-remplie : `label_address ?: address` (l.95 de la vue) → l'« auto-fill adresse » du module est déjà couvert par le fallback existant, non-goal ici.
- Extension PHP : `gd` disponible (vérifié `php -m` en local) ; imagick absent. Polices : `vendor/dompdf/dompdf/lib/fonts/DejaVuSans[-Bold].ttf` ; logo `public/images/logo.png`.
- Convention flags : `FeatureFlagService` (DB) + middleware `feature`.

## Goals / Non-Goals

**Goals:**
- Générer un PNG A4 300 DPI fidèle à l'étiquette PDF actuelle, avec GD uniquement.
- Exposer `?format=png` côté admin et client sans casser le format par défaut (PDF).
- Rendu maîtrisé du texte long (wrapping de l'adresse) et des bordures.

**Non-Goals:**
- Pas de changement du rendu PDF ni de la mise en page.
- Pas de nouvelle migration/colonne.
- Pas de gestion des ligatures arabes RTL (texte rendu tel quel, limitations GD documentées).
- Pas de composant bandeau/filtre (hors périmètre).

## Decisions

- **GD, pas Puppeteer/Imagick** : zéro dépendance système, présent sur la stack. `imagecreatetruecolor(2480, 3508)` + `imagealphablending(false)/imagesavealpha(true)` pour un fond blanc propre, `imagepng()` ; échelle 300/72 px par pt (A4 = 595.28 × 841.89 pt).
  *Alternatives écartées* : dompdf→PNG (inexistant/non fiable), Browsershot (Chromium à installer sur le VPS, dégradé), Imagick (extension absente).
- **Service dédié** `ShippingLabelImageService` : `dimensions()` (A4@300), `render(SourcingOrder $order, ?SourcingRequestDestination $destination): GdImage`, méthode privée `wrapText()` (mesure `imagettfbbox` + retour à la ligne sur limite de mot), `drawTable()`. Testable en unit sans HTTP.
- **Branchement routes** : dans les 4 méthodes d'étiquette (admin + client, globale + destination) — si `?format=png` : `response($image->pngBlob(), 200)->header('Content-Type','image/png')->header('Content-Disposition','inline; filename="shipping-label-...png"')`. Le mode par défaut (sans paramètre) reste le PDF intact.
- **Feature flag** : clé `label_image_output` ; si flag désactivé, `format=png` retombe silencieusement sur le PDF (ou 404 selon convention — choix : retour PDF, moins intrusif). Config `config/fsb.php` → `label.dpi = 300`, `label.font_path`, `label.font_bold_path`, `label.logo_path`.
- **Concurrence/perf** : strings courts, rendu < 100 ms ; aucune mise en cache spécifique (le PNG se régénère à la volée comme le PDF actuel).
- **Tests** : unit — header PNG (`\x89PNG`), dimensions 2480×3508, contenu diffère selon `reference_id` ; Feature — `?format=png` → 200 + content-type image/png, `?format=pdf`/absent → PDF conservé, flag off → PDF retombe, longues adresses ne lèvent pas d'exception, logo absent (fichier manquant) géré sans crash.

## Risks / Trade-offs

- `gd` absent sur le VPS → vérifier `php -m | grep gd` avant déploiement ; mitigation : exception claire si `! function_exists('imagecreatetruecolor')`, repli sur le PDF.
- Texte arabe non sculpté par GD (pas de ligatures/HarfBuzz) → limitation documentée ; les libellés d'étiquette restent latin/chiffres/téléphone ; les adresses sont rendues telles quelles.
- Écart typographique minime avec le PDF (GD vs dompdf) → mise en page répliquée à l'identique (mêmes marges 20 px, mêmes épaisseurs de bordure 2 px, mêmes tailles de police 14/16/18).
- Rollback : retirer le branchement `format=png` + le service ; aucune donnée en DB.