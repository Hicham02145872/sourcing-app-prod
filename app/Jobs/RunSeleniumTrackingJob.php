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
     * Must be LESS than the Horizon supervisor's --timeout (200s for selenium-supervisor),
     * otherwise the worker SIGKILLs the process and refreshTracking() never finishes,
     * leaving nothing in cache and triggering an infinite dispatch loop.
     *
     * @var int
     */
    public $timeout = 180;

    /**
     * Allow up to 3 attempts before marking as permanently failed.
     * Without this, the default (usually 1) means a single Chrome timeout kills the job
     * and never gives WithoutOverlapping a chance to release and retry.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $trackingNumber,
        public ?string $carrier = null,
        public ?string $provider = null
    ) {
        // Route to the dedicated selenium queue (handled by selenium-supervisor in horizon.php).
        // Without this, jobs land on "default" which has a 60s timeout — too short for Chrome boot.
        $this->onQueue('selenium');
    }

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

    /**
     * Called by Laravel when all $tries are exhausted.
     * Without this, the tracking_pending lock is never cleared, causing the next
     * request to skip the "already pending" check and dispatch a new job immediately,
     * re-entering the failure loop indefinitely.
     */
    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Cache::forget("tracking_pending:{$this->trackingNumber}");

        $errorTtl = (int) config('tracking.cache_ttl_by_status.error', 5);
        \Illuminate\Support\Facades\Cache::put("tracking:{$this->trackingNumber}", [
            'success' => false,
            'error' => 'Tracking service temporarily unavailable. Please try again later.',
            'source' => 'error',
            'last_updated_at' => now()->toIso8601String(),
        ], now()->addMinutes($errorTtl));

        Log::error('Job:RunSeleniumTracking permanently failed', [
            'number' => $this->trackingNumber,
            'exception' => $exception->getMessage(),
        ]);
    }
}
