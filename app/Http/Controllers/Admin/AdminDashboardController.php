<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SourcingRequest;
use App\Models\SourcingOrder;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $sourcingRequestsByStatus = SourcingRequest::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $sourcingOrdersByStatus = SourcingOrder::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $quotationsByStatus = Quotation::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pendingSourcingRequests = SourcingRequest::where('status', SourcingRequest::STATUSES[0])->count(); // Assuming 'pending' is the first status
        $pendingPaymentSourcingOrders = SourcingOrder::where('status', SourcingOrder::STATUSES[0])->count(); // Assuming 'pending_payment' is the first status
        $pendingQuotations = Quotation::where('status', Quotation::STATUSES[0])->count(); // Assuming 'pending' is the first status

        $recentActivities = Auth::user()->notifications()->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'sourcingRequestsByStatus', 'sourcingOrdersByStatus', 'quotationsByStatus', 'pendingSourcingRequests', 'pendingPaymentSourcingOrders', 'pendingQuotations', 'recentActivities'));
    }
}
