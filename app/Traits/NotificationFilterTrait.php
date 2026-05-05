<?php

namespace App\Traits;

use App\Models\Quotation;
use App\Models\RefundRequest;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;

trait NotificationFilterTrait
{
    /**
     * Filter notifications based on entity assignment.
     * Super admins see all notifications.
     * Regular admins see only notifications for entities assigned to them.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $notifications
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function filterNotificationsByAssignment($notifications, $user)
    {
        // Super admins see all notifications
        if ($user->isSuperAdmin()) {
            return $notifications;
        }

        // Clients see all their notifications (already filtered by $user->notifications())
        if ($user->isClient()) {
            return $notifications;
        }

        // Extract all entity IDs from notifications
        $sourcingRequestIds = [];
        $quotationIds = [];
        $sourcingOrderIds = [];
        $refundRequestIds = [];

        foreach ($notifications as $notification) {
            $data = is_string($notification->data)
                ? json_decode($notification->data, true) ?? []
                : ($notification->data ?? []);

            if (isset($data['sourcing_request_id'])) {
                $sourcingRequestIds[] = $data['sourcing_request_id'];
            }
            if (isset($data['quotation_id'])) {
                $quotationIds[] = $data['quotation_id'];
            }
            if (isset($data['sourcing_order_id'])) {
                $sourcingOrderIds[] = $data['sourcing_order_id'];
            }
            if (isset($data['refund_request_id'])) {
                $refundRequestIds[] = $data['refund_request_id'];
            }
        }

        // Load all entities assigned to this admin (or unassigned for requests)
        $assignedRequests = SourcingRequest::whereIn('id', array_unique($sourcingRequestIds))
            ->where(function ($query) use ($user) {
                $query->where('assigned_to_admin_id', $user->id)
                    ->orWhereNull('assigned_to_admin_id');
            })
            ->pluck('id')
            ->toArray();

        $assignedQuotations = Quotation::whereIn('id', array_unique($quotationIds))
            ->where('assigned_to_admin_id', $user->id)
            ->pluck('id')
            ->toArray();

        $assignedOrders = SourcingOrder::whereIn('id', array_unique($sourcingOrderIds))
            ->where('assigned_to_admin_id', $user->id)
            ->pluck('id')
            ->toArray();

        $assignedRefunds = RefundRequest::with('sourcingOrder')
            ->whereIn('id', array_unique($refundRequestIds))
            ->whereHas('sourcingOrder', function ($q) use ($user) {
                $q->where('assigned_to_admin_id', $user->id);
            })
            ->pluck('id')
            ->toArray();

        // Filter notifications based on current assignment
        return $notifications->filter(function ($notification) use (
            $assignedRequests,
            $assignedQuotations,
            $assignedOrders,
            $assignedRefunds
        ) {
            $data = is_string($notification->data)
                ? json_decode($notification->data, true) ?? []
                : ($notification->data ?? []);

            // Check each entity type - if ID is present, it must be in the assigned list
            if (isset($data['sourcing_request_id'])) {
                return in_array($data['sourcing_request_id'], $assignedRequests);
            }
            if (isset($data['quotation_id'])) {
                return in_array($data['quotation_id'], $assignedQuotations);
            }
            if (isset($data['sourcing_order_id'])) {
                return in_array($data['sourcing_order_id'], $assignedOrders);
            }
            if (isset($data['refund_request_id'])) {
                return in_array($data['refund_request_id'], $assignedRefunds);
            }

            // Notifications without entity links are shown to everyone
            // (e.g. system notifications, general announcements)
            return true;
        });
    }
}
