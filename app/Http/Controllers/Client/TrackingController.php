<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class TrackingController extends Controller
{
    public function __construct(protected \App\Services\SeventeenTrackService $seventeenTrackService) {}

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
     * AJAX Endpoint to fetch tracking data.
     */
    public function data(Request $request): JsonResponse
    {
        $trackingNumber = $request->query('number');

        if (! $trackingNumber) {
            return response()->json(['error' => __('Please provide a tracking number.')], 400);
        }

        try {
            // API Request to Faster.ae
            $response = Http::timeout(10)->get('https://op-api.faster.ae/service/status-logs/listWithBooking', [
                'bookingNos' => $trackingNumber,
                'isOpen' => 1,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                // Check if the API returned success code and has data
                if (isset($responseData['code']) && $responseData['code'] === 0 && ! empty($responseData['data']) && isset($responseData['data'][0]['statusLogs'])) {

                    $logs = $responseData['data'][0]['statusLogs'];

                    // Sort logs by date descending (latest first) if not already
                    usort($logs, function ($a, $b) {
                        return strtotime($b['statusDate']) - strtotime($a['statusDate']);
                    });

                    return response()->json(['data' => $logs]);
                }

                return response()->json(['error' => __('No tracking details found.')], 404);
            } else {
                return response()->json(['error' => __('Unable to fetch tracking information.')], 502);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Tracking API Error: '.$e->getMessage());

            return response()->json(['error' => __('An error occurred while connecting to the tracking service.')], 500);
        }
    }
}
