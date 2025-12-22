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
        return $user->id === $sourcingRequest->user_id || $user->isAdmin();
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
        return $user->id === $sourcingRequest->user_id && $sourcingRequest->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourcingRequest $sourcingRequest): bool
    {
        return $user->id === $sourcingRequest->user_id && $sourcingRequest->status === 'pending';
    }

    public function cancel(User $user, SourcingRequest $sourcingRequest): bool
    {
        return $user->id === $sourcingRequest->user_id &&
               ($sourcingRequest->status === 'pending' || $sourcingRequest->status === 'in_review');
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
