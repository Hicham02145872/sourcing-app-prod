<?php

namespace App\Services\Tracking;

class ChoiceXPTrackingService extends AbstractSeleniumTrackingService
{
    public function __construct()
    {
        parent::__construct();
        $this->scriptPath = base_path('scraper/choicexp_tracker.py');
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
        return $decoded;
    }

    /**
     * Get the provider name for logging.
     */
    protected function getProviderName(): string
    {
        return 'ChoiceXP';
    }
}
