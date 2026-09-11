<?php

namespace App\Livewire\Admin;

use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SourcingRequestWorkflow extends Component
{
    use WithFileUploads;

    public SourcingRequest $sourcingRequest;

    public $chinaTrackingNumber = '';

    public $packageLabelPhoto;

    public $showTransitForm = false;

    public function mount(SourcingRequest $sourcingRequest)
    {
        $this->sourcingRequest = $sourcingRequest;
    }

    public function updateStatus($status)
    {
        // Security check
        $isAssignedToMe = $this->sourcingRequest->isAssignedTo(auth()->user());
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $isClaimingAction = is_null($this->sourcingRequest->assigned_to_admin_id) && $status === 'in_review';

        if (! $isAssignedToMe && ! $isSuperAdmin && ! $isClaimingAction) {
            $this->dispatch('show-error-toast', message: __('You cannot modify a dossier that is not assigned to you.'));

            return;
        }

        // in_transit_china requires the dedicated action (tracking + label photo)
        if ($status === 'in_transit_china') {
            $this->dispatch('show-error-toast', message: __('Use the "Mark in transit from China" action.'));

            return;
        }

        if (! in_array($status, SourcingRequest::STATUSES)) {
            $this->dispatch('show-error-toast', message: __('Invalid status.'));

            return;
        }

        $actor = auth()->user();

        if ($status === 'in_review' && $actor && $actor->role === 'admin') {
            try {
                app(\App\Services\AdminSourcingWorkflowGuard::class)->assertCanStartReview($this->sourcingRequest, $actor);
            } catch (\App\Exceptions\WorkflowLimitReachedException $e) {
                $this->dispatch('workflow-limit-reached', message: $e->getMessage(), code: $e->errorCode());
                $this->dispatch('show-error-toast', message: $e->getMessage(), code: $e->errorCode());

                return;
            }
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

    public function markInTransit()
    {
        $user = auth()->user();

        if (! $user || ($user->role !== 'admin' && $user->role !== 'super_admin')) {
            $this->dispatch('show-error-toast', message: __('Action not authorized.'));

            return;
        }

        if (! $this->sourcingRequest->canTransitionTo('in_transit_china', $user)) {
            $this->dispatch('show-error-toast', message: __('Invalid status transition.'));

            return;
        }

        if (! $this->sourcingRequest->order) {
            $this->dispatch('show-error-toast', message: __('No sourcing order linked to this request yet.'));

            return;
        }

        $this->validate([
            'chinaTrackingNumber' => ['required', 'string', 'max:255'],
            'packageLabelPhoto' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        try {
            DB::transaction(function () {
                $photoPath = null;
                if ($this->packageLabelPhoto) {
                    $photoPath = app(\App\Services\ImageProcessingService::class)
                        ->compressAndStore($this->packageLabelPhoto, 'sourcing/in-transit')
                        ->path;
                }

                $this->sourcingRequest->transitionTo('in_transit_china');

                $order = $this->sourcingRequest->order;

                $order->update([
                    'china_tracking_number' => $this->chinaTrackingNumber,
                    'package_label_photo_path' => $photoPath,
                ]);

                // Transition the order only if its own validity rules allow it,
                // otherwise keep the order status and just store the data.
                if ($order->canTransitionTo('in_transit_china')) {
                    $order->update(['status' => 'in_transit_china']);
                }
            });

            $this->chinaTrackingNumber = '';
            $this->packageLabelPhoto = null;
            $this->showTransitForm = false;
            $this->sourcingRequest->refresh();
            $this->dispatch('show-success-toast', message: __('Request marked as in transit from China.'));
            $this->dispatch('sourcing-request-updated');
        } catch (\Exception $e) {
            $this->dispatch('show-error-toast', message: __('Error while marking the request in transit: ').$e->getMessage());
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
            'admins' => rescue(static fn () => User::where('role', 'admin')->orderBy('name')->get(), collect()),
        ]);
    }
}
