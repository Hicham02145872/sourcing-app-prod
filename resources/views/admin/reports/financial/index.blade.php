<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
    ['label' => __('Financial Reports')]
]">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Financial Performance Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Summary Cards (original currency, no conversion) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Daily Net Profit') }}</div>
                    <div class="mt-2 space-y-1">
                        @forelse($dailyTotalsByCurrency ?? [] as $currency => $total)
                            <div class="text-2xl font-bold {{ $total >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($total, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">{{ $currency }}</span>
                            </div>
                        @empty
                            <div class="text-2xl font-bold text-slate-400">—</div>
                        @endforelse
                    </div>
                    <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::today()->translatedFormat('M d, Y') }}</div>
                </div>

                <!-- Weekly -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Weekly Net Profit') }}</div>
                    <div class="mt-2 space-y-1">
                        @forelse($weeklyTotalsByCurrency ?? [] as $currency => $total)
                            <div class="text-2xl font-bold {{ $total >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($total, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">{{ $currency }}</span>
                            </div>
                        @empty
                            <div class="text-2xl font-bold text-slate-400">—</div>
                        @endforelse
                    </div>
                    <div class="text-xs text-gray-400 mt-1">{{ __('Current Week') }}</div>
                </div>

                <!-- Monthly -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase">{{ __('Monthly Net Profit') }}</div>
                    <div class="mt-2 space-y-1">
                        @forelse($monthlyTotalsByCurrency ?? [] as $currency => $total)
                            <div class="text-2xl font-bold {{ $total >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($total, 2) }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">{{ $currency }}</span>
                            </div>
                        @empty
                            <div class="text-2xl font-bold text-slate-400">—</div>
                        @endforelse
                    </div>
                    <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
                </div>
            </div>

            <!-- Filter & Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">{{ __('Detailed Profit/Loss Statement') }}</h3>
                        
                        <!-- Filter -->
                        <form action="{{ route('admin.reports.financial.index') }}" method="GET" class="flex items-center gap-2">
                            <select name="period" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>{{ __('All Time') }}</option>
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Order ID') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Sales (Total)') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Costs (Total)') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('Net Profit') }}</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($orders as $order)
                                    @php
                                        $totalCosts = ($order->product_cost_price ?? 0) + ($order->shipping_cost_real ?? 0) + ($order->rejection_loss_cost ?? 0);
                                        $profit = $order->net_profit_or_loss;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.sourcing-orders.show', $order) }}" class="text-blue-600 hover:underline">#{{ $order->id }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->updated_at->translatedFormat('M d, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-300">
                                            {{ number_format($order->total_amount, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $order->quotation->currency }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                            {{ number_format($totalCosts, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $order->quotation->currency }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($profit, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $order->quotation->currency }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                 {{ __($order->status) }}
                                             </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            {{ __('No financial data available for this period.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
