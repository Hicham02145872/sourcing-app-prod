<?php

namespace App\Livewire\Admin;

use App\Events\SourcingOrderStatusChanged;
use App\Models\ShippingCompany;
use App\Models\SourcingOrder;
use App\Models\User;
use App\Notifications\TrackingNumberAdded;
use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SourcingOrderWorkflow extends Component
{
    public SourcingOrder $sourcingOrder;

    public string $status = '';

    public ?string $tracking_number = null;

    public ?string $tracking_carrier = null;

    public ?int $shipping_company_id = null;

    public $deepTrackingResult = null;

    public function mount(SourcingOrder $sourcingOrder)
    {
        $this->sourcingOrder = $sourcingOrder;
        $this->status = $sourcingOrder->status;
        $this->tracking_number = $sourcingOrder->tracking_number;
        $this->tracking_carrier = $sourcingOrder->tracking_carrier;
        $this->shipping_company_id = $sourcingOrder->shipping_company_id;
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
        set_time_limit(120);
        if (! $this->tracking_number) {
            $this->dispatch('show-error-toast', message: __('Veuillez entrer un numéro de suivi.'));

            return;
        }

        try {
            $carrier = $this->tracking_carrier ?: ($this->sourcingOrder->shippingCompany?->name ?? null);
            $result = $trackingService->track($this->tracking_number, $carrier);

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

        $this->dispatch('show-success-toast', message: __($companyId ? 'Shipping company assigned successfully.' : 'Shipping company unassigned.'));
    }

    public function render()
    {
        $selectedCompany = $this->sourcingOrder->shipping_company_id
            ? ShippingCompany::find($this->sourcingOrder->shipping_company_id)
            : null;
        $carrierOptionsForCompany = $selectedCompany ? $selectedCompany->getCarrierOptionsWithLabels() : [];

        return view('livewire.admin.sourcing-order-workflow', [
            'admins' => User::where('role', 'admin')->orderBy('name')->get(),
            'shippingCompanies' => ShippingCompany::where('is_active', true)->orderBy('name')->get(),
            'carrierOptionsForCompany' => $carrierOptionsForCompany,
        ]);
    }
}
