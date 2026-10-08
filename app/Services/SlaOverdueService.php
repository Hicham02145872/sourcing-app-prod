<?php

namespace App\Services;

use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SlaOverdueService
{
    /**
     * Compute the SLA overdue ("restricted due to delay") statistics for a
     * given admin. Super admins are exempt and always get empty stats.
     *
     * @return array{requestCount:int, requestByStatus:array, requestTargets:array, orderCount:int, orderByStatus:array, orderTargets:array}
     */
    public function statsForUser(User $user): array
    {
        if ($user->isSuperAdmin()) {
            return [
                'requestCount' => 0,
                'requestByStatus' => [],
                'requestTargets' => [],
                'orderCount' => 0,
                'orderByStatus' => [],
                'orderTargets' => [],
            ];
        }

        $slaRules = (array) config('fsb.sla', []);

        $requestQuery = SourcingRequest::query()
            ->where('assigned_to_admin_id', $user->id)
            ->where('is_restricted_due_to_delay', true)
            ->whereIn('status', array_keys((array) ($slaRules['requests'] ?? [])));

        $requestByStatus = (clone $requestQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Most urgent (oldest) restricted request per status: used by the banner
        // CTAs to land on the actionable page (quotation creation for in_review,
        // the quotation edit page for negotiating, the request page otherwise).
        $requestTargets = (clone $requestQuery)
            ->select('id', 'status')
            ->with('quotation:id,sourcing_request_id')
            ->orderBy('status_changed_at')
            ->get()
            ->unique('status')
            ->mapWithKeys(fn (SourcingRequest $request) => [
                $request->status => [
                    'request_id' => $request->id,
                    'quotation_id' => $request->quotation?->id,
                ],
            ])
            ->toArray();

        $orderQuery = SourcingOrder::query()
            ->where('assigned_to_admin_id', $user->id)
            ->where('is_restricted_due_to_delay', true)
            ->whereIn('status', array_keys((array) ($slaRules['orders'] ?? [])));

        $orderByStatus = (clone $orderQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Most urgent (oldest) restricted order per status: used by the sidebar
        // "Process SLA" shortcut to land directly on the actionable order page.
        $orderTargets = (clone $orderQuery)
            ->select('id', 'status')
            ->orderBy('status_changed_at')
            ->get()
            ->unique('status')
            ->mapWithKeys(fn (SourcingOrder $order) => [
                $order->status => ['order_id' => $order->id],
            ])
            ->toArray();

        return [
            'requestCount' => array_sum($requestByStatus),
            'requestByStatus' => $requestByStatus,
            'requestTargets' => $requestTargets,
            'orderCount' => array_sum($orderByStatus),
            'orderByStatus' => $orderByStatus,
            'orderTargets' => $orderTargets,
        ];
    }

    /**
     * Resolve the URL of the most urgent overdue item for an admin, following
     * the same priority as the navigation lock (EnforceSlaNavigationLock):
     * in_review request > negotiating request > paid order > in_transit_china
     * order. Used by the sidebar "Process SLA" shortcut so it always points at
     * the actionable page (quotation create/edit, order page) instead of a
     * fixed list.
     *
     * @param  array{requestTargets?:array, orderTargets?:array}  $stats
     */
    public function mostUrgentTargetUrl(array $stats): ?string
    {
        $requestTargets = (array) ($stats['requestTargets'] ?? []);
        $orderTargets = (array) ($stats['orderTargets'] ?? []);

        if (isset($requestTargets['in_review'])) {
            return route('admin.quotations.create', ['sourcingRequest' => $requestTargets['in_review']['request_id']]);
        }

        if (isset($requestTargets['negotiating'])) {
            $target = $requestTargets['negotiating'];

            return ! empty($target['quotation_id'])
                ? route('admin.quotations.edit', $target['quotation_id'])
                : route('admin.sourcing-requests.show', $target['request_id']);
        }

        foreach (['paid', 'in_transit_china'] as $orderStatus) {
            if (isset($orderTargets[$orderStatus])) {
                return route('admin.sourcing-orders.show', $orderTargets[$orderStatus]['order_id']);
            }
        }

        return null;
    }

    /**
     * List every blocked (restricted) item for an admin, oldest first, with
     * the URL of its actionable page. Used by the login popup to show what
     * must be processed before the admin can navigate freely.
     *
     * @return array<int, array{type:string, id:int, status:string, age:?string, url:string}>
     */
    public function overdueItems(User $user): array
    {
        if ($user->isSuperAdmin()) {
            return [];
        }

        $slaRules = (array) config('fsb.sla', []);
        $items = [];

        $requests = SourcingRequest::query()
            ->where('assigned_to_admin_id', $user->id)
            ->where('is_restricted_due_to_delay', true)
            ->whereIn('status', array_keys((array) ($slaRules['requests'] ?? [])))
            ->with('quotation:id,sourcing_request_id')
            ->orderBy('status_changed_at')
            ->get();

        foreach ($requests as $request) {
            $items[] = [
                'type' => 'request',
                'id' => (int) $request->id,
                'reference' => (string) $request->reference_id,
                'title' => (string) $request->product_name,
                'status' => (string) $request->status,
                'age' => $request->status_changed_at?->diffForHumans(),
                'url' => $this->requestActionUrl($request),
                '_ts' => $request->status_changed_at?->getTimestamp() ?? 0,
            ];
        }

        $orders = SourcingOrder::query()
            ->where('assigned_to_admin_id', $user->id)
            ->where('is_restricted_due_to_delay', true)
            ->whereIn('status', array_keys((array) ($slaRules['orders'] ?? [])))
            ->with(['quotation:id,currency,sourcing_request_id', 'quotation.sourcingRequest'])
            ->orderBy('status_changed_at')
            ->get();

        foreach ($orders as $order) {
            $items[] = [
                'type' => 'order',
                'id' => (int) $order->id,
                'reference' => (string) $order->reference_id,
                'title' => (string) ($order->quotation?->sourcingRequest?->product_name ?? ''),
                'amount' => (float) $order->total_amount,
                'currency' => (string) ($order->quotation?->currency ?? 'USD'),
                'status' => (string) $order->status,
                'age' => $order->status_changed_at?->diffForHumans(),
                'url' => route('admin.sourcing-orders.show', $order->id),
                '_ts' => $order->status_changed_at?->getTimestamp() ?? 0,
            ];
        }

        // Same priority as the navigation lock (in_review > negotiating >
        // paid > in_transit_china), oldest first within a rule — the top row
        // always matches the lock redirect and the popup CTA.
        $priority = ['in_review' => 0, 'negotiating' => 1, 'paid' => 2, 'in_transit_china' => 3];
        usort($items, fn (array $a, array $b) => [$priority[$a['status']] ?? 9, $a['_ts']]
            <=> [$priority[$b['status']] ?? 9, $b['_ts']]);
        foreach ($items as &$item) {
            unset($item['_ts']);
        }

        return $items;
    }

    /**
     * Actionable page for a restricted sourcing request: quotation creation
     * for in_review, the quotation edit page for negotiating, the request
     * page otherwise (mirrors the navigation-lock redirect targets).
     */
    private function requestActionUrl(SourcingRequest $request): string
    {
        return match ($request->status) {
            'in_review' => route('admin.quotations.create', ['sourcingRequest' => $request->id]),
            'negotiating' => ! empty($request->quotation?->id)
                ? route('admin.quotations.edit', $request->quotation->id)
                : route('admin.sourcing-requests.show', $request->id),
            default => route('admin.sourcing-requests.show', $request->id),
        };
    }
}