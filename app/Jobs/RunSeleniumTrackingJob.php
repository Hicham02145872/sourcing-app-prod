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
        public ?string $carrier = null
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
        Log::info('Job:RunSeleniumTracking starting', ['number' => $this->trackingNumber]);
        
        $trackingService->refreshTracking($this->trackingNumber, $this->carrier);
        
        Log::info('Job:RunSeleniumTracking finished', ['number' => $this->trackingNumber]);
    }
}
