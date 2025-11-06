<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Notifications\SourcingRequestStatusUpdated;

class AdminSourcingRequestController extends Controller
{
    /**
     * Display a listing of all sourcing requests.
     */
    public function index(Request $request): View
    {
        $query = SourcingRequest::with('category', 'user', 'destinations.country', 'destinations.service');

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by search term
        if ($request->has('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        $sourcingRequests = $query->latest()->paginate(10);

        return view('admin.sourcing-requests.index', compact('sourcingRequests'));
    }

    /**
     * Display the specified sourcing request.
     */
    public function show(SourcingRequest $sourcingRequest): View
    {
        $sourcingRequest->load('category', 'user', 'destinations.country', 'destinations.service');
        return view('admin.sourcing-requests.show', compact('sourcingRequest'));
    }

    /**
     * Update the status of the specified sourcing request and send FCM notification (API v1).
     */
   public function updateStatus(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
{
    $validated = $request->validate([
        'status' => 'required|in:' . implode(',', \App\Models\SourcingRequest::STATUSES),
    ]);

    try {
        $sourcingRequest->transitionTo($validated['status']);
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['generic' => $e->getMessage()]);
    }

    return redirect()
        ->route('admin.sourcing-requests.show', $sourcingRequest)
        ->with('status', 'Sourcing request status updated successfully!');
}

}
