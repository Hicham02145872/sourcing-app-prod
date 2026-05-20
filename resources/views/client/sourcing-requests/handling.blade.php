<x-app-layout>
    

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Statistics Bar --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 mb-6">
                <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Total Requests --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->count() }}</p>
                            </div>
                        </div>

                        {{-- Pending --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Pending') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->where('status', 'pending')->count() }}</p>
                            </div>
                        </div>

                        {{-- Quoted --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Quoted') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->where('status', 'quoted')->count() }}</p>
                            </div>
                        </div>

                        {{-- Completed --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Completed') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->where('status', 'completed')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sourcing Requests Section --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                {{-- Control Panel --}}
                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('All Requests') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-[#EF7722]">{{ $sourcingRequests->count() }}</span> {{ Str::plural(__('request'), $sourcingRequests->count()) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Filters --}}
                        <form action="{{ route('client.sourcing-requests.handling') }}" method="GET" id="filterForm" class="w-full lg:w-auto">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <div class="relative flex-1 lg:w-64">
                                    <input type="text" 
                                           name="search" 
                                           id="searchInput"
                                           placeholder="{{ __('Search product...') }}" 
                                           value="{{ request('search') }}" 
                                           class="w-full pl-9 pr-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm">
                                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>

                                <select name="category" 
                                        id="categoryFilter"
                                        class="px-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white font-medium shadow-sm">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <select name="status" 
                                        id="statusFilter" 
                                        class="px-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white font-medium shadow-sm">
                                    <option value="all">{{ __('All Statuses') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="quoted" {{ request('status') == 'quoted' ? 'selected' : '' }}>{{ __('Quoted') }}</option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>{{ __('Accepted') }}</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                </select>

                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                        {{ __('Filter') }}
                                    </button>
                                    <button type="button" 
                                            id="clearFilters"
                                            class="px-3 py-2 text-sm bg-[#EBEBEB] dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 rounded-lg font-medium transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="p-6">
                    @if ($sourcingRequests->isEmpty())
                        {{-- Empty State --}}
                        <div class="text-center py-20 px-6">
                            <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No requests yet') }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Start by creating your first sourcing request') }}</p>
                            <a href="{{ route('client.sourcing-requests.create') }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Create First Request') }}
                            </a>
                        </div>
                    @else
                        {{-- Requests Table --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Request') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Product') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Status') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Quantity') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Category') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Date') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                    @foreach ($sourcingRequests as $request)
                                        <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150 {{ $request->status === 'quoted' ? 'bg-red-50/50 dark:bg-red-900/10 border-l-4 border-red-500' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative">
                                                        <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600 overflow-hidden flex-shrink-0">
                                                            @if ($request->product_image)
                                                                <img src="{{ asset('storage/' . $request->product_image) }}"
                                                                     alt="{{ $request->product_name }}"
                                                                     class="w-full h-full object-cover">
                                                            @else
                                                                <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                                    <span class="text-sm font-bold text-[#EF7722]">
                                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if($request->status === 'quoted')
                                                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <div class="text-xs font-semibold text-[#EF7722] mb-0.5">{{ $request->reference_id }}</div>
                                                            @if($request->status === 'quoted')
                                                                <span class="px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-black rounded uppercase tracking-tighter animate-pulse">{{ __('New') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $request->category?->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-slate-900 dark:text-white max-w-xs truncate">
                                                    {{ $request->product_name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusConfig = [
                                                        'pending' => ['color' => '#FAA533', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'quoted' => ['color' => '#EF7722', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                                                        'in_review' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'accepted' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        'completed' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    ];
                                                    $statusData = $statusConfig[$request->status] ?? $statusConfig['pending'];
                                                @endphp
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold" style="background-color: {{ $statusData['color'] }}22; color: {{ $statusData['color'] }};">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                                    </svg>
                                                    {{ __(ucfirst(str_replace('_', ' ', $request->status))) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ number_format($request->destinations->sum('quantity')) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                                    {{ $request->category?->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900 dark:text-white">{{ $request->created_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $request->created_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm">
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
                        @if($sourcingRequests->hasPages())
                            <div class="mt-6 px-6 py-4 border-t border-[#EBEBEB] dark:border-slate-700 bg-[#EBEBEB] dark:bg-slate-900/50">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="text-sm text-slate-700 dark:text-slate-300">
                                        {{ __('Showing') }}
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $sourcingRequests->firstItem() }}</span>
                                        {{ __('to') }}
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $sourcingRequests->lastItem() }}</span>
                                        {{ __('of') }}
                                        <span class="font-semibold text-slate-900 dark:text-white">{{ $sourcingRequests->total() }}</span>
                                        {{ __('results') }}
                                    </div>
                                    <div class="flex gap-1">
                                        {{ $sourcingRequests->links() }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const statusFilter = document.getElementById('statusFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            
            let debounceTimeout;

            if(searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        filterForm.submit();
                    }, 500); // 500ms delay
                });
            }

            if(categoryFilter) {
                categoryFilter.addEventListener('change', () => filterForm.submit());
            }
            if(statusFilter) {
                statusFilter.addEventListener('change', () => filterForm.submit());
            }

            if (clearFiltersBtn && filterForm) {
                clearFiltersBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (categoryFilter) categoryFilter.value = '';
                    if (statusFilter) statusFilter.value = 'all';
                    filterForm.submit();
                });
            }
        });
    </script>
    @endpush

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
            background: #EF7722;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Grid background pattern */
        .bg-grid-white\/\[0\.05\] {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }

        /* Custom focus styles for inputs with custom colors */
        input:focus, select:focus {
            outline: none;
        }

        button[type="submit"]:active {
            transform: scale(0.98);
        }
    </style>
</x-app-layout>