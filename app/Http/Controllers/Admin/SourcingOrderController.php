<?php

namespace App\Http\Controllers\Admin;

use App\Events\SourcingOrderStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\SourcingOrder;
use App\Models\SourcingOrderMedia;
use App\Notifications\ProofOfPaymentRejected;
use App\Services\GoogleSheetService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SourcingOrderController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', SourcingOrder::class);
        $query = SourcingOrder::with('user', 'quotation.sourcingRequest', 'assignedAdmin');

        // Scope visibility: Regular admins now see ALL orders (read-only for others)
        // Apply admin-priority ordering first so it is a secondary key after pending_payment prioritization
        if (auth()->check()) {
            $userId = auth()->id();
            $query->orderByRaw('CASE 
                WHEN assigned_to_admin_id = ? THEN 1 
                WHEN assigned_to_admin_id IS NULL THEN 2 
                ELSE 3 
            END', [$userId]);
        }

        // Prioritize by full payment/shipping lifecycle so orders are shown in natural progression
        $query->orderByRaw("CASE 
                WHEN sourcing_orders.status = 'pending_payment' THEN 0
                WHEN sourcing_orders.status = 'paid' THEN 1
                WHEN sourcing_orders.status = 'shipment_preparing' THEN 2
                WHEN sourcing_orders.status = 'in_transit_china' THEN 3
                WHEN sourcing_orders.status = 'arrival_uae' THEN 4
                WHEN sourcing_orders.status = 'customs_clearance_uae' THEN 5
                WHEN sourcing_orders.status = 'in_transit_uae' THEN 6
                WHEN sourcing_orders.status = 'arrival_destination_country' THEN 7
                WHEN sourcing_orders.status = 'customs_clearance_destination_country' THEN 8
                WHEN sourcing_orders.status = 'out_for_delivery' THEN 9
                WHEN sourcing_orders.status = 'delivered' THEN 10
                WHEN sourcing_orders.status = 'order_completed' THEN 11
                WHEN sourcing_orders.status = 'delivery_failed' THEN 12
                WHEN sourcing_orders.status = 'shipment_delayed' THEN 13
                WHEN sourcing_orders.status = 'shipment_returned' THEN 14
                WHEN sourcing_orders.status = 'shipment_canceled' THEN 15
                WHEN sourcing_orders.status = 'waiting_for_refund' THEN 16
                WHEN sourcing_orders.status = 'refund_approved' THEN 17
                WHEN sourcing_orders.status = 'refunded' THEN 18
                WHEN sourcing_orders.status = 'refund_rejected' THEN 19
                ELSE 999
            END")
            ->orderBy('id', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by search term
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%'.$search.'%')
                    ->orWhere('shared_id', 'like', '%'.$search.'%')
                    ->orWhere('sourcing_request_id', 'like', '%'.$search.'%')
                    ->orWhere('quotation_id', 'like', '%'.$search.'%')
                    ->orWhereRaw("CAST((sourcing_orders.id * 5) AS CHAR) LIKE ?", ['%'.$search.'%'])
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('quotation.sourcingRequest', function ($srQuery) use ($search) {
                        $srQuery->where('product_name', 'like', '%'.$search.'%')
                            ->orWhere('id', 'like', '%'.$search.'%')
                            ->orWhere('note', 'like', '%'.$search.'%')
                            ->orWhereHas('destinations', function ($destQuery) use ($search) {
                                $destQuery->where('address', 'like', '%'.$search.'%')
                                    ->orWhereHas('country', function ($countryQuery) use ($search) {
                                        $countryQuery->where('name', 'like', '%'.$search.'%');
                                    });
                            });
                    })
                    ->orWhereHas('assignedAdmin', function ($adminQuery) use ($search) {
                        $adminQuery->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        // Filter by admin (Super Admin only)
        if (auth()->user()->isSuperAdmin() && $request->has('admin_id') && $request->admin_id != 'all') {
            if ($request->admin_id == 'unassigned') {
                $query->whereNull('assigned_to_admin_id');
            } else {
                $query->where('assigned_to_admin_id', $request->admin_id);
            }
        }

        $sourcingOrders = $query->paginate(10);
        $admins = \App\Models\User::where('role', 'admin')->get();

        return view('admin.sourcing-orders.index', compact('sourcingOrders', 'admins'));
    }

    public function show(SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);
        $sourcingOrder->load('user', 'media', 'sourcingRequest', 'quotation.sourcingRequest.category', 'quotation.sourcingRequest.destinations.country', 'quotation.sourcingRequest.destinations.service');

        return view('admin.sourcing-orders.show', compact('sourcingOrder'));
    }

    public function showShippingLabel(SourcingOrder $sourcingOrder)
    {
        $this->authorize('view', $sourcingOrder);

        if (request()->query('format') === 'html') {
            return view('admin.sourcing-orders.shipping-label', compact('sourcingOrder'));
        }

        $pdf = Pdf::loadView('admin.sourcing-orders.shipping-label', compact('sourcingOrder'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('shipping-label-'.$sourcingOrder->id.'.pdf');
    }

    public function showShippingLabelForDestination(SourcingOrder $sourcingOrder, \App\Models\SourcingRequestDestination $destination)
    {
        $this->authorize('view', $sourcingOrder);

        if (request()->query('format') === 'html') {
            return view('admin.sourcing-orders.shipping-label-destination', compact('sourcingOrder', 'destination'));
        }

        $pdf = Pdf::loadView('admin.sourcing-orders.shipping-label-destination', compact('sourcingOrder', 'destination'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('shipping-label-'.$sourcingOrder->id.'-dest-'.$destination->id.'.pdf');
    }

    public function downloadProofOfPayment(SourcingOrder $sourcingOrder)
    {
        $this->authorize('view', $sourcingOrder);
        if (! $sourcingOrder->proof_of_payment_path) {
            abort(404);
        }

        $path = Storage::disk('local')->path($sourcingOrder->proof_of_payment_path);
        Log::debug('Downloading proof of payment', ['path' => $path]);

        return Storage::disk('local')->download($sourcingOrder->proof_of_payment_path);
    }

    public function updateStatus(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('update', $sourcingOrder);
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(SourcingOrder::STATUSES)],
        ]);

        if (! $sourcingOrder->canTransitionTo($validated['status'])) {
            Log::warning('Invalid status transition attempt', [
                'order_id' => $sourcingOrder->id,
                'current_status' => $sourcingOrder->status,
                'requested_status' => $validated['status'],
                'can_transition' => $sourcingOrder->canTransitionTo($validated['status']),
            ]);

            return back()->withErrors(['status' => 'Invalid status transition from '.$sourcingOrder->status.' to '.$validated['status'].'.']);
        }

        try {
            $sourcingOrder->update(['status' => $validated['status']]);

            // Eager-load relationships required by the notification
            $sourcingOrder->load('quotation.sourcingRequest', 'user');

            event(new SourcingOrderStatusChanged($sourcingOrder));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Sourcing order status update failed', [
                'order_id' => $sourcingOrder->id,
                'exception' => $e,
            ]);

            return back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('Could not update order status.'));
        }

        return back()->with('status', 'Sourcing order status updated successfully!');
    }

    public function rejectProof(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('update', $sourcingOrder);
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ]);

        // Delete the old proof of payment file
        if ($sourcingOrder->proof_of_payment_path) {
            Storage::disk('local')->delete($sourcingOrder->proof_of_payment_path);
        }

        $sourcingOrder->update([
            'status' => 'pending_payment',
            'proof_of_payment_path' => null,
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $sourcingOrder->load('user'); // Eager load the user relationship
        $sourcingOrder->user->notify(new ProofOfPaymentRejected($sourcingOrder));

        return back()->with('status', 'Proof of payment rejected and client has been notified.');
    }

    /**
     * Manually sync a sourcing order to Google Sheets
     */
    public function syncToGoogleSheet($orderId): JsonResponse
    {
        $sourcingOrder = SourcingOrder::where('id', $orderId)
            ->orWhereRaw('id * 5 = ?', [$orderId])
            ->firstOrFail();

        $this->authorize('update', $sourcingOrder);

        try {
            // Check if already synced recently (within last hour)
            $cacheKey = "order_synced_to_sheet_{$sourcingOrder->id}";
            if (Cache::has($cacheKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette commande a déjà été synchronisée récemment. Veuillez réessayer plus tard.',
                ], 429);
            }

            // Load necessary relationships
            $sourcingOrder->load(['user', 'quotation.sourcingRequest', 'assignedAdmin']);

            // Verify required data exists
            if (! $sourcingOrder->quotation || ! $sourcingOrder->quotation->sourcingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de commande incomplètes. Impossible de synchroniser.',
                ], 422);
            }

            // Prepare data using model helper
            $data = $sourcingOrder->toGoogleSheetArray();

            // Initialize Google Sheets service and upsert row
            $googleSheetService = new GoogleSheetService;
            $googleSheetService->upsertRow($data, $sourcingOrder->id);

            // Mark as synced (cache for 1 hour)
            Cache::put($cacheKey, true, now()->addHour());

            Log::info("Sourcing Order {$sourcingOrder->id} manually synced (upsert) to Google Sheet.", [
                'order_id' => $sourcingOrder->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commande synchronisée (mise à jour) avec succès vers Google Sheets!',
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to manually upsert Sourcing Order {$sourcingOrder->id} to Google Sheet: ".$e->getMessage(), [
                'order_id' => $sourcingOrder->id,
                'exception' => $e,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la synchronisation: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Manually sync a sourcing order to the specialized Shipping Company Sheet (Google or Lark).
     */
    public function manualSyncToShippingCompanySheet(SourcingOrder $sourcingOrder): JsonResponse
    {
        $this->authorize('update', $sourcingOrder);

        $sourcingOrder->load(['shippingCompany', 'user', 'quotation.sourcingRequest.destinations', 'destinationShipments']);

        // Multi-destination: sync each destination to its assigned company's sheet
        if ($sourcingOrder->hasMultipleDestinations()) {
            return $this->manualSyncMultiDestinationOrder($sourcingOrder);
        }

        if (! $sourcingOrder->shipping_company_id) {
            return response()->json([
                'success' => false,
                'message' => __('No shipping company assigned to this order.'),
            ], 422);
        }

        $company = $sourcingOrder->shippingCompany;
        $factory = app(\App\Services\SheetIntegrationFactory::class);
        $service = $factory->getService($company);

        if (! $service) {
            return response()->json([
                'success' => false,
                'message' => __('The shipping company sheet is not configured. Please set up Google Sheet or Lark in Shipping Companies.'),
            ], 422);
        }

        try {
            $success = $service->syncOrder($sourcingOrder, $company);

            if (! $success) {
                throw new \RuntimeException('Sync returned false.');
            }

            $sourcingOrder->update([
                'sheet_synced_at' => now(),
                'sheet_sync_error' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Successfully synced to the shipping company sheet.'),
            ]);
        } catch (\Throwable $e) {
            Log::error("Manual shipping sheet sync failed for Order #{$sourcingOrder->id}: ".$e->getMessage());

            $userMessage = \App\Support\SheetSyncErrorHelper::toUserMessage($e);
            $sourcingOrder->update([
                'sheet_sync_error' => $userMessage,
            ]);

            return response()->json([
                'success' => false,
                'message' => $userMessage,
            ], 500);
        }
    }

    /**
     * Manual sync for orders with multiple destinations: sync each destination to its assigned company's sheet.
     */
    protected function manualSyncMultiDestinationOrder(SourcingOrder $sourcingOrder): JsonResponse
    {
        $destinationsById = $sourcingOrder->quotation->sourcingRequest->destinations->keyBy('id');
        $byCompany = $sourcingOrder->destinationShipments
            ->filter(fn ($s) => ! empty($s->shipping_company_id))
            ->groupBy('shipping_company_id');

        if ($byCompany->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('Assign at least one shipping company per destination, then sync.'),
            ], 422);
        }

        $factory = app(\App\Services\SheetIntegrationFactory::class);
        $lastError = null;
        $syncedCount = 0;

        foreach ($byCompany as $companyId => $shipments) {
            $company = \App\Models\ShippingCompany::find($companyId);
            if (! $company) {
                continue;
            }
            $service = $factory->getService($company);
            if (! $service) {
                $lastError = __('Sheet not configured for :name.', ['name' => $company->name]);
                continue;
            }

            $destinationIds = $shipments->pluck('sourcing_request_destination_id')->filter()->values();
            $destinations = $destinationIds->map(fn ($id) => $destinationsById->get($id))->filter()->values();

            if ($destinations->isEmpty()) {
                continue;
            }

            try {
                $success = $service->syncOrderDestinations($sourcingOrder, $company, $destinations);
                if ($success) {
                    $syncedCount++;
                } else {
                    $lastError = __('Sync failed for :name.', ['name' => $company->name]);
                }
            } catch (\Throwable $e) {
                Log::error("Manual multi-destination sync failed for Order #{$sourcingOrder->id} / {$company->name}: ".$e->getMessage());
                $lastError = \App\Support\SheetSyncErrorHelper::toUserMessage($e);
            }
        }

        if ($syncedCount > 0) {
            $sourcingOrder->update([
                'sheet_synced_at' => now(),
                'sheet_sync_error' => $lastError ? \App\Support\SheetSyncErrorHelper::toUserMessage(new \Exception($lastError)) : null,
            ]);

            return response()->json([
                'success' => true,
                'message' => $syncedCount === $byCompany->count()
                    ? __('Successfully synced to all shipping company sheets.')
                    : __('Synced to :count company sheet(s). :note', ['count' => $syncedCount, 'note' => $lastError ?? '']),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $lastError ?? __('Sync failed.'),
        ], 500);
    }

    public function updateFinancials(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        // Policy check: use viewFinancials or update. Since update is strictly admin, it's safe.
        // But let's be explicit and ensure only admins can hit this.
        $this->authorize('viewFinancials', $sourcingOrder);

        $validated = $request->validate([
            'product_cost_price' => ['nullable', 'numeric', 'min:0'],
            'shipping_cost_real' => ['nullable', 'numeric', 'min:0'],
            'rejection_loss_cost' => ['nullable', 'numeric', 'min:0'],
            'refund_amount' => ['nullable', 'numeric', 'min:0'],
            'refund_proof' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp,pdf', 'max:10240'],
            'cost_adjustment_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('refund_proof')) {
            $validated['refund_proof_path'] = $this->imageService->compressAndStore(
                $request->file('refund_proof'),
                'refund-proofs'
            );
        }

        $sourcingOrder->update($validated);
        // Observer will handle net_profit_or_loss calculation

        // Sync to Google Sheet immediately to reflect financial updates
        try {
            $googleSheetService = new \App\Services\GoogleSheetService;
            $googleSheetService->upsertRow($sourcingOrder->toGoogleSheetArray(), $sourcingOrder->id);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Immediate Google Sheet sync failed after financial update for Order #{$sourcingOrder->id}: ".$e->getMessage());
            // We don't fail the whole request because the DB update was successful.
        }

        return back()->with('status', 'Financial details updated successfully.');
    }

    public function updateTracking(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('update', $sourcingOrder);

        $validated = $request->validate([
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'tracking_carrier' => ['nullable', 'string', 'max:255'],
        ]);

        // When assigning a real tracking number, set real_tracking_assigned_at for auto-update eligibility
        if (! empty($validated['tracking_number']) && ! $sourcingOrder->hasRealTracking()) {
            $validated['real_tracking_assigned_at'] = now();
        }
        if (empty($validated['tracking_number'])) {
            $validated['real_tracking_assigned_at'] = null;
        }

        $sourcingOrder->update($validated);

        if (! empty($validated['tracking_number']) && $sourcingOrder->user) {
            // Remove FSB tracking notification so client only sees the real tracking one
            $sourcingOrder->user->notifications()
                ->where('type', \App\Notifications\FsbTrackingGenerated::class)
                ->whereJsonContains('data->sourcing_order_id', $sourcingOrder->id)
                ->delete();

            $sourcingOrder->user->notify(new \App\Notifications\TrackingNumberAdded($sourcingOrder));
        }

        return back()->with('status', 'Tracking information updated successfully.');
    }

    public function uploadMedia(Request $request, SourcingOrder $sourcingOrder)
    {
        $this->authorize('update', $sourcingOrder);

        $messages = [
            'files.*.uploaded' => 'Le fichier dépasse la limite du serveur ('.ini_get('upload_max_filesize').'). Modifiez php.ini (upload_max_filesize).',
            'files.*.max' => 'Le fichier ne doit pas dépasser 100Mo.',
            'files.*.mimes' => 'Format de fichier non supporté (Images et Vidéos uniquement).',
        ];

        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,mkv,webm|max:102400', // 100MB max
            'files' => 'required|array|min:1|max:10', // Max 10 files at once
        ], $messages);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Determine file type
                $mimeType = $file->getMimeType();
                $type = str_starts_with($mimeType, 'video') ? 'video' : 'image';

                // Store file (compressed if image)
                $path = $this->imageService->compressAndStore(
                    $file,
                    'sourcing-order-media'
                );

                // Create DB record
                $sourcingOrder->media()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $type,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Médias ajoutés avec succès.');
    }

    public function deleteMedia(SourcingOrderMedia $media)
    {
        // Check authorization (admin or owner of order via relationship)
        $this->authorize('update', $media->sourcingOrder);

        // Delete file from storage
        Storage::disk('public')->delete($media->file_path);

        // Delete record
        $media->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Média supprimé avec succès.']);
        }

        return redirect()->back()->with('success', 'Média supprimé avec succès.');
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('viewAny', SourcingOrder::class);

        $query = SourcingOrder::with('user', 'quotation.sourcingRequest.destinations', 'assignedAdmin')->orderBy('id', 'desc');

        // Scope visibility
        if (! auth()->user()->isSuperAdmin()) {
            $query->where(function ($q) {
                $q->where('assigned_to_admin_id', auth()->id())
                    ->orWhereNull('assigned_to_admin_id');
            });
        }

        // Apply filters to match the current view
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('quotation.sourcingRequest', function ($srQuery) use ($search) {
                        $srQuery->where('product_name', 'like', '%'.$search.'%');
                    });
            });
        }

        $sourcingOrders = $query->get();

        $pdf = Pdf::loadView('admin.sourcing-orders.pdf', compact('sourcingOrders'));
        $pdf->setPaper('a4', 'landscape'); // Landscape for better table fit

        return $pdf->download('sourcing_orders_'.date('Y-m-d_His').'.pdf');
    }

    public function duplicateLast(): RedirectResponse
    {
        $this->authorize('viewAny', SourcingOrder::class);

        $lastOrder = SourcingOrder::latest()->first();

        if (! $lastOrder) {
            return back()->with('error', 'No orders found to duplicate.');
        }

        // Duplicate the order
        $newOrder = $lastOrder->replicate();

        // Reset some fields for a clean test order
        $newOrder->status = 'paid'; // Set to paid so user can move it to shipment_preparing
        $newOrder->tracking_number = null;
        $newOrder->tracking_carrier = null;
        $newOrder->proof_of_payment_path = $lastOrder->proof_of_payment_path; // Keep payment proof if it exists for realism
        $newOrder->sheet_synced_at = null;
        $newOrder->sheet_sync_error = null;
        $newOrder->net_profit_or_loss = $lastOrder->net_profit_or_loss;

        $newOrder->save();

        Log::info("Order #{$lastOrder->id} duplicated to new Order #{$newOrder->id} for testing.", [
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.sourcing-orders.index')->with('success', "Order duplicated successfully! New Order Internal ID: {$newOrder->id} (Display ID: {$newOrder->display_id})");
    }

    public function destroy(SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('delete', $sourcingOrder);

        $sourcingOrder->delete();

        return redirect()->route('admin.sourcing-orders.index')->with('success', __('Order deleted successfully.'));
    }
}
