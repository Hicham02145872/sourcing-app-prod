<x-app-layout>

    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Financial & Logistics Report') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Sales & Margins') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400 hidden sm:inline-block">{{ __('Last Update:') }} {{ now()->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- Section 1: KPIs (Summary Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('DAILY') }}</p>
                                <h4 class="text-sm font-medium text-slate-400">{{ __('Net Profit') }}</h4>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                        </div>
                        
                        <div>
                            @forelse($dailyTotalsByCurrency ?? [] as $currency => $total)
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold tracking-tight {{ $total >= 0 ? 'text-slate-800' : 'text-red-500' }}">
                                        {{ number_format($total, 2) }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400 uppercase ml-1">{{ $currency }}</span>
                                </div>
                            @empty
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold text-slate-400">—</span>
                                    <span class="text-xs text-slate-400">{{ __('No data') }}</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between relative z-10">
                        <span class="text-xs font-medium text-slate-500">{{ \Carbon\Carbon::today()->translatedFormat('d M Y') }}</span>
                         <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ __('Today') }}</span>
                    </div>
                </div>

                <!-- Weekly (Highlighted) -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-xl shadow-lg p-6 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group text-white">
                    <!-- DecorElements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-xl"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-500 opacity-20 rounded-full -ml-10 -mb-10 blur-xl"></div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-indigo-200 mb-1">{{ __('WEEKLY') }}</p>
                                <h4 class="text-sm font-medium text-slate-400">{{ __('Net Profit') }}</h4>
                            </div>
                            <div class="p-2 bg-slate-700 bg-opacity-50 rounded-lg text-indigo-300 border border-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                            </div>
                        </div>
                        
                        <div>
                            @forelse($weeklyTotalsByCurrency ?? [] as $currency => $total)
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold tracking-tight text-white drop-shadow-sm">
                                        {{ number_format($total, 2) }}
                                    </span>
                                    <span class="text-xs font-bold text-indigo-300 uppercase ml-1">{{ $currency }}</span>
                                </div>
                            @empty
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold text-slate-500">—</span>
                                    <span class="text-xs text-slate-400">{{ __('No data') }}</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-700 flex items-center justify-between relative z-10">
                         <span class="text-xs font-medium text-slate-400">{{ __('Current week') }}</span>
                         <span class="text-xs font-bold text-white bg-indigo-600 px-2 py-0.5 rounded-full">{{ __('Ongoing') }}</span>
                    </div>
                </div>

                <!-- Monthly -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                     <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                             <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('MONTHLY') }}</p>
                                <h4 class="text-sm font-medium text-slate-400">{{ __('Net Profit') }}</h4>
                            </div>
                            <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                        </div>
                        
                        <div>
                            @forelse($monthlyTotalsByCurrency ?? [] as $currency => $total)
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold tracking-tight {{ $total >= 0 ? 'text-slate-800' : 'text-red-500' }}">
                                        {{ number_format($total, 2) }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-400 uppercase ml-1">{{ $currency }}</span>
                                </div>
                            @empty
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-extrabold text-slate-400">—</span>
                                    <span class="text-xs text-slate-400">{{ __('No data') }}</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between relative z-10">
                        <span class="text-xs font-medium text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ __('This month') }}</span>
                    </div>
                </div>
            </div>


            <!-- Section 2: Filters Bar -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 sticky top-20 z-10">
                <form action="{{ route('admin.reports.sales-margin') }}" method="GET">
                    <div class="flex flex-col lg:flex-row gap-4 items-end lg:items-center justify-between">
                        
                        <!-- Inputs Group -->
                        <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto flex-1 items-end">
                            <div class="w-full md:w-40">
                                <label for="startDate" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('From') }}</label>
                                <input type="date" id="startDate" name="startDate" value="{{ request('startDate') }}"
                                    class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors">
                            </div>

                            <div class="w-full md:w-40">
                                <label for="endDate" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('To') }}</label>
                                <input type="date" id="endDate" name="endDate" value="{{ request('endDate') }}"
                                    class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors">
                            </div>

                            <div class="w-full md:w-56">
                                <label for="destinationFilter" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Destination') }}</label>
                                <select id="destinationFilter" name="destinationFilter" 
                                    class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors">
                                    <option value="">{{ __('All Destinations') }}</option>
                                    @foreach($availableDestinations as $destination)
                                        <option value="{{ $destination->id }}" {{ request('destinationFilter') == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full md:w-auto h-[34px] px-4 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center justify-center gap-2">
                                    {{ __('Filter') }}
                                </button>
                            </div>
                        </div>

                        <!-- Exports Group -->
                        <div class="flex gap-2 w-full lg:w-auto border-t lg:border-t-0 border-slate-100 pt-3 lg:pt-0">
                            <button type="button" onclick="toggleExportModal()" 
                                class="flex-1 lg:flex-none inline-flex justify-center items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors" title="{{ __('Custom Excel Export') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                {{ __('Excel') }}
                            </button>
                            <a href="{{ route('admin.reports.sales-margin.export-pdf', request()->all()) }}" 
                                class="flex-1 lg:flex-none inline-flex justify-center items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors" title="{{ __('PDF Export') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                                {{ __('PDF') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Messages -->
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('status') }}</p></div>
                    </div>
                </div>
            @endif

            <!-- Section 3: Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Profit Chart -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col h-[400px]">
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Weekly Profit') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('Net financial performance') }}</p>
                    </div>
                    <div class="flex-1 w-full relative">
                        @if(!empty($chartData['weeklyProfitChart']['data']) && count($chartData['weeklyProfitChart']['data']) > 0 && array_sum($chartData['weeklyProfitChart']['data']) != 0)
                            <canvas id="weeklyProfitChart"></canvas>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium">{{ __('No data for this period') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipped Chart -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col h-[400px]">
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Shipping Volume') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('Units shipped over the period') }}</p>
                    </div>
                    <div class="flex-1 w-full relative">
                        @if(!empty($chartData['shippedProductsChart']['data']) && count($chartData['shippedProductsChart']['data']) > 0 && array_sum($chartData['shippedProductsChart']['data']) != 0)
                            <canvas id="shippedProductsChart"></canvas>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium">{{ __('No shipping data') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cost Distribution Chart -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col h-[400px]">
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Cost Distribution') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('Revenue breakdown') }}</p>
                    </div>
                    <div class="flex-1 w-full relative flex items-center justify-center">
                        @if(!empty($chartData['costDistributionChart']['data']) && array_sum($chartData['costDistributionChart']['data']) > 0)
                            <div class="w-full h-full max-h-[280px]">
                                <canvas id="costDistributionChart"></canvas>
                            </div>
                        @else
                             <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium">{{ __('Not enough financial data') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Products Chart -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col h-[400px]">
                    <div class="mb-6 border-b border-slate-100 pb-4">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Top 5 Products') }}</h3>
                        <p class="text-xs text-slate-500">{{ __('Top performing items by volume') }}</p>
                    </div>
                    <div class="flex-1 w-full relative">
                        @if(!empty($chartData['topProductsChart']['data']) && count($chartData['topProductsChart']['data']) > 0)
                            <canvas id="topProductsChart"></canvas>
                        @else
                             <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium">{{ __('No products ranked at the moment') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Global Order Distribution Map Placeholder -->
                <!-- Map was removed as per user request -->
            </div>

            <!-- Section 4: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Order Log') }}</h3>
                    <div class="text-xs text-slate-500">
                        {{ __('Total') }}: <span class="font-medium text-slate-900">{{ $orders->total() }}</span> {{ __('entries') }}
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('ID') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Sales') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Costs') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('Net Margin') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">{{ __('Margin %') }}</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($orders as $order)
                                @php
                                    $totalCosts = ($order->product_cost_price ?? 0) + ($order->shipping_cost_real ?? 0) + ($order->rejection_loss_cost ?? 0);
                                    $profit = $order->net_profit_or_loss;
                                    
                                    // Calculate Margin Percentage
                                    $marginPercentage = 0;
                                    if ($order->total_amount > 0) {
                                        $marginPercentage = ($profit / $order->total_amount) * 100;
                                    }

                                    // Status Logic for badges
                                    $statusClass = match($order->status) {
                                        'completed', 'delivered', 'shipped' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending', 'processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'cancelled', 'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.sourcing-orders.show', $order) }}" class="text-orange-600 hover:text-orange-700 font-mono">#{{ $order->id }}</a>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-600">
                                        {{ $order->updated_at->format('d/m/Y') }} <span class="text-xs text-slate-400">{{ $order->updated_at->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-900 font-medium">
                                        {{ $order->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-slate-900">
                                        {{ number_format($order->total_amount, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $order->quotation->currency }}</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-slate-500">
                                        {{ number_format($totalCosts, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $order->quotation->currency }}</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-right font-bold {{ $profit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ number_format($profit, 2) }} <span class="text-[10px] font-bold text-slate-400">{{ $order->quotation->currency }}</span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-right font-medium">
                                        <div class="flex items-center justify-end gap-1 {{ $marginPercentage >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                            <span>{{ number_format($marginPercentage, 1) }}%</span>
                                            @if($marginPercentage > 0)
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            @elseif($marginPercentage < 0)
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center">
                                         <span class="px-2.5 py-0.5 inline-flex text-[11px] leading-4 font-semibold rounded-full border {{ $statusClass }} uppercase tracking-wide">
                                            {{ $order->status }}
                                         </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <h3 class="text-sm font-medium text-slate-900">{{ __('No data found') }}</h3>
                                            <p class="text-xs text-slate-500 mt-1">{{ __('Try adjusting your date or destination filters.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($orders->hasPages())
                    <div class="bg-white px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-slate-700">
                                    {{ __('Showing') }} <span class="font-medium">{{ $orders->firstItem() }}</span> {{ __('to') }} <span class="font-medium">{{ $orders->lastItem() }}</span> {{ __('of') }} <span class="font-medium">{{ $orders->total() }}</span> {{ __('results') }}
                                </p>
                            </div>
                            <div>
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Export Configuration Modal -->
    <div id="exportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleExportModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">{{ __('Choose columns to export') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">{{ __('Select the data you want to include in your Excel file.') }}</p>
                                
                                <form id="exportForm" action="{{ route('admin.reports.sales-margin.export') }}" method="GET">
                                    <!-- Preserve existing filters -->
                                    <input type="hidden" name="startDate" value="{{ request('startDate') }}">
                                    <input type="hidden" name="endDate" value="{{ request('endDate') }}">
                                    <input type="hidden" name="destinationFilter" value="{{ request('destinationFilter') }}">

                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="id" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Order ID') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="date" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Date') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="product" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Product') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="quantity" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Quantity') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="destination" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Destination') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="status" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Status') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="sales" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Total Sale') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="cost_product" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Product Cost') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="cost_shipping" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Shipping Cost') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="cost_loss" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Losses / Taxes') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="cost_total" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Total Cost') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="profit" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Net Margin') }}</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" name="columns[]" value="margin_percent" checked class="rounded border-slate-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span>{{ __('Margin (%)') }}</span>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="document.getElementById('exportForm').submit(); toggleExportModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-slate-900 text-base font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Download') }}
                    </button>
                    <button type="button" onclick="toggleExportModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleExportModal() {
            const modal = document.getElementById('exportModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
            } else {
                modal.classList.add('hidden');
            }
        }
    </script>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Enterprise Chart Config (Shadcn UI Style) ---
            Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
            Chart.defaults.color = '#64748b'; // slate-500
            
            // Modern Tooltips
            Chart.defaults.plugins.tooltip.backgroundColor = '#0f172a'; // slate-900
            Chart.defaults.plugins.tooltip.titleColor = '#f8fafc'; // slate-50
            Chart.defaults.plugins.tooltip.bodyColor = '#f1f5f9'; // slate-100
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 6;
            Chart.defaults.plugins.tooltip.displayColors = false; // Minimalist
            Chart.defaults.plugins.tooltip.titleFont = { size: 13, weight: '600' };
            Chart.defaults.plugins.tooltip.bodyFont = { size: 12 };

            // 1. Weekly Profit Chart - Thin Bars, Modern
            const weeklyProfitCanvas = document.getElementById('weeklyProfitChart');
            if (weeklyProfitCanvas) {
                new Chart(weeklyProfitCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($chartData['weeklyProfitChart']['labels']),
                        datasets: [{
                            label: '{{ __('Profit') }}',
                            data: @json($chartData['weeklyProfitChart']['data']),
                            backgroundColor: '#f97316', // Orange-500
                            hoverBackgroundColor: '#ea580c', // Orange-600
                            borderRadius: 4,
                            barThickness: 24, // Thinner bars looking more elegant
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 }, color: '#94a3b8' }
                            },
                            y: {
                                border: { display: false },
                                grid: { color: '#f1f5f9', borderDash: [4, 4] }, // Dashed subtle grid
                                ticks: { 
                                    padding: 10,
                                    callback: (val) => val.toLocaleString('{{ str_replace('_', '-', app()->getLocale()) }}') + ' MAD' 
                                }
                            }
                        }
                    }
                });
            }

            // 2. Shipped Products Chart - Smooth Area Line
            const shippedCanvas = document.getElementById('shippedProductsChart');
            if (shippedCanvas) {
                const ctx = shippedCanvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(249, 115, 22, 0.1)'); // Orange fade
                gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartData['shippedProductsChart']['labels']),
                        datasets: [{
                            label: '{{ __('Shipped') }}',
                            data: @json($chartData['shippedProductsChart']['data']),
                            borderColor: '#f97316',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            pointRadius: 0, // No points by default
                            pointHoverRadius: 4,
                            fill: true,
                            tension: 0.4 // Smooth curve
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 }, color: '#94a3b8' }
                            },
                            y: {
                                border: { display: false },
                                grid: { color: '#f1f5f9' },
                                ticks: { stepSize: 1 }
                            }
                        }
                    }
                });
            }

            // 3. Top Products - Minimalist Horizontal Bar
            const topProductsCanvas = document.getElementById('topProductsChart');
            if (topProductsCanvas) {
                new Chart(topProductsCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($chartData['topProductsChart']['labels']),
                        datasets: [{
                            data: @json($chartData['topProductsChart']['data']),
                            backgroundColor: '#334155', // Slate-700
                            hoverBackgroundColor: '#0f172a', // Slate-900
                            borderRadius: 4,
                            barThickness: 20,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                grid: { color: '#f1f5f9', borderDash: [4, 4] },
                                border: { display: false },
                            },
                            y: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { 
                                    font: { size: 12, weight: '500' },
                                    color: '#334155',
                                    autoSkip: false
                                }
                            }
                        }
                    }
                });
            }


            // 4. Cost Distribution - Modern Donut
            const costDistCanvas = document.getElementById('costDistributionChart');
            if (costDistCanvas) {
                new Chart(costDistCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartData['costDistributionChart']['labels']),
                        datasets: [{
                            data: @json($chartData['costDistributionChart']['data']),
                            backgroundColor: [
                                '#3b82f6', // Products: Blue-500
                                '#f97316', // Shipping: Orange-500
                                '#ef4444', // Others: Red-500
                                '#10b981', // Profit: Emerald-500
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%', // Thinner ring
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    font: { size: 12 }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>