<?php

namespace App\Services;

use App\Models\SourcingOrder;
use App\Models\SourcingRequestDestination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ShippingLabelImageService
{
    /**
     * Generate a PNG image of the shipping label and return its public URL.
     * If the image already exists, return the cached URL.
     */
    public static function getImageUrl(SourcingOrder $order, ?SourcingRequestDestination $destination = null): string
    {
        $filename = $destination
            ? "shipping-labels/label-{$order->id}-dest-{$destination->id}.png"
            : "shipping-labels/label-{$order->id}.png";

        if (Storage::disk('public')->exists($filename)) {
            return asset('storage/'.$filename);
        }

        return self::generateImage($order, $destination, $filename);
    }

    /**
     * Force regenerate the image (useful when order data changes).
     */
    public static function regenerate(SourcingOrder $order, ?SourcingRequestDestination $destination = null): string
    {
        $filename = $destination
            ? "shipping-labels/label-{$order->id}-dest-{$destination->id}.png"
            : "shipping-labels/label-{$order->id}.png";

        Storage::disk('public')->delete($filename);

        return self::generateImage($order, $destination, $filename);
    }

    protected static function generateImage(SourcingOrder $order, ?SourcingRequestDestination $destination, string $filename): string
    {
        $order->load(['user', 'quotation.sourcingRequest.destinations.country', 'quotation.sourcingRequest.destinations.service']);

        if ($destination) {
            $destination->load(['country', 'service']);
            $view = 'admin.sourcing-orders.shipping-label-destination';
            $data = ['sourcingOrder' => $order, 'destination' => $destination];
        } else {
            $view = 'admin.sourcing-orders.shipping-label';
            $data = ['sourcingOrder' => $order];
        }

        $pdf = Pdf::loadView($view, $data);
        $pdf->setPaper('a4', 'portrait');
        $pdfContent = $pdf->output();

        $pngContent = self::pdfToPng($pdfContent);

        Storage::disk('public')->makeDirectory('shipping-labels');
        Storage::disk('public')->put($filename, $pngContent);

        return asset('storage/'.$filename);
    }

    protected static function pdfToPng(string $pdfContent): string
    {
        if (extension_loaded('imagick')) {
            $imagick = new \Imagick();
            $imagick->setResolution(150, 150);
            $imagick->readImageBlob($pdfContent);
            $imagick->setImageFormat('png');
            $imagick->setImageBackgroundColor('white');
            $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
            $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);
            $png = $imagick->getImageBlob();
            $imagick->clear();
            $imagick->destroy();

            return $png;
        }

        return self::htmlToPngFallback($pdfContent);
    }

    /**
     * Fallback: create a simple placeholder PNG with order info using GD.
     */
    protected static function htmlToPngFallback(string $pdfContent): string
    {
        $width = 600;
        $height = 400;
        $img = imagecreatetruecolor($width, $height);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $blue = imagecolorallocate($img, 0, 102, 204);
        $gray = imagecolorallocate($img, 128, 128, 128);
        $lightGray = imagecolorallocate($img, 240, 240, 240);

        imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $white);
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $black);

        imagefilledrectangle($img, 0, 0, $width - 1, 50, $blue);
        imagestring($img, 5, 200, 17, 'SHIPPING LABEL', $white);

        imagestring($img, 4, 20, 70, 'FastSourcingBrothers', $black);
        imagestring($img, 3, 20, 100, 'Click to download the full PDF label', $gray);

        imagestring($img, 3, 20, 350, 'Generated: '.date('Y-m-d H:i'), $gray);

        imagerectangle($img, 10, 55, $width - 10, $height - 10, $lightGray);

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return $png;
    }
}
