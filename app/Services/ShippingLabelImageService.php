<?php

namespace App\Services;

use App\Models\SourcingOrder;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;

class ShippingLabelImageService
{
    /**
     * Generate a JPG shipping label for the given order.
     * Returns the public URL of the generated image.
     */
    public function generateLabelImage(SourcingOrder $order): string
    {
        $directory = 'shipping-labels';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = "label-{$order->id}.jpg";
        $path = "{$directory}/{$filename}";

        // If file exists and is recent (last 24h), reuse it to save resources
        if (Storage::disk('public')->exists($path) && Storage::disk('public')->lastModified($path) > (time() - 86400)) {
            return asset('storage/' . $path);
        }

        // 1. Create a blank canvas (A4 ratio-ish but optimized for screen/sheet)
        // Let's go with 800x1200 for a clear label
        $canvas = Image::create(800, 1100)->fill('#ffffff');

        // 2. Load and add logo
        $logoPath = public_path('images/logo.png');
        if (File::exists($logoPath)) {
            $logo = Image::read($logoPath)->scale(width: 400);
            $canvas->place($logo, 'top-center', 0, 40);
        }

        // 3. Draw Header Separator
        $canvas->drawRectangle(40, 180, function ($draw) {
            $draw->background('#000000');
        })->resize(720, 4);

        // 4. Draw Content (Text)
        $dest = $order->quotation->sourcingRequest->destinations->first();
        $country = $dest->country->name ?? 'N/A';
        $product = $order->quotation->sourcingRequest->product_name ?? 'N/A';
        $quantity = $order->quotation->sourcingRequest->destinations->sum('quantity');
        
        $y = 240;
        $lineHeight = 60;

        // Helper to draw label: value rows
        $this->drawInfoRow($canvas, "COUNTRY", $country, $y);
        $y += $lineHeight + 20;
        
        $this->drawInfoRow($canvas, "ORDER ID", "#" . $order->display_id, $y);
        $y += $lineHeight + 20;

        $this->drawInfoRow($canvas, "PRODUCT", $product, $y);
        $y += $lineHeight + 20;

        $this->drawInfoRow($canvas, "QUANTITY", (string)$quantity, $y);
        $y += $lineHeight + 20;

        // Address Section
        $canvas->text("RECIPIENT ADDRESS:", 60, $y, function($font) {
            $font->size(24);
            $font->color('#666666');
            $font->weight('bold');
        });
        $y += 40;

        $addressLines = [
            $order->user->name,
            $order->user->phone ?? '',
            $order->quotation->sourcingRequest->address ?? 'N/A'
        ];

        foreach ($addressLines as $line) {
            if (empty($line)) continue;
            // Simple word wrap for long addresses
            $wrapped = wordwrap($line, 45, "\n");
            $subLines = explode("\n", $wrapped);
            foreach ($subLines as $sl) {
                $canvas->text($sl, 60, $y, function($font) {
                    $font->size(28);
                    $font->color('#000000');
                    $font->weight('bold');
                });
                $y += 45;
            }
        }

        // 5. Build Final JPG
        $encoded = $canvas->toJpeg(90);
        Storage::disk('public')->put($path, (string)$encoded);

        return asset('storage/' . $path);
    }

    protected function drawInfoRow($canvas, $label, $value, $y)
    {
        // Label
        $canvas->text($label . ":", 60, $y, function($font) {
            $font->size(24);
            $font->color('#666666');
            $font->weight('bold');
        });

        // Value
        $canvas->text($value, 240, $y, function($font) {
            $font->size(32);
            $font->color('#000000');
            $font->weight('bold');
        });

        // Underline
        $canvas->drawRectangle(60, $y + 15, function($draw) {
            $draw->background('#eeeeee');
        })->resize(680, 2);
    }
}
