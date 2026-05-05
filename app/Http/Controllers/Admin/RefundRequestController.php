<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RefundRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = RefundRequest::with(['sourcingOrder', 'user', 'assignedAdmin'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'under_review' THEN 2 WHEN 'approved' THEN 3 WHEN 'rejected' THEN 4 ELSE 5 END")
            ->latest();

        // Security Filter: Standard admins only see their assigned refunds
        if (! auth()->user()->isSuperAdmin()) {
            $query->where(function ($q) {
                $q->where('assigned_to_admin_id', auth()->id())
                    ->orWhereNull('assigned_to_admin_id');
            });
        }

        // Apply Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('admin_id')) {
            $query->where('assigned_to_admin_id', $request->admin_id);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount_requested', '>=', $request->min_amount);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Stats for the header (unfiltered or filtered? Usually unfiltered for total context, but let's stick to base query)
        $statsBaseQuery = RefundRequest::query();
        if (! auth()->user()->isSuperAdmin()) {
            $statsBaseQuery->where(function ($q) {
                $q->where('assigned_to_admin_id', auth()->id())
                    ->orWhereNull('assigned_to_admin_id');
            });
        }

        $stats = [
            'pending' => (clone $statsBaseQuery)->where('status', 'pending')->count(),
            'under_review' => (clone $statsBaseQuery)->where('status', 'under_review')->count(),
            'approved' => (clone $statsBaseQuery)->where('status', 'approved')->count(),
            'total_refunded' => (clone $statsBaseQuery)->where('status', 'approved')->sum('amount_approved'),
        ];

        $requests = $query->paginate(20)->withQueryString();

        $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();

        return view('admin.refund-requests.index', compact('requests', 'stats', 'admins'));
    }

    public function show(RefundRequest $refundRequest): View
    {
        $this->authorize('view', $refundRequest);

        $refundRequest->load(['sourcingOrder.quotation.sourcingRequest', 'user', 'assignedAdmin']);

        return view('admin.refund-requests.show', compact('refundRequest'));
    }

    public function updateStatus(Request $request, RefundRequest $refundRequest): RedirectResponse
    {
        $this->authorize('update', $refundRequest);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'amount_approved' => 'required_if:status,approved|nullable|numeric|min:0',
            'admin_notes' => 'nullable|string',
            'refund_proof' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,pdf|max:10240', // 10MB
        ]);

        $status = $validated['status'];
        $amountApproved = $validated['amount_approved'] ?? null;

        if ($status === 'approved') {
            $sourcingOrder = $refundRequest->sourcingOrder;
            $alreadyApprovedSum = $sourcingOrder->refundRequests()
                ->where('status', 'approved')
                ->where('id', '!=', $refundRequest->id)
                ->sum('amount_approved');

            if (($alreadyApprovedSum + $amountApproved) > $sourcingOrder->total_amount) {
                return back()->with('error', __('The total approved refund amount cannot exceed the order total (Max allowed: '.($sourcingOrder->total_amount - $alreadyApprovedSum).').'));
            }
        }

        $updateData = [
            'status' => ($status === 'approved') ? 'approved' : 'rejected',
            'amount_approved' => $amountApproved,
            'admin_notes' => $validated['admin_notes'],
        ];

        if ($request->hasFile('refund_proof')) {
            $updateData['refund_proof_path'] = $request->file('refund_proof')->store('refund-proofs', 'public');
        }

        try {
            $refundRequest->update($updateData);

            $sourcingOrder = $refundRequest->sourcingOrder;

            if ($status === 'approved') {
                // Cumulative refund: order.refund_amount = sum of all approved refund requests for this order
                $totalApproved = $sourcingOrder->refundRequests()
                    ->where('status', 'approved')
                    ->sum('amount_approved');

                $sourcingOrder->update([
                    'refund_amount' => $totalApproved,
                    'refund_proof_path' => $refundRequest->refund_proof_path,
                    'status' => ($totalApproved >= $sourcingOrder->total_amount) ? 'refunded' : 'refund_approved',
                ]);
            } else {
                $sourcingOrder->update(['status' => 'refund_rejected']);
            }
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('Could not process the refund request.'));
        }

        return redirect()->route('admin.refund-requests.index')
            ->with('success', __('Refund request processed successfully.'));
    }

    public function assignToMe(RefundRequest $refundRequest): RedirectResponse
    {
        // Check if already assigned to another admin
        if ($refundRequest->assigned_to_admin_id && $refundRequest->assigned_to_admin_id !== auth()->id()) {
            return back()->with('error', __('This request is already assigned to an admin.'));
        }

        // Assign and set status to under_review so it stays assigned to this admin while in review
        $refundRequest->update([
            'assigned_to_admin_id' => auth()->id(),
            'status' => 'under_review',
        ]);

        return back()->with('success', __('Refund request assigned to you and marked as under review.'));
    }
}
