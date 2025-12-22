<?php

namespace App\Observers;

use App\Models\SourcingRequest;

class SourcingRequestObserver
{
    public static bool $isAutoAssigning = false;

    /**
     * Handle the SourcingRequest "created" event.
     */
    public function created(SourcingRequest $sourcingRequest): void
    {
        // Round Robin Auto-Assignment: Distribute new requests evenly among admins
        if (! $sourcingRequest->assigned_to_admin_id) {
            static::$isAutoAssigning = true;
            $this->assignSmartLoadBalance($sourcingRequest);
            static::$isAutoAssigning = false;
        }
    }

    /**
     * Handle the SourcingRequest "updated" event.
     */
    public function updated(SourcingRequest $sourcingRequest): void
    {
        // If it's part of auto-assignment, we've already sent a specific notification
        if (static::$isAutoAssigning) {
            return;
        }

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

            // 2. Notify the NEW admin and all super admins
            $admins = \App\Models\User::where('role', 'super_admin')
                ->when($newAdminId && $newAdminId !== auth()->id(), function ($query) use ($newAdminId) {
                    $query->orWhere('id', $newAdminId);
                })
                ->get();

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

    /**
     * Smart Load-Balancing Assignment Logic
     */
    private function assignSmartLoadBalance(SourcingRequest $sourcingRequest): void
    {
        // 1. Get all eligible admins with their count of "active" sourcing requests and orders
        // Active Request = status not in ['completed', 'rejected', 'cancelled']
        // Active Order = status not in ['delivered', 'shipment_canceled', 'order_completed']
        $admins = \App\Models\User::where('role', 'admin')
            ->withCount([
                'assignedSourcingRequests as active_requests_count' => function ($query) {
                    $query->whereNotIn('status', ['completed', 'rejected', 'cancelled']);
                },
                'assignedSourcingOrders as active_orders_count' => function ($query) {
                    $query->whereNotIn('status', ['delivered', 'shipment_canceled', 'order_completed']);
                },
            ])
            ->get();

        if ($admins->isEmpty()) {
            return;
        }

        // 2. Find the admin with the minimum workload
        // Sort by active_requests_count (primary), then active_orders_count (secondary), then by ID
        $nextAdmin = $admins->sortBy([
            ['active_requests_count', 'asc'],
            ['active_orders_count', 'asc'],
            ['id', 'asc'],
        ])->first();

        if ($nextAdmin) {
            // 3. Execute Assignment
            $sourcingRequest->update([
                'assigned_to_admin_id' => $nextAdmin->id,
                'assigned_at' => now(),
            ]);

            // 4. Notify specifically for Auto-Assignment (Target Admin + Super Admins)
            $notifiables = \App\Models\User::where('role', 'super_admin')
                ->orWhere('id', $nextAdmin->id)
                ->get();

            foreach ($notifiables as $notifiable) {
                $notifiable->notify(new \App\Notifications\SourcingRequestAutoAssigned($sourcingRequest, $nextAdmin->active_requests_count));
            }

            \Illuminate\Support\Facades\Log::info("Smart Allocation: Assigned Sourcing Request #{$sourcingRequest->id} to Admin #{$nextAdmin->id} (Workload: {$nextAdmin->active_requests_count} active tickets, {$nextAdmin->active_orders_count} active orders)");
        }
    }
}
