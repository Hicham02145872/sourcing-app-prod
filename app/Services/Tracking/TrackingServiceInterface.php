<?php

namespace App\Services\Tracking;

interface TrackingServiceInterface
{
    /**
     * Get tracking info for a specific number.
     */
    public function getTrackingInfo(string $trackingNumber): array;
}
