<?php

namespace App\Services\Tracking;

class UPSTrackingService extends AbstractSeleniumTrackingService
{
    protected $translationService;

    public function __construct()
    {
        parent::__construct();
        $this->scriptPath = base_path('scraper/ups_tracker.py');
        $this->translationService = new \App\Services\TrackingTranslationService();
    }

    /**
     * Get tracking info for a specific number.
     */
    public function getTrackingInfo(string $trackingNumber): array
    {
        return $this->runScript($trackingNumber);
    }

    /**
     * Parse the script output (UPS-specific).
     * Normalizes to unified format: success, tracking_number, events, current_status, current_status_fr.
     *
     * @param  mixed  $decoded
     */
    protected function parseScriptOutput($decoded): array
    {
        $data = is_array($decoded) ? $decoded : [];

        if (isset($data['error']) && $data['error'] && empty($data['success'])) {
            return [
                'success' => false,
                'tracking_number' => $data['tracking_number'] ?? '',
                'error' => $data['error'],
            ];
        }

        if (empty($data['success']) || empty($data['events']) && empty($data['current_status'])) {
            return [
                'success' => false,
                'tracking_number' => $data['tracking_number'] ?? '',
                'error' => $data['error'] ?? 'No tracking data returned.',
            ];
        }

        $events = $data['events'] ?? [];
        foreach ($events as &$event) {
            $rawStatus = $event['status'] ?? '';
            $event['status_en'] = $this->translationService->translate($this->getProviderName(), $rawStatus, 'en') ?: $rawStatus;
            $event['status_fr'] = $this->translationService->translate($this->getProviderName(), $rawStatus, 'fr') ?: $rawStatus;
            $event['location'] = $event['location'] ?? '';
        }
        unset($event);

        $latestEvent = $events[0] ?? null;
        $currentStatusEn = $latestEvent['status_en'] ?? $data['current_status'] ?? 'Unknown';
        $currentStatusFr = $latestEvent['status_fr'] ?? $data['current_status'] ?? 'Inconnu';

        return [
            'success' => true,
            'tracking_number' => $data['tracking_number'] ?? '',
            'current_status' => $currentStatusEn,
            'current_status_fr' => $currentStatusFr,
            'events' => $events,
            'provider' => 'UPS',
        ];
    }

    protected function getProviderName(): string
    {
        return 'UPS';
    }
}
