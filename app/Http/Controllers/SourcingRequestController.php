<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSourcingRequestRequest;
use App\Http\Requests\UpdateClientDestinationQuantitiesRequest;
use App\Http\Requests\UpdateSourcingRequestRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\SourcingRequest;
use App\Models\SourcingRequestDestination;
use App\Models\User;
use App\Notifications\SourcingRequestCreated;
use App\Services\QuotationAmountRecalculationService;
use App\Services\TimelineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PDF;

class SourcingRequestController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(string $locale): View
    {
        $this->authorize('viewAny', SourcingRequest::class);
        $sourcingRequests = auth()->user()->sourcingRequests()
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->with(['category', 'destinations.country', 'destinations.service'])
            ->latest()
            ->get();

        return view('client.sourcing-requests.index', compact('sourcingRequests'));
    }

    public function archived(string $locale): View
    {
        $this->authorize('viewAny', SourcingRequest::class);
        $sourcingRequests = auth()->user()->sourcingRequests()
            ->whereIn('status', ['cancelled', 'rejected'])
            ->with(['category', 'destinations.country', 'destinations.service'])
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return view('client.sourcing-requests.archived', compact('sourcingRequests', 'categories'));
    }

    public function handling(Request $request, string $locale): View
    {
        $this->authorize('viewAny', SourcingRequest::class);
        $query = auth()->user()->sourcingRequests()
            ->with(['category', 'destinations.country', 'destinations.service', 'quotation'])
            ->latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('product_name', 'like', '%'.$request->search.'%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $sourcingRequests = $query->paginate(10)->withQueryString();

        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $categories = Category::all();

        return view('client.sourcing-requests.handling', compact('sourcingRequests', 'paymentMethods', 'categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale, SourcingRequest $sourcingRequest): View
    {
        $this->authorize('view', $sourcingRequest);

        $sourcingRequest->load('category', 'destinations.country', 'destinations.service', 'quotation.order');
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('client.sourcing-requests.show', compact('sourcingRequest', 'paymentMethods'));
    }

    public function updateDestinationQuantities(
        string $locale,
        SourcingRequest $sourcingRequest,
        UpdateClientDestinationQuantitiesRequest $request,
        QuotationAmountRecalculationService $recalculationService,
    ): RedirectResponse {
        $sourcingRequest->load('destinations', 'quotation');
        $quotation = $sourcingRequest->quotation;

        if (! $quotation) {
            abort(404);
        }

        $previousTotalQuantity = (int) $sourcingRequest->destinations->sum('quantity');

        DB::transaction(function () use ($request, $sourcingRequest, $quotation, $previousTotalQuantity, $recalculationService) {
            foreach ($request->validated()['destinations'] as $row) {
                SourcingRequestDestination::query()
                    ->where('sourcing_request_id', $sourcingRequest->id)
                    ->where('id', $row['id'])
                    ->update(['quantity' => $row['quantity']]);
            }

            $recalculationService->recalculate($quotation->fresh(), $previousTotalQuantity);
        });

        return redirect()
            ->route('client.sourcing-requests.show', $sourcingRequest)
            ->with('status', __('Quantities updated. Your quotation totals have been recalculated.'));
    }

    /**
     * Display the sourcing request creation form.
     */
    public function create(string $locale): View
    {
        $this->authorize('create', SourcingRequest::class);
        // Retrieve all categories, countries, and services for the form
        $categories = \App\Models\Category::all();
        $countries = \App\Models\Country::all();
        $services = \App\Models\Service::all();

        // Pass the data to the view
        return view('client.sourcing-requests.create', compact('categories', 'countries', 'services', 'locale'));
    }

    /**
     * Store a newly created sourcing request in storage.
     */
    public function store(StoreSourcingRequestRequest $request, string $locale)
    {
        $this->authorize('create', SourcingRequest::class);
        $validated = $request->validated();

        $sourcingRequest = DB::transaction(function () use ($request, $validated) {
            if ($request->hasFile('product_image')) {
                $validated['product_image'] = $this->imageService->compressAndStore(
                    $request->file('product_image'),
                    'product_images'
                );
            }

            $sourcingRequest = $request->user()->sourcingRequests()->create([
                'product_name' => $validated['product_name'],
                'product_url' => $validated['product_url'] ?? null,
                'product_image' => $validated['product_image'] ?? null,
                'category_id' => $validated['category_id'],
                'note' => $validated['note'] ?? null,
                'phone_number' => $request->user()->phone,
                'shipping_method' => $validated['shipping_method'] ?? null,
                'sourcing_location' => $validated['sourcing_location'],
            ]);

            foreach ($validated['destinations'] as $destinationData) {
                $sourcingRequest->destinations()->create($destinationData);
            }

            return $sourcingRequest;
        });

        // Notify all admins and super admins
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();

        foreach ($admins as $admin) {
            try {
                $admin->notify(new SourcingRequestCreated($sourcingRequest));
            } catch (\Throwable $e) {
                \Log::error('Failed to notify admin about new sourcing request', [
                    'sourcing_request_id' => $sourcingRequest->id,
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Sourcing request created successfully!',
                'redirect_url' => route('client.dashboard'),
            ]);
        }

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale, SourcingRequest $sourcingRequest): View
    {
        $this->authorize('update', $sourcingRequest);

        $sourcingRequest->load('category', 'destinations.country', 'destinations.service');
        $categories = Category::all();
        $countries = Country::all();
        $services = Service::all();

        return view('client.sourcing-requests.edit', compact('sourcingRequest', 'categories', 'countries', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSourcingRequestRequest $request, string $locale, SourcingRequest $sourcingRequest)
    {
        $this->authorize('update', $sourcingRequest);

        $validated = $request->validated();

        try {
            DB::transaction(function () use ($request, $sourcingRequest, $validated) {
                if ($request->hasFile('product_image')) {
                    // Delete old image if exists
                    if ($sourcingRequest->product_image) {
                        Storage::disk('public')->delete($sourcingRequest->product_image);
                    }
                    $validated['product_image'] = $this->imageService->compressAndStore(
                        $request->file('product_image'),
                        'product_images'
                    );
                }

                $sourcingRequest->update([
                    'product_name' => $validated['product_name'],
                    'product_url' => $validated['product_url'] ?? null,
                    'product_image' => $validated['product_image'] ?? $sourcingRequest->product_image,
                    'category_id' => $validated['category_id'],
                    'note' => $validated['note'] ?? null,
                    'phone_number' => $validated['phone_number'] ?? $sourcingRequest->phone_number,
                    'address' => $validated['address'] ?? $sourcingRequest->address,
                    'latitude' => $validated['latitude'] ?? $sourcingRequest->latitude,
                    'longitude' => $validated['longitude'] ?? $sourcingRequest->longitude,
                    'shipping_method' => $validated['shipping_method'] ?? null,
                    'sourcing_location' => $validated['sourcing_location'],
                ]);

                // Update destinations
                $sourcingRequest->destinations()->delete(); // Delete existing destinations
                foreach ($validated['destinations'] as $destinationData) {
                    $sourcingRequest->destinations()->create($destinationData);
                }
            });

            // Support for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sourcing request updated successfully!',
                    'redirect_url' => route('client.sourcing-requests.show', $sourcingRequest),
                ]);
            }

            return redirect()->route('client.sourcing-requests.show', $sourcingRequest)->with('status', 'Sourcing request updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Sourcing Request Update Error: '.$e->getMessage(), [
                'request_id' => $sourcingRequest->id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'An error occurred while updating the sourcing request.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'An error occurred while updating the sourcing request. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $locale, SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('delete', $sourcingRequest);

        // Delete associated image if exists
        if ($sourcingRequest->product_image) {
            Storage::disk('public')->delete($sourcingRequest->product_image);
        }

        $sourcingRequest->delete();

        return redirect()->back()->with('status', 'Sourcing request deleted successfully!');
    }

    public function history(Request $request, string $locale, TimelineService $timelineService): View
    {
        $user = auth()->user();
        $fullTimeline = $timelineService->generateTimeline($user);

        if ($request->has('type') && $request->type != 'all') {
            $type = $request->type;
            $fullTimeline = $fullTimeline->filter(function ($event) use ($type) {
                return str_starts_with($event['type'], $type);
            });
        }

        // Manually paginate the collection
        $perPage = 10;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $fullTimeline->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedTimeline = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $fullTimeline->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return view('client.history.index', ['timeline' => $paginatedTimeline]);
    }

    public function exportHistory(Request $request, string $locale, TimelineService $timelineService)
    {
        $user = auth()->user();
        $timeline = $timelineService->generateTimeline($user);

        if ($request->has('type') && $request->type != 'all') {
            $type = $request->type;
            $timeline = $timeline->filter(function ($event) use ($type) {
                return str_starts_with($event['type'], $type);
            });
        }

        $pdf = PDF::loadView('client.history.pdf', compact('timeline'));

        return $pdf->stream('history.pdf');
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate(string $locale, SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('create', $sourcingRequest);

        DB::transaction(function () use ($sourcingRequest) {
            $newSourcingRequest = $sourcingRequest->replicate();
            $newSourcingRequest->status = 'pending';
            $newSourcingRequest->assigned_to_admin_id = null;
            $newSourcingRequest->assigned_at = null;
            $newSourcingRequest->created_at = now();
            $newSourcingRequest->updated_at = now();
            $newSourcingRequest->save();

            foreach ($sourcingRequest->destinations as $destination) {
                $newSourcingRequest->destinations()->create($destination->toArray());
            }
        });

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request duplicated successfully!');
    }

    public function cancel(string $locale, SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('cancel', $sourcingRequest);

        try {
            $sourcingRequest->transitionTo('rejected');
        } catch (\Throwable $e) {
            return redirect()->route('client.dashboard')
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('Could not cancel the sourcing request.'));
        }

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request cancelled successfully!');
    }
}
