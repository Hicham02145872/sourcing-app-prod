<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\SourcingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSourcingRequestRequest;
use App\Http\Requests\UpdateSourcingRequestRequest;
use App\Services\TimelineService;

class SourcingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', SourcingRequest::class);
        $sourcingRequests = auth()->user()->sourcingRequests()->with('category', 'destinations.country', 'destinations.service')->get();
        return view('client.sourcing-requests.index', compact('sourcingRequests'));
    }

    public function handling(): View
    {
        $this->authorize('viewAny', SourcingRequest::class);
        $sourcingRequests = auth()->user()->sourcingRequests()
            ->where('status', 'in_review')
            ->with('category', 'destinations.country', 'destinations.service', 'quotation')
            ->paginate(10);

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('client.sourcing-requests.handling', compact('sourcingRequests', 'paymentMethods'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SourcingRequest $sourcingRequest): View
    {
        $this->authorize('view', $sourcingRequest);

        $sourcingRequest->load('category', 'destinations.country', 'destinations.service', 'quotation.order');
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('client.sourcing-requests.show', compact('sourcingRequest', 'paymentMethods'));
    }

    /**
     * Display the sourcing request creation form.
     */
    public function create(): View
    {
        $this->authorize('create', SourcingRequest::class);
        // Retrieve all categories, countries, and services for the form
        $categories = \App\Models\Category::all();
        $countries = \App\Models\Country::all();
        $services = \App\Models\Service::all();
        // Pass the data to the view
        return view('client.sourcing-requests.create', compact('categories', 'countries', 'services'));
    }

    /**
     * Store a newly created sourcing request in storage.
     */
    public function store(StoreSourcingRequestRequest $request)
    {
        $this->authorize('create', SourcingRequest::class);
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            if ($request->hasFile('product_image')) {
                $validated['product_image'] = $request->file('product_image')->store('product_images', 'public');
            }

            $sourcingRequest = $request->user()->sourcingRequests()->create([
                'product_name' => $validated['product_name'],
                'product_url' => $validated['product_url'] ?? null,
                'product_image' => $validated['product_image'] ?? null,
                'category_id' => $validated['category_id'],
                'note' => $validated['note'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'shipping_method' => $validated['shipping_method'] ?? null,
                'sourcing_location' => $validated['sourcing_location'],
            ]);

            foreach ($validated['destinations'] as $destinationData) {
                $sourcingRequest->destinations()->create($destinationData);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Sourcing request created successfully!',
                'redirect_url' => route('client.dashboard')
            ]);
        }

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SourcingRequest $sourcingRequest): View
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
    public function update(UpdateSourcingRequestRequest $request, SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('update', $sourcingRequest);

        $validated = $request->validated();

        DB::transaction(function () use ($request, $sourcingRequest, $validated) {
            if ($request->hasFile('product_image')) {
                // Delete old image if exists
                if ($sourcingRequest->product_image) {
                    Storage::disk('public')->delete($sourcingRequest->product_image);
                }
                $validated['product_image'] = $request->file('product_image')->store('product_images', 'public');
            }

            $sourcingRequest->update([
                'product_name' => $validated['product_name'],
                'product_url' => $validated['product_url'] ?? null,
                'product_image' => $validated['product_image'] ?? $sourcingRequest->product_image,
                'category_id' => $validated['category_id'],
                'note' => $validated['note'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'shipping_method' => $validated['shipping_method'] ?? null,
                'sourcing_location' => $validated['sourcing_location'],
            ]);

            // Update destinations
            $sourcingRequest->destinations()->delete(); // Delete existing destinations
            foreach ($validated['destinations'] as $destinationData) {
                $sourcingRequest->destinations()->create($destinationData);
            }
        });

        return redirect()->route('client.sourcing-requests.show', $sourcingRequest)->with('status', 'Sourcing request updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('delete', $sourcingRequest);

        // Delete associated image if exists
        if ($sourcingRequest->product_image) {
            Storage::disk('public')->delete($sourcingRequest->product_image);
        }

        $sourcingRequest->delete();

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request deleted successfully!');
    }

    public function history(TimelineService $timelineService)
    {
        $user = auth()->user();
        $sortedTimeline = $timelineService->generateTimeline($user);

        return view('client.history.index', ['timeline' => $sortedTimeline]);
    }

    /**
     * Duplicate the specified resource.
     */
    public function duplicate(SourcingRequest $sourcingRequest): RedirectResponse
    {
        $this->authorize('create', $sourcingRequest);

        DB::transaction(function () use ($sourcingRequest) {
            $newSourcingRequest = $sourcingRequest->replicate();
            $newSourcingRequest->status = 'pending';
            $newSourcingRequest->created_at = now();
            $newSourcingRequest->updated_at = now();
            $newSourcingRequest->save();

            foreach ($sourcingRequest->destinations as $destination) {
                $newSourcingRequest->destinations()->create($destination->toArray());
            }
        });

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request duplicated successfully!');
    }
}
