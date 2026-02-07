<?php

namespace App\Services\Tracking;

use App\Jobs\RunSeleniumTrackingJob;
use App\Models\SourcingOrder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UnifiedTrackingService
{
    public function __construct(
        protected ItdidaTrackingService $itdidaService,
        protected FasterTrackingService $fasterService,
        protected ChoiceXPTrackingService $choiceXPService,
        protected \App\Services\SeventeenTrackService $seventeenTrackService
    ) {}

    public function track(string $trackingNumber, ?string $carrier = null): array
    {
        $startTime = microtime(true);
        $cacheKey = "tracking:{$trackingNumber}";

        Log::info('🔄 [UNIFIED SERVICE] Track method called', [
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
            'cache_key' => $cacheKey,
        ]);

        // 1. Check Cache
        $cached = Cache::get($cacheKey);
        if ($cached) {
            Log::info('⚡ [UNIFIED SERVICE] Cache HIT - returning cached data', [
                'tracking_number' => $trackingNumber,
                'elapsed_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ]);
            return $cached;
        }

        Log::info('🔍 [UNIFIED SERVICE] Cache MISS', ['tracking_number' => $trackingNumber]);

        // 2. Resolve Alias first to detect the real provider
        [$realNumber, $realCarrier, $isAlias, $error] = $this->resolveAlias($trackingNumber, $carrier);

        if ($error) {
            return $error;
        }

        // 3. Detect Provider
        $provider = $this->detectProvider($realNumber, $realCarrier);
        Log::info('📡 [UNIFIED SERVICE] Provider detected', ['provider' => $provider]);

        // 4. Check if we should queue (Selenium providers)
        // These providers use browser automation which is resource intensive
        $seleniumProviders = ['itdida', 'faster', 'choicexp', 'gcc'];

        if (in_array(strtolower($provider), $seleniumProviders)) {
            Log::info('⏳ [UNIFIED SERVICE] Detected Selenium provider - dispatching job', ['provider' => $provider]);

            // Dispatch Job to run in background (limited to 1 concurrent instance)
            RunSeleniumTrackingJob::dispatch($trackingNumber, $carrier);

            return [
                'success' => false,
                'status' => 'pending',
                'error' => 'Tracking update in progress. Please refresh in a few minutes.',
                'provider' => ucfirst($provider),
                'tracking_number' => $trackingNumber,
            ];
        }

        // 5. If API based (default/17track), run synchronously
        return $this->refreshTracking($trackingNumber, $carrier);
    }

    /**
     * Run the tracking logic immediately and cache the result.
     * This is public so it can be called by the Job.
     */
    public function refreshTracking(string $trackingNumber, ?string $carrier = null): array
    {
        Log::info('🚀 [UNIFIED SERVICE] refreshTracking called', ['number' => $trackingNumber]);
        
        $fetchStartTime = microtime(true);

        // Perform the actual tracking logic
        $result = $this->performTracking($trackingNumber, $carrier);

        $fetchTime = round((microtime(true) - $fetchStartTime) * 1000, 2);

        Log::info('✅ [UNIFIED SERVICE] Tracking completed', [
            'tracking_number' => $trackingNumber,
            'success' => $result['success'] ?? false,
            'provider' => $result['provider'] ?? 'unknown',
            'fetch_time_ms' => $fetchTime,
        ]);

        // Only cache if successful
        if ($result['success'] ?? false) {
            $cacheTtl = config('tracking.cache_ttl', 30);
            $cacheKey = "tracking:{$trackingNumber}";
            Cache::put($cacheKey, $result, now()->addMinutes($cacheTtl));
            Log::info('💾 [UNIFIED SERVICE] Result successfully cached', [
                'tracking_number' => $trackingNumber,
                'ttl_minutes' => $cacheTtl
            ]);
        } else {
            Log::warning('⚠️ [UNIFIED SERVICE] Result NOT cached due to failure', [
                'tracking_number' => $trackingNumber,
                'error' => $result['error'] ?? 'Unknown error'
            ]);
        }

        return $result;
    }

    /**
     * Core tracking logic (formerly trackDirect)
     */
    protected function performTracking(string $trackingNumber, ?string $carrier = null): array
    {
        $displayNumber = $trackingNumber;

        // Resolve Alias
        [$realNumber, $realCarrier, $isAlias, $error] = $this->resolveAlias($trackingNumber, $carrier);

        if ($error) {
            return $error;
        }

        if ($isAlias) {
             Log::info('🔗 [UNIFIED SERVICE] FSB Alias resolved', [
                'original_number' => $trackingNumber,
                'real_number' => $realNumber,
                'carrier' => $realCarrier
            ]);
        }

        $provider = $this->detectProvider($realNumber, $realCarrier);
        
        $service = $this->getService($provider);

        if (! $service) {
            Log::error('❌ [UNIFIED SERVICE] Unsupported carrier', ['provider' => $provider]);
            return [
                'success' => false,
                'error' => "Unknown or unsupported carrier: {$provider}",
            ];
        }

        Log::info('🛰️ [UNIFIED SERVICE] Calling provider service', ['class' => get_class($service)]);
        $response = $service->getTrackingInfo($realNumber);
        
        // Map provider names for branding (e.g. Faster -> FSB)
        $providerDisplayMap = [
            'faster' => 'FSB',
            'itdida' => 'FSB',
            'choicexp' => 'FSB',
            'gcc' => 'FSB',
        ];

        $response['provider'] = $providerDisplayMap[strtolower($provider)] ?? ucfirst($provider);
        
        // Mask the original tracking number if we used an alias (FSB number)
        if ($isAlias) {
            $response['tracking_number'] = $displayNumber;
        }

        return $response;
    }

    protected function resolveAlias(string $trackingNumber, ?string $carrier): array
    {
        // Default returns
        // [RealNumber, RealCarrier, IsAlias, ErrorArray]
        
        if (str_starts_with(strtoupper($trackingNumber), 'FSB')) {
            $id = (int) substr($trackingNumber, 3);
            
            $order = SourcingOrder::find($id);

            if (! $order) {
                Log::warning('❌ [UNIFIED SERVICE] Order not found for FSB alias', ['id' => $id]);
                return [
                    $trackingNumber, 
                    $carrier, 
                    true, 
                    [
                        'success' => false,
                        'error' => "Internal tracking number {$trackingNumber} not found.",
                    ]
                ];
            }

            if (! $order->tracking_number) {
                 Log::warning('⚠️ [UNIFIED SERVICE] Order has no tracking number', ['id' => $id]);
                 return [
                    $trackingNumber,
                    $carrier,
                    true,
                    [
                        'success' => false,
                        'error' => "No carrier tracking assigned to {$trackingNumber}.",
                    ]
                ];
            }

            return [
                $order->tracking_number,
                $order->tracking_carrier ?: ($order->shippingCompany?->name ?? null),
                true,
                null
            ];
        }

        // Not an alias
        return [$trackingNumber, $carrier, false, null];
    }

    protected function detectProvider(string $trackingNumber, ?string $carrier = null): string
    {
        if ($carrier) {
            $carrierLower = strtolower($carrier);
            if (str_contains($carrierLower, 'itdida') || str_contains($carrierLower, 'ydl')) {
                return 'itdida';
            }
            if (str_contains($carrierLower, 'choice')) {
                return 'choicexp';
            }
            if (str_contains($carrierLower, 'faster') || str_contains($carrierLower, 'gcc')) {
                return 'faster';
            }
        }

        // Auto-detection by number pattern
        $number = strtoupper($trackingNumber);

        if (str_starts_with($number, 'DBC')) {
            return 'choicexp';
        }
        if (str_starts_with($number, 'ME')) {
            return 'faster';
        }

        // Default to a lookup or generic
        return $carrier ?: 'unknown';
    }

    protected function getService(string $provider): ?TrackingServiceInterface
    {
        return match (strtolower($provider)) {
            'itdida' => $this->itdidaService,
            'faster', 'gcc' => $this->fasterService,
            'choicexp' => $this->choiceXPService,
            default => $this->seventeenTrackService, // Use 17Track as fallback
        };
    }
}
