<?php

namespace App\Livewire\Admin;

use App\Events\SourcingOrderStatusChanged;
use App\Models\ShippingCompany;
use App\Models\SourcingOrder;
use App\Models\SourcingOrderDestinationShipment;
use App\Models\User;
use App\Notifications\TrackingNumberAdded;
use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SourcingOrderWorkflow extends Component
{
    public SourcingOrder $sourcingOrder;

    public string $status = '';

    public ?string $tracking_number = null;

    public ?string $tracking_carrier = null;

    public ?int $shipping_company_id = null;

    /** @var array<int, array{tracking_number: string, tracking_carrier: string, shipping_company_id: ?int}> Per-destination tracking when order has multiple destinations */
    public array $destinationTrackings = [];

    public $deepTrackingResult = null;

    /** @var array<int, array> Per-destination deep tracking results keyed by destination index */
    public array $deepTrackingResults = [];

    public function mount(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder->load(['quotation.sourcingRequest.destinations.country', 'destinationShipments', 'shippingCompany']);
        $this->status = $sourcingOrder->status;
        $this->tracking_number = $sourcingOrder->tracking_number;
        $this->tracking_carrier = $sourcingOrder->tracking_carrier;
        $this->shipping_company_id = $sourcingOrder->shipping_company_id;

        if ($this->sourcingOrder->hasMultipleDestinations()) {
            foreach ($this->sourcingOrder->quotation->sourcingRequest->destinations as $dest) {
                $shipment = $this->sourcingOrder->destinationShipments->firstWhere('sourcing_request_destination_id', $dest->id);
                $this->destinationTrackings[$dest->id] = [
                    'tracking_number' => $shipment?->tracking_number ?? '',
                    'tracking_carrier' => $shipment?->tracking_carrier ?? '',
                    'shipping_company_id' => $shipment?->shipping_company_id,
                ];
            }
        }
    }

    public function updateStatus()
    {
        if (! auth()->user()->isSuperAdmin() && $this->sourcingOrder->assigned_to_admin_id !== auth()->id()) {
            $this->dispatch('show-error-toast', message: __('You are not authorized to update this order.'));

            return;
        }

        if ($this->status === $this->sourcingOrder->status) {
            return;
        }

        if (! $this->sourcingOrder->canTransitionTo($this->status)) {
            $this->dispatch('show-error-toast', message: __('Invalid status transition.'));
            // Reset status to current
            $this->status = $this->sourcingOrder->status;

            return;
        }

        $this->sourcingOrder->update(['status' => $this->status]);
        $this->sourcingOrder->refresh();

        event(new SourcingOrderStatusChanged($this->sourcingOrder));

        $this->dispatch('show-success-toast', message: __('Status updated successfully!'));
    }

    public function updateTracking()
    {
        if (! auth()->user()->isSuperAdmin() && $this->sourcingOrder->assigned_to_admin_id !== auth()->id()) {
            $this->dispatch('show-error-toast', message: __('You are not authorized to update this order.'));

            return;
        }

        $this->tracking_number = trim((string) $this->tracking_number);
        $this->tracking_carrier = trim((string) $this->tracking_carrier);

        if ($this->sourcingOrder->hasMultipleDestinations() === false) {
            // A tracking number never contains whitespace; strip it so a pasted
            // tab/space prefix can't corrupt the number sent to the scraper.
            $this->tracking_number = preg_replace('/\s+/', '', (string) $this->tracking_number) ?? '';
        }

        $updateData = [
            'tracking_number' => $this->tracking_number,
            'tracking_carrier' => $this->tracking_carrier,
        ];

        // If a real tracking number is being assigned for the first time, record the timestamp
        if ($this->tracking_number && !$this->sourcingOrder->hasRealTracking()) {
            $updateData['real_tracking_assigned_at'] = now();
            Log::info('🎯 [FSB TRACKING] Real tracking number assigned to FSB', [
                'order_id' => $this->sourcingOrder->id,
                'fsb_number' => $this->sourcingOrder->fsb_tracking_number,
                'real_tracking' => $this->tracking_number,
            ]);
        }

        $this->sourcingOrder->update($updateData);

        $this->sourcingOrder->refresh();

        Cache::forget("tracking:{$this->sourcingOrder->fsb_tracking_number}");

        if ($this->sourcingOrder->tracking_number && $this->sourcingOrder->user) {
            // Remove FSB tracking notification so client only sees the real tracking one
            $this->sourcingOrder->user->notifications()
                ->where('type', \App\Notifications\FsbTrackingGenerated::class)
                ->whereJsonContains('data->sourcing_order_id', $this->sourcingOrder->id)
                ->delete();

            $this->sourcingOrder->user->notify(new TrackingNumberAdded($this->sourcingOrder));
        }

        $this->dispatch('show-success-toast', message: __('Tracking information updated successfully!'));
    }

    public function fetchTrackingStatus(UnifiedTrackingService $trackingService)
    {
        set_time_limit(180);

        if ($this->sourcingOrder->hasMultipleDestinations()) {
            $this->fetchMultiDestinationTracking($trackingService);
            return;
        }

        if (! $this->tracking_number) {
            $this->dispatch('show-error-toast', message: __('Veuillez entrer un numéro de suivi.'));
            return;
        }

        try {
            $carrier = $this->tracking_carrier ?: ($this->sourcingOrder->shippingCompany?->name ?? null);
            $result = $trackingService->refreshTracking($this->tracking_number, $carrier);

            $this->deepTrackingResult = $result;

            if ($result['success']) {
                $this->dispatch('show-success-toast', message: __('Statut de suivi récupéré avec succès.'));
            } elseif (($result['status'] ?? '') === 'pending') {
                $this->dispatch('show-success-toast', message: __('Actualisation en cours. Veuillez patienter quelques instants...'));
            } else {
                $this->dispatch('show-error-toast', message: __('Erreur: ').($result['error'] ?? 'Inconnue'));
            }
        } catch (\Exception $e) {
            Log::error('Deep Tracking Error: '.$e->getMessage());
            $this->dispatch('show-error-toast', message: __('Une erreur est survenue lors du tracking.'));
        }
    }

    protected function fetchMultiDestinationTracking(UnifiedTrackingService $trackingService): void
    {
        $destinations = $this->sourcingOrder->quotation->sourcingRequest->destinations;
        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($destinations as $dest) {
            $data = $this->destinationTrackings[$dest->id] ?? [];
            $trackingNumber = trim($data['tracking_number'] ?? '');
            $carrier = trim($data['tracking_carrier'] ?? '');

            if (empty($trackingNumber)) {
                $results[$dest->id] = [
                    'success' => false,
                    'error' => __('No tracking number entered for this destination.'),
                    'dest_label' => $dest->country?->name ?? __('Destination #:n', ['n' => $dest->id]),
                ];
                continue;
            }

            try {
                $result = $trackingService->refreshTracking($trackingNumber, $carrier ?: null);

                // Validation: vérifier que le provider détecté est compatible avec la shipping company assignée à cette destination
                $shippingCompany = $this->sourcingOrder->getShippingCompanyForDestination($dest->id);
                $rawProvider = strtolower($result['raw_provider'] ?? '');
                if ($shippingCompany) {
                    $allowedProviders = $this->getAllowedProvidersForShippingCompany($shippingCompany);
                    if (! empty($allowedProviders) && $rawProvider && ! in_array($rawProvider, $allowedProviders, true)) {
                        $results[$dest->id] = [
                            'success' => false,
                            'error' => __('Tracking number is not valid for :company (detected carrier: :carrier).', [
                                'company' => $shippingCompany->name,
                                'carrier' => $result['provider'] ?? $rawProvider,
                            ]),
                            'dest_label' => $dest->country?->name ?? __('Destination #:n', ['n' => $dest->id]),
                        ];
                        $errorCount++;
                        continue;
                    }
                }

                $result['dest_label'] = $dest->country?->name ?? __('Destination #:n', ['n' => $dest->id]);
                $results[$dest->id] = $result;

                if ($result['success'] ?? false) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                Log::error('Deep Tracking Error (multi-dest): '.$e->getMessage(), [
                    'destination_id' => $dest->id,
                    'tracking_number' => $trackingNumber,
                ]);
                $results[$dest->id] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'dest_label' => $dest->country?->name ?? __('Destination #:n', ['n' => $dest->id]),
                ];
                $errorCount++;
            }
        }

        $this->deepTrackingResults = $results;

        if ($successCount > 0 && $errorCount === 0) {
            $this->dispatch('show-success-toast', message: __('Tracking retrieved for all :count destination(s).', ['count' => $successCount]));
        } elseif ($successCount > 0) {
            $this->dispatch('show-success-toast', message: __(':ok of :total destination(s) tracked successfully.', ['ok' => $successCount, 'total' => $successCount + $errorCount]));
        } else {
            $this->dispatch('show-error-toast', message: __('Could not retrieve tracking for any destination.'));
        }
    }

    /**
     * Providers autorisés pour une shipping company donnée (en fonction de ses carrier_options).
     *
     * Exemple :
     *  - gcc  → faster
     *  - ups  → ups
     *  - choicexp → choicexp
     *  - itdida → itdida
     */
    protected function getAllowedProvidersForShippingCompany(?ShippingCompany $company): array
    {
        if (! $company) {
            return [];
        }

        $options = $company->carrier_options ?? [];
        if (! is_array($options) || empty($options)) {
            return [];
        }

        $map = [
            'gcc' => 'faster',
            'faster' => 'faster',
            'ups' => 'ups',
            'choicexp' => 'choicexp',
            'itdida' => 'itdida',
        ];

        $allowed = [];
        foreach ($options as $key) {
            if (isset($map[$key])) {
                $allowed[] = $map[$key];
            }
        }

        return array_values(array_unique($allowed));
    }

    public function assignTo($adminId)
    {
        if (! auth()->user()->isSuperAdmin()) {
            $this->dispatch('show-error-toast', message: __('Only Super Admins can reassign orders.'));

            return;
        }

        $this->sourcingOrder->update([
            'assigned_to_admin_id' => $adminId ?: null,
        ]);

        $this->sourcingOrder->refresh();

        $this->dispatch('show-success-toast', message: __($adminId ? 'Order assigned successfully.' : 'Order unassigned.'));
    }

    public function assignShippingCompany($companyId)
    {
        if (! auth()->user()->isSuperAdmin() && $this->sourcingOrder->assigned_to_admin_id !== auth()->id()) {
            $this->dispatch('show-error-toast', message: __('You are not authorized to assign shipping companies.'));

            return;
        }

        $company = $companyId ? ShippingCompany::find($companyId) : null;

        // If company has child carriers (e.g. YNPS → GCC, UPS), leave carrier for admin to choose; otherwise set to company name
        $newCarrier = null;
        if ($company) {
            $newCarrier = $company->getCarrierOptionsWithLabels() !== [] ? null : $company->name;
        } else {
            $newCarrier = $this->tracking_carrier;
        }

        $this->sourcingOrder->update([
            'shipping_company_id' => $companyId ?: null,
            'tracking_carrier' => $newCarrier,
        ]);

        $this->sourcingOrder->refresh();
        $this->sourcingOrder->load('shippingCompany');
        $this->shipping_company_id = $this->sourcingOrder->shipping_company_id;
        $this->tracking_carrier = $this->sourcingOrder->tracking_carrier;

        // Invalidate cached tracking for main FSB + per-destination FSBs
        Cache::forget("tracking:{$this->sourcingOrder->fsb_tracking_number}");
        if ($this->sourcingOrder->hasMultipleDestinations()) {
            foreach ($this->sourcingOrder->quotation->sourcingRequest->destinations as $index => $dest) {
                $fsbNumber = $this->sourcingOrder->getFsbTrackingNumberForDestinationIndex($index);
                Cache::forget("tracking:{$fsbNumber}");
            }
        }

        $this->dispatch('show-success-toast', message: __($companyId ? 'Shipping company assigned successfully.' : 'Shipping company unassigned.'));
    }

    public function saveDestinationTrackings()
    {
        if (! $this->sourcingOrder->hasMultipleDestinations()) {
            return;
        }
        if (! auth()->user()->isSuperAdmin() && $this->sourcingOrder->assigned_to_admin_id !== auth()->id()) {
            $this->dispatch('show-error-toast', message: __('You are not authorized to update this order.'));

            return;
        }

        $destinations = $this->sourcingOrder->quotation->sourcingRequest->destinations;
        foreach ($destinations as $dest) {
            $data = $this->destinationTrackings[$dest->id] ?? null;
            if (! is_array($data)) {
                continue;
            }
            SourcingOrderDestinationShipment::updateOrCreate(
                [
                    'sourcing_order_id' => $this->sourcingOrder->id,
                    'sourcing_request_destination_id' => $dest->id,
                ],
                [
                    'tracking_number' => $data['tracking_number'] ?? '',
                    'tracking_carrier' => $data['tracking_carrier'] ?? '',
                    'shipping_company_id' => ! empty($data['shipping_company_id']) ? (int) $data['shipping_company_id'] : null,
                ]
            );
        }

        $this->sourcingOrder->load('destinationShipments');

        // Invalidate cached tracking results for each per-destination FSB + the main FSB
        foreach ($destinations as $index => $dest) {
            $fsbNumber = $this->sourcingOrder->getFsbTrackingNumberForDestinationIndex($index);
            Cache::forget("tracking:{$fsbNumber}");
            Cache::forget("tracking_blocked:{$fsbNumber}");
            Cache::forget("tracking_pending:{$fsbNumber}");
        }
        Cache::forget("tracking:{$this->sourcingOrder->fsb_tracking_number}");

        $this->dispatch('show-success-toast', message: __('Tracking per destination updated successfully.'));
    }

    public function render()
    {
        $selectedCompany = $this->sourcingOrder->shipping_company_id
            ? rescue(fn () => ShippingCompany::findOrFail($this->sourcingOrder->shipping_company_id), null)
            : null;
        $carrierOptionsForCompany = $selectedCompany ? $selectedCompany->getCarrierOptionsWithLabels() : [];

        return view('livewire.admin.sourcing-order-workflow', [
            'admins' => rescue(static fn () => User::where('role', 'admin')->orderBy('name')->get(), collect()),
            'shippingCompanies' => rescue(static fn () => ShippingCompany::where('is_active', true)->orderBy('name')->get(), collect()),
            'carrierOptionsForCompany' => $carrierOptionsForCompany,
        ]);
    }
}
