<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SalesMarginExport;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\SourcingOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function salesMarginReport(Request $request)
    {
        $query = $this->getFilteredQuery($request);

        $orders = $query->paginate(10);

        $dailyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereDate('sourcing_orders.updated_at', \Carbon\Carbon::today())->with('quotation')->get(),
            'net_profit_or_loss'
        );

        $weeklyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereBetween('sourcing_orders.updated_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->with('quotation')->get(),
            'net_profit_or_loss'
        );

        $monthlyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereMonth('sourcing_orders.updated_at', \Carbon\Carbon::now()->month)->whereYear('sourcing_orders.updated_at', \Carbon\Carbon::now()->year)->with('quotation')->get(),
            'net_profit_or_loss'
        );
        $chartData = [
            'weeklyProfitChart' => $this->prepareWeeklyProfitChartData(clone $query),
            'shippedProductsChart' => $this->prepareShippedProductsChartData(clone $query),
            'topProductsChart' => $this->prepareTopProductsChartData(clone $query),
            'costDistributionChart' => $this->prepareCostDistributionChartData(clone $query),
        ];

        $availableDestinations = Country::all();

        return view('admin.reports.sales-margin', compact(
            'orders',
            'availableDestinations',
            'chartData',
            'dailyTotal',
            'weeklyTotal',
            'monthlyTotal'
        ));
    }

    private function getFilteredQuery(Request $request): Builder
    {
        $query = SourcingOrder::with([
            'quotation.sourcingRequest.destinations.country',
            'user',
            'quotation.sourcingRequest',
        ]);

        $query->whereNotNull('net_profit_or_loss');

        if ($request->filled('startDate')) {
            $query->whereDate('updated_at', '>=', $request->input('startDate'));
        }
        if ($request->filled('endDate')) {
            $query->whereDate('updated_at', '<=', $request->input('endDate'));
        }

        if ($request->filled('destinationFilter')) {
            $query->whereHas('quotation.sourcingRequest.destinations.country', function ($q) use ($request) {
                $q->where('id', $request->input('destinationFilter'));
            });
        }

        $sortColumn = $request->input('sortColumn', 'updated_at');
        $sortDirection = $request->input('sortDirection', 'desc');

        return $query->orderBy($sortColumn, $sortDirection);
    }

    private function prepareWeeklyProfitChartData(Builder $query): array
    {
        $orders = $query->with('quotation')->get();

        $weeklyProfits = $orders->groupBy(function ($order) {
            return $order->updated_at->format('W-Y');
        })->map(function ($weekOrders) {
            return $this->calculateTotalInMad($weekOrders, 'net_profit_or_loss');
        })->sortBy(function ($value, $key) {
            [$week, $year] = explode('-', $key);

            return $year * 100 + $week;
        });

        $weeklyProfits = $weeklyProfits->take(-10);

        return [
            'labels' => $weeklyProfits->keys()->map(fn ($weekYear) => 'S'.explode('-', $weekYear)[0])->toArray(),
            'data' => $weeklyProfits->values()->toArray(),
        ];
    }

    private function prepareShippedProductsChartData(Builder $query): array
    {
        $shippedOrders = $query->whereIn('status', ['delivered', 'order_completed'])
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('W-Y');
            })
            ->map(fn ($weekOrders) => $weekOrders->count())
            ->sortBy(function ($value, $key) {
                [$week, $year] = explode('-', $key);

                return $year * 100 + $week;
            });

        $shippedOrders = $shippedOrders->take(-10);

        return [
            'labels' => $shippedOrders->keys()->map(fn ($weekYear) => 'S'.explode('-', $weekYear)[0])->toArray(),
            'data' => $shippedOrders->values()->toArray(),
        ];
    }

    private function prepareTopProductsChartData(Builder $query): array
    {
        $topProducts = $query->get()
            ->filter(fn ($order) => $order->quotation && $order->quotation->sourcingRequest)
            ->groupBy(fn ($order) => $order->quotation->sourcingRequest->product_name)
            ->map(fn ($productOrders) => $productOrders->sum(fn ($order) => $order->quotation->sourcingRequest->destinations->sum('quantity') ?: 1))
            ->sortDesc()
            ->take(5);

        return [
            'labels' => $topProducts->keys()->toArray(),
            'data' => $topProducts->values()->toArray(),
        ];
    }

    private function prepareCostDistributionChartData(Builder $query): array
    {
        $orders = $query->with('quotation')->get();

        $totalProductCostInMad = $this->calculateTotalInMad($orders, 'product_cost_price');
        $totalShippingCostInMad = $this->calculateTotalInMad($orders, 'shipping_cost_real');
        // Summing other costs (rejection + customs)
        $totalOtherCostsInMad = $this->calculateTotalInMad($orders, 'rejection_loss_cost') + $this->calculateTotalInMad($orders, 'customs_tax_amount');
        $totalProfitInMad = $this->calculateTotalInMad($orders, 'net_profit_or_loss');

        $data = [
            __('Product Purchase (MAD)') => $totalProductCostInMad,
            __('Shipping (MAD)') => $totalShippingCostInMad,
            __('Others / Losses (MAD)') => $totalOtherCostsInMad,
            __('Net Margin (MAD)') => max(0, $totalProfitInMad),
        ];

        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
        ];
    }

    public function export(Request $request)
    {
        $data = $this->getFilteredQuery($request)->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', __('No data to export for selected criteria.'));
        }

        $columns = $request->input('columns', []);

        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }

        return Excel::download(new SalesMarginExport($data, $columns), 'margin_report_'.now()->format('Y-m-d_H-i').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $orders = $this->getFilteredQuery($request)->get();

        if ($orders->isEmpty()) {
            return redirect()->back()->with('error', __('No data to export for selected criteria.'));
        }

        $dailyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereDate('sourcing_orders.updated_at', \Carbon\Carbon::today())->with('quotation')->get(),
            'net_profit_or_loss'
        );
        $weeklyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereBetween('sourcing_orders.updated_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])->with('quotation')->get(),
            'net_profit_or_loss'
        );
        $monthlyTotal = $this->calculateTotalInMad(
            SourcingOrder::whereMonth('sourcing_orders.updated_at', \Carbon\Carbon::now()->month)->whereYear('sourcing_orders.updated_at', \Carbon\Carbon::now()->year)->with('quotation')->get(),
            'net_profit_or_loss'
        );

        $pdf = \PDF::loadView('admin.reports.sales-margin-pdf', compact('orders', 'dailyTotal', 'weeklyTotal', 'monthlyTotal'));

        return $pdf->download('financial_report_'.now()->format('Y-m-d_H-i').'.pdf');
    }

    private function calculateTotalInMad($collection, string $column): float
    {
        $total = 0;
        $rates = config('currencies.rates');

        foreach ($collection as $item) {
            $amount = $item->{$column} ?? 0;
            $currency = $item->quotation->currency ?? 'USD';
            $rate = $rates[$currency] ?? 1.0;
            $total += ($amount * $rate);
        }

        return (float) $total;
    }
}
