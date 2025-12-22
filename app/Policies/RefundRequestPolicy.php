<?php

namespace App\Policies;

use App\Models\RefundRequest;
use App\Models\User;

class RefundRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RefundRequest $refundRequest): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $refundRequest->assigned_to_admin_id === $user->id || is_null($refundRequest->assigned_to_admin_id);
        }

        return $user->id === $refundRequest->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isClient();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RefundRequest $refundRequest): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && ($refundRequest->assigned_to_admin_id === $user->id || is_null($refundRequest->assigned_to_admin_id));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RefundRequest $refundRequest): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RefundRequest $refundRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RefundRequest $refundRequest): bool
    {
        return false;
    }
}
