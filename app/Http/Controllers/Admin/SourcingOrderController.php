<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use App\Notifications\SourcingOrderStatusUpdated;
use Illuminate\Support\Facades\Log;

class SourcingOrderController extends Controller
{
    public function index(): View
    {
        $sourcingOrders = SourcingOrder::with('user', 'quotation.sourcingRequest')->get();
        return view('admin.sourcing-orders.index', compact('sourcingOrders'));
    }

    public function show(SourcingOrder $sourcingOrder): View
    {
        $sourcingOrder->load('user', 'quotation.sourcingRequest.category', 'quotation.sourcingRequest.destinations.country', 'quotation.sourcingRequest.destinations.service');
        return view('admin.sourcing-orders.show', compact('sourcingOrder'));
    }

    public function downloadProofOfPayment(SourcingOrder $sourcingOrder)
    {
        if (!$sourcingOrder->proof_of_payment_path) {
            abort(404);
        }

        $path = Storage::disk('public')->path($sourcingOrder->proof_of_payment_path);
        Log::debug('Downloading proof of payment', ['path' => $path]);

        return Storage::disk('public')->download($sourcingOrder->proof_of_payment_path);
    }

    public function updateStatus(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(SourcingOrder::STATUSES)],
        ]);

        $sourcingOrder->update(['status' => $validated['status']]);

        // Eager-load relationships required by the notification
        $sourcingOrder->load('quotation.sourcingRequest');

        Log::debug("Dispatching SourcingOrderStatusUpdated for SourcingOrder #{$sourcingOrder->id} to user {$sourcingOrder->user->id}", ['new_status' => $sourcingOrder->status]);
        $sourcingOrder->user->notify(new SourcingOrderStatusUpdated($sourcingOrder));

        return back()->with('status', 'Sourcing order status updated successfully!');
    }
}
