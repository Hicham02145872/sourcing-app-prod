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
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Quotation::class);

        $baseQuery = Quotation::query();

        $totalQuotations = $baseQuery->count();
        $pendingQuotations = (clone $baseQuery)->where('status', 'pending')->count();
        $approvedQuotations = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedQuotations = (clone $baseQuery)->where('status', 'rejected')->count();

        $query = Quotation::with('sourcingRequest.user');

        // Search
        if ($request->has('search') && $request->search) {
            $query->whereHas('sourcingRequest', function ($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->search . '%');
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
        $sourcingRequests = SourcingRequest::where('status', 'in_review')->get();
        $sourcingRequestsInReview = $sourcingRequests->count();
        $pendingQuotations = $sourcingRequests->whereNull('quoted_at')->count();
        $sourcingRequestsThisMonth = SourcingRequest::where('status', 'in_review')->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
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

        event(new \App\Events\QuotationCreated($quotation));

        return redirect()->route('admin.dashboard')->with('status', 'Quotation created successfully!');
    }
}
