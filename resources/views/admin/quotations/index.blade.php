<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <!-- Clipboard List Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Quotations Management') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Quotations') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <button class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-slate-700 bg-white border border-slate-300 rounded hover:bg-slate-50 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ __('Export') }}
                        </button>
                        <a href="{{ route('admin.quotations.select-request') }}" class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-white bg-slate-900 rounded hover:bg-slate-800 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('New Quotation') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Section 1: KPIs (Summary Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Quotations -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Quotations') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $totalQuotations }}</h3>
                        </div>
                        <div class="p-2 bg-orange-50 rounded-md text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400">{{ __('All time volume') }}</span>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Pending') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $pendingQuotations }}</h3>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-md text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                         <span class="text-amber-600 font-medium">{{ __('Awaiting review') }}</span>
                    </div>
                </div>

                <!-- Approved -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Approved') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $approvedQuotations }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-50 rounded-md text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                         <span class="text-emerald-600 font-medium">{{ __('Accepted by clients') }}</span>
                    </div>
                </div>

                <!-- Rejected -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Rejected') }}</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $rejectedQuotations }}</h3>
                        </div>
                        <div class="p-2 bg-red-50 rounded-md text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                         <span class="text-red-600 font-medium">{{ __('Declined by clients') }}</span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Filters Bar -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 sticky top-20 z-10">
                <form action="{{ route('admin.quotations.index') }}" method="GET">
                    <div class="flex flex-col lg:flex-row gap-4 items-end lg:items-center justify-between">
                        
                        <!-- Inputs Group -->
                        <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto flex-1 md:items-end">
                            <div class="w-full md:w-64">
                                <label for="search" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Search') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="text" name="search" id="search" placeholder="{{ __('Search quotations...') }}" value="{{ request('search') }}"
                                        class="pl-9 w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors">
                                </div>
                            </div>

                            <div class="w-full md:w-48">
                                <label for="status" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Status') }}</label>
                                <select name="status" id="status" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                </select>
                            </div>
                            
                            <div class="pb-[1px]">
                                <button type="submit" class="w-full md:w-auto h-[34px] px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center justify-center gap-2">
                                    {{ __('Apply') }}
                                </button>
                            </div>
                            
                            @if(request()->has('search') || request()->has('status'))
                                <div class="pb-[1px]">
                                    <a href="{{ route('admin.quotations.index') }}" class="w-full md:w-auto h-[34px] px-4 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded transition-colors shadow-sm flex items-center justify-center gap-2">
                                        {{ __('Clear') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Section 3: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Active Quotations') }}</h3>
                    <div class="text-xs text-slate-500">
                        {{ __('Total') }}: <span class="font-medium text-slate-900">{{ $quotations->total() }}</span> {{ __('records') }}
                    </div>
                </div>

                @if ($quotations->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                        <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">{{ __('No Quotations Found') }}</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm">{{ __('There are no quotations matching your criteria. Create a new quotation to get started.') }}</p>
                        <a href="{{ route('admin.quotations.select-request') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-slate-900 rounded hover:bg-slate-800 transition-colors">
                            {{ __('Create Quotation') }}
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Quotation') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Product') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach ($quotations as $quotation)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <!-- Quotation ID -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 rounded border border-slate-200 overflow-hidden flex-shrink-0">
                                                    @if ($quotation->sourcingRequest->product_image)
                                                        <img src="{{ asset('storage/' . $quotation->sourcingRequest->product_image) }}"
                                                             alt="{{ $quotation->sourcingRequest->product_name }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-orange-50 text-orange-500 font-bold text-xs">
                                                            {{ mb_substr($quotation->sourcingRequest->product_name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-mono font-medium text-orange-600">#{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}</div>
                                                    <div class="text-[11px] text-slate-400">{{ $quotation->sourcingRequest->category->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Product -->
                                        <td class="px-6 py-3">
                                            <div class="text-sm font-semibold text-slate-900 max-w-xs truncate">
                                                {{ $quotation->sourcingRequest->product_name }}
                                            </div>
                                        </td>

                                        <!-- Client -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                    {{ substr($quotation->sourcingRequest->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-slate-700">{{ $quotation->sourcingRequest->user->name }}</span>
                                                    <span class="text-[10px] text-slate-400">{{ $quotation->sourcingRequest->user->email }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                                                    'approved' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                                    'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'border' => 'border-red-200', 'dot' => 'bg-red-500'],
                                                ];
                                                $config = $statusConfig[$quotation->status] ?? $statusConfig['pending'];
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold {{ $config['bg'] }} {{ $config['text'] }} {{ $config['border'] }} border uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                                {{ __(ucfirst($quotation->status)) }}
                                            </span>
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-3 whitespace-nowrap text-right">
                                            <div class="text-sm font-bold text-slate-900">
                                                {{ number_format($quotation->amount, 2) }} <span class="text-[10px] text-slate-400 font-normal ml-0.5">{{ $quotation->currency }}</span>
                                            </div>
                                        </td>

                                        <!-- Date -->
                                        <td class="px-6 py-3 whitespace-nowrap text-right">
                                            <div class="text-sm text-slate-600">{{ $quotation->created_at->format('d/m/Y') }}</div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-3 whitespace-nowrap text-center">
                                            <a href="{{ route('admin.quotations.show', $quotation) }}" class="text-slate-400 hover:text-orange-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($quotations->hasPages())
                        <div class="bg-white px-6 py-3 border-t border-slate-200">
                            {{ $quotations->appends(request()->query())->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>