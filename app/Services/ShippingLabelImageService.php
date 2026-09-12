<?php

namespace App\Services;

use App\Models\SourcingOrder;
use App\Models\SourcingRequestDestination;
use GdImage;
use RuntimeException;

class ShippingLabelImageService
{
    private const CSS_DPI = 96.0;

    private int $dpi;

    private string $fontPath;

    private string $fontBoldPath;

    private string $logoPath;

    private int $logoBottomY = 0;

    public function __construct()
    {
        $this->dpi = (int) config('fsb.label.dpi', 300);
        $this->fontPath = (string) config('fsb.label.font_path');
        $this->fontBoldPath = (string) config('fsb.label.font_bold_path');
        $this->logoPath = (string) config('fsb.label.logo_path');
    }

    /**
     * A4 canvas size in pixels at the configured DPI (portrait).
     *
     * @return array{0: int, 1: int} [width, height]
     */
    public function dimensions(): array
    {
        $pointsPerMm = $this->dpi / 25.4;

        return [
            (int) round(210 * $pointsPerMm), // A4 width
            (int) round(297 * $pointsPerMm), // A4 height
        ];
    }

    /**
     * Convert a CSS pixel value to canvas pixels at the configured DPI.
     */
    private function px(float $css): int
    {
        return (int) round($css * ($this->dpi / self::CSS_DPI));
    }

    /**
     * Convert a typographic point value to canvas pixels at the configured DPI.
     */
    private function pt(float $points): int
    {
        return (int) round($points * ($this->dpi / 72.0));
    }

    public function render(SourcingOrder $order, ?SourcingRequestDestination $destination = null): GdImage
    {
        if (! function_exists('imagecreatetruecolor')) {
            throw new RuntimeException('The GD extension is required to render shipping label images.');
        }

        [$width, $height] = $this->dimensions();

        $image = imagecreatetruecolor($width, $height);
        // Start with a fully opaque white canvas, then switch blending back on
        // so the remaining drawing operations blend the same way across setups.
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, $width, $height, $white);
        imagealphablending($image, true);

        $destination ??= $order->quotation->sourcingRequest->destinations->first();

        $this->drawLogo($image, $width);
        $this->drawTable($image, $order, $destination);
        $this->drawFooter($image, $width, $height);

