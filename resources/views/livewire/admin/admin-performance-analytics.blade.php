<div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">

    <!-- Header -->
    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900">{{ __('Admin Performance Analytics') }}</h1>
                        <p class="text-xs text-slate-500">{{ __('Workload distribution by admin') }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded bg-slate-800 text-slate-300 text-[10px] font-bold uppercase tracking-wider">{{ __('Real Time') }}</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if(count($metrics) === 0)
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-12 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <p class="text-sm text-slate-500">{{ __('No data available') }}</p>
            </div>
        @else
            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $totalAll = collect($metrics)->sum('total');
                    $totalPaid = collect($metrics)->sum('paid');
                    $totalTransit = collect($metrics)->sum('transit');
                    $totalDelivered = collect($metrics)->sum('delivered');
                @endphp
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Orders') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalAll) }}</h3>
                        </div>
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-2"></span>{{ __('Across all admins') }}
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Paid') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalPaid) }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span>{{ __('Awaiting transit') }}
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-cyan-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('In Transit') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalTransit) }}</h3>
                        </div>
                        <div class="p-2 bg-cyan-50 text-cyan-600 rounded-lg group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 mr-2"></span>{{ __('Moving to destination') }}
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-emerald-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Delivered') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalDelivered) }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>{{ __('Successfully completed') }}
                    </div>
                </div>
            </div>

            <!-- Performance Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            {{ __('Operational Performance Score') }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ __('Productivity analysis by account manager') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $topAdmin = collect($metrics)->sortByDesc('total')->first();
                        @endphp
                        @if($topAdmin)
                            <span class="inline-flex items-center px-2 py-1 rounded bg-orange-500 text-white text-[10px] font-bold uppercase tracking-wider">
                                {{ __('Top') }}: {{ $topAdmin['admin_name'] }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Admin') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Pending Payment') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Paid') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('In Transit') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Delivered') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Issues') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Refund') }}</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($metrics as $row)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">
                                                {{ substr($row['admin_name'] ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-slate-900">{{ $row['admin_name'] ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        @if($row['pending_payment'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-100">{{ $row['pending_payment'] }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        @if($row['paid'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold border border-blue-100">{{ $row['paid'] }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        @if($row['transit'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-cyan-50 text-cyan-700 font-semibold border border-cyan-100">{{ $row['transit'] }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        @if($row['delivered'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100">{{ $row['delivered'] }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        @if($row['issues'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-50 text-red-700 font-semibold border border-red-100">{{ $row['issues'] }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right">
                                        @if($row['refund'] > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-semibold border border-purple-100">{{ $row['refund'] }}</span>
                                        @else
                                            <span class="text-slate-400">0</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-slate-900">{{ $row['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
