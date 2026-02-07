<?php

namespace App\Services;

use App\Services\Tracking\TrackingServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SeventeenTrackService implements TrackingServiceInterface
{
    protected string $apiKey;

    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.17track.api_key');
        $this->baseUrl = config('services.17track.base_url');
    }

    /**
     * Register a tracking number.
     */
    /**
     * Detect carrier for a tracking number.
     */
    public function detectCarrier(string $number): array
    {
        try {
            $response = Http::withHeaders([
                '17token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/getcarrier", [
                [
                    'number' => $number,
                ],
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('17TRACK DetectCarrier Exception: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Register a tracking number.
     */
    public function register(string $number, ?int $carrier = null): array
    {
        try {
            $url = "{$this->baseUrl}/register";
            Log::info("Attempting to register tracking number: {$number} at {$url}".($carrier ? " with carrier: {$carrier}" : ''));

            $payload = [
                'number' => $number,
            ];

            if ($carrier) {
                $payload['carrier'] = $carrier;
            }

            $response = Http::withHeaders([
                '17token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                $payload,
            ]);

            $body = $response->body();

            return $response->json();
        } catch (\Exception $e) {
            Log::error('17TRACK Register Exception: '.$e->getMessage());

            return ['code' => 500, 'error' => $e->getMessage()];
        }
    }

    /**
     * Fetch tracking information for a given number.
     */
    public function getTrackInfo(string $number, bool $retry = true): array
    {
        try {
            Log::info("Fetching track info for: {$number}");

            $response = Http::withHeaders([
                '17token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/gettrackinfo", [
                [
                    'number' => $number,
                ],
            ]);

            $body = $response->body();
            if ($response->successful()) {
                $data = $response->json();

                // Check if it's rejected
                if (isset($data['data']['rejected'][0]['error']['code'])) {
                    $errorCode = $data['data']['rejected'][0]['error']['code'];

                    // -18019902: Not registered
                    if ($errorCode == -18019902 && $retry) {
                        Log::info("Number not registered, trying registration for: {$number}");
                        $regResult = $this->register($number);

                        // If registration failed due to carrier detection
                        if (isset($regResult['data']['rejected'][0]['error']['code']) && $regResult['data']['rejected'][0]['error']['code'] == -18019903) {
                            Log::info("Carrier detection failed for {$number}. Trying manual detection...");
                            $detectResult = $this->detectCarrier($number);

                            $carrierKey = null;
                            $upperNumber = strtoupper($number);

                            // Try to find if 17track detected our preferred carriers
                            if (isset($detectResult['data']['accepted'][0]['carriers'])) {
                                foreach ($detectResult['data']['accepted'][0]['carriers'] as $c) {
                                    if ($c['key'] == 191259 || $c['key'] == 100074) {
                                        $carrierKey = $c['key'];
                                        break;
                                    }
                                }
                                // If not preferred but detected, take the first one
                                if (! $carrierKey && ! empty($detectResult['data']['accepted'][0]['carriers'])) {
                                    $carrierKey = $detectResult['data']['accepted'][0]['carriers'][0]['key'];
                                }
                            }

                            // Hard fallback based on known patterns if still null
                            if (! $carrierKey) {
                                if (str_starts_with($upperNumber, 'ME')) {
                                    $carrierKey = 191259; // GCC56
                                } elseif (str_starts_with($upperNumber, 'JT')) {
                                    $carrierKey = 100074; // J&T Express (ID)
                                }
                            }

                            if ($carrierKey) {
                                Log::info("Attempting registration with carrier key: {$carrierKey} for {$number}");
                                $regResult = $this->register($number, $carrierKey);
                            }
                        }

                        // Check if registration was accepted
                        if (isset($regResult['code']) && $regResult['code'] === 0 && ! empty($regResult['data']['accepted'])) {
                            Log::info("Registration successful for: {$number}. Retrying gettrackinfo...");
                            // Wait a bit and retry
                            sleep(2);

                            return $this->getTrackInfo($number, false);
                        } else {
                            Log::warning("Registration failed or was not accepted for: {$number}", $regResult);
                        }
                    }
                }

                return $data;
            }

            Log::error('17TRACK API Error: '.$body);

            return [
                'code' => $response->status(),
                'data' => null,
                'error' => 'API Request failed',
            ];
        } catch (Exception $e) {
            Log::error('17TRACK Exception: '.$e->getMessage());

            return [
                'code' => 500,
                'data' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get tracking info for a specific number (normalized for UnifiedTrackingService).
     */
    public function getTrackingInfo(string $trackingNumber): array
    {
        $result = $this->getTrackInfo($trackingNumber);

        if (isset($result['code']) && $result['code'] === 0 && ! empty($result['data']['accepted'])) {
            $data = $result['data']['accepted'][0];
            $track = $data['track'] ?? null;

            if ($track) {
                $events = collect($track['z2'] ?? [])->map(function ($event) {
                    return [
                        'status' => $event['z'] ?? 'Status Update',
                        'location' => $event['c'] ?? '',
                        'statusDate' => $event['a'] ?? '',
                        'details' => $event['z'] ?? '',
                    ];
                })->toArray();

                return [
                    'success' => true,
                    'current_status' => $track['z0'] ?? 'In Transit',
                    'events' => $events,
                    'provider' => '17Track',
                ];
            }
        }

        return [
            'success' => false,
            'error' => $result['error'] ?? 'No tracking details found from 17Track.',
        ];
    }
}
