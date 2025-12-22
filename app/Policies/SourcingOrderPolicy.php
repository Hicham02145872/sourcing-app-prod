<?php

namespace App\Policies;

use App\Models\SourcingOrder;
use App\Models\User;

class SourcingOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourcingOrder $sourcingOrder): bool
    {
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return true;
        }

        return $sourcingOrder->user_id === $user->id;
    }

    /**
     * Determine whether the user can view financial data.
     */
    public function viewFinancials(User $user, SourcingOrder $sourcingOrder): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SourcingOrder $sourcingOrder): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can upload proof of payment.
     */
    public function uploadProofOfPayment(User $user, SourcingOrder $sourcingOrder): bool
    {
        // Admin and Super Admin can always upload
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return true;
        }

        // Clients can upload only for their own orders
        return $sourcingOrder->user_id === $user->id;
    }

    /**
     * Determine whether the user can request a refund for the order.
     */
    public function requestRefund(User $user, SourcingOrder $sourcingOrder): bool
    {
        return $sourcingOrder->user_id === $user->id;
    }
}
