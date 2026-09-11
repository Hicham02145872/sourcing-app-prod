<?php

namespace App\Http\Middleware;

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
     * Lock a non-super-admin to the dashboard and sourcing requests when the
     * SLA autolock feature is active and the admin has overdue requests.
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

        $hasRestrictedRequests = SourcingRequest::query()
            ->where('assigned_to_admin_id', $user->id)
            ->where('is_restricted_due_to_delay', true)
            ->whereIn('status', array_keys((array) config('fsb.sla', [])))
            ->exists();

        if (! $hasRestrictedRequests) {
            return $next($request);
        }

        View::share('slaNavigationLocked', true);

        $routeName = $request->route()?->getName();

        $allowed = $routeName === 'admin.dashboard'
            || str_starts_with((string) $routeName, 'admin.sourcing-requests.');

        if ($allowed) {
            return $next($request);
        }

        if (! $request->isMethod('GET')) {
            abort(403);
        }

        return redirect()->route('admin.sourcing-requests.index', [
            'status' => 'all',
            'overdue' => 1,
            'admin_id' => 'me',
        ]);
    }
}