<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;

class SlaGuideController extends Controller
{
    public function index()
    {
        $rules = (array) config('fsb.sla', []);

        $restrictedOrders = SourcingOrder::where('is_restricted_due_to_delay', true)->count();
        $restrictedRequests = SourcingRequest::where('is_restricted_due_to_delay', true)->count();

        $demoOrder = SourcingOrder::where('is_restricted_due_to_delay', true)
            ->where('status', 'in_transit_china')
            ->orderByDesc('status_changed_at')
            ->first();

        return view('admin.sla-guide', [
            'rules' => $rules,
            'restrictedOrders' => $restrictedOrders,
            'restrictedRequests' => $restrictedRequests,
            'demoOrder' => $demoOrder,
        ]);
    }
}
