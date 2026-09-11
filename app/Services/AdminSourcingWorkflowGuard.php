<?php

namespace App\Services;

use App\Exceptions\WorkflowLimitReachedException;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminSourcingWorkflowGuard
{
    public function __construct(
        protected FeatureFlagService $featureFlagService
    ) {}

    /**
     * Ensure an admin is allowed to move a request to 'in_review'.
     *
     * Enforces the per-admin in-review limit when the feature flag
     * 'workflow_in_review_limit' is enabled. Super admins are exempt.
     *
     * @throws WorkflowLimitReachedException when the admin already holds the limit
     */
    public function assertCanStartReview(SourcingRequest $sourcingRequest, User $admin): void
    {
        if ($admin->isSuperAdmin()) {
            return;
        }

        if (! $this->featureFlagService->isEnabled('workflow_in_review_limit', $admin)) {
            return;
        }

        $limit = (int) config('fsb.workflow.in_review_limit', 5);

        $currentCount = DB::transaction(function () use ($sourcingRequest, $admin): int {
            // Lock the target request to serialize concurrent claims
            SourcingRequest::query()
                ->whereKey($sourcingRequest->getKey())
                ->lockForUpdate()
                ->first();

            return SourcingRequest::query()
                ->where('assigned_to_admin_id', $admin->id)
                ->where('status', 'in_review')
                ->count();
        });

        if ($currentCount >= $limit) {
            throw new WorkflowLimitReachedException($limit, $currentCount);
        }
    }
}