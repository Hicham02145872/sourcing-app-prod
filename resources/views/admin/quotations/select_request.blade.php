<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <!-- Cube/Box Icon for Sourcing -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Sourcing Requests') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Requests') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded border border-slate-200">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-semibold text-slate-600">{{ now()->format('F d, Y') }}</span>
                        </div>
                        <button class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded hover:bg-slate-50 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            {{ __('Filter') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- Section 1: KPIs (Summary Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- In Review -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('In Review') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $sourcingRequestsInReview }}
                            </h3>
                        </div>
                        <div class="p-2 bg-orange-50 rounded-md text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                         <span class="text-orange-600 font-medium flex items-center gap-1">
                            {{ __('Awaiting action') }}
                         </span>
                    </div>
                </div>

                <!-- Pending Quotations -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('Pending Quotations') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $pendingQuotations }}
                            </h3>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-md text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400">{{ __('Ready for quotes') }}</span>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ __('This Month') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">
                                {{ $sourcingRequestsThisMonth }}
                            </h3>
                        </div>
                        <div class="p-2 bg-emerald-50 rounded-md text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400">{{ __('Monthly total') }}</span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Active Requests') }}</h3>
                         <div class="text-xs text-slate-500 mt-1">
                             <span class="font-medium text-slate-900">{{ count($sourcingRequests) }}</span> {{ Str::plural(__('request'), count($sourcingRequests)) }} {{ __('pending') }}
                        </div>
                    </div>
                </div>
                
                @if ($sourcingRequests->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">{{ __('No Sourcing Requests') }}</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm text-center">{{ __('All requests have been processed or no requests are pending. New requests will appear here once clients submit them.') }}</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Request') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Product') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Category') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach ($sourcingRequests as $request)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <!-- ID -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 rounded border border-slate-200 overflow-hidden flex-shrink-0">
                                                    @if ($request->product_image)
                                                        <img src="{{ asset('storage/' . $request->product_image) }}" alt="{{ $request->product_name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-mono font-medium text-orange-600">#{{ $request->display_id }}</div>
                                                    <div class="text-xs text-slate-400">{{ $request->created_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Product -->
                                        <td class="px-6 py-3">
                                            <div class="max-w-xs">
                                                <div class="text-sm font-semibold text-slate-900 truncate">{{ $request->product_name }}</div>
                                                @if($request->product_url)
                                                    <a href="{{ $request->product_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-blue-600 hover:text-blue-800 mt-0.5 font-medium">
                                                        {{ __('View Link') }}
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Category -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $request->category?->name ?? __('Uncategorized') }}
                                            </span>
                                        </td>

                                        <!-- Client -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-6 w-6 rounded-full bg-orange-100 flex items-center justify-center text-[10px] font-bold text-orange-600 mr-2">
                                                    {{ substr($request->user->name, 0, 1) }}
                                                </div>
                                                <div class="text-sm text-slate-700 font-medium">{{ $request->user->name }}</div>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                {{ __('In Review') }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.quotations.create', $request) }}" class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-slate-900 hover:bg-slate-800 transition-colors shadow-sm">
                                                {{ __('Create Quote') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($sourcingRequests->hasPages())
                        <div class="bg-white px-6 py-3 border-t border-slate-200">
                            {{ $sourcingRequests->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>