<?php

namespace App\Services\Tracking;

use App\Jobs\RunSeleniumTrackingJob;
use App\Models\SourcingOrder;
use App\Services\Tracking\VirtualTrackingStatusService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UnifiedTrackingService
{
    public function __construct(
        protected ItdidaTrackingService $itdidaService,
        protected FasterTrackingService $fasterService,
        protected ChoiceXPTrackingService $choiceXPService,
        protected UPSTrackingService $upsService,
        protected VirtualTrackingStatusService $virtualTrackingService
    ) {}

    public function track(string $trackingNumber, ?string $carrier = null): array
    {
        $trackingNumber = trim($trackingNumber);
        $startTime = microtime(true);
        $cacheKey = "tracking:{$trackingNumber}";

        Log::info('🔄 [UNIFIED SERVICE] Track method called', [
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
            'cache_key' => $cacheKey,
        ]);

        // 0. Circuit breaker: numéro temporairement bloqué ?
        $blockKey = "tracking_blocked:{$trackingNumber}";
        if (Cache::has($blockKey)) {
            $blockedUntil = Cache::get($blockKey);
            Log::warning('⛔ [UNIFIED SERVICE] Tracking blocked by circuit breaker', [
                'tracking_number' => $trackingNumber,
                'blocked_until' => $blockedUntil,
            ]);

            return [
                'success' => false,
                'error' => __('Tracking temporarily unavailable for this number. Please try again later.'),
                'blocked_until' => $blockedUntil,
            ];
        }

        // 1. Check Cache
        $cached = Cache::get($cacheKey);
        if ($cached) {
            Log::info('⚡ [UNIFIED SERVICE] Cache HIT - returning cached data', [
                'tracking_number' => $trackingNumber,
                'elapsed_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ]);
            
            // Log the cache hit to history
            $this->logSearch($trackingNumber, $cached);

            // Marquer explicitement la source comme "cache"
            $cached['source'] = 'cache';

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
        $seleniumProviders = ['itdida', 'faster', 'choicexp', 'gcc', 'ups'];

        if (in_array(strtolower($provider), $seleniumProviders)) {
            // Contrôle de la concurrence globale & par provider
            $globalKey = "selenium_current:global";
            $providerKey = "selenium_current:{$provider}";
            $currentGlobal = (int) Cache::get($globalKey, 0);
            $currentProvider = (int) Cache::get($providerKey, 0);
            $maxGlobal = (int) config('tracking.selenium_max_concurrent_global', 1);
            $maxPerProvider = (int) config('tracking.selenium_max_concurrent_per_provider', 1);

            if ($currentGlobal >= $maxGlobal || $currentProvider >= $maxPerProvider) {
                Log::warning('🚦 [UNIFIED SERVICE] Selenium concurrency limit reached', [
                    'tracking_number' => $trackingNumber,
                    'provider' => $provider,
                    'current_global' => $currentGlobal,
                    'current_provider' => $currentProvider,
                    'max_global' => $maxGlobal,
                    'max_per_provider' => $maxPerProvider,
                ]);

                return [
                    'success' => false,
                    'status' => 'pending',
                    'error' => __('Tracking system is currently busy. Please try again in a few minutes.'),
                    'provider' => ucfirst($provider),
                    'tracking_number' => $trackingNumber,
                ];
            }

            // Check if a job is already pending for this number
            $pendingKey = "tracking_pending:{$trackingNumber}";
            if (Cache::has($pendingKey)) {
                Log::info('⏳ [UNIFIED SERVICE] Job already pending, skipping dispatch', ['tracking_number' => $trackingNumber]);
                return [
                    'success' => false,
                    'status' => 'pending',
                    'error' => 'Tracking update in progress. Please refresh in a few minutes.',
                    'provider' => ucfirst($provider),
                    'tracking_number' => $trackingNumber,
                ];
            }

            Log::info('⏳ [UNIFIED SERVICE] Detected Selenium provider - dispatching job', ['provider' => $provider]);
            
            // Set pending lock for 2 minutes
            Cache::put($pendingKey, true, now()->addMinutes(2));

            // Dispatch Job to run in background
            RunSeleniumTrackingJob::dispatch($trackingNumber, $carrier, $provider);

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
        $trackingNumber = trim($trackingNumber);
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

        // Ajouter métadonnées de fraîcheur
        $result['last_updated_at'] = now()->toIso8601String();
        $result['source'] = 'live';

        // Attempt to find matching order to attach internal status
        $order = null;
        if (str_starts_with(strtoupper($trackingNumber), 'FSB')) {
            $id = (int) substr($trackingNumber, 3);
            $order = SourcingOrder::find($id);
        } else {
            $order = SourcingOrder::where('tracking_number', $trackingNumber)->first();
        }

        if ($order) {
            $result['order_status'] = $order->status;
            $result['order_id'] = $order->id;
        }

        // Circuit breaker: comptabiliser échecs / succès
        $cbConfig = config('tracking.circuit_breaker', []);
        $failureThreshold = (int) ($cbConfig['failure_threshold'] ?? 3);
        $cooldownMinutes = (int) ($cbConfig['cooldown_minutes'] ?? 30);
        $failureKey = "tracking_failures:{$trackingNumber}";
        $blockKey = "tracking_blocked:{$trackingNumber}";

        if ($result['success'] ?? false) {
            // Reset des échecs si succès
            Cache::forget($failureKey);
            Cache::forget($blockKey);
        } else {
            $failures = (int) Cache::increment($failureKey);
            Cache::put($failureKey, $failures, now()->addMinutes($cooldownMinutes));

            if ($failures >= $failureThreshold && ! Cache::has($blockKey)) {
                $blockedUntil = now()->addMinutes($cooldownMinutes);
                Cache::put($blockKey, $blockedUntil->toIso8601String(), $blockedUntil);
                Log::warning('⛔ [UNIFIED SERVICE] Circuit breaker activated', [
                    'tracking_number' => $trackingNumber,
                    'failures' => $failures,
                    'blocked_until' => $blockedUntil->toIso8601String(),
                ]);
            }
        }

        // Only cache if successful
        if ($result['success'] ?? false) {
            // Choisir un TTL en fonction du statut
            $baseTtl = (int) config('tracking.cache_ttl', 30);
            $status = strtolower((string) ($result['current_status'] ?? ''));
            $ttlByStatus = config('tracking.cache_ttl_by_status', []);

            $statusKey = null;
            if (str_contains($status, 'livré') || str_contains($status, 'delivered')) {
                $statusKey = 'delivered';
            } elseif (str_contains($status, 'transit') || str_contains($status, 'en transit') || str_contains($status, 'shipped')) {
                $statusKey = 'in_transit';
            } elseif (str_contains($status, 'pending')) {
                $statusKey = 'pending';
            } elseif (str_contains($status, 'error') || str_contains($status, 'erreur') || str_contains($status, 'not found')) {
                $statusKey = 'error';
            }

            $cacheTtl = $baseTtl;
            if ($statusKey && isset($ttlByStatus[$statusKey])) {
                $cacheTtl = (int) $ttlByStatus[$statusKey];
            }

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

        // Clear pending lock
        Cache::forget("tracking_pending:{$trackingNumber}");

        // Log the search result to the database
        $this->logSearch($trackingNumber, $result);

        return $result;
    }

    /**
     * Log the tracking search result to the database.
     */
    protected function logSearch(string $trackingNumber, array $result): void
    {
        try {
            $status = $result['current_status'] ?? ($result['success'] ? 'Success' : 'Failed');
            $provider = $result['provider'] ?? 'Unknown';
            
            // Extract location from the latest event if available
            $location = '';
            if (!empty($result['events']) && is_array($result['events'])) {
                $latestEvent = $result['events'][0] ?? null;
                $location = $latestEvent['location'] ?? '';
            }

            \App\Models\TrackingLog::create([
                'tracking_number' => $trackingNumber,
                'provider' => $provider,
                'status' => $status,
                'location' => $location,
                'payload' => $result,
                'user_id' => auth()->id(), // Log the user if authenticated
                'ip_address' => request()->ip(),
            ]);

        } catch (\Exception $e) {
            Log::error('❌ [UNIFIED SERVICE] Failed to log tracking search', [
                'error' => $e->getMessage(),
                'tracking_number' => $trackingNumber
            ]);
        }
    }

    /**
     * Core tracking logic (formerly trackDirect)
     */
    protected function performTracking(string $trackingNumber, ?string $carrier = null): array
    {
        $trackingNumber = trim($trackingNumber);
        $displayNumber = $trackingNumber;

        // Resolve Alias
        [$realNumber, $realCarrier, $isAlias, $error] = $this->resolveAlias($trackingNumber, $carrier);

        // If error is a virtual tracking response (success => true), return it directly
        if ($error && isset($error['success']) && $error['success'] === true) {
            return $error;
        }

        // If error is a real error (success => false), return it
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
            'ups' => 'UPS',
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
                        'error' => __("Order reference :number was not found in our records.", ['number' => $trackingNumber]),
                    ]
                ];
            }

            // Check if we should use virtual tracking status
            if ($this->virtualTrackingService->shouldUseVirtualStatus($order)) {
                Log::info('🎭 [UNIFIED SERVICE] Using virtual tracking status for FSB alias', [
                    'fsb_number' => $trackingNumber,
                    'order_id' => $id,
                    'virtual_status' => $this->virtualTrackingService->getVirtualStatus($order)
                ]);
                
                return [
                    $trackingNumber,
                    null,
                    true,
                    $this->virtualTrackingService->getVirtualTrackingResponse($order)
                ];
            }

            // If no real tracking number assigned yet, return error
            if (! $order->tracking_number) {
                Log::warning('⚠️ [UNIFIED SERVICE] Order has no tracking number', ['id' => $id]);
                return [
                    $trackingNumber,
                    $carrier,
                    true,
                    [
                        'success' => false,
                        'error' => __("Tracking information has not yet been assigned to order :number.", ['number' => $trackingNumber]),
                    ]
                ];
            }

            // Real tracking number is available, use it
            Log::info('✅ [UNIFIED SERVICE] Using real tracking number for FSB alias', [
                'fsb_number' => $trackingNumber,
                'real_number' => $order->tracking_number,
                'order_id' => $id
            ]);

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
            if (str_contains($carrierLower, 'ups')) {
                return 'ups';
            }
        }

        // Auto-detection by number pattern
        $number = strtoupper($trackingNumber);

        if (str_starts_with($number, '1Z')) {
            return 'ups';
        }
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
            'ups' => $this->upsService,
            default => null,
        };
    }
}
