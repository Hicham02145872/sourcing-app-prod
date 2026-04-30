<?php

namespace App\Policies;

use App\Models\SourcingRequest;
use App\Models\User;

class SourcingRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isClient() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourcingRequest $sourcingRequest): bool
    {
        return $user->isAdmin() || $user->id === $sourcingRequest->user_id;
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
    public function update(User $user, SourcingRequest $sourcingRequest): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            // Admin can update if assigned to them OR unassigned
            return $sourcingRequest->assigned_to_admin_id === $user->id || is_null($sourcingRequest->assigned_to_admin_id);
        }

        return $user->id === $sourcingRequest->user_id && $sourcingRequest->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourcingRequest $sourcingRequest): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            // Admin can only delete if assigned to them (and maybe unassigned logic if desired, keeping strict for now)
            return $sourcingRequest->assigned_to_admin_id === $user->id;
        }

        return $user->id === $sourcingRequest->user_id &&
               in_array($sourcingRequest->status, ['pending', 'cancelled', 'rejected']);
    }

    public function cancel(User $user, SourcingRequest $sourcingRequest): bool
    {
        return $user->id === $sourcingRequest->user_id &&
               ($sourcingRequest->status === 'pending' || $sourcingRequest->status === 'in_review');
    }

    /**
     * Le client peut ajuster les quantités par destination tant qu'un devis existe,
     * n'est pas accepté / rejeté, et qu'aucune commande n'a été créée.
     */
    public function updateDestinationQuantities(User $user, SourcingRequest $sourcingRequest): bool
    {
        if ($user->id !== $sourcingRequest->user_id) {
            return false;
        }

        $quotation = $sourcingRequest->relationLoaded('quotation')
            ? $sourcingRequest->quotation
            : $sourcingRequest->quotation()->first();

        if (! $quotation || $quotation->order) {
            return false;
        }

        if (in_array($quotation->status, ['accepted', 'rejected'], true)) {
            return false;
        }

        if (in_array($sourcingRequest->status, ['accepted', 'completed', 'cancelled', 'rejected'], true)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SourcingRequest $sourcingRequest): bool
    {
        return $user->id === $sourcingRequest->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SourcingRequest $sourcingRequest): bool
    {
        return $user->id === $sourcingRequest->user_id;
    }
}
