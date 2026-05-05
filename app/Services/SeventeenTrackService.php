<?php

namespace App\Services;

use App\Services\Tracking\TrackingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SeventeenTrackService implements TrackingServiceInterface
{
    protected string $apiKey;

    protected string $baseUrl;

    protected $translationService;

    public function __construct(\App\Services\TrackingTranslationService $service)
    {
        $this->apiKey = config('services.17track.api_key', '');
        $this->baseUrl = config('services.17track.base_url', 'https://api.17track.net/track/v2.2');
        $this->translationService = $service;
    }

    // ... (existing methods detectCarrier, register, getTrackInfo remain unchanged)

    /**
     * Get tracking info for a specific number (normalized for UnifiedTrackingService).
     */
    public function getTrackingInfo(string $trackingNumber): array
    {
        $data = $this->getTrackInfo($trackingNumber);

        if (isset($data['code']) && $data['code'] === 0 && ! empty($data['data']['accepted'])) {
            $accepted = $data['data']['accepted'][0];
            $track = $accepted['track'] ?? null;

            if ($track) {
                $events = [];
                $rawEvents = $track['z2'] ?? [];
                
                foreach ($rawEvents as $event) {
                    $rawStatus = $event['z'] ?? 'Status Update';
                    $rawLocation = $event['c'] ?? '';

                    $events[] = [
                        'status' => $rawStatus,
                        'status_en' => $this->translationService->translate('17Track', $rawStatus, 'en'),
                        'status_fr' => $this->translationService->translate('17Track', $rawStatus, 'fr'),
                        'location' => $this->translationService->translate('17Track', $rawLocation, 'fr'),
                        'date' => $event['a'] ?? '', // 'a' is date
                        'details' => $rawStatus,
                    ];
                }

                $currentStatus = $track['z0']['z'] ?? 'In Transit'; // z0 is latest event context, z is content

                return [
                    'success' => true,
                    'current_status' => $this->translationService->translate('17Track', $currentStatus, 'fr'),
                    'current_status_raw' => $currentStatus,
                    'events' => $events,
                    'provider' => '17Track',
                ];
            }
        }

        return [
            'success' => false,
            'error' => $data['error'] ?? 'No tracking details found from 17Track.',
        ];
    }
}
