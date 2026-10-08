<?php

namespace App\Services;

use App\Models\SourcingRequest;
use App\Models\User;

class WorkflowAlertService
{
    public function __construct(protected SlaOverdueService $slaOverdueService) {}

    /**
     * Compute the persistent workflow-alert banner data (SLA overdue +
     * per-admin in-review limit) shared between the dashboard and the other
     * admin pages the banner is rendered on (request list, quotation pages…).
     *
     * @return array<string, int|bool|array>
     */
    public function bannerData(User $user): array
    {
        $featureFlagService = app(FeatureFlagService::class);
        $isSuperAdmin = $user->isSuperAdmin();

        // Per-admin in-review limit
        $inReviewLimit = (int) config('fsb.workflow.in_review_limit', 5);
        $inReviewCount = 0;
        if (! $isSuperAdmin) {
            $inReviewCount = SourcingRequest::query()
                ->where('assigned_to_admin_id', $user->id)
                ->where('status', 'in_review')
                ->count();
        }

        // SLA overdue stats
        $sla = $this->slaOverdueService->statsForUser($user);

        return [
            'inReviewLimit' => $inReviewLimit,
            'showInReviewLimitBanner' => ! $isSuperAdmin
                && $featureFlagService->isEnabled('workflow_in_review_limit', $user)
                && $inReviewCount >= $inReviewLimit,
            'showSlaOverdueBanner' => ! $isSuperAdmin
                && $featureFlagService->isEnabled('sla_deadlines_autolock', $user)
                && ($sla['requestCount'] + $sla['orderCount']) > 0,
            'slaOverdueCount' => $sla['requestCount'],
            'slaOverdueByStatus' => $sla['requestByStatus'],
            'slaOverdueRequestTargets' => $sla['requestTargets'],
            'slaOverdueOrderCount' => $sla['orderCount'],
            'slaOverdueOrderByStatus' => $sla['orderByStatus'],
        ];
    }
}