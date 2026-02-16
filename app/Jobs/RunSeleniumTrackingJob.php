<?php

namespace App\Jobs;

use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class RunSeleniumTrackingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes given Selenium can be slow

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $trackingNumber,
        public ?string $carrier = null,
        public ?string $provider = null
    ) {}

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        // Limit to 1 concurrent job to avoid overloading the VPS
        // We use a fixed key so ALL selenium jobs share this lock
        return [(new WithoutOverlapping('selenium-tracker-global'))->releaseAfter(120)];
    }

    /**
     * Execute the job.
     */
    public function handle(UnifiedTrackingService $trackingService): void
    {
        $start = microtime(true);

        // Incrémenter les compteurs de jobs en cours
        $provider = $this->provider ?: 'unknown';
        \Illuminate\Support\Facades\Cache::increment('selenium_current:global');
        \Illuminate\Support\Facades\Cache::increment("selenium_current:{$provider}");

        Log::info('Job:RunSeleniumTracking starting', [
            'number' => $this->trackingNumber,
            'carrier' => $this->carrier,
            'provider' => $provider,
        ]);

        try {
            $trackingService->refreshTracking($this->trackingNumber, $this->carrier);
        } finally {
            $elapsed = round((microtime(true) - $start) * 1000, 2);

            // Décrémenter les compteurs (sans descendre en-dessous de 0)
            $globalKey = 'selenium_current:global';
            $providerKey = "selenium_current:{$provider}";
            $g = max(0, (int) \Illuminate\Support\Facades\Cache::decrement($globalKey));
            $p = max(0, (int) \Illuminate\Support\Facades\Cache::decrement($providerKey));
            \Illuminate\Support\Facades\Cache::put($globalKey, $g, now()->addMinutes(10));
            \Illuminate\Support\Facades\Cache::put($providerKey, $p, now()->addMinutes(10));

            Log::info('Job:RunSeleniumTracking finished', [
                'number' => $this->trackingNumber,
                'carrier' => $this->carrier,
                'provider' => $provider,
                'time_ms' => $elapsed,
                'concurrent_global_after' => $g,
                'concurrent_provider_after' => $p,
            ]);
        }
    }
}
