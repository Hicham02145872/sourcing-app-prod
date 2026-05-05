<?php

namespace App\Services;

use App\Models\SourcingOrder;
use App\Models\SourcingRequestDestination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShippingLabelImageService
{
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
        try {
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

            Storage::disk('public')->makeDirectory('shipping-labels');
            Storage::disk('public')->put($filename, $png);

            return asset('storage/'.$filename);
        } catch (\Exception $e) {
            Log::error('ShippingLabelImageService: Failed to generate image', [
                'order_id' => $order->id,
                'destination_id' => $destination?->id,
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }
}
