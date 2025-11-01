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
use App\Notifications\ProofOfPaymentRejected;
use Illuminate\Support\Facades\Log;

class SourcingOrderController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', SourcingOrder::class);
        $query = SourcingOrder::with('user', 'quotation.sourcingRequest');

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by search term
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('quotation.sourcingRequest', function ($srQuery) use ($search) {
                      $srQuery->where('product_name', 'like', '%' . $search . '%');
                  });
            });
        }

        $sourcingOrders = $query->paginate(10);

        return view('admin.sourcing-orders.index', compact('sourcingOrders'));
    }

    public function show(SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);
        $sourcingOrder->load('user', 'quotation.sourcingRequest.category', 'quotation.sourcingRequest.destinations.country', 'quotation.sourcingRequest.destinations.service');
        return view('admin.sourcing-orders.show', compact('sourcingOrder'));
    }

    public function downloadProofOfPayment(SourcingOrder $sourcingOrder)
    {
        $this->authorize('view', $sourcingOrder);
        if (!$sourcingOrder->proof_of_payment_path) {
            abort(404);
        }

        $path = Storage::disk('local')->path($sourcingOrder->proof_of_payment_path);
        Log::debug('Downloading proof of payment', ['path' => $path]);

        return Storage::disk('local')->download($sourcingOrder->proof_of_payment_path);
    }

    public function updateStatus(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('update', $sourcingOrder);
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(SourcingOrder::STATUSES)],
        ]);

        if (!$sourcingOrder->canTransitionTo($validated['status'])) {
            Log::warning('Invalid status transition attempt', [
                'order_id' => $sourcingOrder->id,
                'current_status' => $sourcingOrder->status,
                'requested_status' => $validated['status'],
                'can_transition' => $sourcingOrder->canTransitionTo($validated['status']),
            ]);
            return back()->withErrors(['status' => 'Invalid status transition from ' . $sourcingOrder->status . ' to ' . $validated['status'] . '.']);
        }

        $sourcingOrder->update(['status' => $validated['status']]);

        // Eager-load relationships required by the notification
        $sourcingOrder->load('quotation.sourcingRequest', 'user');

        Log::debug("Dispatching SourcingOrderStatusUpdated for SourcingOrder #{$sourcingOrder->id} to user {$sourcingOrder->user->id}", ['new_status' => $sourcingOrder->status]);
        $sourcingOrder->user->notify(new SourcingOrderStatusUpdated($sourcingOrder));

        return back()->with('status', 'Sourcing order status updated successfully!');
    }

    public function rejectProof(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('update', $sourcingOrder);
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        // Delete the old proof of payment file
        if ($sourcingOrder->proof_of_payment_path) {
            Storage::disk('local')->delete($sourcingOrder->proof_of_payment_path);
        }

        $sourcingOrder->update([
            'status' => 'pending_payment',
            'proof_of_payment_path' => null,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $sourcingOrder->load('user'); // Eager load the user relationship
        $sourcingOrder->user->notify(new ProofOfPaymentRejected($sourcingOrder));

        return back()->with('status', 'Proof of payment rejected and client has been notified.');
    }
}
