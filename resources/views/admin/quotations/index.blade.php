<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -m-6 p-6 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Quotations') }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1.5">{{ __('Manage and track all quotations') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export
<a href="{{ route('admin.quotations.select-request') }}" class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('New Quotation') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Quotations') }}</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $totalQuotations }}</p>
                        </div>
                        <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $pendingQuotations }}</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $approvedQuotations }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $rejectedQuotations }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <form action="{{ route('admin.quotations.index') }}" method="GET" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                    <div class="flex-1 max-w-lg">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="{{ __('Search quotations...') }}" value="{{ $request->search ?? '' }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-sm dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-sm bg-white dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="pending" {{ ($request->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ ($request->status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ ($request->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filters
                        </button>
                    </div>
                </div>
            </form>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <a href="{{ route('admin.quotations.index', array_merge($request->query(), ['sort_by' => 'id', 'sort_direction' => $request->sort_by == 'id' && $request->sort_direction == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        ID
                                        @if($request->sort_by == 'id')
                                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $request->sort_direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Product Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Client') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <a href="{{ route('admin.quotations.index', array_merge($request->query(), ['sort_by' => 'amount', 'sort_direction' => $request->sort_by == 'amount' && $request->sort_direction == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        {{ __('Amount') }}
                                        @if($request->sort_by == 'amount')
                                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $request->sort_direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <a href="{{ route('admin.quotations.index', array_merge($request->query(), ['sort_by' => 'created_at', 'sort_direction' => $request->sort_by == 'created_at' && $request->sort_direction == 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                        {{ __('Date') }}
                                        @if($request->sort_by == 'created_at')
                                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $request->sort_direction == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($quotations as $quotation)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-violet-100 dark:bg-violet-900/50 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $quotation->sourcingRequest->product_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                    {{ substr($quotation->sourcingRequest->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $quotation->sourcingRequest->user->name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($quotation->amount, 2) }} <span class="text-gray-500 dark:text-gray-400 font-normal">{{ $quotation->currency }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 border-yellow-200 dark:border-yellow-700',
                                                'approved' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 border-green-200 dark:border-green-700',
                                                'rejected' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 border-red-200 dark:border-red-700',
                                            ];
                                            $statusClass = $statusColors[$quotation->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border {{ $statusClass }}">
                                            {{ ucfirst($quotation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $quotation->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty State (if no quotations) -->
                @if($quotations->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ __('No quotations found') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Get started by creating a new quotation.') }}</p>
                    <a href="{{ route('admin.quotations.select-request') }}" class="inline-flex items-center px-4 py-2 bg-violet-600 hover:bg-violet-700 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Create Quotation') }}
                    </a>
                </div>
                @endif

                <!-- Pagination -->
                @if($quotations->hasPages())
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400">
                            Showing <span class="font-medium">{{ $quotations->firstItem() }}</span> to <span class="font-medium">{{ $quotations->lastItem() }}</span> of <span class="font-medium">{{ $quotations->total() }}</span> results
                        </div>
                        <div>
                            {{ $quotations->appends($request->query())->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>