<?php

namespace App\Livewire\Admin;

use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SourcingRequestWorkflow extends Component
{
    public SourcingRequest $sourcingRequest;

    public function mount(SourcingRequest $sourcingRequest)
    {
        $this->sourcingRequest = $sourcingRequest;
    }

    public function updateStatus($status)
    {
        // Security check
        if (! $this->sourcingRequest->isAssignedTo(auth()->user()) && ! auth()->user()->isSuperAdmin()) {
            $this->dispatch('show-error-toast', message: __('You cannot modify a dossier that is not assigned to you.'));

            return;
        }

        if (! in_array($status, SourcingRequest::STATUSES)) {
            $this->dispatch('show-error-toast', message: __('Invalid status.'));

            return;
        }

        try {
            $this->sourcingRequest->transitionTo($status);
            $this->sourcingRequest->refresh();
            $this->dispatch('show-success-toast', message: __('Status updated successfully.'));
            // Refresh parent or other components if needed
            $this->dispatch('sourcing-request-updated');
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: $e->getMessage());
        }
    }

    public function release()
    {
        if (! $this->sourcingRequest->isAssignedTo(auth()->user()) && ! auth()->user()->isSuperAdmin()) {
            $this->dispatch('show-error-toast', message: __('You cannot release this dossier.'));

            return;
        }

        $this->sourcingRequest->update([
            'assigned_to_admin_id' => null,
            'assigned_at' => null,
        ]);

        $this->sourcingRequest->refresh();
        $this->dispatch('show-success-toast', message: __('Dossier released.'));

        // If the current user was the one assigned, they might need to go back to index
        if (! auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.sourcing-requests.index');
        }
    }

    public function assignTo($adminId)
    {
        if (! auth()->user()->isSuperAdmin()) {
            $this->dispatch('show-error-toast', message: __('Action not authorized.'));

            return;
        }

        try {
            DB::transaction(function () use ($adminId) {
                $lockedRequest = SourcingRequest::where('id', $this->sourcingRequest->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedRequest->update([
                    'assigned_to_admin_id' => $adminId,
                    'assigned_at' => now(),
                ]);
            });

            $this->sourcingRequest->refresh();
            $this->dispatch('show-success-toast', message: __('Dossier reassigned successfully.'));
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: __('Error during assignment: ').$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.sourcing-request-workflow', [
            'admins' => User::where('role', 'admin')->orderBy('name')->get(),
        ]);
    }
}
