<x-app-layout>
    <x-slot name="header">
        
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ __('Sourcing Requests') }}
                        </h2>
                        <p class="mt-0 text-base text-gray-500 dark:text-gray-400">{{ __('Manage and track all your sourcing requests') }}</p>
                    </div>
                    <a href="{{ route('client.sourcing-requests.create') }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('New Request') }}
                    </a>
                </div>
            </div>
        
    </x-slot>

    <div class="py-3 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if ($sourcingRequests->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-24 h-24 bg-violet-100 dark:bg-violet-900/30 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-12 h-12 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No Sourcing Requests Found') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-8">{{ __('Create your first sourcing request to get started or adjust your filters.') }}</p>
                        <a href="{{ route('client.sourcing-requests.create') }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
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
                                <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('All Requests') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sourcingRequests->count() }} {{ Str::plural('request', $sourcingRequests->count()) }} {{ __('in total') }}</p>
                                </div>
                            </div>
                            
                            <!-- Filter/Sort Options (Placeholder logic - requires actual backend logic for filter/sort) -->
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <select class="w-full sm:w-auto px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 dark:text-white font-medium shadow-sm">
                                    <option>{{ __('All Statuses') }}</option>
                                    <option>{{ __('Pending') }}</option>
                                    <option>{{ __('Quoted') }}</option>
                                    <option>{{ __('Accepted') }}</option>
                                    <option>{{ __('Completed') }}</option>
                                </select>
                                <button class="w-full sm:w-auto p-2.5 border border-gray-300 dark:border-gray-600 rounded-lg hover:border-violet-400 dark:hover:border-violet-600 hover:bg-violet-50 dark:hover:bg-gray-700 transition-all shadow-sm">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Cards Grid --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-violet-400 dark:hover:border-violet-600 shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                                    <!-- Image Section -->
                                    <div class="relative h-48 bg-gray-50 dark:bg-gray-900 overflow-hidden">
                                        @if ($request->product_image)
                                            <img src="{{ asset('storage/' . $request->product_image) }}"
                                                 alt="{{ $request->product_name }}"
                                                 class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-20 h-20 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center shadow-inner">
                                                    <span class="text-3xl font-extrabold text-violet-600 dark:text-violet-400">
                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3">
                                            @php
                                                $statusClass = [
                                                    'pending' => 'bg-amber-500 text-white border-amber-400',
                                                    'quoted' => 'bg-red-500 text-white border-red-400 animate-pulse-fast',
                                                    'accepted' => 'bg-purple-500 text-white border-purple-400',
                                                    'in_review' => 'bg-blue-500 text-white border-blue-400',
                                                    'completed' => 'bg-green-500 text-white border-green-400',
                                                ][$request->status] ?? 'bg-gray-500 text-white border-gray-400';
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full shadow-md border {{ $statusClass }}">
                                                {{ __(ucfirst(str_replace('_', ' ', $request->status))) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <!-- Header -->
                                        <div class="mb-4">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                                                {{ $request->product_name }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center px-2.5 py-0.5 bg-violet-100 dark:bg-violet-900/30 rounded-full">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-violet-700 dark:text-violet-300">{{ $request->category->name }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
                                                    {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Metadata -->
                                        <div class="space-y-3 mb-5 border-t border-b border-gray-100 dark:border-gray-700 py-4">
                                            @if($request->destinations->count() > 0)
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ __('Destinations') }}
                                                    </div>
                                                    <div class="flex items-center -space-x-1">
                                                        @foreach($request->destinations->take(3) as $destination)
                                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-lg border-2 border-white dark:border-gray-800 rounded-full shadow-sm" title="{{ $destination->country->name }}"></span>
                                                        @endforeach
                                                        @if($request->destinations->count() > 3)
                                                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400 ml-2">
                                                                +{{ $request->destinations->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                    {{ __('Shipping Method') }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-900 dark:text-white capitalize">
                                                    {{ $request->shipping_method ? __(ucfirst($request->shipping_method)) : __('N/A') }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ __('Total Units') }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ number_format($request->destinations->sum('quantity')) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                           class="w-full bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl">
                                            <span>{{ __('View Details') }}</span>
                                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>