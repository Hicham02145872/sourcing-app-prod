<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    public function __construct(
        protected \App\Services\SeventeenTrackService $seventeenTrackService,
        protected \App\Services\Tracking\UnifiedTrackingService $unifiedTrackingService,
        protected \App\Services\OrderStatus\AutoUpdateOrderStatusFromTracking $autoUpdateOrderStatus
    ) {}

    /**
     * Show the tracking page.
     */
    public function index(Request $request): View
    {
        // Simple view return, no API call here.
        // If query params exist (e.g. from redirect), we can pass them to view to auto-trigger JS.
        $initialNumber = $request->query('number', '');

        return view('client.tracking.index', compact('initialNumber'));
    }

    /**
     * Show the 17TRACK tracking page.
     */
    public function seventeenTrackIndex(Request $request): View
    {
        $initialNumber = $request->query('number', '');

        return view('client.tracking.17track', compact('initialNumber'));
    }

    /**
     * AJAX Endpoint to fetch 17TRACK data.
     */
    public function seventeenTrackData(Request $request): JsonResponse
    {
        $trackingNumber = $request->query('number');

        if (! $trackingNumber) {
            return response()->json(['error' => __('Please provide a tracking number.')], 400);
        }

        $result = $this->seventeenTrackService->getTrackInfo($trackingNumber);

        if (isset($result['code']) && $result['code'] === 0) {
            return response()->json($result);
        }

        return response()->json([
            'error' => $result['error'] ?? __('Unable to fetch tracking information from 17TRACK.'),
        ], 500);
    }

    /**
     * AJAX Endpoint to fetch tracking data using unified service.
     */
    public function data(Request $request): JsonResponse
    {
        \Log::debug('!!! [TRACKING DEBUG] DATA METHOD ENTERED !!!', ['number' => $request->query('number')]);
        set_time_limit(120);
        $startTime = microtime(true);
        $trackingNumber = $request->query('number');

        \Log::info('🔍 [TRACKING CONTROLLER] Request received', [
            'tracking_number' => $trackingNumber,
            'timestamp' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'method' => $request->method(),
            'full_url' => $request->fullUrl()
        ]);

        if (! $trackingNumber) {
            \Log::warning('⚠️ [TRACKING CONTROLLER] Missing tracking number');
            return response()->json(['error' => __('Please provide a tracking number.')], 400);
        }

        try {
            \Log::info('⏱️ [TRACKING CONTROLLER] Calling UnifiedTrackingService', [
                'tracking_number' => $trackingNumber,
                'elapsed_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ]);

            $result = $this->unifiedTrackingService->track($trackingNumber);

            $elapsedTime = round((microtime(true) - $startTime) * 1000, 2);

            if ($result['success']) {
                $provider = $result['provider'] ?? null;

                \Log::info('✅ [TRACKING CONTROLLER] Request completed successfully', [
                    'tracking_number' => $trackingNumber,
                    'provider' => $provider,
                    'events_count' => count($result['events'] ?? []),
                    'total_time_ms' => $elapsedTime,
                    'cached' => $elapsedTime < 100
                ]);

                // Mettre à jour le statut de la commande côté client quand on a du tracking réel (cron ou cache)
                if (empty($result['is_virtual']) && str_starts_with(strtoupper(trim($trackingNumber)), 'FSB')) {
                    $orderId = (int) substr(trim($trackingNumber), 3);
                    $order = \App\Models\SourcingOrder::find($orderId);
                    if ($order && $order->hasRealTracking()) {
                        $this->autoUpdateOrderStatus->updateOrderStatusFromTrackingResult($order, $result);
                    }
                }

                // If not in result, we can detect it again or pass it from unified service
                return response()->json([
                    'data' => $result['events'] ?? [],
                    'current_status' => $result['status_text'] ?? $result['current_status'] ?? ($result['events'][0]['status_fr'] ?? 'Update Success'),
                    'tracking_number' => $result['tracking_number'] ?? $trackingNumber,
                    'provider' => $provider,
                    'order_status' => $result['order_status'] ?? null,
                    'is_virtual' => $result['is_virtual'] ?? false,
                    'virtual_message' => $result['message'] ?? null,
                ]);
            }

            // Handle Pending State
            if (($result['status'] ?? '') === 'pending') {
                 return response()->json([
                    'data' => [],
                    'current_status' => 'Pending Update',
                    'tracking_number' => $trackingNumber,
                    'provider' => $result['provider'] ?? null,
                    'message' => $result['error'], 
                    'pending' => true
                 ], 202); 
            }

            \Log::warning('⚠️ [TRACKING CONTROLLER] Tracking failed', [
                'tracking_number' => $trackingNumber,
                'error' => $result['error'] ?? 'No tracking details found',
                'total_time_ms' => $elapsedTime
            ]);

            return response()->json(['error' => $result['error'] ?? __('No tracking details found.')], 404);
        } catch (\Exception $e) {
            $elapsedTime = round((microtime(true) - $startTime) * 1000, 2);

            \Log::error('❌ [TRACKING CONTROLLER] Exception occurred', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'total_time_ms' => $elapsedTime
            ]);

            return response()->json(['error' => __('An error occurred while connecting to the tracking service.')], 500);
        }
    }
}
