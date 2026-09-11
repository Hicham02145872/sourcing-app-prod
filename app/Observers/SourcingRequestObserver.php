<?php

namespace App\Observers;

use App\Models\RequestStatusLog;
use App\Models\SourcingRequest;

class SourcingRequestObserver
{
    /**
     * Handle the SourcingRequest "created" event.
     */
    public function created(SourcingRequest $sourcingRequest): void
    {
        // Round Robin Auto-Assignment removed.
    }

    /**
     * Handle the SourcingRequest "updating" event.
     */
    public function updating(SourcingRequest $sourcingRequest): void
    {
        if ($sourcingRequest->isDirty('status')) {
            // Track when the current status began and clear any SLA delay restriction,
            // as a status movement always resets the deadline counter.
            $sourcingRequest->status_changed_at = now();
            $sourcingRequest->is_restricted_due_to_delay = false;

            // Audit trail of every status movement (with the acting user when known)
            RequestStatusLog::create([
                'sourcing_request_id' => $sourcingRequest->getKey(),
                'from_status' => $sourcingRequest->getOriginal('status'),
                'to_status' => $sourcingRequest->status,
                'changed_by_user_id' => auth()->check() ? auth()->id() : null,
                'changed_at' => now(),
            ]);
        }

        // Auto-assign to the admin who changes status to 'in_review'
        if ($sourcingRequest->isDirty('status') && $sourcingRequest->status === 'in_review') {
            $user = auth()->user();
            if ($user && ($user->role === 'admin' || $user->role === 'super_admin') && ! $sourcingRequest->assigned_to_admin_id) {
                // Ensure we don't overwrite if already assigned (logic says ! assigned)
                $sourcingRequest->assigned_to_admin_id = $user->id;
                $sourcingRequest->assigned_at = now();
            }
        }
    }

    /**
     * Handle the SourcingRequest "updated" event.
     */
    public function updated(SourcingRequest $sourcingRequest): void
    {

        // Automatically sync assignment to the related Sourcing Order
        if ($sourcingRequest->isDirty('assigned_to_admin_id')) {
            $newAdminId = $sourcingRequest->assigned_to_admin_id;
            $oldAdminId = $sourcingRequest->getOriginal('assigned_to_admin_id');

            // 1. Notify the PREVIOUS admin if one existed and it was reassigned
            // We only notify if the old admin is NOT the person making the change (super admin case)
            if ($oldAdminId && $oldAdminId !== $newAdminId) {
                $oldAdmin = \App\Models\User::find($oldAdminId);
                if ($oldAdmin) {
                    $oldAdmin->notify(new \App\Notifications\DossierAssignmentRemoved($sourcingRequest));
                }
            }

            // 2. Notify all admins and super admins
            $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\SourcingRequestAssigned($sourcingRequest));
            }

            // Sync to related Quotation
            if ($sourcingRequest->quotation) {
                $sourcingRequest->quotation->update([
                    'assigned_to_admin_id' => $newAdminId,
                ]);
            }

            // Sync to related Sourcing Order
            if ($sourcingRequest->order) {
                $sourcingRequest->order->update([
                    'assigned_to_admin_id' => $newAdminId,
                ]);

                // Sync to related Refund Requests (ensure they follow the order admin)
                $sourcingRequest->order->refundRequests()->update([
                    'assigned_to_admin_id' => $newAdminId,
                ]);
            }
        }
    }
}
