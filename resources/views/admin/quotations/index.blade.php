<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-violet-600 dark:bg-violet-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Quotations Management') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Enterprise quotation tracking and management system') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-start">
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Export') }}
                    </button>
                    <a href="{{ route('admin.quotations.select-request') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold bg-violet-600 hover:bg-violet-700 dark:bg-violet-700 dark:hover:bg-violet-600 text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('New Quotation') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total Quotations') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $totalQuotations }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                {{ __('All time') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Pending') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $pendingQuotations }}</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Awaiting review') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Approved') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $approvedQuotations }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Accepted by clients') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Rejected') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $rejectedQuotations }}</p>
                            <p class="text-xs text-red-600 dark:text-red-400 font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                {{ __('Declined by clients') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-violet-600 rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Active Quotations') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-violet-600 dark:text-violet-400">{{ $quotations->total() }}</span> {{ Str::plural('quotation', $quotations->total()) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <form action="{{ route('admin.quotations.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                            {{-- Search --}}
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search"
                                       placeholder="{{ __('Search quotations...') }}" 
                                       value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm font-medium">
                            </div>

                            {{-- Status Filter --}}
                            <div class="w-full sm:w-48">
                                <select name="status" class="block w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm font-medium">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-700 dark:bg-violet-700 dark:hover:bg-violet-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                {{ __('Apply Filters') }}
                            </button>
                            <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Content --}}
                @if ($quotations->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Quotations Found') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('There are no quotations matching your criteria. Create a new quotation to get started.') }}</p>
                        <a href="{{ route('admin.quotations.select-request') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 dark:bg-violet-700 dark:hover:bg-violet-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Create Quotation') }}
                        </a>
                    </div>
                @else
                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Quotation') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Product') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Client') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Amount') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach ($quotations as $quotation)
                                    <tr class="hover:bg-violet-50 dark:hover:bg-violet-900/10 transition-colors duration-150">
                                        {{-- Quotation ID --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 overflow-hidden flex-shrink-0">
                                                    @if ($quotation->sourcingRequest->product_image)
                                                        <img src="{{ asset('storage/' . $quotation->sourcingRequest->product_image) }}"
                                                             alt="{{ $quotation->sourcingRequest->product_name }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-violet-100 dark:bg-violet-900/30">
                                                            <span class="text-sm font-bold text-violet-600 dark:text-violet-400">
                                                                {{ mb_substr($quotation->sourcingRequest->product_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900 dark:text-white">#{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}</div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $quotation->sourcingRequest->category->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Product --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-slate-900 dark:text-white max-w-xs truncate">
                                                {{ $quotation->sourcingRequest->product_name }}
                                            </div>
                                        </td>

                                        {{-- Client --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                                        {{ substr($quotation->sourcingRequest->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $quotation->sourcingRequest->user->name }}</div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $quotation->sourcingRequest->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    'approved' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    'rejected' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                                ];
                                                $statusData = $statusConfig[$quotation->status] ?? $statusConfig['pending'];
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900/30 text-{$statusData['color']}-700 dark:text-{$statusData['color']}-400" }}">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                                </svg>
                                                {{ __(ucfirst($quotation->status)) }}
                                            </span>
                                        </td>

                                        {{-- Amount --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                {{ number_format($quotation->amount, 2) }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $quotation->currency }}</div>
                                        </td>

                                        {{-- Date --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-900 dark:text-white">{{ $quotation->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $quotation->created_at->diffForHumans() }}</div>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.quotations.show', $quotation) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-600 hover:bg-violet-700 dark:bg-violet-700 dark:hover:bg-violet-600 text-white text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                                                    {{ __('View') }}
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($quotations->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ __('Showing') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $quotations->firstItem() }}</span>
                                    {{ __('to') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $quotations->lastItem() }}</span>
                                    {{ __('of') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $quotations->total() }}</span>
                                    {{ __('results') }}
                                </div>
                                <div class="flex gap-1">
                                    {{ $quotations->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Enterprise table styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #6d28d9;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>