<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use App\Traits\NotificationFilterTrait;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    use NotificationFilterTrait;

    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        $totalUsers = User::count();

        // Base queries
        $requestQuery = SourcingRequest::query();
        $orderQuery = SourcingOrder::query();
        $quotationQuery = Quotation::query();

        // Filter by assigned admin if not super admin
        if (! $isSuperAdmin) {
            $requestQuery->where('assigned_to_admin_id', $user->id);
            $orderQuery->where('assigned_to_admin_id', $user->id);
            $quotationQuery->where('assigned_to_admin_id', $user->id);
        }

        $totalSourcingRequests = (clone $requestQuery)->count();

        $sourcingRequestsByStatus = (clone $requestQuery)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $sourcingOrdersByStatus = (clone $orderQuery)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $quotationsByStatus = (clone $quotationQuery)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pendingSourcingRequests = (clone $requestQuery)->where('status', SourcingRequest::STATUSES[0])->count();
        $pendingPaymentSourcingOrders = (clone $orderQuery)->where('status', SourcingOrder::STATUSES[0])->count();
        $pendingQuotations = (clone $quotationQuery)->where('status', Quotation::STATUSES[0])->count();

        // Persistent workflow alert (per-admin in-review limit)
        $inReviewLimit = (int) config('fsb.workflow.in_review_limit', 5);
        $showInReviewLimitBanner = ! $isSuperAdmin
            && app(\App\Services\FeatureFlagService::class)->isEnabled('workflow_in_review_limit', $user)
            && ($sourcingRequestsByStatus['in_review'] ?? 0) >= $inReviewLimit;

        // Persistent workflow alert (per-admin action deadline enforcement)
        $slaOverdueCount = 0;
        $slaOverdueByStatus = [];
        $slaOverdueRequestTargets = [];
        $slaOverdueOrderCount = 0;
        $slaOverdueOrderByStatus = [];
        if (! $isSuperAdmin) {
            $slaRules = (array) config('fsb.sla', []);

            $slaOverdueQuery = SourcingRequest::query()
                ->where('assigned_to_admin_id', $user->id)
                ->where('is_restricted_due_to_delay', true)
                ->whereIn('status', array_keys((array) ($slaRules['requests'] ?? [])));
            $slaOverdueByStatus = (clone $slaOverdueQuery)
                ->select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
            $slaOverdueCount = array_sum($slaOverdueByStatus);

            // Most urgent (oldest) restricted request per status, used by the
            // banner CTAs to land on the actionable page: quotation creation
            // for in_review, the quotation edit (update quotation) page for
            // negotiating, the request page otherwise.
            $slaOverdueRequestTargets = (clone $slaOverdueQuery)
                ->select('id', 'status')
                ->with('quotation:id')
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

            $slaOverdueOrderQuery = SourcingOrder::query()
                ->where('assigned_to_admin_id', $user->id)
                ->where('is_restricted_due_to_delay', true)
                ->whereIn('status', array_keys((array) ($slaRules['orders'] ?? [])));
            $slaOverdueOrderByStatus = (clone $slaOverdueOrderQuery)
                ->select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
            $slaOverdueOrderCount = array_sum($slaOverdueOrderByStatus);
        }
        $showSlaOverdueBanner = ! $isSuperAdmin
            && app(\App\Services\FeatureFlagService::class)->isEnabled('sla_deadlines_autolock', $user)
            && ($slaOverdueCount + $slaOverdueOrderCount) > 0;

        $allActivities = $user->notifications()->latest()->get();
        $filteredActivities = $this->filterNotificationsByAssignment($allActivities, $user);
        $recentActivities = $filteredActivities->take(3);

        $allAdminsPerformance = [];
        $notificationStats = [];
        if ($isSuperAdmin) {
            $allAdminsPerformance = User::where('role', 'admin')
                ->withCount([
                    'assignedSourcingRequests',
                    'assignedSourcingOrders',
                ])
                ->get();

            $notificationStats = [
                'users_with_fcm' => User::whereNotNull('fcm_token')->count(),
                'users_without_fcm' => User::whereNull('fcm_token')->count(),
                'total_notifications' => \DB::table('notifications')->count(),
                'unread_notifications' => \DB::table('notifications')->whereNull('read_at')->count(),
                'samples_with_token' => User::whereNotNull('fcm_token')->latest()->limit(5)->get(),
                'samples_without_token' => User::whereNull('fcm_token')->where('role', 'client')->latest()->limit(5)->get(),
            ];
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSourcingRequests',
            'sourcingRequestsByStatus',
            'sourcingOrdersByStatus',
            'quotationsByStatus',
            'pendingSourcingRequests',
            'pendingPaymentSourcingOrders',
            'pendingQuotations',
            'recentActivities',
            'allAdminsPerformance',
            'notificationStats',
            'showInReviewLimitBanner',
            'inReviewLimit',
            'showSlaOverdueBanner',
            'slaOverdueCount',
            'slaOverdueByStatus',
            'slaOverdueRequestTargets',
            'slaOverdueOrderCount',
            'slaOverdueOrderByStatus'
        ));
    }
}
