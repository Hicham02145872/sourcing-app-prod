<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Track Shipment')]
]">
    <div class="py-8 sm:py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <header class="mb-8 sm:mb-10 text-center">
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                    {{ __('Track your shipment') }}
                </h1>
                <p class="mt-2 text-sm sm:text-base text-slate-500 dark:text-slate-400 max-w-xl mx-auto">
                    {{ __('Enter your tracking number to see status and delivery progress in real time.') }}
                </p>
            </header>

            <!-- Search card -->
            <section class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-8">
                <div class="p-6 sm:p-8">
                    <form id="trackingForm" class="max-w-2xl mx-auto">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <div class="relative flex-1">
                                <label for="trackingNumberInput" class="sr-only">{{ __('Tracking number') }}</label>
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                </div>
                                <input type="text" id="trackingNumberInput" name="number" value="{{ $initialNumber ?? '' }}" 
                                       class="block w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-[#EF7722] focus:border-[#EF7722] transition-all text-base" 
                                       placeholder="{{ __('e.g. FSB000043 or carrier number') }}" required autofocus
                                       autocomplete="off">
                            </div>
                            <button type="submit" id="trackButton" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-[#EF7722] hover:bg-[#d66616] text-white font-semibold rounded-xl shadow-sm hover:shadow transition-all focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 dark:focus:ring-offset-slate-800 disabled:opacity-70 disabled:cursor-not-allowed">
                                <svg id="trackButtonIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span id="trackButtonText">{{ __('Search') }}</span>
                            </button>
                        </div>
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 text-center sm:text-left">
                            {{ __('Use your order ID (FSB...) or the tracking number from your carrier.') }}
                        </p>
                    </form>
                    
                    <div id="performanceIndicator" class="hidden mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span>{{ __('Carrier') }}:</span>
                            <strong id="providerName" class="text-slate-700 dark:text-slate-300"></strong>
                        </span>
                        <span class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            <span id="responseTime"></span>
                        </span>
                        <span id="cacheStatusBadge" class="flex items-center gap-2">
                            <span id="cacheDot" class="w-1.5 h-1.5 rounded-full"></span>
                            <span id="cacheStatusText"></span>
                        </span>
                    </div>
                </div>
            </section>

            <!-- Loading -->
            <div id="loadingState" class="hidden animate-fade-in">
                <div class="max-w-sm mx-auto bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-14 h-14 rounded-full border-4 border-slate-100 dark:border-slate-700 border-t-[#EF7722] animate-spin mb-6" aria-hidden="true"></div>
                        <h3 id="loadingText" class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ __('Searching...') }}
                        </h3>
                        <p id="loadingSubtext" class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('Fetching tracking information') }}
                        </p>
                        <div class="mt-6 w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div id="loadingProgress" class="h-full bg-[#EF7722] transition-all duration-500 rounded-full" style="width: 20%"></div>
                        </div>
                        <button type="button" id="cancelSearch" class="mt-6 text-sm text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </div>
            </div>

            @if(session('error') || session('success') || session('status'))
            <div class="mb-6 animate-fade-in">
                @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 p-4 rounded-xl flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
                @endif
                @if(session('success') || session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 p-4 rounded-xl flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-emerald-800 dark:text-emerald-200">{{ session('success') ?? session('status') }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Error state -->
            <div id="errorState" class="hidden mb-8 animate-fade-in">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-red-200 dark:border-red-900/50 p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Tracking unavailable') }}</h3>
                            <p id="errorMessage" class="mt-1 text-sm text-slate-600 dark:text-slate-400"></p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <button type="button" onclick="document.getElementById('trackingForm').scrollIntoView({ behavior: 'smooth' }); document.getElementById('trackingNumberInput').focus();" class="inline-flex items-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#d66616] text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    {{ __('Try again') }}
                                </button>
                                <a href="{{ route('client.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
                                    {{ __('Back to dashboard') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Grid -->
            <div id="resultsContainer" class="hidden space-y-8 animate-fade-in">
                
                <!-- Shipment Journey Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Tracking number') }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span id="resultTrackingNumber" class="text-lg font-bold text-slate-900 dark:text-white font-mono truncate"></span>
                                <button type="button" onclick="copyTracking()" class="p-1.5 rounded-lg text-slate-400 hover:text-[#EF7722] hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" title="{{ __('Copy') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div id="latestStatusBadge" class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-semibold bg-[#EF7722]/10 text-[#EF7722] border border-[#EF7722]/30">
                            <span id="latestStatusText">--</span>
                        </div>
                    </div>
                    
                    <!-- Virtual Status Indicator -->
                    <div id="virtualStatusIndicator" class="hidden mb-6 p-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/30 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-200 mb-1">{{ __('Estimated status') }}</p>
                            <p id="virtualStatusMessage" class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
                                {{ __('Your real tracking number will be available shortly. Automatic updates will start once the number is assigned.') }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ __('Last location') }}</p>
                                <p id="latestLocation" class="mt-0.5 text-sm font-semibold text-slate-900 dark:text-white"><span>--</span></p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ __('Last update') }}</p>
                                <p id="latestDate" class="mt-0.5 text-sm font-semibold text-slate-900 dark:text-white"><span>--</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Journey Progress Bar -->
                    <div class="relative py-8 min-h-[7rem]">
                        <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 dark:bg-slate-700 -translate-y-1/2 rounded-full"></div>
                        <div id="journeyProgressBar" class="absolute top-1/2 left-0 h-1 bg-[#EF7722] -translate-y-1/2 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        
                        <div class="relative flex justify-between">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-[#EF7722] flex items-center justify-center z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="absolute top-12 text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">{{ __('Order') }}</span>
                            </div>
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center">
                                <div id="step2Dot" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1m3 0V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1m8 1a1 1 0 021 1v1a1 1 0 01-1 1h-1a1 1 0 00-1 1v1a1 1 0 01-1 1h-1a1 1 0 00-1 1v1a1 1 0 01-1 1H9m4-1V8a1 1 0 00-1-1h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 00-.293.707V16m0 0a1 1 0 001 1h6.586a1 1 0 00.707-.293l2.414-2.414a1 1 0 00.293-.707V8.414a1 1 0 00-.293-.707l-2.414-2.414a1 1 0 00-.707-.293H13"/></svg>
                                </div>
                                <span class="absolute top-12 text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">{{ __('In transit') }}</span>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex flex-col items-center">
                                <div id="step3Dot" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span class="absolute top-12 text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">{{ __('Out for delivery') }}</span>
                            </div>
                            <!-- Step 4 -->
                            <div class="flex flex-col items-center">
                                <div id="step4Dot" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="absolute top-12 text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">{{ __('Delivered') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dubai Notice (inside card) -->
                    <div class="mt-8 w-full">
                        <div class="p-4 bg-orange-50 dark:bg-orange-950/20 border border-orange-100 dark:border-orange-900/30 rounded-xl flex items-start gap-3 min-w-0">
                            <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs text-amber-800 dark:text-amber-200 leading-relaxed break-words min-w-0">
                                {{ __('From Dubai to your country, status updates may be entered manually by our team. Progress will appear here as your order moves.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div id="mapContainer" class="hidden bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                <h4 class="text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Shipment location') }}</h4>
                            </div>
                            <div id="map" class="h-56 w-full z-0 relative"></div>
                        </div>
                    </div>

                    <!-- Right Column: Timeline -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 h-full overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Activity history') }}</h3>
                                <span id="eventCountLabel" class="text-xs font-medium px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400">0 {{ __('events') }}</span>
                            </div>
                            <div class="p-6 sm:p-8">
                                <div id="timelineContainer" class="relative border-l-2 border-slate-100 dark:border-slate-700 ml-4 space-y-8 pb-10">
                                    <!-- JS Timeline Content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="emptyState" class="hidden text-center py-12 px-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                    <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">{{ __('No tracking data') }}</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ __('No shipment history was found for this tracking number. Check the number or try again later.') }}</p>
                <button type="button" onclick="document.getElementById('trackingNumberInput').focus();" class="mt-6 text-sm font-medium text-[#EF7722] hover:text-[#d66616] transition-colors">
                    {{ __('Enter another number') }}
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    @php
        $loadingMessages = [
            __('Connecting to carrier network...'),
            __('Intercepting logistics signals...'),
            __('Parsing shipment history...'),
            __('Fetching real-time updates...'),
            __('Analyzing transit route...'),
            __('Decrypting tracking data...'),
            __('Finalizing results...'),
        ];
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uiLocale = @json(app()->getLocale() === 'ar' ? 'ar' : (app()->getLocale() === 'fr' ? 'fr-FR' : 'en-US'));
            // 1. Declare State
            let messageInterval;
            let abortController = null;
            const messages = @json($loadingMessages);

            // 2. DOM Elements
            const elements = {
                form: document.getElementById('trackingForm'),
                input: document.getElementById('trackingNumberInput'),
                button: document.getElementById('trackButton'),
                loadingState: document.getElementById('loadingState'),
                loadingProgress: document.getElementById('loadingProgress'),
                errorState: document.getElementById('errorState'),
                errorMessage: document.getElementById('errorMessage'),
                resultsContainer: document.getElementById('resultsContainer'),
                timelineContainer: document.getElementById('timelineContainer'),
                eventCountLabel: document.getElementById('eventCountLabel'),
                emptyState: document.getElementById('emptyState'),
                loadingText: document.getElementById('loadingText'),
                loadingSubtext: document.getElementById('loadingSubtext'),
                resultTrackingNumber: document.getElementById('resultTrackingNumber'),
                latestStatusBadge: document.getElementById('latestStatusBadge'),
                latestStatusText: document.getElementById('latestStatusText'),
                latestLocation: document.getElementById('latestLocation'),
                latestDate: document.getElementById('latestDate'),
                performanceIndicator: document.getElementById('performanceIndicator'),
                cacheStatusBadge: document.getElementById('cacheStatusBadge'),
                cacheDot: document.getElementById('cacheDot'),
                cacheStatusText: document.getElementById('cacheStatusText'),
                responseTime: document.getElementById('responseTime'),
                providerName: document.getElementById('providerName'),
                journeyProgressBar: document.getElementById('journeyProgressBar'),
                step2Dot: document.getElementById('step2Dot'),
                step3Dot: document.getElementById('step3Dot'),
                step4Dot: document.getElementById('step4Dot'),
                cancelSearch: document.getElementById('cancelSearch')
            };

            // 3. UI Helpers
            function resetUI() {
                if (elements.errorState) elements.errorState.classList.add('hidden');
                if (elements.resultsContainer) elements.resultsContainer.classList.add('hidden');
                if (elements.emptyState) elements.emptyState.classList.add('hidden');
                if (elements.performanceIndicator) elements.performanceIndicator.classList.add('hidden');
            }

            function startLoadingMessages() {
                if (!elements.loadingText) return;
                let i = 0;
                let progress = 10;
                if (messageInterval) clearInterval(messageInterval);
                if (elements.loadingProgress) elements.loadingProgress.style.width = '15%';

                messageInterval = setInterval(() => {
                    i++;
                    elements.loadingText.style.opacity = '0';
                    setTimeout(() => {
                        elements.loadingText.innerText = messages[i % messages.length];
                        elements.loadingText.style.opacity = '1';
                        progress = Math.min(90, progress + (Math.random() * 15));
                        if (elements.loadingProgress) elements.loadingProgress.style.width = `${progress}%`;
                    }, 300);
                }, 3000);
            }

            function stopLoadingMessages() {
                if (messageInterval) {
                   clearInterval(messageInterval);
                   messageInterval = null;
                }
            }

            function resetStatus() {
                if (elements.button) {
                    elements.button.disabled = false;
                    const btnText = '{{ __("Search") }}';
                    elements.button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><span>' + btnText + '</span>';
                }
                if (elements.cancelSearch) elements.cancelSearch.classList.add('hidden');
                if (elements.loadingState) elements.loadingState.classList.add('hidden');
                stopLoadingMessages();
            }

            // 4. Core Fetch Logic
            // Compteur de retries pour l'état "pending"
            let pendingRetries = 0;
            const maxPendingRetries = 6; // après ~2-3 min, on arrête

            async function fetchTrackingData(number) {
                let shouldResetUI = true;
                if (abortController) abortController.abort();
                abortController = new AbortController();

                try {
                    resetUI();
                    if (elements.loadingState) elements.loadingState.classList.remove('hidden');
                    if (elements.cancelSearch) elements.cancelSearch.classList.remove('hidden');
                    if (elements.button) {
                        elements.button.disabled = true;
                        const searchLabel = '{{ __("Searching...") }}';
                        elements.button.innerHTML = '<svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>' + searchLabel + '</span>';
                    }
                    
                    startLoadingMessages();

                    const url = `{{ route('client.tracking.data') }}?number=${encodeURIComponent(number)}`;
                    const startTime = performance.now();
                    const response = await fetch(url, {
                        signal: abortController.signal,
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const responseTime = Math.round(performance.now() - startTime);
                    const result = await response.json();

                    if (response.status === 202 || (result.status && result.status === 'pending')) {
                        pendingRetries++;

                        if (pendingRetries >= maxPendingRetries) {
                            // Trop de tentatives: informer l'utilisateur et arrêter le polling
                            if (elements.errorMessage) {
                                elements.errorMessage.textContent = result.error || "{{ __('Tracking update is still in progress. Please try again in a few minutes.') }}";
                            }
                            if (elements.errorState) elements.errorState.classList.remove('hidden');
                            return;
                        }

                        if (elements.loadingText) {
                            elements.loadingText.innerText = result.current_status || "{{ __('Processing request...') }}";
                        }
                        if (elements.loadingSubtext) {
                            elements.loadingSubtext.innerText = "{{ __('This may take a couple of minutes for live carrier data. We will retry automatically.') }}";
                        }

                        // Délai progressif: 15s pour les 2-3 premiers retries, puis 60s
                        const delay = pendingRetries <= 3 ? 15000 : 60000;
                        setTimeout(() => {
                            if (!abortController?.signal.aborted) {
                                fetchTrackingData(number);
                            }
                        }, delay);

                        shouldResetUI = false;
                        return;
                    }
                    
                    if (response.status === 404) {
                        throw new Error(`{{ __("Shipment Not Found:") }} ${result.error || "{{ __('We could not locate this number. Please verify or contact support.') }}"}`);
                    }
                    
                    if (!response.ok) throw new Error(result.error || 'A connection error occurred.');

                    pendingRetries = 0;

                    if (result.data && result.data.length > 0) {
                        processTrackingData(result, number, responseTime);
                    } else {
                        if (elements.emptyState) elements.emptyState.classList.remove('hidden');
                    }

                } catch (error) {
                    if (error.name === 'AbortError') return;
                    if (elements.errorMessage) elements.errorMessage.textContent = error.message.includes('Failed to fetch') ? "{{ __("Network error. Please check your connection.") }}" : error.message;
                    if (elements.errorState) elements.errorState.classList.remove('hidden');
                } finally {
                    if (shouldResetUI) resetStatus();
                }
            }

            function processTrackingData(result, number, responseTime) {
                const data = result.data;
                const latest = data[0];
                
                // Handle virtual status indicator
                const virtualIndicator = document.getElementById('virtualStatusIndicator');
                const virtualMessage = document.getElementById('virtualStatusMessage');
                if (result.is_virtual) {
                    if (virtualIndicator) virtualIndicator.classList.remove('hidden');
                    if (virtualMessage && result.virtual_message) {
                        virtualMessage.textContent = result.virtual_message;
                    }
                } else {
                    if (virtualIndicator) virtualIndicator.classList.add('hidden');
                }
                
                if (elements.resultTrackingNumber) elements.resultTrackingNumber.textContent = number;
                if (elements.latestStatusText) elements.latestStatusText.textContent = result.current_status || getField(latest, ['status_en', 'status', 'Status']) || @json(__('Status Pending'));
                
                let location = getField(latest, ['location', 'Location']);
                if (!location || location.trim() === '') {
                    const fromText = extractLocationFromText(getField(latest, ['status', 'status_en']) || '');
                    if (fromText) location = fromText;
                }
                if (elements.latestLocation) {
                    const span = elements.latestLocation.querySelector('span');
                    if (span) span.textContent = (location && location.trim()) ? location : '{{ __("N/A") }}';
                }
                
                if (location && location.trim() !== '') {
                    updateMap(location);
                } else if(document.getElementById('mapContainer')) {
                    document.getElementById('mapContainer').classList.add('hidden');
                }

                let dateRaw = getField(latest, ['statusDate', 'created_at', 'date', 'Date']);
                if (!dateRaw || formatDate(dateRaw) === 'Invalid Date') {
                    const fromText = extractDateFromText(getField(latest, ['status', 'status_en']) || '');
                    if (fromText) dateRaw = fromText;
                }
                const displayDate = dateRaw ? formatDate(dateRaw) : 'N/A';
                if (elements.latestDate) {
                    const span = elements.latestDate.querySelector('span');
                    if (span) span.textContent = (displayDate && displayDate !== 'Invalid Date') ? displayDate : '{{ __("N/A") }}';
                }

                showPerformanceMetrics(result.provider, responseTime);

                if (elements.timelineContainer) {
                    elements.timelineContainer.innerHTML = '';
                    data.forEach((item, index) => renderTimelineItem(item, index === 0));
                    if (elements.eventCountLabel) elements.eventCountLabel.textContent = data.length + ' ' + (data.length === 1 ? '{{ __("event") }}' : '{{ __("events") }}');
                }

                updateJourneyProgress(data, result.order_status);

                if (elements.resultsContainer) {
                    elements.resultsContainer.classList.remove('hidden');
                    elements.resultsContainer.classList.add('animate-fade-in');
                    elements.resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }

            // Map & Formatting Helpers
            let mapInstance = null;
            async function updateMap(locationQuery) {
                const mapContainer = document.getElementById('mapContainer');
                if (!mapContainer) return;
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(locationQuery)}&limit=1`, { headers: { 'Accept-Language': uiLocale } });
                    const data = await response.json();
                    if (data?.length > 0) {
                        const { lat, lon } = data[0];
                        mapContainer.classList.remove('hidden');
                        if (!mapInstance) {
                            mapInstance = L.map('map').setView([lat, lon], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapInstance);
                        } else {
                            mapInstance.setView([lat, lon], 13);
                            mapInstance.eachLayer(l => l instanceof L.Marker && mapInstance.removeLayer(l));
                        }
                        L.marker([lat, lon]).addTo(mapInstance).bindPopup(`<b>${escapeHtml(locationQuery)}</b>`).openPopup();
                        setTimeout(() => mapInstance.invalidateSize(), 200);
                    } else {
                        mapContainer.classList.add('hidden');
                    }
                } catch (e) { mapContainer.classList.add('hidden'); }
            }

            function showPerformanceMetrics(provider, responseTime) {
                if (!elements.performanceIndicator) return;
                const isCached = responseTime < 150;
                if (elements.cacheDot && elements.cacheStatusText) {
                    elements.cacheDot.className = `w-2 h-2 rounded-full mr-2 ${isCached ? 'bg-green-500' : 'bg-blue-500'}`;
                    elements.cacheStatusText.textContent = isCached ? @json(__('System Cache')) : @json(__('Live Data'));
                }
                if (elements.responseTime) elements.responseTime.textContent = responseTime + ' ms';
                if (provider && elements.providerName) elements.providerName.textContent = provider.toUpperCase();
                elements.performanceIndicator.classList.remove('hidden');
            }

            function updateJourneyProgress(data, orderStatus) {
                if (!elements.journeyProgressBar) return;
                const autoStatus = (data[0] ? (data[0].current_status || data[0].status || '').toLowerCase() : '');
                let percentage = 0;
                if (autoStatus) {
                    percentage = 25;
                    if (/transit|shipped|departed|expéd|arrivé|signed|livré|签收|reçus/.test(autoStatus)) percentage = 50;
                }
                if (orderStatus) {
                    const status = orderStatus.toLowerCase();
                    if (/delivered|completed/.test(status)) percentage = 100;
                    else if (/out_for_delivery|destination_country/.test(status)) percentage = 75;
                    else if (/_uae|_china|transit/.test(status)) percentage = Math.max(percentage, 50);
                    else if (/paid|preparing/.test(status)) percentage = Math.max(percentage, 25);
                }
                if (percentage === 0) percentage = 25;

                if (percentage >= 100) { markStep(elements.step2Dot); markStep(elements.step3Dot); markStep(elements.step4Dot); }
                else if (percentage >= 75) { markStep(elements.step2Dot); markStep(elements.step3Dot); unmarkStep(elements.step4Dot); }
                else if (percentage >= 50) { markStep(elements.step2Dot); unmarkStep(elements.step3Dot); unmarkStep(elements.step4Dot); }
                else { unmarkStep(elements.step2Dot); unmarkStep(elements.step3Dot); unmarkStep(elements.step4Dot); }
                
                elements.journeyProgressBar.style.width = `${percentage}%`;
            }

            function markStep(el) {
                if (!el) return;
                el.classList.remove('border-slate-200', 'dark:border-slate-700');
                el.classList.add('border-[#EF7722]');
                el.querySelector('svg').classList.replace('text-slate-300', 'text-[#EF7722]');
            }

            function unmarkStep(el) {
                if (!el) return;
                el.classList.add('border-slate-200', 'dark:border-slate-700');
                el.classList.remove('border-[#EF7722]');
                el.querySelector('svg').classList.replace('text-[#EF7722]', 'text-slate-300');
            }

            function renderTimelineItem(item, isLatest) {
                const status = getField(item, ['status_fr', 'status_en', 'status', 'Status']) || 'Update';
                const details = getField(item, ['statusDetails', 'details', 'remarks', 'Remarks']);
                let location = getField(item, ['location', 'Location']);
                if (!location && status) location = extractLocationFromText(status);
                let dateStr = getField(item, ['date', 'statusDate', 'Date']);
                if (!dateStr && status) dateStr = extractDateFromText(status);
                const displayDate = dateStr ? formatDate(dateStr) : 'N/A';
                const dateDisplay = (displayDate && displayDate !== 'Invalid Date') ? displayDate : (dateStr || 'N/A');
                
                const html = `
                    <div class="relative pl-8 group animate-fade-in">
                        <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full ${isLatest ? 'bg-[#EF7722] ring-4 ring-[#EF7722]/20' : 'bg-slate-200 dark:bg-slate-700'} border-2 border-white dark:border-slate-800 z-10 transition-all"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline gap-2">
                            <div class="flex-grow">
                                <h4 class="text-sm font-bold ${isLatest ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400'}">${escapeHtml(status)}</h4>
                                ${details ? `<p class="text-xs text-slate-500 mt-1">${escapeHtml(details)}</p>` : ''}
                                ${location ? `<div class="flex items-center mt-2 space-x-1"><svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span class="text-[10px] font-bold text-slate-400 uppercase">${escapeHtml(location)}</span></div>` : ''}
                            </div>
                            <div class="sm:text-right flex-shrink-0">
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 dark:bg-slate-900 px-2 py-0.5 rounded border border-slate-100 dark:border-slate-800">${escapeHtml(dateDisplay)}</span>
                            </div>
                        </div>
                    </div>
                `;
                elements.timelineContainer?.insertAdjacentHTML('beforeend', html);
            }

            function getField(obj, keys) {
                if (!obj) return null;
                const lowerObj = Object.fromEntries(Object.entries(obj).map(([k, v]) => [k.toLowerCase(), v]));
                for (let key of keys) {
                    const row = lowerObj[key.toLowerCase()];
                    if (row) return row;
                }
                return null;
            }

            /** Extract a date string from activity text like "Delivered POME, IT 01/14/2026, 11:03 A.M." */
            function extractDateFromText(text) {
                if (!text || typeof text !== 'string') return '';
                const match = text.match(/(\d{1,2})\/(\d{1,2})\/(\d{4}),\s*(\d{1,2}):(\d{2})\s*([AP])\.?M\.?/i);
                if (!match) return '';
                const [, month, day, year, hour, min, ampm] = match;
                const h = parseInt(hour, 10);
                const hour12 = ampm.toUpperCase() === 'A' ? (h === 12 ? 0 : h) : (h === 12 ? 12 : h + 12);
                return `${year}-${month.padStart(2,'0')}-${day.padStart(2,'0')} ${hour12.toString().padStart(2,'0')}:${min}`;
            }

            /** Extract location (e.g. "POME, IT", "Roma, Italy", "Belgium") from activity text before the date. */
            function extractLocationFromText(text) {
                if (!text || typeof text !== 'string') return '';
                const beforeDate = text.replace(/\d{1,2}\/\d{1,2}\/\d{4},?\s*\d{1,2}:\d{2}\s*[AP]\.?M\.?/i, '').trim();
                const locMatch = beforeDate.match(/\s+([^,]+,\s*[A-Za-z]{2,})$/);
                if (locMatch) return locMatch[1].trim();
                const lastWord = beforeDate.match(/\s+([A-Za-z][A-Za-z\s]{1,30})$/);
                return lastWord ? lastWord[1].trim() : '';
            }

            function formatDate(s) {
                if (!s) return '';
                let str = typeof s === 'string' ? s.trim() : String(s);
                if (!str) return '';
                if (/^\d{4}-\d{2}-\d{2}/.test(str)) {
                    const d = new Date(str.replace(/-/g, '/'));
                    if (!isNaN(d.getTime())) return d.toLocaleString(uiLocale, { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                }
                const extracted = extractDateFromText(str);
                if (extracted) {
                    const d = new Date(extracted.replace(/-/g, '/'));
                    if (!isNaN(d.getTime())) return d.toLocaleString(uiLocale, { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                }
                const d = new Date(str.replace(/-/g, '/'));
                if (isNaN(d.getTime())) return '';
                return d.toLocaleString(uiLocale, { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
            }

            function escapeHtml(t) { return t?.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;"); }
            
            // Events
            elements.form?.addEventListener('submit', (e) => {
                e.preventDefault();
                const n = elements.input.value.trim();
                if (n) {
                    fetchTrackingData(n);
                }
            });

            elements.cancelSearch?.addEventListener('click', () => {
                abortController?.abort();
                resetStatus();
            });

            // Important: ne PAS auto-déclencher la recherche au chargement de la page.
            // Avant, si le champ contenait déjà un numéro (ex: refresh), la requête partait
            // automatiquement, même si le client ne cliquait pas sur \"Search\".
            // On désactive donc l'appel auto:
            // if (elements.input?.value.trim() !== '') fetchTrackingData(elements.input.value.trim());

            window.copyTracking = () => {
                const text = elements.resultTrackingNumber?.innerText || '';
                navigator.clipboard.writeText(text).then(() => {
                    const btn = document.querySelector('button[onclick="copyTracking()"]');
                    const old = btn.innerHTML;
                    btn.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                    setTimeout(() => btn.innerHTML = old, 2000);
                });
            };
        });
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        
        @keyframes fadeIn { 
            from { opacity: 0; transform: translateY(8px); } 
            to { opacity: 1; transform: translateY(0); } 
        }

        #timelineContainer::before {
            content: '';
            position: absolute;
            left: -2px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #EF7722 0%, #EF7722 30%, #e2e8f0 100%);
        }

        .dark #timelineContainer::before {
            background: linear-gradient(to bottom, #EF7722 0%, #EF7722 30%, #334155 100%);
        }

        /* Enterprise Spinner Optimization */
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .animate-spin { animation: spin 1s linear infinite; }
        
        #map { z-index: 10; }
    </style>
    @endpush
</x-app-layout>