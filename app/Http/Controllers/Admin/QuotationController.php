<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\SourcingRequest;
use App\Services\ImageProcessingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(protected ImageProcessingService $imageService) {}
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Quotation::class);

        $baseQuery = Quotation::query();

        // Filter for non-Super Admins: REMOVED to allow full visibility (read-only)
        // if (! Auth::user()->isSuperAdmin()) {
        //    $baseQuery->where('assigned_to_admin_id', Auth::id());
        // }

        $totalQuotations = $baseQuery->count();
        $pendingQuotations = (clone $baseQuery)->where('status', 'pending')->count();
        $approvedQuotations = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedQuotations = (clone $baseQuery)->where('status', 'rejected')->count();

        $query = Quotation::with('sourcingRequest.user');

        // Filter for non-Super Admins: REMOVED to allow full visibility
        // if (! Auth::user()->isSuperAdmin()) {
        //    $query->where('assigned_to_admin_id', Auth::id());
        // }

        // Search
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', '%'.$searchTerm.'%')
                    ->orWhereHas('sourcingRequest', function ($srQuery) use ($searchTerm) {
                        $srQuery->where('product_name', 'like', '%'.$searchTerm.'%')
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
                                    });
                            });
                    })
                    ->orWhereHas('assignedAdmin', function ($adminQuery) use ($searchTerm) {
                        $adminQuery->where('name', 'like', '%'.$searchTerm.'%');
                    });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Default sort: newest first
        $query->orderBy('created_at', 'desc');

        $quotations = $query->paginate(10)->withQueryString();

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

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Quotation::class);

        $validated = $request->validate([
            'sourcing_request_id' => 'required|exists:sourcing_requests,id',
            'unit_price' => 'required|numeric|min:0',
            'commission_service' => 'required|numeric|min:0',
            'delivery_cost_china' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'actual_sourcing_location' => 'nullable|string|in:china,dubai',
            'sourcing_note' => 'nullable|string',
            'comments' => 'nullable|string',
            // Financial estimation fields (optional)
            'estimated_product_cost' => 'nullable|numeric|min:0',
            'estimated_shipping_cost' => 'nullable|numeric|min:0',
            'estimated_other_costs' => 'nullable|numeric|min:0',
            'real_product_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
            'supplier_url' => 'nullable|url|max:2048',

            'quality_options' => 'nullable|array',
            'quality_options.low.price' => 'nullable|numeric|min:0',
            'quality_options.medium.price' => 'nullable|numeric|min:0',
            'quality_options.good.price' => 'nullable|numeric|min:0',
            'quality_options.low.weight' => 'nullable|required_with:quality_options.low.price|numeric|min:0',
            'quality_options.medium.weight' => 'nullable|required_with:quality_options.medium.price|numeric|min:0',
            'quality_options.good.weight' => 'nullable|required_with:quality_options.good.price|numeric|min:0',
            'quality_options.low.weight_unit' => 'nullable|required_with:quality_options.low.weight|string|in:g,kg,colis',
            'quality_options.medium.weight_unit' => 'nullable|required_with:quality_options.medium.weight|string|in:g,kg,colis',
            'quality_options.good.weight_unit' => 'nullable|required_with:quality_options.good.weight|string|in:g,kg,colis',
            'quality_options_images' => 'nullable|array',
            'quality_options_images.low' => 'nullable|array',
            'quality_options_images.low.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
            'quality_options_images.medium' => 'nullable|array',
            'quality_options_images.medium.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
            'quality_options_images.good' => 'nullable|array',
            'quality_options_images.good.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
        ], [
            'supplier_url.url' => __('Veuillez entrer une URL valide pour le lien fournisseur.'),
            'real_product_image.max' => __('L\'image ou la vidéo ne doit pas dépasser 20 MB.'),
            'real_product_image.mimes' => __('Le fichier doit être une image (JPEG, PNG, GIF) ou une vidéo (MP4, MOV, AVI).'),

        ]);

        // Get the sourcing request and load its destinations
        $sourcingRequest = SourcingRequest::with('destinations')->findOrFail($validated['sourcing_request_id']);

        // Calculate total quantity from all destinations (use actual sum; do not force minimum 1)
        $totalQuantity = $sourcingRequest->destinations->sum('quantity');

        // Auto-set unit_price from quality options if hidden field is 0
        // Quality options define per-quality pricing; use first available as base unit_price
        $effectiveUnitPrice = (float) $validated['unit_price'];
        if ($effectiveUnitPrice == 0 && $request->has('quality_options')) {
            $rawOptions = $request->input('quality_options');
            foreach (['medium', 'good', 'low'] as $quality) {
                if (isset($rawOptions[$quality]['price']) && $rawOptions[$quality]['price'] !== '') {
                    $effectiveUnitPrice = (float) $rawOptions[$quality]['price'];
                    break;
                }
            }
        }

        // Calculate total amount: (unit_price * totalQuantity) + commission + delivery
        $subtotal = $effectiveUnitPrice * $totalQuantity;
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

        $realProductImagePath = null;
        if ($request->hasFile('real_product_image')) {
            $file = $request->file('real_product_image');
            if (str_starts_with($file->getMimeType(), 'video/')) {
                $realProductImagePath = $file->store('quotations/real_images', 'public');
            } else {
                $result = $this->imageService->compressAndStore(
                    $file,
                    'quotations/real_images',
                    'public',
                    1200,
                    80
                );
                $realProductImagePath = $result->path;
            }
        }

        $qualityOptionsData = [];
        if ($request->has('quality_options')) {
            $rawOptions = $request->input('quality_options');
            foreach (['low', 'medium', 'good'] as $quality) {
                if (isset($rawOptions[$quality]['price']) && $rawOptions[$quality]['price'] !== '') {
                    $price = (float) $rawOptions[$quality]['price'];
                    
                    $imagePath = null;
                    $imagePaths = [];
                    if ($request->hasFile("quality_options_images.{$quality}")) {
                        $files = $request->file("quality_options_images.{$quality}");
                        if (!is_array($files)) {
                            $files = [$files];
                        }
                        foreach ($files as $file) {
                            if (str_starts_with($file->getMimeType(), 'video/')) {
                                $path = $file->store('quotations/quality', 'public');
                                if ($path) {
                                    $imagePaths[] = $path;
                                }
                            } else {
                                $result = $this->imageService->compressAndStore(
                                    $file,
                                    'quotations/quality',
                                    'public',
                                    1200,
                                    80
                                );
                                if ($result->path) {
                                    $imagePaths[] = $result->path;
                                }
                            }
                        }
                        if (!empty($imagePaths)) {
                            $imagePath = $imagePaths[0];
                        }
                    }
                    
                    $qualityOptionsData[$quality] = [
                        'price' => $price,
                        'weight' => isset($rawOptions[$quality]['weight']) && $rawOptions[$quality]['weight'] !== '' ? (float) $rawOptions[$quality]['weight'] : null,
                        'weight_unit' => $rawOptions[$quality]['weight_unit'] ?? 'g',
                        'image_path' => $imagePath,
                        'image_paths' => $imagePaths,
                    ];
                }
            }
        }

        $actualSourcingLocation = strtolower($validated['actual_sourcing_location'] ?? $sourcingRequest->sourcing_location ?? 'china');

        $quotation = Quotation::create([
            'sourcing_request_id' => $validated['sourcing_request_id'],
            'assigned_to_admin_id' => $sourcingRequest->assigned_to_admin_id, // Inherit assignment from request
            'amount' => $amount,
            'unit_price' => $effectiveUnitPrice,
            'commission_service' => $validated['commission_service'],
            'delivery_cost_china' => $validated['delivery_cost_china'],
            'currency' => $validated['currency'],
            'status' => 'pending', // Default status
            'actual_sourcing_location' => $actualSourcingLocation,
            // Financial estimation fields
            'estimated_product_cost' => $estimatedProductCostTotal,
            'estimated_shipping_cost' => $validated['estimated_shipping_cost'] ?? null,
            'estimated_other_costs' => $validated['estimated_other_costs'] ?? null,
            'sourcing_note' => $validated['sourcing_note'] ?? null,
            'comments' => $validated['comments'] ?? null,
            'real_product_image' => $realProductImagePath,
            'supplier_url' => $validated['supplier_url'] ?? null,
            'quality_options' => !empty($qualityOptionsData) ? $qualityOptionsData : null,
            // estimated_net_profit will be calculated by QuotationObserver
        ]);



        event(new \App\Events\QuotationCreated($quotation));

        // Notify client if sourcing location differs from requested
        if ($quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location) {
            $sourcingRequest->user->notify(new \App\Notifications\AlternativeSourcingNotification($quotation));
        }

        return redirect()->route('admin.dashboard')->with('status', 'Quotation created successfully!');
    }

    public function show(Quotation $quotation): View
    {
        $this->authorize('view', $quotation);

        $quotation->load('sourcingRequest.user', 'sourcingRequest.destinations.country');

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

        try {
            Log::info('Quotation update started', ['quotation_id' => $quotation->id, 'user_id' => Auth::id()]);

            $validated = $request->validate([
                'unit_price' => 'required|numeric|min:0',
                'commission_service' => 'required|numeric|min:0',
                'delivery_cost_china' => 'required|numeric|min:0',
                'currency' => 'required|string|max:3',
                'actual_sourcing_location' => 'nullable|string|in:china,dubai',
                'sourcing_note' => 'nullable|string',
                'comments' => 'nullable|string',
                'admin_negotiation_reply' => 'nullable|string|max:1000',
                'estimated_product_cost' => 'nullable|numeric|min:0',
                'estimated_shipping_cost' => 'nullable|numeric|min:0',
                'estimated_other_costs' => 'nullable|numeric|min:0',
                'real_product_image' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
                'supplier_url' => 'nullable|url|max:2048',


                'quality_options' => 'nullable|array',
                'quality_options.low.price' => 'nullable|numeric|min:0',
                'quality_options.medium.price' => 'nullable|numeric|min:0',
                'quality_options.good.price' => 'nullable|numeric|min:0',
                'quality_options.*.weight' => 'required_with:quality_options.*.price|numeric|min:0',
                'quality_options.*.weight_unit' => 'required_with:quality_options.*.weight|string|in:g,kg,colis',
                'quality_options_images' => 'nullable|array',
                'quality_options_images.low' => 'nullable|array',
                'quality_options_images.low.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
                'quality_options_images.medium' => 'nullable|array',
                'quality_options_images.medium.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
                'quality_options_images.good' => 'nullable|array',
                'quality_options_images.good.*' => 'nullable|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:20480',
            ], [
                'supplier_url.url' => __('Veuillez entrer une URL valide pour le lien fournisseur.'),
                'real_product_image.max' => __('L\'image ou la vidéo ne doit pas dépasser 20 MB.'),
                'real_product_image.mimes' => __('Le fichier doit être une image (JPEG, PNG, GIF) ou une vidéo (MP4, MOV, AVI).'),

            ]);

            $sourcingRequest = $quotation->sourcingRequest;

            if (!$sourcingRequest) {
                Log::error('Sourcing request not found for quotation', ['quotation_id' => $quotation->id]);
                return redirect()->route('admin.quotations.index')
                    ->withErrors(['generic' => __('La demande d\'approvisionnement associée n\'existe plus.')]);
            }

            $totalQuantity = $sourcingRequest->destinations->sum('quantity');

            $effectiveUnitPrice = (float) $validated['unit_price'];
            if ($effectiveUnitPrice == 0 && $request->has('quality_options')) {
                $rawOptions = $request->input('quality_options');
                foreach (['medium', 'good', 'low'] as $quality) {
                    if (isset($rawOptions[$quality]['price']) && $rawOptions[$quality]['price'] !== '') {
                        $effectiveUnitPrice = (float) $rawOptions[$quality]['price'];
                        break;
                    }
                }
            }

            $subtotal = $effectiveUnitPrice * $totalQuantity;
            $amount = $subtotal + $validated['commission_service'] + $validated['delivery_cost_china'];

            Log::info('Quotation update calculation', [
                'quotation_id' => $quotation->id,
                'unit_price' => $effectiveUnitPrice,
                'quantity' => $totalQuantity,
                'subtotal' => $subtotal,
                'amount' => $amount,
            ]);

            $estimatedProductCostTotal = null;
            if (isset($validated['estimated_product_cost'])) {
                $estimatedProductCostTotal = $validated['estimated_product_cost'] * $totalQuantity;
            }

            $realProductImagePath = $quotation->real_product_image;
            if ($request->hasFile('real_product_image')) {
                if ($realProductImagePath && Storage::disk('public')->exists($realProductImagePath)) {
                    Storage::disk('public')->delete($realProductImagePath);
                }
                $file = $request->file('real_product_image');
                if (str_starts_with($file->getMimeType(), 'video/')) {
                    $realProductImagePath = $file->store('quotations/real_images', 'public');
                } else {
                    $result = $this->imageService->compressAndStore(
                        $file,
                        'quotations/real_images',
                        'public',
                        1200,
                        80
                    );
                    $realProductImagePath = $result->path;
                }
            }

            // Process Quality Pricing Options for Update
            $qualityOptionsData = [];
            if ($request->has('quality_options')) {
                $rawOptions = $request->input('quality_options');
                $existingOptions = $quotation->quality_options ?? [];
                
                foreach (['low', 'medium', 'good'] as $quality) {
                    if (isset($rawOptions[$quality]['price']) && $rawOptions[$quality]['price'] !== '') {
                        $price = (float) $rawOptions[$quality]['price'];
                        
                        $imagePath = $existingOptions[$quality]['image_path'] ?? null;
                        $imagePaths = $existingOptions[$quality]['image_paths'] ?? ($imagePath ? [$imagePath] : []);
                        
                        // Handle deletions
                        if ($request->has("delete_quality_images.{$quality}")) {
                            $deletedPaths = $request->input("delete_quality_images.{$quality}");
                            if (!is_array($deletedPaths)) {
                                $deletedPaths = [$deletedPaths];
                            }
                            foreach ($deletedPaths as $dPath) {
                                if (in_array($dPath, $imagePaths)) {
                                    if (Storage::disk('public')->exists($dPath)) {
                                        Storage::disk('public')->delete($dPath);
                                    }
                                    $imagePaths = array_diff($imagePaths, [$dPath]);
                                }
                            }
                            $imagePaths = array_values($imagePaths); // Re-index array
                        }

                        if ($request->hasFile("quality_options_images.{$quality}")) {
                            $files = $request->file("quality_options_images.{$quality}");
                            if (!is_array($files)) {
                                $files = [$files];
                            }
                            foreach ($files as $file) {
                                if (str_starts_with($file->getMimeType(), 'video/')) {
                                    $path = $file->store('quotations/quality', 'public');
                                    if ($path) {
                                        $imagePaths[] = $path;
                                    }
                                } else {
                                    $result = $this->imageService->compressAndStore(
                                        $file,
                                        'quotations/quality',
                                        'public',
                                        1200,
                                        80
                                    );
                                    if ($result->path) {
                                        $imagePaths[] = $result->path;
                                    }
                                }
                            }
                        }
                        
                        $imagePath = !empty($imagePaths) ? $imagePaths[0] : null;
                        
                        $qualityOptionsData[$quality] = [
                            'price' => $price,
                            'weight' => isset($rawOptions[$quality]['weight']) && $rawOptions[$quality]['weight'] !== '' ? (float) $rawOptions[$quality]['weight'] : null,
                            'weight_unit' => $rawOptions[$quality]['weight_unit'] ?? 'g',
                            'image_path' => $imagePath,
                            'image_paths' => $imagePaths,
                        ];
                    } else {
                        // Price is empty, delete the old files if any
                        $imagePath = $existingOptions[$quality]['image_path'] ?? null;
                        $imagePaths = $existingOptions[$quality]['image_paths'] ?? ($imagePath ? [$imagePath] : []);
                        foreach ($imagePaths as $path) {
                            if (Storage::disk('public')->exists($path)) {
                                Storage::disk('public')->delete($path);
                            }
                        }
                    }
                }
            }

            $actualSourcingLocation = strtolower($validated['actual_sourcing_location'] ?? $sourcingRequest->sourcing_location ?? 'china');

            $quotation->update([
                'amount' => $amount,
                'unit_price' => $effectiveUnitPrice,
                'commission_service' => $validated['commission_service'],
                'delivery_cost_china' => $validated['delivery_cost_china'],
                'currency' => $validated['currency'],
                'status' => 'pending',
                'actual_sourcing_location' => $actualSourcingLocation,
                'estimated_product_cost' => $estimatedProductCostTotal,
                'estimated_shipping_cost' => $validated['estimated_shipping_cost'] ?? null,
                'estimated_other_costs' => $validated['estimated_other_costs'] ?? null,
                'sourcing_note' => $validated['sourcing_note'] ?? null,
                'comments' => $validated['comments'] ?? null,
                'admin_negotiation_reply' => $validated['admin_negotiation_reply'] ?? null,
                'real_product_image' => $realProductImagePath,
                'supplier_url' => $validated['supplier_url'] ?? null,
                'quality_options' => !empty($qualityOptionsData) ? $qualityOptionsData : null,
            ]);

            if ($quotation->wasChanged('actual_sourcing_location') && $quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location) {
                if ($sourcingRequest->user) {
                    $sourcingRequest->user->notify(new \App\Notifications\AlternativeSourcingNotification($quotation));
                }
            }



            // Transition sourcing request back to quoted if it was negotiating
            if ($sourcingRequest->status === 'negotiating') {
                try {
                    $sourcingRequest->transitionTo('quoted');
                } catch (\Throwable $e) {
                    Log::warning('Could not transition sourcing request status', [
                        'sourcing_request_id' => $sourcingRequest->id,
                        'error' => $e->getMessage(),
                    ]);
                    return redirect()->route('admin.sourcing-requests.show', $sourcingRequest)
                        ->withErrors(['generic' => $e->getMessage()])
                        ->with('error', __('Impossible de mettre à jour le statut de la demande.'));
                }
            }

            Log::info('Quotation updated successfully', ['quotation_id' => $quotation->id]);

            return redirect()->route('admin.sourcing-requests.show', $sourcingRequest)
                ->with('status', __('Devis mis à jour et renvoyé au client.'));
        } catch (\Throwable $e) {
            Log::error('Quotation update failed', [
                'quotation_id' => $quotation->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('admin.quotations.edit', $quotation)
                ->withInput()
                ->with('error', __('Une erreur est survenue lors de la mise à jour du devis. Veuillez réessayer ou contacter le support.'));
        }
    }

    public function reject(Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $quotation->update(['status' => 'rejected']);

        event(new \App\Events\QuotationRejected($quotation));

        return redirect()->route('admin.quotations.show', $quotation)->with('status', 'Quotation rejected successfully!');
    }
}
