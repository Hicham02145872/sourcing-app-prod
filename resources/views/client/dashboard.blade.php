<x-app-layout>
    <x-slot name="header">
        <div class="w-full">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white truncate">
                            {{ __('Client Dashboard') }}
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ __('Manage and track your sourcing requests efficiently') }}
                        </p>
                    </div>
                    <a href="{{ route('client.sourcing-requests.create') }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm sm:text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:-translate-y-0.5 flex-shrink-0 whitespace-nowrap">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>{{ __('New Request') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6 sm:space-y-8">
            
            {{-- Welcome Banner --}}
            <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-800 dark:to-blue-900 rounded-xl shadow-lg overflow-hidden">
                <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:16px_16px]"></div>
                <div class="relative px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">
                                {{ __('Welcome Back,') }} {{ Auth::user()->name }}!
                            </h3>
                            <p class="text-sm text-blue-100 mt-2 max-w-xl">
                                {{ __('Your centralized dashboard to manage and track the progress of all your sourcing requests.') }}
                            </p>
                        </div>
                        <div class="hidden lg:flex flex-shrink-0 items-center justify-center">
                            <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- Total Requests Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-md transition-all duration-300 group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">
                                {{ __('Total Requests') }}
                            </p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $sourcingRequests->count() }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ __('All time requests') }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Action Required Card --}}
                <a href="{{ route('client.sourcing-requests.index', ['status' => 'quoted']) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-md hover:ring-2 hover:ring-amber-500 transition-all duration-300 group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-amber-600 dark:text-amber-400 mb-1 uppercase tracking-wider">
                                {{ __('Action Required') }}
                            </p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $sourcingRequests->where('status', 'quoted')->count() }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ __('New quotes awaiting approval') }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- In Progress Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-md hover:ring-2 hover:ring-blue-500 transition-all duration-300 group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-1 uppercase tracking-wider">
                                {{ __('In Progress') }}
                            </p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $sourcingRequests->whereIn('status', ['pending', 'in_review', 'accepted'])->count() }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ __('Currently being processed') }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completed Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-md hover:ring-2 hover:ring-green-500 transition-all duration-300 group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-green-600 dark:text-green-400 mb-1 uppercase tracking-wider">
                                {{ __('Completed') }}
                            </p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $sourcingRequests->where('status', 'completed')->count() }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                {{ __('Successfully delivered') }}
                            </p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sourcing Requests Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        {{-- Title --}}
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="truncate">{{ __('Sourcing Requests') }}</span>
                        </h3>

                        {{-- Filters --}}
                        <form action="{{ route('client.dashboard') }}" method="GET" id="filterForm" class="w-full lg:w-auto">
                            <div class="flex flex-col sm:flex-row gap-3">
                                {{-- Search --}}
                                <div class="relative flex-1">
                                    <input type="text" 
                                           name="search" 
                                           id="searchInput"
                                           placeholder="{{ __('Search product...') }}" 
                                           value="{{ request('search') }}" 
                                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-white">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>

                                {{-- Category & Status --}}
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <select name="category" 
                                            id="categoryFilter"
                                            class="w-full sm:w-40 px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-white">
                                        <option value="">{{ __('All Categories') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <select name="status" 
                                            id="statusFilter" 
                                            class="w-full sm:w-40 px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-white">
                                        <option value="all">{{ __('All Statuses') }}</option>
                                        <option value="quoted" {{ request('status') == 'quoted' ? 'selected' : '' }}>{{ __('Action Required') }}</option>
                                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                    </select>
                                </div>

                                {{-- Buttons --}}
                                <div class="flex gap-2">
                                    <button type="submit" 
                                            class="flex-1 sm:flex-initial px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-sm transition-all duration-200">
                                        {{ __('Filter') }}
                                    </button>
                                    <button type="button" 
                                            id="clearFilters"
                                            title="{{ __('Clear filters') }}"
                                            class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg font-medium transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    @if ($sourcingRequests->isEmpty())
                        <div class="text-center py-12 sm:py-20">
                            <div class="relative inline-flex items-center justify-center mb-6">
                                <div class="absolute inset-0 bg-blue-100 dark:bg-blue-900/50 rounded-full animate-pulse opacity-20"></div>
                                <div class="relative w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 rounded-full flex items-center justify-center shadow-sm">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ __('No requests yet') }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6 px-4">
                                {{ __('Start by creating your first sourcing request') }}
                            </p>
                            <a href="{{ route('client.sourcing-requests.create') }}" 
                               class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Create First Request') }}
                            </a>
                        </div>
                    @else
                        {{-- Requests List --}}
                        <div class="space-y-4" id="requestsList">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg sm:rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300">
                                    <div class="flex flex-col sm:flex-row">
                                        
                                        {{-- Image Section --}}
                                        <div class="w-full sm:w-32 lg:w-40 h-32 sm:h-auto bg-gray-50 dark:bg-gray-900 relative flex-shrink-0">
                                            @if ($request->product_image)
                                                <img src="{{ asset('storage/' . $request->product_image) }}" 
                                                     alt="{{ $request->product_name }}" 
                                                     class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-300">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-800 dark:to-gray-900">
                                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-blue-400 dark:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            {{-- Status Badge --}}
                                            <div class="absolute top-2 left-2">
                                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full shadow-sm 
                                                    @if($request->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                                    @elseif($request->status === 'quoted') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                    @elseif($request->status === 'in_review') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                    @elseif($request->status === 'accepted') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                                    @elseif($request->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                                    @if($request->status === 'quoted')
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/></svg>
                                                    @endif
                                                    {{ __(ucfirst(str_replace('_', ' ', $request->status))) }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Content Section --}}
                                        <div class="flex-1 p-4 sm:p-5 lg:p-6 flex flex-col justify-between">
                                            {{-- Header --}}
                                            <div class="mb-4">
                                                <div class="flex items-start justify-between gap-2 mb-3">
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-bold text-base sm:text-lg text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" title="{{ $request->product_name }}">
                                                            {{ $request->product_name }}
                                                        </h4>
                                                        <span class="inline-block px-2 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded mt-2">
                                                            {{ $request->category->name }}
                                                        </span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 whitespace-nowrap">
                                                        {{ $request->created_at->diffForHumans() }}
                                                    </p>
                                                </div>

                                                {{-- Info Grid --}}
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 border-t border-b border-gray-100 dark:border-gray-700 py-3">
                                                    {{-- Shipping --}}
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                        </div>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                            <span class="font-medium">{{ __('Ship') }}:</span> {{ $request->shipping_method ? __(ucfirst($request->shipping_method)) : __('N/A') }}
                                                        </p>
                                                    </div>

                                                    {{-- Destinations --}}
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        </div>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                            <span class="font-medium">{{ __('Dest') }}:</span> {{ $request->destinations->count() }}
                                                        </p>
                                                    </div>

                                                    {{-- Quantity --}}
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                            </svg>
                                                        </div>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                            <span class="font-medium">{{ __('Qty') }}:</span> {{ $request->destinations->sum('quantity') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                {{-- Destinations --}}
                                                @if($request->destinations->count() > 0)
                                                <div class="mt-4">
                                                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Countries') }}</p>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach ($request->destinations->take(4) as $destination)
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 dark:bg-gray-700 rounded text-xs border border-gray-200 dark:border-gray-600 whitespace-nowrap">
                                                                <span class="font-medium">{{ $destination->country->name }}</span>
                                                                <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-1.5 rounded text-xs font-bold">{{ $destination->quantity }}</span>
                                                            </span>
                                                        @endforeach
                                                        @if ($request->destinations->count() > 4)
                                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs border border-gray-200 dark:border-gray-600 font-semibold text-gray-600 dark:text-gray-400">
                                                                +{{ $request->destinations->count() - 4 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Actions Footer --}}
                                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                                                <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                                   class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 order-2 sm:order-1">
                                                    <span>{{ __('View Details') }}</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                    </svg>
                                                </a>

                                                <div class="flex items-center justify-end gap-2 order-1 sm:order-2">
                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('client.sourcing-requests.edit', $request) }}" 
                                                       class="w-10 h-10 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 border border-gray-200 dark:border-gray-600 font-medium"
                                                       title="{{ __('Edit') }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>

                                                    {{-- More Actions Dropdown --}}
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" 
                                                                @click.away="open = false"
                                                                type="button" 
                                                                title="{{ __('More') }}"
                                                                class="w-10 h-10 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 border border-gray-200 dark:border-gray-600">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                            </svg>
                                                        </button>
                                                        
                                                        <div x-show="open" 
                                                             x-transition:enter="transition ease-out duration-200"
                                                             x-transition:enter-start="transform opacity-0 scale-95"
                                                             x-transition:enter-end="transform opacity-100 scale-100"
                                                             x-transition:leave="transition ease-in duration-100"
                                                             x-transition:leave-start="transform opacity-100 scale-100"
                                                             x-transition:leave-end="transform opacity-0 scale-95"
                                                             class="absolute right-0 bottom-full mb-2 w-48 sm:w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10 origin-bottom-right">
                                                            
                                                            <p class="px-3 sm:px-4 py-2 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">{{ __('Actions') }}</p>

                                                            {{-- Duplicate --}}
                                                            <form action="{{ route('client.sourcing-requests.duplicate', $request) }}" method="POST" onsubmit="return confirm('{{ __('Duplicate this request?') }}');">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 group">
                                                                    <svg class="w-4 h-4 text-blue-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    <span>{{ __('Duplicate') }}</span>
                                                                </button>
                                                            </form>

                                                            {{-- Delete --}}
                                                            <form action="{{ route('client.sourcing-requests.destroy', $request) }}" method="POST" onsubmit="return confirm('{{ __('Delete permanently?') }}');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                                                                    <svg class="w-4 h-4 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                    <span>{{ __('Delete') }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $sourcingRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clearFiltersBtn = document.getElementById('clearFilters');
            const filterForm = document.getElementById('filterForm');
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const statusFilter = document.getElementById('statusFilter');
            
            if (clearFiltersBtn && filterForm) {
                clearFiltersBtn.addEventListener('click', function() {
                    if (searchInput) searchInput.value = '';
                    if (categoryFilter) categoryFilter.value = '';
                    if (statusFilter) statusFilter.value = 'all';
                    filterForm.submit();
                });
            }

            // Smooth animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('#requestsList > div').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(el);
            });
        });
    </script>

    <style>
        @keyframes pulse-fast {
            0%, 100% { 
                opacity: 1; 
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); 
            }
            50% { 
                opacity: 0.8; 
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0); 
            }
        }

        .animate-pulse-fast {
            animation: pulse-fast 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease forwards;
        }

        .fi {
            line-height: 1;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        }
    </style>
    @endpush
</x-app-layout>