<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Track Order')]
]">
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            
            <!-- Header Section -->
            <div class="mb-10 text-center sm:text-left">
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    {{ __('Track Your Shipment') }}
                </h2>
                <p class="mt-3 text-lg text-slate-500 dark:text-slate-400 max-w-2xl">
                    {{ __('Enter your tracking number below to see real-time status updates and delivery progress.') }}
                </p>
            </div>

            <!-- Search Section (Simplified Enterprise) -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-10">
                <div class="p-6 sm:p-8">
                    <form id="trackingForm" class="relative max-w-3xl mx-auto">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" id="trackingNumberInput" name="number" value="{{ $initialNumber ?? '' }}" 
                                       class="block w-full pl-11 pr-4 py-3.5 border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent sm:text-sm transition-all" 
                                       placeholder="{{ __('e.g. FSB000043, ME49508327') }}" required autofocus>
                            </div>
                            <button type="submit" id="trackButton" class="sm:w-auto px-8 py-3.5 bg-[#EF7722] hover:bg-[#d66616] text-white font-bold rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 group">
                                <span>{{ __('Track Now') }}</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Performance Metrics Display (Simple) -->
                    <div id="performanceIndicator" class="hidden mt-6 flex flex-wrap items-center justify-center gap-6 text-xs font-medium text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-700 pt-6">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-slate-300 mr-2"></span>
                            {{ __('Provider:') }} <span id="providerName" class="ml-1 text-slate-900 dark:text-slate-200"></span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-slate-300 mr-2"></span>
                            {{ __('Response:') }} <span id="responseTime" class="ml-1 text-slate-900 dark:text-slate-200"></span>
                        </div>
                        <div id="cacheStatusBadge" class="flex items-center">
                            <span id="cacheDot" class="w-2 h-2 rounded-full mr-2"></span>
                            <span id="cacheStatusText"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State (Enterprise Style) -->
            <div id="loadingState" class="hidden py-16">
                <div class="max-w-md mx-auto">
                    <div class="flex flex-col items-center text-center">
                        <!-- Professional Spinner -->
                        <div class="w-16 h-16 relative mb-6">
                            <div class="absolute inset-0 rounded-full border-4 border-slate-100 dark:border-slate-800"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-[#EF7722] border-t-transparent animate-spin"></div>
                        </div>
                        
                        <div class="space-y-4 w-full">
                            <h3 id="loadingText" class="text-xl font-bold text-slate-900 dark:text-white transition-all duration-300">
                                {{ __('Locating Shipment...') }}
                            </h3>
                            
                            <!-- Static Progress Bar (Enterprise Look) -->
                            <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div id="loadingProgress" class="h-full bg-[#EF7722] transition-all duration-500 ease-out" style="width: 10%"></div>
                            </div>
                            
                            <p id="loadingSubtext" class="text-sm text-slate-500 font-medium">
                                {{ __('Connecting to secure logistics database...') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div id="errorState" class="hidden max-w-2xl mx-auto rounded-lg bg-red-50 dark:bg-red-900/10 p-5 border border-red-200 dark:border-red-800 mb-8">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800 dark:text-red-200">{{ __('Tracking Error') }}</h3>
                        <p id="errorMessage" class="mt-1 text-sm text-red-700 dark:text-red-300"></p>
                    </div>
                </div>
            </div>

            <!-- Results Grid -->
            <div id="resultsContainer" class="hidden space-y-8 animate-fade-in">
                
                <!-- Shipment Journey Card -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Shipment ID') }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <h3 id="resultTrackingNumber" class="text-2xl font-black text-slate-900 dark:text-white font-mono"></h3>
                                <button onclick="copyTracking()" class="p-1 text-slate-400 hover:text-[#EF7722] transition-colors rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Current Status') }}</span>
                            <div id="latestStatusBadge" class="mt-1 px-4 py-1.5 rounded-full text-sm font-bold bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700">
                                <span id="latestStatusText">--</span>
                            </div>
                        </div>
                    </div>

                    <!-- Journey Progress Bar -->
                    <div class="relative py-8">
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
                                <span class="absolute top-12 text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">{{ __('In Transit') }}</span>
                            </div>
                            <!-- Step 3 -->
                            <div class="flex flex-col items-center">
                                <div id="step3Dot" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 flex items-center justify-center z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span class="absolute top-12 text-[10px] font-bold text-slate-500 uppercase whitespace-nowrap">{{ __('Out for Delivery') }}</span>
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
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Location and Date -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-6 pb-2 border-b border-slate-100 dark:border-slate-700">{{ __('Latest Details') }}</h4>
                            
                            <dl class="space-y-6">
                                <div>
                                    <dt class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Last Location') }}</dt>
                                    <dd id="latestLocation" class="mt-1 text-sm font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>--</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Last Update') }}</dt>
                                    <dd id="latestDate" class="mt-1 text-sm font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>--</span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Right Column: Timeline -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 h-full overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex justify-between items-center">
                                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Activity History') }}</h3>
                                <span id="eventCountLabel" class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400">0 Events</span>
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
            
            <!-- Empty State -->
            <div id="emptyState" class="hidden text-center py-16 bg-white dark:bg-slate-900 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">{{ __('No data found') }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ __('We could not find any shipment history for this tracking number.') }}</p>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('✅ [TRACKING JS] DOMContentLoaded fired');
            
            // 1. Declare all variables/state first to avoid TDZ
            let messageInterval;
            const messages = [
                "{{ __('Connecting to carrier network...') }}",
                "{{ __('Intercepting logistics signals...') }}",
                "{{ __('Parsing shipment history...') }}",
                "{{ __('Fetching real-time updates...') }}",
                "{{ __('Analyzing transit route...') }}",
                "{{ __('Decrypting tracking data...') }}",
                "{{ __('Finalizing results...') }}"
            ];

            // 2. Cache DOM Elements
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
                step4Dot: document.getElementById('step4Dot')
            };

            console.log('✅ [TRACKING JS] All elements initialized');

            // 3. Helper Functions
            function resetUI() {
                console.log('🧹 [TRACKING JS] resetUI()');
                if (elements.errorState) elements.errorState.classList.add('hidden');
                if (elements.resultsContainer) elements.resultsContainer.classList.add('hidden');
                if (elements.emptyState) elements.emptyState.classList.add('hidden');
                if (elements.performanceIndicator) elements.performanceIndicator.classList.add('hidden');
            }

            function startLoadingMessages() {
                console.log('📝 [TRACKING JS] startLoadingMessages()');
                if (!elements.loadingText) return;
                let i = 0;
                let progress = 10;
                if (messageInterval) clearInterval(messageInterval);
                
                // Initial jump
                if (elements.loadingProgress) elements.loadingProgress.style.width = '15%';

                messageInterval = setInterval(() => {
                    i++;
                    elements.loadingText.style.opacity = '0';
                    
                    setTimeout(() => {
                        elements.loadingText.innerText = messages[i % messages.length];
                        elements.loadingText.style.opacity = '1';
                        
                        // Fake progress crawl
                        progress = Math.min(90, progress + (Math.random() * 15));
                        if (elements.loadingProgress) elements.loadingProgress.style.width = `${progress}%`;
                    }, 300);
                }, 3000);
            }

            function stopLoadingMessages() {
                console.log('📝 [TRACKING JS] stopLoadingMessages()');
                if (messageInterval) {
                    clearInterval(messageInterval);
                    messageInterval = null;
                }
            }

            async function fetchTrackingData(number) {
                console.log('🚀 [TRACKING JS] fetchTrackingData started for:', number);
                try {
                    resetUI();
                    
                    // Stop global spinner
                    window.dispatchEvent(new CustomEvent('loading-stop'));
                    
                    if (elements.loadingState) elements.loadingState.classList.remove('hidden');
                    startLoadingMessages();

                    if (elements.button) {
                        elements.button.disabled = true;
                        elements.button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Searching...';
                    }

                    const url = `{{ route('client.tracking.data') }}?number=${encodeURIComponent(number)}`;
                    console.log('🚀 [TRACKING JS] Fetching URL:', url);
                    
                    const startTime = performance.now();
                    const response = await fetch(url, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const responseTime = Math.round(performance.now() - startTime);
                    
                    console.log('📡 [TRACKING JS] Response received:', response.status);

                    const result = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(result.error || 'Server error occurred');
                    }

                    if (result.data && result.data.length > 0) {
                        processTrackingData(result, number, responseTime);
                    } else {
                        console.warn('⚠️ [TRACKING JS] No data returned');
                        if (elements.emptyState) elements.emptyState.classList.remove('hidden');
                    }

                } catch (error) {
                    console.error('❌ [TRACKING JS] ERROR:', error);
                    if (elements.errorMessage) elements.errorMessage.textContent = error.message;
                    if (elements.errorState) elements.errorState.classList.remove('hidden');
                } finally {
                    if (elements.loadingState) elements.loadingState.classList.add('hidden');
                    stopLoadingMessages();
                    if (elements.button) {
                        elements.button.disabled = false;
                        elements.button.innerHTML = '<span>{{ __("Track Shipment") }}</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>';
                    }
                }
            }

            function processTrackingData(result, number, responseTime) {
                console.log('📊 [TRACKING JS] processTrackingData');
                const data = result.data;
                const latest = data[0];
                
                if (elements.resultTrackingNumber) elements.resultTrackingNumber.textContent = number;
                if (elements.latestStatusText) elements.latestStatusText.textContent = result.current_status || getField(latest, ['status_fr', 'status', 'Status']);
                if (elements.latestLocation) elements.latestLocation.textContent = getField(latest, ['location', 'Location']) || 'N/A';
                
                const dateRaw = getField(latest, ['statusDate', 'created_at', 'date', 'Date']);
                if (elements.latestDate) elements.latestDate.textContent = dateRaw ? formatDate(dateRaw) : 'N/A';

                showPerformanceMetrics(result.provider, responseTime);

                if (elements.timelineContainer) {
                    elements.timelineContainer.innerHTML = '';
                    data.forEach((item, index) => renderTimelineItem(item, index === 0));
                    if (elements.eventCountLabel) elements.eventCountLabel.textContent = `${data.length} Events`;
                }

                updateJourneyProgress(data);

                if (elements.resultsContainer) {
                    elements.resultsContainer.classList.remove('hidden');
                    elements.resultsContainer.classList.add('animate-fade-in');
                }
            }

            function showPerformanceMetrics(provider, responseTime) {
                if (!elements.performanceIndicator) return;
                
                const isCached = responseTime < 150;

                if (elements.cacheDot && elements.cacheStatusText) {
                    elements.cacheDot.className = isCached 
                        ? 'w-2 h-2 rounded-full mr-2 bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]'
                        : 'w-2 h-2 rounded-full mr-2 bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]';
                    elements.cacheStatusText.textContent = isCached ? 'System Cache' : 'Live Data';
                }

                if (elements.responseTime) {
                    elements.responseTime.textContent = responseTime < 1000 ? `${responseTime}ms` : `${(responseTime / 1000).toFixed(2)}s`;
                }

                if (provider && elements.providerName) {
                    elements.providerName.textContent = provider.toUpperCase();
                }

                elements.performanceIndicator.classList.remove('hidden');
                elements.performanceIndicator.classList.add('animate-fade-in');
            }

            function updateJourneyProgress(data) {
                if (!elements.journeyProgressBar) return;
                
                const status = (data[0].current_status || data[0].status_fr || data[0].status || '').toLowerCase();
                let percentage = 25; // Default: Order Placed
                
                if (status.includes('deliv') || status.includes('livré')) {
                    percentage = 100;
                    markStepFilled(elements.step2Dot);
                    markStepFilled(elements.step3Dot);
                    markStepFilled(elements.step4Dot);
                } else if (status.includes('out') || status.includes('delivery') || status.includes('cours de livra')) {
                    percentage = 75;
                    markStepFilled(elements.step2Dot);
                    markStepFilled(elements.step3Dot);
                    unmarkStep(elements.step4Dot);
                } else if (status.includes('transit') || status.includes('shipped') || status.includes('departed') || status.includes('expéd') || status.includes('arrivé')) {
                    percentage = 50;
                    markStepFilled(elements.step2Dot);
                    unmarkStep(elements.step3Dot);
                    unmarkStep(elements.step4Dot);
                } else {
                    unmarkStep(elements.step2Dot);
                    unmarkStep(elements.step3Dot);
                    unmarkStep(elements.step4Dot);
                }
                
                elements.journeyProgressBar.style.width = `${percentage}%`;
            }

            function markStepFilled(el) {
                if (!el) return;
                el.classList.remove('border-slate-200', 'dark:border-slate-700');
                el.classList.add('border-[#EF7722]', 'bg-white', 'dark:bg-slate-800');
                el.querySelector('svg').classList.remove('text-slate-300');
                el.querySelector('svg').classList.add('text-[#EF7722]');
            }

            function unmarkStep(el) {
                if (!el) return;
                el.classList.add('border-slate-200', 'dark:border-slate-700');
                el.classList.remove('border-[#EF7722]');
                el.querySelector('svg').classList.add('text-slate-300');
                el.querySelector('svg').classList.remove('text-[#EF7722]');
            }

            function renderTimelineItem(item, isLatest) {
                const status = getField(item, ['status_fr', 'status', 'Status']) || 'Status Update';
                const details = getField(item, ['statusDetails', 'statusDetailsCn', 'details', 'remarks', 'Details', 'Remarks']);
                const location = getField(item, ['location', 'Location']);
                const dateStr = getField(item, ['statusDate', 'created_at', 'date', 'Date']);
                
                const iconBg = isLatest ? 'bg-[#EF7722]' : 'bg-slate-200 dark:bg-slate-700';
                const ring = isLatest ? 'ring-4 ring-[#EF7722]/20' : '';

                const html = `
                    <div class="relative pl-8 group animate-fade-in">
                        <div class="absolute -left-[9px] top-1.5 w-4 h-4 rounded-full ${iconBg} ${ring} border-2 border-white dark:border-slate-800 z-10 transition-all duration-300"></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline gap-2">
                            <div class="flex-grow">
                                <h4 class="text-sm font-bold ${isLatest ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-slate-400'}">${escapeHtml(status)}</h4>
                                ${details ? `<p class="text-xs text-slate-500 dark:text-slate-500 mt-1 leading-relaxed">${escapeHtml(details)}</p>` : ''}
                                ${location ? `
                                    <div class="flex items-center mt-2 space-x-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">${escapeHtml(location)}</span>
                                    </div>
                                ` : ''}
                            </div>
                            <div class="sm:text-right flex-shrink-0">
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-900/50 px-2 py-0.5 rounded border border-slate-100 dark:border-slate-800">
                                    ${dateStr ? formatDate(dateStr) : 'N/A'}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                if (elements.timelineContainer) elements.timelineContainer.insertAdjacentHTML('beforeend', html);
            }

            function getField(obj, keys) {
                if (!obj) return null;
                const lowerObj = {};
                for (let key in obj) {
                    if (obj.hasOwnProperty(key)) lowerObj[key.toLowerCase()] = obj[key];
                }
                
                for (let key of keys) {
                    const k = key.toLowerCase();
                    const val = lowerObj[k];
                    if (val !== undefined && val !== null && val !== '') return val;
                }
                return null;
            }

            function formatDate(dateString) {
                if(!dateString) return '';
                const d = new Date(dateString.replace(/-/g, "/")); 
                return d.toLocaleString('en-US', { month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
            }

            function escapeHtml(text) {
                if (!text) return text;
                return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
            }
            
            // 4. Initial Execution & Events
            if (elements.input && elements.input.value.trim() !== '') {
                fetchTrackingData(elements.input.value.trim());
            }

            if (elements.form) {
                elements.form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const number = elements.input.value.trim();
                    if (number) fetchTrackingData(number);
                });
            }

            window.copyTracking = function() {
                const text = elements.resultTrackingNumber ? elements.resultTrackingNumber.innerText : '';
                if(text) { navigator.clipboard.writeText(text); alert("Tracking number copied!"); }
            }
        });
    </script>
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
    </style>
    @endpush
</x-app-layout>