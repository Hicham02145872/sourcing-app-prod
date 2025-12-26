<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\SourcingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Kreait\Firebase\Contract\Messaging;

class QuotationController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Quotation::class);

        $baseQuery = Quotation::query();

        // Filter for non-Super Admins: only show quotations assigned to them
        if (! Auth::user()->isSuperAdmin()) {
            $baseQuery->where('assigned_to_admin_id', Auth::id());
        }

        $totalQuotations = $baseQuery->count();
        $pendingQuotations = (clone $baseQuery)->where('status', 'pending')->count();
        $approvedQuotations = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedQuotations = (clone $baseQuery)->where('status', 'rejected')->count();

        $query = Quotation::with('sourcingRequest.user');

        // Filter for non-Super Admins
        if (! Auth::user()->isSuperAdmin()) {
            $query->where('assigned_to_admin_id', Auth::id());
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->whereHas('sourcingRequest', function ($q) use ($request) {
                $q->where('product_name', 'like', '%'.$request->search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%'.$request->search.'%');
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $quotations = $query->paginate(10);

        return view('admin.quotations.index', compact(
            'quotations',
            'request',
            'totalQuotations',
            'pendingQuotations',
            'approvedQuotations',
            'rejectedQuotations'
        ));
    }

    public function selectRequest(): View
    {
        $this->authorize('create', Quotation::class);
        $sourcingRequestsQuery = SourcingRequest::where('status', 'in_review');

        // Filter for non-Super Admins: only show assigned requests
        if (! Auth::user()->isSuperAdmin()) {
            $sourcingRequestsQuery->where('assigned_to_admin_id', Auth::id());
        }
        $sourcingRequestsInReview = $sourcingRequestsQuery->count();
        $pendingQuotations = (clone $sourcingRequestsQuery)->whereDoesntHave('quotation')->count();
        $sourcingRequestsThisMonth = (clone $sourcingRequestsQuery)->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
        $sourcingRequests = $sourcingRequestsQuery->paginate(10);

        return view('admin.quotations.select_request', compact('sourcingRequests', 'sourcingRequestsInReview', 'pendingQuotations', 'sourcingRequestsThisMonth'));
    }

    public function create(SourcingRequest $sourcingRequest): View
    {
        $this->authorize('create', Quotation::class);

        return view('admin.quotations.create', compact('sourcingRequest'));
    }

    public function store(Request $request, Messaging $messaging): RedirectResponse
    {
        $this->authorize('create', Quotation::class);

        $validated = $request->validate([
            'sourcing_request_id' => 'required|exists:sourcing_requests,id',
            'unit_price' => 'required|numeric|min:0',
            'commission_service' => 'required|numeric|min:0',
            'unit_weight' => 'required|numeric|min:0',
            'weight_unit' => 'required|string|in:g,kg,colis',
            'delivery_cost_china' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            // Financial estimation fields (optional)
            'estimated_product_cost' => 'nullable|numeric|min:0',
            'estimated_shipping_cost' => 'nullable|numeric|min:0',
            'estimated_other_costs' => 'nullable|numeric|min:0',
        ]);

        // Get the sourcing request and load its destinations
        $sourcingRequest = SourcingRequest::with('destinations')->findOrFail($validated['sourcing_request_id']);

        // Calculate total quantity from all destinations
        $totalQuantity = $sourcingRequest->destinations->sum('quantity');

        // Ensure totalQuantity is at least 1 (or 0 if an empty request should result in 0 total)
        // Assuming at least one destination with quantity > 0 is required by validation.
        $totalQuantity = max(1, $totalQuantity);

        // Calculate total amount: (unit_price * totalQuantity) + commission + delivery
        $subtotal = $validated['unit_price'] * $totalQuantity;
        $amount = $subtotal + $validated['commission_service'] + $validated['delivery_cost_china'];

        // Handle Estimated Product Cost: Input is Unit Cost -> Store as Total Cost
        $estimatedProductCostTotal = null;
        if (isset($validated['estimated_product_cost'])) {
            $estimatedProductCostTotal = $validated['estimated_product_cost'] * $totalQuantity;
        }

        // Debug log
        Log::info('Quotation Calculation', [
            'unit_price' => $validated['unit_price'],
            'quantity' => $totalQuantity,
            'subtotal' => $subtotal,
            'commission_service' => $validated['commission_service'],
            'delivery_cost_china' => $validated['delivery_cost_china'],
            'total_amount' => $amount,
            'currency' => $validated['currency'],
            'estimated_unit_product_cost' => $validated['estimated_product_cost'] ?? null,
            'estimated_total_product_cost' => $estimatedProductCostTotal,
        ]);

        $quotation = Quotation::create([
            'sourcing_request_id' => $validated['sourcing_request_id'],
            'assigned_to_admin_id' => $sourcingRequest->assigned_to_admin_id, // Inherit assignment from request
            'amount' => $amount,
            'unit_price' => $validated['unit_price'],
            'commission_service' => $validated['commission_service'],
            'unit_weight' => $validated['unit_weight'],
            'weight_unit' => $validated['weight_unit'],
            'delivery_cost_china' => $validated['delivery_cost_china'],
            'currency' => $validated['currency'],
            'status' => 'pending', // Default status
            // Financial estimation fields
            'estimated_product_cost' => $estimatedProductCostTotal,
            'estimated_shipping_cost' => $validated['estimated_shipping_cost'] ?? null,
            'estimated_other_costs' => $validated['estimated_other_costs'] ?? null,
            // estimated_net_profit will be calculated by QuotationObserver
        ]);

        event(new \App\Events\QuotationCreated($quotation));

        return redirect()->route('admin.dashboard')->with('status', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation): View
    {
        $this->authorize('view', $quotation);

        return view('admin.quotations.show', compact('quotation'));
    }

    public function approve(Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $quotation->update(['status' => 'approved']);

        // You might want to dispatch an event here
        // event(new QuotationApproved($quotation));

        return redirect()->route('admin.quotations.show', $quotation)->with('status', 'Quotation approved successfully!');
    }

    public function edit(Quotation $quotation): View
    {
        $this->authorize('update', $quotation);
        $quotation->load('sourcingRequest.user', 'sourcingRequest.destinations');

        return view('admin.quotations.edit', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $validated = $request->validate([
            'unit_price' => 'required|numeric|min:0',
            'commission_service' => 'required|numeric|min:0',
            'unit_weight' => 'required|numeric|min:0',
            'weight_unit' => 'required|string|in:g,kg,colis',
            'delivery_cost_china' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'estimated_product_cost' => 'nullable|numeric|min:0',
            'estimated_shipping_cost' => 'nullable|numeric|min:0',
            'estimated_other_costs' => 'nullable|numeric|min:0',
        ]);

        $sourcingRequest = $quotation->sourcingRequest;
        $totalQuantity = $sourcingRequest->destinations->sum('quantity');
        $totalQuantity = max(1, $totalQuantity);

        $subtotal = $validated['unit_price'] * $totalQuantity;
        $amount = $subtotal + $validated['commission_service'] + $validated['delivery_cost_china'];

        $estimatedProductCostTotal = null;
        if (isset($validated['estimated_product_cost'])) {
            $estimatedProductCostTotal = $validated['estimated_product_cost'] * $totalQuantity;
        }

        $quotation->update([
            'amount' => $amount,
            'unit_price' => $validated['unit_price'],
            'commission_service' => $validated['commission_service'],
            'unit_weight' => $validated['unit_weight'],
            'weight_unit' => $validated['weight_unit'],
            'delivery_cost_china' => $validated['delivery_cost_china'],
            'currency' => $validated['currency'],
            'status' => 'pending', // Reset to pending for approval if needed, or set to 'quoted' directly
            'estimated_product_cost' => $estimatedProductCostTotal,
            'estimated_shipping_cost' => $validated['estimated_shipping_cost'] ?? null,
            'estimated_other_costs' => $validated['estimated_other_costs'] ?? null,
        ]);

        // If the request was negotiating, transition it back to quoted
        if ($sourcingRequest->status === 'negotiating') {
            $sourcingRequest->transitionTo('quoted');
        }

        return redirect()->route('admin.sourcing-requests.show', $sourcingRequest)
            ->with('status', 'Quotation updated successfully and sent back to client.');
    }

    public function reject(Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $quotation->update(['status' => 'rejected']);

        // You might want to dispatch an event here
        // event(new QuotationRejected($quotation));

        return redirect()->route('admin.quotations.show', $quotation)->with('status', 'Quotation rejected successfully!');
    }
}
