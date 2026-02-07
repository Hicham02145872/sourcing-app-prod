<?php

namespace App\Services\Tracking;

class ItdidaTrackingService extends AbstractSeleniumTrackingService
{
    public function __construct()
    {
        parent::__construct();
        $this->scriptPath = base_path('scraper/itdida_tracker.py');
    }

    /**
     * Get tracking info for a specific number.
     */
    public function getTrackingInfo(string $trackingNumber): array
    {
        return $this->runScript($trackingNumber);
    }

    /**
     * Parse the script output (Itdida-specific).
     *
     * @param  mixed  $decoded
     */
    protected function parseScriptOutput($decoded): array
    {
        // The python script returns an array of results, even for a single number.
        // We need to get the first element.
        $data = $decoded[0] ?? null;

        if (! $data) {
            return [
                'success' => false,
                'error' => 'Réponse JSON vide ou invalide du script.',
            ];
        }

        return $data;
    }

    /**
     * Get the provider name for logging.
     */
    protected function getProviderName(): string
    {
        return 'ITDIDA';
    }
}
