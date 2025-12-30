<?php

namespace App\Livewire\Admin;

use App\Events\SourcingOrderStatusChanged;
use App\Models\ShippingCompany;
use App\Models\SourcingOrder;
use App\Models\User;
use Livewire\Component;

class SourcingOrderWorkflow extends Component
{
    public SourcingOrder $sourcingOrder;

    public string $status;

    public ?string $tracking_number;

    public ?string $tracking_carrier;

    public ?int $shipping_company_id;

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

        $this->sourcingOrder->update([
            'tracking_number' => $this->tracking_number,
            'tracking_carrier' => $this->tracking_carrier,
        ]);

        $this->sourcingOrder->refresh();

        $this->dispatch('show-success-toast', message: __('Tracking information updated successfully!'));
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

        $this->sourcingOrder->update([
            'shipping_company_id' => $companyId ?: null,
        ]);

        $this->sourcingOrder->refresh();
        $this->shipping_company_id = $this->sourcingOrder->shipping_company_id;

        $this->dispatch('show-success-toast', message: __($companyId ? 'Shipping company assigned successfully.' : 'Shipping company unassigned.'));
    }

    public function render()
    {
        return view('livewire.admin.sourcing-order-workflow', [
            'admins' => User::where('role', 'admin')->orderBy('name')->get(),
            'shippingCompanies' => ShippingCompany::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
