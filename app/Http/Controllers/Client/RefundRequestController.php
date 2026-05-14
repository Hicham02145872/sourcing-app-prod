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

    public function index(string $locale): View
    {
        $deliveredOrders = SourcingOrder::where('user_id', auth()->id())
            ->where('status', 'delivered')
            ->with(['quotation.sourcingRequest'])
            ->latest()
            ->paginate(5, ['*'], 'orders_page');

        $refundRequests = RefundRequest::where('user_id', auth()->id())
            ->with(['sourcingOrder.quotation.sourcingRequest'])
            ->latest()
            ->paginate(10, ['*'], 'refunds_page');

        // Stats for the dashboard-like header
        $stats = [
            'eligible_count' => SourcingOrder::where('user_id', auth()->id())->where('status', 'delivered')->count(),
            'pending_count' => RefundRequest::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'approved_count' => RefundRequest::where('user_id', auth()->id())->where('status', 'approved')->count(),
            'total_refunded' => RefundRequest::where('user_id', auth()->id())->where('status', 'approved')->sum('amount_approved'),
            'currency' => $deliveredOrders->first()?->quotation->currency ?? 'USD',
        ];

        return view('client.refund-requests.index', compact('deliveredOrders', 'refundRequests', 'stats'));
    }

    public function create(string $locale, SourcingOrder $sourcingOrder): View
    {
        $this->authorize('requestRefund', $sourcingOrder);

        // Enforcement: Cannot refund if not delivered (as per new requirements)
        if ($sourcingOrder->status !== 'delivered') {
            abort(403, __('Refund requests are only available for delivered orders.'));
        }

        $totalAlreadyRequested = $sourcingOrder->refundRequests()->whereIn('status', ['pending', 'approved'])->sum('amount_requested');
        $remainingAmount = max(0, $sourcingOrder->total_amount - $totalAlreadyRequested);

        return view('client.refund-requests.create', compact('sourcingOrder', 'remainingAmount'));
    }

    public function store(Request $request, string $locale, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('requestRefund', $sourcingOrder);

        $validated = $request->validate([
            'type' => 'required|in:full,partial',
            'damaged_quantity' => 'required|integer|min:1',
            'reason_category' => 'required|string|max:255',
            'reason_description' => 'required|string|min:10',
            'amount_requested' => 'required_if:type,partial|nullable|numeric|min:0.01',
            'evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,mkv,webm|max:51200',
        ]);

        try {
            $refundRequest = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $sourcingOrder, $validated) {
                // Lock the order to prevent race conditions and ensure the latest state
                $sourcingOrder = SourcingOrder::where('id', $sourcingOrder->id)->lockForUpdate()->first();

                // Strict Enforcement: Refunds are only available for delivered orders
                if ($sourcingOrder->status !== 'delivered') {
                     throw new \Exception(__('Refund requests are only available for delivered orders.'));
                }

                $totalAlreadyRequested = $sourcingOrder->refundRequests()->whereIn('status', ['pending', 'approved'])->sum('amount_requested');
                $remainingAmount = $sourcingOrder->total_amount - $totalAlreadyRequested;
                $requestedAmount = $validated['type'] === 'full' ? $sourcingOrder->total_amount : $validated['amount_requested'];

                if ($requestedAmount > $sourcingOrder->total_amount) {
                    throw new \Exception(__('The requested refund amount cannot exceed the order total price (:amount :currency).', [
                        'amount' => number_format($sourcingOrder->total_amount, 2),
                        'currency' => $sourcingOrder->quotation->currency
                    ]));
                }

                if ($requestedAmount > $remainingAmount) {
                    throw new \Exception(__('The requested amount exceeds the remaining refundable balance (:remaining :currency). You have already requested :already :currency.', [
                        'remaining' => number_format($remainingAmount, 2),
                        'already' => number_format($totalAlreadyRequested, 2),
                        'currency' => $sourcingOrder->quotation->currency
                    ]));
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

                $refundRequest = RefundRequest::create([
                    'sourcing_order_id' => $sourcingOrder->id,
                    'sourcing_request_id' => $sourcingOrder->quotation->sourcing_request_id,
                    'user_id' => auth()->id(),
                    'assigned_to_admin_id' => $sourcingOrder->assigned_to_admin_id,
                    'type' => $validated['type'],
                    'status' => 'pending', // Always pending
                    'amount_requested' => $amount,
                    'amount_approved' => null,
                    'damaged_quantity' => $validated['damaged_quantity'],
                    'reason_category' => $validated['reason_category'],
                    'reason_description' => $validated['reason_description'],
                    'evidence_paths' => $evidencePaths,
                    'admin_notes' => null,
                ]);

                $sourcingOrder->update(['status' => 'waiting_for_refund']);
                
                return $refundRequest;
            });

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

            return redirect()->route('client.sourcing-orders.index')->with('success', __('Refund request submitted successfully and is being reviewed.'));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $locale, RefundRequest $refundRequest): View
    {
        $this->authorize('view', $refundRequest);

        $refundRequest->load(['sourcingOrder.quotation']);

        return view('client.refund-requests.show', compact('refundRequest'));
    }
}
