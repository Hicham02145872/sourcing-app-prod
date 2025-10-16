<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use App\Models\Service;
use App\Models\SourcingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SourcingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sourcingRequests = auth()->user()->sourcingRequests()->with('category', 'destinations.country', 'destinations.service')->get();
        return view('client.sourcing-requests.index', compact('sourcingRequests'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SourcingRequest $sourcingRequest): View
    {
        // Ensure the authenticated user owns this sourcing request
        if (auth()->user()->id !== $sourcingRequest->user_id) {
            abort(403);
        }

        $sourcingRequest->load('category', 'destinations.country', 'destinations.service');

        return view('client.sourcing-requests.show', compact('sourcingRequest'));
    }

    /**
     * Display the sourcing request creation form.
     */
    public function create(): View
    {
        // Retrieve all categories, countries, and services for the form
        $categories = Category::all();
        $countries = Country::all();
        $services = Service::all();
        // Pass the data to the view
        return view('client.sourcing-requests.create', compact('categories', 'countries', 'services'));
    }

    /**
     * Store a newly created sourcing request in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_url' => 'nullable|url|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string',
            'shipping_method' => 'nullable|in:air,sea',
            'destinations' => 'required|array|min:1',
            'destinations.*.country_id' => 'required|exists:countries,id',
            'destinations.*.service_id' => 'required|exists:services,id',
            'destinations.*.quantity' => 'required|integer|min:1',
        ]);

        if ($request->hasFile('product_image')) {
            $validated['product_image'] = $request->file('product_image')->store('product_images', 'public');
        }

        $sourcingRequest = $request->user()->sourcingRequests()->create([
            'product_name' => $validated['product_name'],
            'product_url' => $validated['product_url'] ?? null,
            'product_image' => $validated['product_image'] ?? null,
            'category_id' => $validated['category_id'],
            'note' => $validated['note'] ?? null,
            'shipping_method' => $validated['shipping_method'] ?? null,
        ]);

        foreach ($validated['destinations'] as $destinationData) {
            $sourcingRequest->destinations()->create($destinationData);
        }

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SourcingRequest $sourcingRequest): View
    {
        // Ensure the authenticated user owns this sourcing request
        if (auth()->user()->id !== $sourcingRequest->user_id) {
            abort(403);
        }

        $sourcingRequest->load('category', 'destinations.country', 'destinations.service');
        $categories = Category::all();
        $countries = Country::all();
        $services = Service::all();

        return view('client.sourcing-requests.edit', compact('sourcingRequest', 'categories', 'countries', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
    {
        // Ensure the authenticated user owns this sourcing request
        if (auth()->user()->id !== $sourcingRequest->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_url' => 'nullable|url|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string',
            'shipping_method' => 'nullable|in:air,sea',
            'destinations' => 'required|array|min:1',
            'destinations.*.country_id' => 'required|exists:countries,id',
            'destinations.*.service_id' => 'required|exists:services,id',
            'destinations.*.quantity' => 'required|integer|min:1',
        ]);

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
            'shipping_method' => $validated['shipping_method'] ?? null,
        ]);

        // Update destinations
        $sourcingRequest->destinations()->delete(); // Delete existing destinations
        foreach ($validated['destinations'] as $destinationData) {
            $sourcingRequest->destinations()->create($destinationData);
        }

        return redirect()->route('client.sourcing-requests.show', $sourcingRequest)->with('status', 'Sourcing request updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SourcingRequest $sourcingRequest): RedirectResponse
    {
        // Ensure the authenticated user owns this sourcing request
        if (auth()->user()->id !== $sourcingRequest->user_id) {
            abort(403);
        }

        // Delete associated image if exists
        if ($sourcingRequest->product_image) {
            Storage::disk('public')->delete($sourcingRequest->product_image);
        }

        $sourcingRequest->delete();

        return redirect()->route('client.dashboard')->with('status', 'Sourcing request deleted successfully!');
    }
}