        return $image;
    }

    public function pngBlob(GdImage $image): string
    {
        ob_start();
        imagepng($image);
        $blob = (string) ob_get_clean();
        imagedestroy($image);

        return $blob;
    }

    private function font(bool $bold = false): string
    {
        $candidates = $bold
            ? [$this->fontBoldPath, $this->fontPath]
            : [$this->fontPath, $this->fontBoldPath];

        foreach ($candidates as $candidate) {
            if ($candidate && is_file($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException('Shipping label font file(s) not found. Check config fsb.label.font_path.');
    }

    private function drawLogo(GdImage $image, int $canvasWidth): void
    {
        if (! $this->logoPath || ! is_file($this->logoPath)) {
            $this->logoBottomY = 0;

            return;
        }

        $source = @imagecreatefrompng($this->logoPath);
        if (! $source) {
            $this->logoBottomY = 0;

            return;
        }

        $maxWidth = $this->px(400);
        $srcW = imagesx($source);
        $srcH = imagesy($source);

        $ratio = min(1.0, $maxWidth / $srcW);
        $dstW = (int) round($srcW * $ratio);
        $dstH = (int) round($srcH * $ratio);
        $dstX = (int) round(($canvasWidth - $dstW) / 2);
        $dstY = $this->px(30);

        imagecopyresampled($image, $source, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
        imagedestroy($source);

        $this->logoBottomY = $dstY + $dstH;
    }

    private function drawTable(GdImage $image, SourcingOrder $order, SourcingRequestDestination $destination): void
    {
        $black = imagecolorallocate($image, 0, 0, 0);
        $labelGray = imagecolorallocate($image, 249, 249, 249);

        $margin = $this->px(20);
        $tableStartX = $margin;
        $tableWidth = imagesx($image) - 2 * $margin;
        $labelWidth = (int) round($tableWidth * 0.35);
        $valueWidth = $tableWidth - $labelWidth;
        $borderW = max(1, $this->px(2));
        $cellPad = $this->px(15);

        // Top of the table sits under the logo with a clear gap.
        $y = $this->logoBottomY > 0
            ? $this->logoBottomY + $this->px(45)
            : $this->px(30) + $this->px(45);

        $rows = $this->tableRows($order, $destination);

        foreach ($rows as $row) {
            $labelLines = $this->wrapText($row['label'], $this->font(true), $this->pt(14), $labelWidth - 2 * $cellPad);
            $valueLines = $row['value_lines'] ?? $this->wrapText($row['value'], $this->font(false), $this->pt(16), $valueWidth - 2 * $cellPad);

            $labelLineH = $this->pt(14) + 10;
            $valueLineH = $this->pt(16) + 12;

            $cellHeight = max(
                count($labelLines) * $labelLineH + 2 * $cellPad,
                count($valueLines) * $valueLineH + 2 * $cellPad
            );

            // Label cell background
            imagefilledrectangle($image, $tableStartX, $y, $tableStartX + $labelWidth, $y + $cellHeight, $labelGray);

            // Label cell text (block vertically centered within the cell)
            $labelBlockH = count($labelLines) * $labelLineH;
            $labelTextY = $y + (int) round(($cellHeight - $labelBlockH) / 2) + $this->pt(14);
            foreach ($labelLines as $labelLine) {
                imagettftext($image, $this->pt(14), 0, $tableStartX + $cellPad, $labelTextY, $black, $this->font(true), $labelLine);
                $labelTextY += $labelLineH;
            }

            // Value cell text (block vertically centered within the cell)
            $valueBlockH = count($valueLines) * $valueLineH;
            $valueTextY = $y + (int) round(($cellHeight - $valueBlockH) / 2) + $this->pt(16);
            foreach ($valueLines as $valueLine) {
                imagettftext($image, $this->pt(16), 0, $tableStartX + $labelWidth + $cellPad, $valueTextY, $black, $this->font(false), $valueLine);
                $valueTextY += $valueLineH;
            }

            $this->drawTableBorders($image, $tableStartX, $y, $tableWidth, $cellHeight, $labelWidth, $borderW);

            $y += $cellHeight;
        }
    }

    private function drawTableBorders(GdImage $image, int $x, int $y, int $tableWidth, int $height, int $labelWidth, int $borderW): void
    {
        $black = imagecolorallocate($image, 0, 0, 0);

        // Row outline
        imagefilledrectangle($image, $x, $y, $x + $tableWidth, $y + $borderW, $black);            // top
        imagefilledrectangle($image, $x, $y + $height - $borderW, $x + $tableWidth, $y + $height, $black); // bottom
        imagefilledrectangle($image, $x, $y, $x + $borderW, $y + $height, $black);                // left
        imagefilledrectangle($image, $x + $tableWidth - $borderW, $y, $x + $tableWidth, $y + $height, $black); // right

        // Label/value separator
        imagefilledrectangle($image, $x + $labelWidth - $borderW, $y, $x + $labelWidth + $borderW, $y + $height, $black);
    }

    private function tableRows(SourcingOrder $order, ?SourcingRequestDestination $destination): array
    {
        $address = trim(sprintf(
            '%s: %s',
            $destination?->service?->name ?? 'Service',
            $destination?->label_address ?: ($destination?->address ?? 'N/A')
        ));

        return [
            ['label' => 'Country', 'value' => $destination?->country?->name ?? 'N/A'],
            ['label' => 'Seller Name', 'value' => $order->label_seller_name ?: $order->user?->name],
            ['label' => 'Order ID', 'value' => $order->reference_id],
            ['label' => 'Product Name', 'value' => $order->label_product_name ?: ($order->quotation->sourcingRequest->product_name ?? 'N/A')],
            ['label' => 'Quantity', 'value' => $destination?->quantity ?? 'N/A'],
            ['label' => 'Recipient Address', 'value' => $address],
        ];
    }

    private function drawFooter(GdImage $image, int $canvasWidth, int $canvasHeight): void
    {
        $black = imagecolorallocate($image, 0, 0, 0);
        $whatsapp = imagecolorallocate($image, 37, 211, 102);

        $title = 'For support or questions';
        $contact = 'Contact Us  +212 646-522071';

        $titleFontSize = $this->pt(14);
        $contactFontSize = $this->pt(18);

        $centerX = (int) round($canvasWidth / 2);
        $titleWidth = $this->textWidth($this->font(true), $titleFontSize, $title);
        $contactWidth = $this->textWidth($this->font(bold: true), $contactFontSize, $contact);

        $y = $canvasHeight - $this->px(120);

        imagettftext($image, $titleFontSize, 0, $centerX - (int) round($titleWidth / 2), $y, $black, $this->font(true), $title);
        imagettftext($image, $contactFontSize, 0, $centerX - (int) round($contactWidth / 2), $y + $this->pt(30), $whatsapp, $this->font(true), $contact);
    }

    /**
     * Wrap text on word boundaries so it fits a given pixel width.
     *
     * @return string[]
     */
    private function wrapText(string $text, string $font, int $size, int $maxWidth): array
    {
        $text = trim((string) $text);
        if ($text === '' || $text === 'N/A') {
            return [$text];
        }

        $lines = [];
        $line = '';

        foreach (preg_split('/\s+/', $text) as $word) {
            $probe = $line === '' ? $word : $line.' '.$word;

            if ($this->textWidth($font, $size, $probe) <= $maxWidth) {
                $line = $probe;

                continue;
            }

            if ($line !== '') {
                $lines[] = $line;
                $line = $word;

                while ($this->textWidth($font, $size, mb_substr($line, 0, -1)) > $maxWidth) {
                    $cut = mb_substr($line, 0, -1);
                    if ($cut === '') {
                        break;
                    }
                    $lines[] = $cut;
                    $line = mb_substr($line, mb_strlen($cut));
                }
            } else {
                // Single word wider than the cell: hard-break by characters.
                while ($this->textWidth($font, $size, $word) > $maxWidth && mb_strlen($word) > 1) {
                    $cut = mb_substr($word, 0, -1);
                    if ($this->textWidth($font, $size, $cut) > $maxWidth) {
                        $lines[] = $cut;
                        $word = mb_substr($word, mb_strlen($cut));
                    } else {
                        break;
                    }
                }
                $line = $word;
            }
        }

        if ($line !== '') {
            $lines[] = $line;
        }

        return $lines ?: [''];
    }

    private function textWidth(string $font, int $size, string $text): int
    {
        $box = imagettfbbox($size, 0, $font, $text);
        if ($box === false) {
            return 0;
        }

        return (int) max($box[2], $box[4]) - (int) min($box[0], $box[6]);
    }
}
