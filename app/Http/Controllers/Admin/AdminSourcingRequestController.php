<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminSourcingRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\SourcingRequestDestination;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminSourcingRequestController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    /**
     * Show the form for creating a new sourcing request.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        $clients = User::where('role', 'client')->orderBy('name')->get();

        return view('admin.sourcing-requests.create', compact('categories', 'countries', 'services', 'clients'));
    }

    /**
     * Store a newly created sourcing request.
     */
    public function store(StoreAdminSourcingRequest $request): RedirectResponse
    {
        $sourcingRequest = null;
        try {
            DB::transaction(function () use ($request, &$sourcingRequest) {
                // 1. Identify or Create the Client
                $userId = null;
                if ($request->client_type === 'existing') {
                    $userId = $request->user_id;
                } else {
                    $plainPassword = Str::random(12);
                    $user = User::create([
                        'name' => $request->client_name,
                        'email' => $request->client_email,
                        'phone' => $request->client_phone,
                        'password' => Hash::make($plainPassword),
                        'role' => 'client',
                        'email_verified_at' => now(), // Auto-verify
                    ]);
                    $userId = $user->id;

                    // Send credentials to the user
                    $user->notify(new \App\Notifications\ClientAccountCreated($plainPassword));
                }

                $imagePath = null;
                if ($request->hasFile('product_image')) {
                    $result = $this->imageService->compressAndStore(
                        $request->file('product_image'),
                        'sourcing-requests'
                    );
                    $imagePath = $result->path;
                }

                $sourcingRequest = SourcingRequest::create([
                    'user_id' => $userId,
                    'product_name' => $request->product_name,
                    'product_url' => $request->product_url,
                    'product_image' => $imagePath,
                    'category_id' => $request->category_id,
                    'note' => $request->note,
                    'shipping_method' => $request->shipping_method,
                    'status' => 'pending',
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'sourcing_location' => $request->sourcing_location,
                    'assigned_to_admin_id' => auth()->id(),
                    'assigned_at' => now(),
                ]);

                // 4. Create Destinations
                foreach ($request->destinations as $destData) {
                    SourcingRequestDestination::create([
                        'sourcing_request_id' => $sourcingRequest->id,
                        'country_id' => $destData['country_id'],
                        'service_id' => $destData['service_id'],
                        'quantity' => $destData['quantity'],
                    ]);
                }
            });
            return redirect()
                ->route('admin.sourcing-requests.show', $sourcingRequest)
                ->with('success', 'Demande de sourcing créée et assignée avec succès.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : '.$e->getMessage());
        }
    }

    /**
     * Display a listing of all sourcing requests.
     */
    public function index(Request $request): View
    {
        $query = SourcingRequest::with('category', 'user', 'destinations.country', 'destinations.service', 'assignedAdmin');

        // Filter by search term
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('product_name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('shared_id', 'like', '%'.$searchTerm.'%')
                    ->orWhere('id', 'like', '%'.$searchTerm.'%')
                    ->orWhere('note', 'like', '%'.$searchTerm.'%')
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', '%'.$searchTerm.'%')
                            ->orWhere('email', 'like', '%'.$searchTerm.'%');
                    })
                    ->orWhereHas('destinations', function ($destQuery) use ($searchTerm) {
                        $destQuery->where('address', 'like', '%'.$searchTerm.'%')
                            ->orWhereHas('country', function ($countryQuery) use ($searchTerm) {
                                $countryQuery->where('name', 'like', '%'.$searchTerm.'%');
                            })
                            ->orWhereHas('service', function ($serviceQuery) use ($searchTerm) {
                                $serviceQuery->where('name', 'like', '%'.$searchTerm.'%');
                            });
                    })
                    ->orWhereHas('assignedAdmin', function ($adminQuery) use ($searchTerm) {
                        $adminQuery->where('name', 'like', '%'.$searchTerm.'%');
                    });
            });
        }

        // Filter by admin (Super Admin only, or self via admin_id=me)
        if ($request->admin_id === 'me') {
            $query->where('assigned_to_admin_id', auth()->id());
        } elseif (auth()->user()->isSuperAdmin() && $request->has('admin_id') && $request->admin_id != 'all') {
            if ($request->admin_id == 'unassigned') {
                $query->whereNull('assigned_to_admin_id');
            } else {
                $query->where('assigned_to_admin_id', $request->admin_id);
            }
        }

        // Filter by SLA-overdue requests (SLA deadline banner "View" action)
        if ($request->boolean('overdue')) {
            $query->where('is_restricted_due_to_delay', true);
        }

        // Status counts (respecting search + admin filters, but NOT status filter)
        $statusCountsQuery = SourcingRequest::query();
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $statusCountsQuery->where(function ($q) use ($searchTerm) {
                $q->where('product_name', 'like', '%'.$searchTerm.'%')
                    ->orWhere('shared_id', 'like', '%'.$searchTerm.'%')
                    ->orWhere('id', 'like', '%'.$searchTerm.'%')
                    ->orWhere('note', 'like', '%'.$searchTerm.'%')
                    ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', '%'.$searchTerm.'%')
                            ->orWhere('email', 'like', '%'.$searchTerm.'%');
                    })
                    ->orWhereHas('destinations', function ($destQuery) use ($searchTerm) {
                        $destQuery->where('address', 'like', '%'.$searchTerm.'%')
                            ->orWhereHas('country', function ($countryQuery) use ($searchTerm) {
                                $countryQuery->where('name', 'like', '%'.$searchTerm.'%');
                            })
                            ->orWhereHas('service', function ($serviceQuery) use ($searchTerm) {
                                $serviceQuery->where('name', 'like', '%'.$searchTerm.'%');
                            });
                    })
                    ->orWhereHas('assignedAdmin', function ($adminQuery) use ($searchTerm) {
                        $adminQuery->where('name', 'like', '%'.$searchTerm.'%');
                    });
            });
        }
        if ($request->admin_id === 'me') {
            $statusCountsQuery->where('assigned_to_admin_id', auth()->id());
        } elseif (auth()->user()->isSuperAdmin() && $request->has('admin_id') && $request->admin_id != 'all') {
            if ($request->admin_id == 'unassigned') {
                $statusCountsQuery->whereNull('assigned_to_admin_id');
            } else {
                $statusCountsQuery->where('assigned_to_admin_id', $request->admin_id);
            }
        }
        if ($request->boolean('overdue')) {
            $statusCountsQuery->where('is_restricted_due_to_delay', true);
        }
        $statusCountsRaw = $statusCountsQuery->select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');
        $statusCounts = [];
        foreach (SourcingRequest::STATUSES as $s) {
            $statusCounts[$s] = $statusCountsRaw->get($s, 0);
        }

        // Filter by status (default to 'pending' when no status param)
        $activeStatus = $request->input('status', 'pending');
        if ($activeStatus !== 'all') {
            $query->where('status', $activeStatus);
        }

        // Sort: 1) Critical statuses first (e.g. negotiating), 2) unassigned → my assignments → others, 3) newest first
        $criticalStatuses = SourcingRequest::CRITICAL_STATUSES_FOR_LIST;
        $criticalSql = empty($criticalStatuses)
            ? '1'
            : "CASE WHEN sourcing_requests.status IN (".implode(',', array_map(fn ($s) => "'".addslashes($s)."'", $criticalStatuses)).") THEN 0 ELSE 1 END";
        $userId = auth()->id();
        $assignmentSql = $userId
            ? "CASE WHEN sourcing_requests.assigned_to_admin_id IS NULL THEN 1 WHEN sourcing_requests.assigned_to_admin_id = ".(int) $userId." THEN 2 ELSE 3 END"
            : '0';
        $query->orderByRaw("{$criticalSql} ASC, {$assignmentSql} ASC, sourcing_requests.created_at DESC");

        $sourcingRequests = $query->paginate(10)->withQueryString();
        $admins = \App\Models\User::where('role', 'admin')->get();

        return view('admin.sourcing-requests.index', compact('sourcingRequests', 'admins', 'statusCounts', 'activeStatus'));
    }

    /**
     * Display the specified sourcing request.
     */
    public function show(SourcingRequest $sourcingRequest): View
    {
        $currentUser = auth()->user();

        // 1. Auto-Claim Logic REMOVED - Admin Visibility Logic Updated
        if ($sourcingRequest->assigned_to_admin_id !== $currentUser->id && ! is_null($sourcingRequest->assigned_to_admin_id)) {
            // Warn if viewing another admin's assignment, but do not block
            session()->flash('warning', 'Note: Ce dossier est assigné à un autre administrateur ('.($sourcingRequest->assignedAdmin->name ?? 'Inconnu').'). Mode lecture seule.');
        }

        $sourcingRequest->load('category', 'user', 'destinations.country', 'destinations.service', 'assignedAdmin', 'order');

        // Calculate quality-aware pricing for display
        $qualityOptions = [];
        $selectedQuality = null;
        $effectiveUnitPrice = 0;
        $effectiveTotal = 0;
        $totalQuantity = $sourcingRequest->destinations->sum('quantity');

        if ($sourcingRequest->quotation) {
            $quotation = $sourcingRequest->quotation;
            $qualityOptions = $quotation->quality_options ?? [];
            $effectiveUnitPrice = (float) $quotation->unit_price;

            // Use stored selected_quality from client acceptance
            $selectedQuality = $quotation->selected_quality ?? null;

            // If no selected_quality stored, detect by matching unit_price to quality prices
            if ($selectedQuality === null && !empty($qualityOptions)) {
                foreach (['low', 'medium', 'good'] as $quality) {
                    if (isset($qualityOptions[$quality]['price']) && (float) $qualityOptions[$quality]['price'] === $effectiveUnitPrice) {
                        $selectedQuality = $quality;
                        break;
                    }
                }

                // If unit_price is 0, use first available as reference
                if ($selectedQuality === null && $effectiveUnitPrice == 0) {
                    foreach (['medium', 'good', 'low'] as $quality) {
                        if (isset($qualityOptions[$quality]['price']) && !empty($qualityOptions[$quality]['price'])) {
                            $selectedQuality = $quality;
                            $effectiveUnitPrice = (float) $qualityOptions[$quality]['price'];
                            break;
                        }
                    }
                }
            }

            $effectiveTotal = ($effectiveUnitPrice * $totalQuantity) + (float) $quotation->commission_service + (float) $quotation->delivery_cost_china;
        }

        return view('admin.sourcing-requests.show', compact('sourcingRequest', 'qualityOptions', 'selectedQuality', 'effectiveUnitPrice', 'effectiveTotal', 'totalQuantity'));
    }

    /**
     * Update the status of the specified sourcing request.
     */
    public function updateStatus(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
    {
        // Security: Ensure the user owns the ticket or is Super Admin OR is claiming it (unassigned -> in_review)
        $isAssignedToMe = $sourcingRequest->isAssignedTo(auth()->user());
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $isClaimingAction = is_null($sourcingRequest->assigned_to_admin_id) && $request->input('status') === 'in_review';

        if (! $isAssignedToMe && ! $isSuperAdmin && ! $isClaimingAction) {
            abort(403, 'Vous ne pouvez pas modifier un dossier qui ne vous est pas assigné.');
        }

        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', \App\Models\SourcingRequest::STATUSES),
        ]);

        try {
            $sourcingRequest->transitionTo($validated['status']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('The status could not be updated.'));
        }

        return back()
            ->with('status', 'Statut mis à jour avec succès !');
    }

    /**
     * Manually assign a request (Super Admin only).
     */
    public function assign(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'admin_id' => 'required|exists:users,id',
        ]);

        try {
            DB::transaction(function () use ($sourcingRequest, $validated) {
                // 1. Re-fetch the request with a lock to ensure no one else is writing to it
                $lockedRequest = SourcingRequest::where('id', $sourcingRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // 2. Update the request
                // We allow overwrite (reassignment) for Super Admins.
                // The SourcingRequestObserver will automatically sync this change to the SourcingOrder.
                $lockedRequest->update([
                    'assigned_to_admin_id' => $validated['admin_id'],
                    'assigned_at' => now(),
                ]);
            });

            return back()->with('success', 'Dossier réassigné avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'assignation : '.$e->getMessage());
        }
    }

    /**
     * Unassign a request (Super Admin only).
     */
    public function unassign(SourcingRequest $sourcingRequest): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Action non autorisée.');
        }

        $sourcingRequest->update([
            'assigned_to_admin_id' => null,
            'assigned_at' => null,
        ]);

        return back()->with('success', 'Dossier libéré avec succès.');
    }

    /**
     * Release a request (Assigned Admin).
     */
    public function release(SourcingRequest $sourcingRequest): RedirectResponse
    {
        // Only the assigned admin or super admin can release
        if (! $sourcingRequest->isAssignedTo(auth()->user()) && ! auth()->user()->isSuperAdmin()) {
            abort(403, 'Vous ne pouvez pas libérer ce dossier.');
        }

        $sourcingRequest->update([
            'assigned_to_admin_id' => null,
            'assigned_at' => null,
        ]);

        return redirect()->route('admin.sourcing-requests.index')->with('success', 'Dossier libéré.');
    }

    public function destroy(SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('delete', $sourcingRequest);

        $sourcingRequest->delete();

        return redirect()->route('admin.sourcing-requests.index')->with('success', __('Request deleted successfully.'));
    }
}
