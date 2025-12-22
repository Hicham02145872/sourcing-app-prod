<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundRequestController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    public function store(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('requestRefund', $sourcingOrder);

        $validated = $request->validate([
            'type' => 'required|in:full,partial',
            'reason_category' => 'required|string|max:255',
            'reason_description' => 'required|string|min:10',
            'amount_requested' => 'required_if:type,partial|nullable|numeric|min:0.01',
            'evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,mkv,webm|max:51200',
        ]);

        // Enforcement: Cannot refund if pending payment
        if ($sourcingOrder->status === 'pending_payment') {
            return back()->with('error', __('Refunds cannot be requested for orders pending payment.'));
        }

        // Logicment: Refund is usually after delivery or specific stages (user: "if the order is delivered")
        $allowedStatuses = ['paid', 'delivered', 'order_completed', 'refund_approved', 'refund_rejected', 'in_transit_uae', 'out_for_delivery'];
        if (! in_array($sourcingOrder->status, $allowedStatuses)) {
            return back()->with('error', __('Refund requests are only available after delivery or during final transit stages.'));
        }

        $totalAlreadyRequested = $sourcingOrder->refundRequests()->whereIn('status', ['pending', 'approved'])->sum('amount_requested');
        $requestedAmount = $validated['type'] === 'full' ? $sourcingOrder->total_amount : $validated['amount_requested'];

        if (($totalAlreadyRequested + $requestedAmount) > $sourcingOrder->total_amount) {
            return back()->with('error', __('The total requested refund amount cannot exceed the order total.'));
        }

        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $this->imageService->compressAndStore(
                    $file,
                    'refund-evidence'
                );
                $evidencePaths[] = $path;
            }
        }

        $amount = $validated['type'] === 'full'
            ? $sourcingOrder->total_amount
            : $validated['amount_requested'];

        $autoApproveLimit = config('refunds.auto_approve_limit', 0);
        $shouldAutoApprove = $autoApproveLimit > 0 && $amount <= $autoApproveLimit;

        $refundRequest = RefundRequest::create([
            'sourcing_order_id' => $sourcingOrder->id,
            'user_id' => auth()->id(),
            'assigned_to_admin_id' => $sourcingOrder->assigned_to_admin_id,
            'type' => $validated['type'],
            'status' => $shouldAutoApprove ? 'approved' : 'pending',
            'amount_requested' => $amount,
            'amount_approved' => $shouldAutoApprove ? $amount : null,
            'reason_category' => $validated['reason_category'],
            'reason_description' => $validated['reason_description'],
            'evidence_paths' => $evidencePaths,
            'admin_notes' => $shouldAutoApprove ? __('Automatically approved (under $:limit)', ['limit' => $autoApproveLimit]) : null,
        ]);

        if ($shouldAutoApprove) {
            $sourcingOrder->update([
                'refund_amount' => $amount,
                'status' => ($amount >= $sourcingOrder->total_amount) ? 'refunded' : 'refund_approved',
            ]);

            \Illuminate\Support\Facades\Log::info('Refund request auto-approved', [
                'refund_request_id' => $refundRequest->id,
                'amount' => $amount,
            ]);
        } else {
            $sourcingOrder->update(['status' => 'waiting_for_refund']);
        }

        // Notify only the assigned admin and all super admins
        $assignedAdminId = $refundRequest->assigned_to_admin_id;
        $admins = \App\Models\User::where('role', 'super_admin')
            ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
                $query->orWhere(function ($q) use ($assignedAdminId) {
                    $q->where('role', 'admin')
                        ->where('id', $assignedAdminId);
                });
            })
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\RefundRequestCreated($refundRequest));
        }

        return back()->with('success', __('Refund request submitted successfully and is being reviewed.'));
    }

    public function show(RefundRequest $refundRequest): View
    {
        $this->authorize('view', $refundRequest);

        $refundRequest->load(['sourcingOrder.quotation']);

        return view('client.refund-requests.show', compact('refundRequest'));
    }
}
