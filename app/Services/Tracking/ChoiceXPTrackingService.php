<?php

namespace App\Services\Tracking;

class ChoiceXPTrackingService extends AbstractSeleniumTrackingService
{
    protected $translationService;

    public function __construct()
    {
        parent::__construct();
        $this->scriptPath = base_path('scraper/choicexp_tracker.py');
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
     * Parse the script output (ChoiceXP-specific).
     *
     * @param  mixed  $decoded
     */
    protected function parseScriptOutput($decoded): array
    {
        // ChoiceXP script returns the result directly (not in an array)
        $data = $decoded;

        // Translate events
        if (isset($data['events']) && is_array($data['events'])) {
            foreach ($data['events'] as &$event) {
                // Translate Status
                $rawStatus = $event['status'] ?? '';
                if ($rawStatus) {
                    $event['status_en'] = $this->translationService->translate($this->getProviderName(), $rawStatus, 'en');
                    $event['status_fr'] = $this->translationService->translate($this->getProviderName(), $rawStatus, 'fr');
                }
                
                // Translate Location
                $rawLocation = $event['location'] ?? '';
                if ($rawLocation) {
                    $event['location'] = $this->translationService->translate($this->getProviderName(), $rawLocation, 'fr');
                }
            }
        }

        // Populate/Translate Current Status from latest event
        $latestEvent = $data['events'][0] ?? null;
        if ($latestEvent) {
            $data['current_status'] = $latestEvent['status_fr'] ?? $latestEvent['status'] ?? 'Inconnu';
        } elseif (!isset($data['current_status'])) {
            $data['current_status'] = 'Aucun événement';
        }
        
        return $data;
    }

    /**
     * Get the provider name for logging.
     */
    protected function getProviderName(): string
    {
        return 'ChoiceXP';
    }
}
