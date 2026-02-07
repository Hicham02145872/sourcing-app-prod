<?php

namespace App\Services\Tracking;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FasterTrackingService implements TrackingServiceInterface
{
    public function getTrackingInfo(string $trackingNumber): array
    {
        $startTime = microtime(true);
        try {
            Log::info("🚀 [FASTER] Starting API request", [
                'tracking_number' => $trackingNumber,
                'url' => 'https://op-api.faster.ae/service/status-logs/listWithBooking'
            ]);

            $response = Http::timeout(20)
                ->withOptions([
                    'verify' => config('tracking.verify_ssl', true)
                ])
                ->get('https://op-api.faster.ae/service/status-logs/listWithBooking', [
                    'bookingNos' => $trackingNumber,
                    'isOpen' => 1,
                ]);

            $elapsedTime = round((microtime(true) - $startTime) * 1000, 2);

            if ($response->successful()) {
                $responseData = $response->json();

                Log::info("✅ [FASTER] API request successful", [
                    'tracking_number' => $trackingNumber,
                    'elapsed_ms' => $elapsedTime,
                    'status_code' => $response->status(),
                    'response_code' => $responseData['code'] ?? 'N/A'
                ]);

                if (isset($responseData['code']) && $responseData['code'] === 0 && ! empty($responseData['data']) && isset($responseData['data'][0]['statusLogs'])) {
                    $logs = $responseData['data'][0]['statusLogs'];

                    $events = array_map(function ($log) {
                        return [
                            'date' => $log['statusDate'] ?? $log['createdAt'] ?? date('Y-m-d H:i:s'),
                            'status' => $log['status'] ?? $log['statusName'] ?? $log['statusDetails'] ?? 'Unknown',
                            'location' => $log['location'] ?? $log['statusLocation'] ?? '',
                        ];
                    }, $logs);

                    Log::info("📊 [FASTER] Data parsed successfully", [
                        'tracking_number' => $trackingNumber,
                        'events_count' => count($events),
                        'latest_status' => $events[0]['status'] ?? 'N/A'
                    ]);

                    return [
                        'success' => true,
                        'tracking_number' => $trackingNumber,
                        'current_status' => $events[0]['status'] ?? 'Unknown',
                        'events' => $events,
                        'provider' => 'Faster',
                        'time_ms' => $elapsedTime
                    ];
                }

                Log::warning("⚠️ [FASTER] API returned no data", [
                    'tracking_number' => $trackingNumber,
                    'response' => $responseData
                ]);

                return [
                    'success' => false,
                    'error' => 'No tracking details found.',
                    'provider' => 'Faster',
                    'time_ms' => $elapsedTime
                ];
            }

            Log::error("❌ [FASTER] API request failed", [
                'tracking_number' => $trackingNumber,
                'status_code' => $response->status(),
                'error' => $response->body(),
                'elapsed_ms' => $elapsedTime
            ]);

            return [
                'success' => false,
                'error' => 'Unable to fetch tracking information from Faster.ae.',
                'provider' => 'Faster',
                'time_ms' => $elapsedTime
            ];

        } catch (\Exception $e) {
            $elapsedTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::error('❌ [FASTER] Exception: ' . $e->getMessage(), [
                'tracking_number' => $trackingNumber,
                'elapsed_ms' => $elapsedTime,
                'trace' => substr($e->getTraceAsString(), 0, 500)
            ]);

            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
                'provider' => 'Faster',
                'time_ms' => $elapsedTime
            ];
        }
    }
}
