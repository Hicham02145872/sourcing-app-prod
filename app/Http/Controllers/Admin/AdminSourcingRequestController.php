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
        try {
            return DB::transaction(function () use ($request) {
                // 1. Identify or Create the Client
                $userId = null;
                if ($request->client_type === 'existing') {
                    $userId = $request->user_id;
                } else {
                    $user = User::create([
                        'name' => $request->client_name,
                        'email' => $request->client_email,
                        'phone' => $request->client_phone,
                        'password' => Hash::make(Str::random(12)),
                        'role' => 'client',
                    ]);
                    $userId = $user->id;

                    // Trigger welcome/verification email if needed
                    $user->sendEmailVerificationNotification();
                }

                // 2. Handle Product Image
                $imagePath = null;
                if ($request->hasFile('product_image')) {
                    $imagePath = $this->imageService->compressAndStore(
                        $request->file('product_image'),
                        'sourcing-requests'
                    );
                }

                // 3. Create Sourcing Request
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

                return redirect()
                    ->route('admin.sourcing-requests.show', $sourcingRequest)
                    ->with('success', 'Demande de sourcing créée et assignée avec succès.');
            });
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

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by search term
        if ($request->has('search')) {
            $query->where('product_name', 'like', '%'.$request->search.'%');
        }

        // Filter by admin (Super Admin only)
        if (auth()->user()->isSuperAdmin() && $request->has('admin_id') && $request->admin_id != 'all') {
            if ($request->admin_id == 'unassigned') {
                $query->whereNull('assigned_to_admin_id');
            } else {
                $query->where('assigned_to_admin_id', $request->admin_id);
            }
        }

        // AUTO-ASSIGNMENT SECURITY FILTER
        // If not Super Admin, show only:
        // 1. My assigned requests
        // 2. Unassigned requests
        // 3. (Optional) You might want to allow viewing others' requests but read-only,
        //    but the requirement says "Avoid two admins working on same file".
        //    Let's stick to the plan: Hide others' work to prevent collision.
        if (! auth()->user()->isSuperAdmin()) {
            $query->where(function ($q) {
                $q->where('assigned_to_admin_id', auth()->id())
                    ->orWhereNull('assigned_to_admin_id');
            });
        }

        $sourcingRequests = $query->latest()->paginate(10);
        $admins = \App\Models\User::where('role', 'admin')->get(); // For manual assignment dropdown

        return view('admin.sourcing-requests.index', compact('sourcingRequests', 'admins'));
    }

    /**
     * Display the specified sourcing request.
     */
    public function show(SourcingRequest $sourcingRequest): View
    {
        $currentUser = auth()->user();

        // 1. Auto-Claim Logic
        if (is_null($sourcingRequest->assigned_to_admin_id)) {
            // Automatically assign to current admin
            $sourcingRequest->update([
                'assigned_to_admin_id' => $currentUser->id,
                'assigned_at' => now(),
            ]);

            session()->flash('success', 'Dossier automatiquement assigné à vous.');
        }
        // 2. Security Check
        elseif ($sourcingRequest->assigned_to_admin_id !== $currentUser->id) {
            // Super Admin can see everything
            if ($currentUser->isSuperAdmin()) {
                session()->flash('warning', 'Attention: Ce dossier est assigné à un autre administrateur ('.($sourcingRequest->assignedAdmin->name ?? 'Inconnu').').');
            } else {
                // Regular admin is blocked
                abort(403, 'Ce dossier est verrouillé par un autre administrateur.');
            }
        }

        $sourcingRequest->load('category', 'user', 'destinations.country', 'destinations.service', 'assignedAdmin');

        return view('admin.sourcing-requests.show', compact('sourcingRequest'));
    }

    /**
     * Update the status of the specified sourcing request.
     */
    public function updateStatus(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
    {
        // Security: Ensure the user owns the ticket or is Super Admin
        if (! $sourcingRequest->isAssignedTo(auth()->user()) && ! auth()->user()->isSuperAdmin()) {
            abort(403, 'Vous ne pouvez pas modifier un dossier qui ne vous est pas assigné.');
        }

        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', \App\Models\SourcingRequest::STATUSES),
        ]);

        try {
            $sourcingRequest->transitionTo($validated['status']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['generic' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.sourcing-requests.show', $sourcingRequest)
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
}
