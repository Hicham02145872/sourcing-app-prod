<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\SourcingRequest;
use App\Notifications\QuotationCreated;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;

class QuotationController extends Controller
{
    public function index(): View
    {
        $quotations = Quotation::with('sourcingRequest.user')->get();
        return view('admin.quotations.index', compact('quotations'));
    }

    public function create(): View
    {
        $sourcingRequests = SourcingRequest::where('status', 'in_review')->get();
        return view('admin.quotations.create', compact('sourcingRequests'));
    }

    public function store(Request $request, Messaging $messaging): RedirectResponse
    {
        $validated = $request->validate([
            'sourcing_request_id' => 'required|exists:sourcing_requests,id',
            'unit_price' => 'required|numeric',
            'commission_service' => 'required|numeric',
            'unit_weight' => 'required|numeric',
            'delivery_cost_china' => 'required|numeric',
            'currency' => 'required|string|max:3',
        ]);

        $amount = $validated['unit_price'] + $validated['commission_service'] + $validated['delivery_cost_china'];

        $quotation = Quotation::create([
            'sourcing_request_id' => $validated['sourcing_request_id'],
            'amount' => $amount,
            'unit_price' => $validated['unit_price'],
            'commission_service' => $validated['commission_service'],
            'unit_weight' => $validated['unit_weight'],
            'delivery_cost_china' => $validated['delivery_cost_china'],
            'currency' => $validated['currency'],
            'status' => 'pending', // Default status
        ]);

        $sourcingRequest = SourcingRequest::find($validated['sourcing_request_id']);
        $notification = new QuotationCreated($quotation);

        // Notify the user via mail and database
        $sourcingRequest->user->notify($notification);

        // Manually send the FCM notification
        if ($sourcingRequest->user->fcm_token) {
            try {
                $fcmMessage = $notification->toFcm($sourcingRequest->user);
                if ($fcmMessage) {
                    $messaging->send($fcmMessage);
                }
            } catch (\Exception $e) {
                // Log the error but don't block the user
                Log::error('FCM notification failed to send: '.$e->getMessage());
            }
        }

        // Invalidate the cache for the user's notifications
        Cache::forget("user_notifications_{$sourcingRequest->user->id}");

        return redirect()->route('admin.dashboard')->with('status', 'Quotation created successfully!');
    }
}
