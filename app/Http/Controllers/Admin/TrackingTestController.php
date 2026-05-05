<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Tracking\ItdidaTrackingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingTestController extends Controller
{
    public function __construct(
        protected ItdidaTrackingService $trackingService
    ) {}

    public function index(): View
    {
        return view('admin.tracking.test');
    }

    public function test(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|min:5',
        ]);

        $trackingNumber = $request->input('tracking_number');

        // Timeout handling can be done here or in the service.
        // PHP default execution time might need extending for selenium
        set_time_limit(60);

        $result = $this->trackingService->getTrackingInfo($trackingNumber);

        return redirect()->route('admin.tracking.test')->with('result', $result)->withInput();
    }
}
