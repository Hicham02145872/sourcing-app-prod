<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Sourcing Requests') }}
                    </h2>
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">{{ __('Manage and track all your sourcing requests') }}</p>
                </div>
                <a href="{{ route('client.sourcing-requests.create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 w-full sm:w-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('New Request') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if ($sourcingRequests->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-16 px-6">
                        <div class="mx-auto w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No Sourcing Requests Found') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-8">{{ __('Create your first sourcing request to get started or adjust your filters.') }}</p>
                        <a href="{{ route('client.sourcing-requests.create') }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Create First Request') }}
                        </a>
                    </div>
                @else
                    {{-- Header Section with Filters --}}
                    <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('All Requests') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sourcingRequests->count() }} {{ Str::plural('request', $sourcingRequests->count()) }} {{ __('in total') }}</p>
                                </div>
                            </div>
                            
                            <!-- Filter/Sort Options -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <select class="w-full sm:w-40 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-white font-medium">
                                    <option>{{ __('All Statuses') }}</option>
                                    <option>{{ __('Pending') }}</option>
                                    <option>{{ __('Quoted') }}</option>
                                    <option>{{ __('Accepted') }}</option>
                                    <option>{{ __('Completed') }}</option>
                                </select>
                                <select class="w-full sm:w-40 px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-white font-medium">
                                    <option>{{ __('Newest First') }}</option>
                                    <option>{{ __('Oldest First') }}</option>
                                    <option>{{ __('Status') }}</option>
                                </select>
                                <button class="w-full sm:w-auto p-2.5 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-blue-400 dark:hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Requests List --}}
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($sourcingRequests as $request)
                            <div class="group p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                    {{-- Image and Basic Info --}}
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        {{-- Product Image --}}
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                                @if ($request->product_image)
                                                    <img src="{{ asset('storage/' . $request->product_image) }}"
                                                         alt="{{ $request->product_name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-blue-50 dark:bg-blue-900/20">
                                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                            {{ mb_substr($request->product_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Product Details --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-2">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                                        {{ $request->product_name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 dark:bg-blue-900/30 rounded-full text-xs font-medium text-blue-700 dark:text-blue-300">
                                                            {{ $request->category->name }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $request->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Status Badge --}}
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $statusConfig = [
                                                            'pending' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                            'quoted' => ['color' => 'red', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                                                            'accepted' => ['color' => 'purple', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                            'in_review' => ['color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                                            'completed' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        ];
                                                        $statusData = $statusConfig[$request->status] ?? $statusConfig['pending'];
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900/30 text-{$statusData['color']}-700 dark:text-{$statusData['color']}-300" }}">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                                        </svg>
                                                        {{ __(ucfirst(str_replace('_', ' ', $request->status))) }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Metadata --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                                {{-- Destinations --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Destinations') }}:</span>
                                                    <div class="flex items-center -space-x-1">
                                                        @foreach($request->destinations->take(3) as $destination)
                                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-base border-2 border-white dark:border-gray-800 rounded shadow-xs" title="{{ $destination->country->name }}"></span>
                                                        @endforeach
                                                        @if($request->destinations->count() > 3)
                                                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 ml-1">
                                                                +{{ $request->destinations->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Shipping Method --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Shipping') }}:</span>
                                                    <span class="text-gray-900 dark:text-white font-medium capitalize">
                                                        {{ $request->shipping_method ? __(ucfirst($request->shipping_method)) : __('N/A') }}
                                                    </span>
                                                </div>

                                                {{-- Total Units --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Total Units') }}:</span>
                                                    <span class="text-gray-900 dark:text-white font-medium">
                                                        {{ number_format($request->destinations->sum('quantity')) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Button --}}
                                    <div class="flex-shrink-0 lg:pl-4">
                                        <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                           class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 group shadow-sm hover:shadow-md">
                                            <span>{{ __('View Details') }}</span>
                                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($sourcingRequests->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700 dark:text-gray-400">
                                    {{ __('Showing') }}
                                    <span class="font-medium">{{ $sourcingRequests->firstItem() }}</span>
                                    {{ __('to') }}
                                    <span class="font-medium">{{ $sourcingRequests->lastItem() }}</span>
                                    {{ __('of') }}
                                    <span class="font-medium">{{ $sourcingRequests->total() }}</span>
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

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @keyframes pulse-fast {
            0%, 100% { 
                opacity: 1; 
            }
            50% { 
                opacity: 0.8; 
            }
        }

        .animate-pulse-fast {
            animation: pulse-fast 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .grid-cols-1\.5 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-layout>