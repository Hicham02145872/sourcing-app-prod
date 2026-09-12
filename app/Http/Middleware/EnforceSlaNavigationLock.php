<?php

namespace App\Http\Middleware;

use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Services\FeatureFlagService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnforceSlaNavigationLock
{
    public function __construct(protected FeatureFlagService $featureFlagService) {}

    /**
     * Lock a non-super-admin to the dashboard, sourcing requests/quotation
     * creation and (when an order deadline is overdue) the related order pages.
     *
     * Each rule redirects to its own workflow:
     *  - in_review (request)  -> quotation creation page
     *  - negotiating (request) -> the sourcing request page
     *  - paid (order)         -> the order page
     *  - in_transit_china (order) -> the order page (parcel/evidence upload)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $this->featureFlagService->isEnabled('sla_deadlines_autolock', $user)) {
            return $next($request);
        }

        $rules = (array) config('fsb.sla', []);
        $requestRules = (array) ($rules['requests'] ?? []);
        $orderRules = (array) ($rules['orders'] ?? []);

        $restrictedRequestStatuses = [];
        $restrictedRequest = null;
        if ($requestRules !== []) {
            $restrictedRequest = SourcingRequest::query()
                ->where('assigned_to_admin_id', $user->id)
                ->where('is_restricted_due_to_delay', true)
                ->whereIn('status', array_keys($requestRules))
                ->orderByRaw("CASE WHEN status = 'in_review' THEN 0 ELSE 1 END, status_changed_at ASC")
                ->first();

            if ($restrictedRequest) {
                $restrictedRequestStatuses = SourcingRequest::query()
                    ->where('assigned_to_admin_id', $user->id)
                    ->where('is_restricted_due_to_delay', true)
                    ->whereIn('status', array_keys($requestRules))
                    ->distinct()
                    ->pluck('status')
                    ->all();
            }
        }

        $restrictedOrderStatuses = [];
        $restrictedOrder = null;
        if ($orderRules !== []) {
            $restrictedOrder = SourcingOrder::query()
                ->where('assigned_to_admin_id', $user->id)
                ->where('is_restricted_due_to_delay', true)
                ->whereIn('status', array_keys($orderRules))
                ->orderByRaw("CASE WHEN status = 'paid' THEN 0 ELSE 1 END, status_changed_at ASC")
                ->first();

            if ($restrictedOrder) {
                $restrictedOrderStatuses = SourcingOrder::query()
                    ->where('assigned_to_admin_id', $user->id)
                    ->where('is_restricted_due_to_delay', true)
                    ->whereIn('status', array_keys($orderRules))
                    ->distinct()
                    ->pluck('status')
                    ->all();
            }
        }

        if (! $restrictedRequest && ! $restrictedOrder) {
            return $next($request);
        }

        $target = $this->mostUrgentTarget($restrictedRequest, $restrictedOrder);

        $redirectUrl = match (true) {
            $target instanceof SourcingRequest && $target->status === 'in_review' => route('admin.quotations.create', ['sourcingRequest' => $target]),
            $target instanceof SourcingRequest => route('admin.sourcing-requests.show', $target),
            default => route('admin.sourcing-orders.show', $target),
        };

        View::share('slaNavigationLocked', true);
        View::share('slaLockRedirectUrl', $redirectUrl);
        View::share('slaLockedRequestStatuses', $restrictedRequestStatuses);
        View::share('slaLockedOrderStatuses', $restrictedOrderStatuses);

        $routeName = $request->route()?->getName();

        $allowed = $routeName === 'admin.dashboard'
            || in_array($routeName, [
                'admin.quotations.create',
                'admin.quotations.store',
            ], true)
            || str_starts_with((string) $routeName, 'admin.sourcing-requests.');

        if ($restrictedOrderStatuses !== []) {
            $allowed = $allowed || in_array($routeName, [
                'admin.sourcing-orders.index',
                'admin.sourcing-orders.show',
                'admin.sourcing-orders.update-status',
                'admin.sourcing-orders.update-tracking',
                'admin.sourcing-orders.parcel.store',
                'admin.sourcing-orders.media.store',
                'admin.sourcing-orders.download-proof-of-payment',
            ], true);
        }

        if ($allowed) {
            return $next($request);
        }

        if (! $request->isMethod('GET')) {
            abort(403);
        }

        return redirect()->to($redirectUrl);
    }

    /**
     * Pick the most urgent restricted item. Priority: in_review request,
     * then negotiating request, then paid order, then in_transit_china order.
     * Within the same priority the oldest status wins.
     */
    protected function mostUrgentTarget(?SourcingRequest $request, ?SourcingOrder $order): SourcingRequest|SourcingOrder
    {
        $candidates = [];

        if ($request) {
            $candidates[] = [
                'priority' => $request->status === 'in_review' ? 0 : 1,
                'since' => $request->status_changed_at?->timestamp ?? PHP_INT_MAX,
                'target' => $request,
            ];
        }

        if ($order) {
            $candidates[] = [
                'priority' => $order->status === 'paid' ? 2 : 3,
                'since' => $order->status_changed_at?->timestamp ?? PHP_INT_MAX,
                'target' => $order,
            ];
        }

        usort($candidates, function ($a, $b) {
            return [$a['priority'], $a['since']] <=> [$b['priority'], $b['since']];
        });

        return $candidates[0]['target'];
    }
}
