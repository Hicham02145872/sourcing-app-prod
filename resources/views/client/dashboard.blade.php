<x-app-layout :clientQuotationCount="$totalClientQuotations">
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- IMPROVED SPAM FOLDER WARNING BANNER --}}
            {{-- High visibility: Amber background, thick left border, shadow --}}
            <div x-data="{ show: localStorage.getItem('spam_warning_dismissed') !== 'true' }" 
                 x-show="show" 
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="relative bg-amber-50 dark:bg-amber-900/20 border-s-4 border-amber-500 rounded-e-lg shadow-md mb-8 p-4 sm:p-5">
                
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        {{-- Animated Pulse Icon --}}
                        <span class="relative flex h-6 w-6">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-6 w-6 bg-amber-100 dark:bg-amber-800 items-center justify-center">
                              <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                              </svg>
                          </span>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0 pt-0.5">
                        <h3 class="text-sm font-bold text-amber-800 dark:text-amber-300 mb-1">
                            {{ __('Important: Check your Email') }}
                        </h3>
                        <p class="text-sm text-amber-700 dark:text-amber-400 leading-relaxed">
                            {{ __('spam_warning_message') }}
                        </p>
                    </div>
                    <button @click="show = false; localStorage.setItem('spam_warning_dismissed', 'true')" 
                            class="flex-shrink-0 -mt-1 -me-1 p-1.5 bg-amber-100 dark:bg-amber-800/50 hover:bg-amber-200 dark:hover:bg-amber-800 rounded-lg text-amber-600 dark:text-amber-400 transition-colors duration-200 group"
                            title="{{ __('Don\'t show this again') }}">
                        <span class="sr-only">{{ __('Dismiss') }}</span>
                        <svg class="h-5 w-5 transform group-hover:rotate-90 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- IMPROVED WATCH DEMO BANNER --}}
            {{-- High visibility: YouTube Red Gradient, Play Button Watermark, Call to Action --}}
            @if($socialMediaLinks && $socialMediaLinks->youtube_url)
            <div x-data="{ show: localStorage.getItem('youtube_demo_dismissed') !== 'true' }" 
                 x-show="show" 
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-4"
                 class="relative bg-gradient-to-br from-[#FF0000] to-[#C4302B] rounded-xl shadow-lg shadow-red-500/20 overflow-hidden mb-8 group">
                
                {{-- Decorative background elements --}}
                <div class="absolute inset-0 bg-grid-white/[0.1] bg-[size:20px_20px]"></div>
                <div class="absolute -end-6 -bottom-6 text-white/10 transform rotate-12 pointer-events-none">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.615 3.184c-3.613-.253-11.128-.253-14.742 0C1.98 3.336.5 4.981.5 7.643v8.52c0 2.662 1.48 4.307 4.373 4.459 3.613.253 11.128.253 14.742 0 2.893-.152 4.373-1.797 4.373-4.459v-8.52c0-2.662-1.48-4.307-4.373-4.459zm-9.544 11.189V7.625l5.064 3.376-5.064 3.372z"/>
                    </svg>
                </div>

                <div class="relative px-6 py-5 flex items-center justify-between flex-wrap gap-5">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-sm">
                                {{ __('Tutorial') }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">
                            {{ __('New to our platform?') }}
                        </h3>
                        <p class="text-red-100 text-sm font-medium">
                            {{ __('Watch a quick 2-minute demo to learn how to source products efficiently.') }}
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ $socialMediaLinks->youtube_url }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-[#C4302B] bg-white hover:bg-red-50 rounded-full shadow-lg transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.613-.253-11.128-.253-14.742 0C1.98 3.336.5 4.981.5 7.643v8.52c0 2.662 1.48 4.307 4.373 4.459 3.613.253 11.128.253 14.742 0 2.893-.152 4.373-1.797 4.373-4.459v-8.52c0-2.662-1.48-4.307-4.373-4.459zm-9.544 11.189V7.625l5.064 3.376-5.064 3.372z"/>
                            </svg>
                            {{ __('Watch Demo') }}
                        </a>
                        
                        <button @click="show = false; localStorage.setItem('youtube_demo_dismissed', 'true')"
                                class="p-2 rounded-full text-white/70 hover:text-white hover:bg-white/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-600 focus:ring-white"
                                title="{{ __('Dismiss') }}">
                            <span class="sr-only">{{ __('Dismiss') }}</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="relative bg-gradient-to-r from-[#EF7722] to-[#FAA533] dark:from-[#EF7722] dark:to-[#FAA533] rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:16px_16px]"></div>
                <div class="relative px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-white">
                                {{ __('Welcome Back,') }} {{ Auth::user()->name }}!
                            </h3>
                            <p class="text-sm text-white/80 mt-2 max-w-xl">
                                {{ __('Your centralized dashboard to manage and track the progress of all your sourcing requests.') }}
                            </p>
                        </div>
                        <div class="hidden lg:flex">
                            <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

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

                        {{-- Action Required --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#FAA533]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Action Required') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->where('status', 'quoted')->count() }}</p>
                            </div>
                        </div>

                        {{-- In Progress --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('In Progress') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->whereIn('status', ['pending', 'in_review', 'accepted'])->count() }}</p>
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
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Sourcing Requests') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-[#EF7722]">{{ $sourcingRequests->count() }}</span> {{ Str::plural(__('request'), $sourcingRequests->count()) }}
                                    </p>
                                </div>
                            </div>
                            
                            {{-- Archived Link --}}
                            <a href="{{ route('client.sourcing-requests.archived') }}" class="text-xs font-semibold text-slate-500 hover:text-red-500 underline decoration-slate-300 hover:decoration-red-300 transition-colors ms-2">
                                {{ __('View Archived') }}
                            </a>
                        </div>
                        
                        {{-- Filters --}}
                        <form action="{{ route('client.dashboard') }}" method="GET" id="filterForm" class="w-full lg:w-auto">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <div class="relative flex-1 lg:w-64">
                                    <input type="text" 
                                           name="search" 
                                           id="searchInput"
                                           placeholder="{{ __('Search product...') }}" 
                                           value="{{ request('search') }}" 
                                           class="w-full ps-9 pe-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm">
                                    <svg class="w-4 h-4 text-slate-400 absolute start-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <option value="quoted" {{ request('status') == 'quoted' ? 'selected' : '' }}>{{ __('Action Required') }}</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
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
                                            {{ __('Shipping') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Destinations') }}
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
                                        <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
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
                                                    <div>
                                                        <div class="text-xs font-semibold text-[#EF7722] mb-0.5">{{ $request->reference_id }}</div>
                                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $request->category?->name }}</div>
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
                                                        'negotiating' => ['color' => '#3B82F6', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                                                    ];
                                                    $statusData = $statusConfig[$request->status] ?? $statusConfig['pending'];
                                                @endphp
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold" style="background-color: {{ $statusData['color'] }}22; color: {{ $statusData['color'] }};">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                                    </svg>
                                                    {{ $request->status_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900 dark:text-white">
                                                    {{ $request->shipping_method_label }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-1">
                                                    @foreach($request->destinations->take(3) as $destination)
                                                        <span class="fi fi-{{ strtolower($destination->country->code) }} text-base border border-[#EBEBEB] dark:border-slate-600 rounded-sm" title="{{ $destination->country->name }}"></span>
                                                    @endforeach
                                                    @if($request->destinations->count() > 3)
                                                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 ms-1">
                                                            +{{ $request->destinations->count() - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900 dark:text-white">{{ $request->created_at->translatedFormat('d M Y') }}</div>
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
                                                    
                                                    {{-- Dropdown Menu --}}
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" 
                                                                @click.away="open = false"
                                                                type="button" 
                                                                class="inline-flex items-center justify-center w-8 h-8 text-slate-600 dark:text-slate-300 hover:text-[#EF7722] dark:hover:text-[#EF7722] hover:bg-[#EBEBEB] dark:hover:bg-slate-700 rounded-lg transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                            </svg>
                                                        </button>
                                                        
                                                        <div x-show="open" 
                                                             x-transition
                                                             class="absolute end-0 top-full mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-[#EBEBEB] dark:border-slate-700 py-1 z-20">
                                                            
                                                            @if ($request->status === 'pending')
                                                                <a href="{{ route('client.sourcing-requests.edit', $request) }}" 
                                                                   class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-[#EBEBEB] dark:hover:bg-slate-700 hover:text-[#EF7722] dark:hover:text-[#EF7722]">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                    {{ __('Edit') }}
                                                                </a>
                                                            @endif

                                                            <form action="{{ route('client.sourcing-requests.duplicate', $request) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-[#EBEBEB] dark:hover:bg-slate-700 hover:text-[#EF7722] dark:hover:text-[#EF7722]">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    {{ __('Duplicate') }}
                                                                </button>
                                                            </form>

                                                            @if ($request->status === 'pending' || $request->status === 'in_review')
                                                                <form action="{{ route('client.sourcing-requests.cancel', $request) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to cancel this request?') }}');">
                                                                    @csrf
                                                                    <button type="submit" 
                                                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-[#FAA533] hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                        </svg>
                                                                        {{ __('Cancel') }}
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            @if ($request->status === 'pending')
                                                                <form action="{{ route('client.sourcing-requests.destroy', $request) }}" method="POST" onsubmit="return confirm('{{ __('Delete permanently?') }}');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                        </svg>
                                                                        {{ __('Delete') }}
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
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
</x-app-layout>