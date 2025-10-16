<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminSourcingRequestController extends Controller
{
    /**
     * Display a listing of all sourcing requests.
     */
    public function index(): View
    {
        $sourcingRequests = SourcingRequest::with('category', 'user', 'destinations.country', 'destinations.service')->get();
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
     * Update the status of the specified sourcing request.
     */
    public function updateStatus(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,handling,completed,cancelled',
        ]);

        $sourcingRequest->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.sourcing-requests.show', $sourcingRequest)->with('status', 'Sourcing request status updated successfully!');
    }
}
