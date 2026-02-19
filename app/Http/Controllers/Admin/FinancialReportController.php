<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'daily');

        $query = SourcingOrder::query()
            ->whereNotNull('net_profit_or_loss')
            ->with(['quotation.sourcingRequest', 'user']);

        if ($period === 'daily') {
            $query->whereDate('updated_at', Carbon::today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('updated_at', Carbon::now()->month)
                ->whereYear('updated_at', Carbon::now()->year);
        }

        $orders = $query->latest('updated_at')->paginate(20);

        // Aggregations in MAD
        $totalProfit = $this->calculateTotalInMad($query->get(), 'net_profit_or_loss');

        $dailyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereDate('updated_at', Carbon::today())->with('quotation')->get(),
            'net_profit_or_loss'
        );

        $weeklyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->with('quotation')->get(),
            'net_profit_or_loss'
        );

        $monthlyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year)->with('quotation')->get(),
            'net_profit_or_loss'
        );

        // Subtract approved refunds from totals
        $dailyRefunds = $this->calculateRefundsTotal(Carbon::today(), Carbon::today());
        $weeklyRefunds = $this->calculateRefundsTotal(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $monthlyRefunds = $this->calculateRefundsTotal(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        );
        $totalRefunds = $this->calculateRefundsTotal(null, null);

        $dailyTotal -= $dailyRefunds;
        $weeklyTotal -= $weeklyRefunds;
        $monthlyTotal -= $monthlyRefunds;
        $totalProfit -= $totalRefunds;

        return view('admin.reports.financial.index', compact(
            'orders',
            'period',
            'totalProfit',
            'dailyTotal',
            'weeklyTotal',
            'monthlyTotal'
        ));
    }

    public function refunds(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $query = \App\Models\RefundRequest::where('status', 'approved')
            ->with(['sourcingOrder.quotation', 'user']);

        if ($period === 'daily') {
            $query->whereDate('updated_at', Carbon::today());
        } elseif ($period === 'weekly') {
            $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $query->whereMonth('updated_at', Carbon::now()->month)
                ->whereYear('updated_at', Carbon::now()->year);
        }

        $refunds = $query->latest('updated_at')->paginate(20);

        // Aggregations in MAD
        $dailyTotal = $this->calculateTotalInMad(
            \App\Models\RefundRequest::where('status', 'approved')->whereDate('updated_at', Carbon::today())->with('sourcingOrder.quotation')->get(),
            'amount_approved'
        );

        $weeklyTotal = $this->calculateTotalInMad(
            \App\Models\RefundRequest::where('status', 'approved')->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->with('sourcingOrder.quotation')->get(),
            'amount_approved'
        );

        $monthlyTotal = $this->calculateTotalInMad(
            \App\Models\RefundRequest::where('status', 'approved')->whereMonth('updated_at', Carbon::now()->month)->whereYear('updated_at', Carbon::now()->year)->with('sourcingOrder.quotation')->get(),
            'amount_approved'
        );

        $totalRefunded = $this->calculateTotalInMad(
            \App\Models\RefundRequest::where('status', 'approved')->with('sourcingOrder.quotation')->get(),
            'amount_approved'
        );

        // Data for Chart.js (Last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');

            $monthRecords = \App\Models\RefundRequest::where('status', 'approved')
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->with('sourcingOrder.quotation')
                ->get();

            $amountInMad = $this->calculateTotalInMad($monthRecords, 'amount_approved');

            $chartData[] = [
                'label' => $monthName,
                'value' => (float) $amountInMad,
            ];
        }

        return view('admin.reports.financial.refunds', compact(
            'refunds',
            'period',
            'dailyTotal',
            'weeklyTotal',
            'monthlyTotal',
            'totalRefunded',
            'chartData'
        ));
    }

    private function calculateTotalInMad($collection, string $column): float
    {
        $total = 0;
        $rates = config('currencies.rates');

        foreach ($collection as $item) {
            $amount = $item->{$column} ?? 0;

            // RefundRequest has relation to sourcingOrder, which has relation to quotation
            // SourcingOrder has relation to quotation
            $quotation = $item->quotation ?? ($item->sourcingOrder->quotation ?? null);
            $currency = $quotation->currency ?? 'USD';

            $rate = $rates[$currency] ?? 1.0;
            $total += ($amount * $rate);
        }

        return (float) $total;
    }

    /**
     * Calculate total approved refunds in MAD for a given period
     */
    private function calculateRefundsTotal($startDate = null, $endDate = null): float
    {
        $query = \App\Models\RefundRequest::where('status', 'approved')
            ->with('sourcingOrder.quotation');

        if ($startDate && $endDate) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $refunds = $query->get();

        return $this->calculateTotalInMad($refunds, 'amount_approved');
    }
}
