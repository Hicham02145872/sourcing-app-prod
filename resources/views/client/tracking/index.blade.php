<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Track Order')]
]">
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            
            <!-- Header Section -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                        {{ __('Shipment Tracking') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Monitor shipment status and history in real-time.') }}
                    </p>
                </div>
            </div>

            <!-- Search Section (CRM Style) -->
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 p-4 mb-8">
                <form id="trackingForm" class="flex flex-col sm:flex-row gap-4 items-end sm:items-center">
                    <div class="w-full flex-1">
                        <label for="trackingNumberInput" class="block text-xs font-semibold uppercase text-slate-500 mb-1 ml-1">
                            {{ __('Tracking Number') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" id="trackingNumberInput" name="number" value="{{ $initialNumber ?? '' }}" 
                                   class="block w-full pl-10 pr-3 py-2.5 border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white rounded-md focus:ring-[#EF7722] focus:border-[#EF7722] sm:text-sm shadow-sm transition-all" 
                                   placeholder="e.g. ME49508327" required>
                        </div>
                    </div>
                    <button type="submit" id="trackButton" class="w-full sm:w-auto px-6 py-2.5 bg-[#EF7722] hover:bg-[#d66616] text-white font-medium rounded-md shadow-sm transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        <span>{{ __('Track Shipment') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="hidden py-12">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full border-4 border-slate-200 dark:border-slate-700"></div>
                        <div class="absolute top-0 left-0 w-12 h-12 rounded-full border-4 border-[#EF7722] border-t-transparent animate-spin"></div>
                    </div>
                    <p class="text-slate-500 font-medium animate-pulse">{{ __('Retrieving shipment data...') }}</p>
                </div>
            </div>

            <!-- Error State -->
            <div id="errorState" class="hidden rounded-md bg-red-50 dark:bg-red-900/10 p-4 border border-red-200 dark:border-red-800 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">{{ __('System Alert') }}</h3>
                        <div class="mt-1 text-sm text-red-700 dark:text-red-300">
                            <p id="errorMessage"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Grid -->
            <div id="resultsContainer" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
                
                <!-- Left Column: Summary Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 sticky top-6">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Shipment Info') }}</h3>
                            <button onclick="copyTracking()" class="text-slate-400 hover:text-[#EF7722] transition-colors" title="Copy ID">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                        <div class="p-5 space-y-6">
                            <div>
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ __('Tracking ID') }}</label>
                                <p id="resultTrackingNumber" class="text-xl font-bold font-mono text-slate-800 dark:text-white mt-1 select-all"></p>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ __('Current Status') }}</label>
                                <div id="latestStatusBadge" class="mt-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#EF7722]/10 text-[#EF7722] border border-[#EF7722]/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#EF7722] mr-1.5 animate-pulse"></span>
                                    <span id="latestStatusText">Processing</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Latest Location') }}</p>
                                        <p id="latestLocation" class="text-sm text-slate-500 mt-0.5">--</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Last Update') }}</p>
                                        <p id="latestDate" class="text-sm text-slate-500 mt-0.5">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Timeline -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-900 rounded-lg shadow-sm border border-slate-200 dark:border-slate-800 h-full">
                        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Activity Log') }}</h3>
                        </div>
                        <div class="p-6">
                             <div id="timelineContainer" class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-3.5 space-y-10 pb-2">
                                <!-- JS content -->
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
            const form = document.getElementById('trackingForm');
            const input = document.getElementById('trackingNumberInput');
            const button = document.getElementById('trackButton');
            
            // UI References
            const loadingState = document.getElementById('loadingState');
            const errorState = document.getElementById('errorState');
            const errorMessage = document.getElementById('errorMessage');
            const resultsContainer = document.getElementById('resultsContainer');
            const timelineContainer = document.getElementById('timelineContainer');
            const emptyState = document.getElementById('emptyState');
            
            // Summary Card References
            const resultTrackingNumber = document.getElementById('resultTrackingNumber');
            const latestStatusText = document.getElementById('latestStatusText');
            const latestLocation = document.getElementById('latestLocation');
            const latestDate = document.getElementById('latestDate');

            if (input.value.trim() !== '') {
                fetchTrackingData(input.value.trim());
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const number = input.value.trim();
                if (number) fetchTrackingData(number);
            });

            async function fetchTrackingData(number) {
                // Reset UI
                resetUI();
                loadingState.classList.remove('hidden');
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Searching...';

                try {
                    const response = await fetch(`{{ route('client.tracking.data') }}?number=${encodeURIComponent(number)}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const result = await response.json();

                    if (!response.ok) throw new Error(result.error || 'Unable to fetch data');

                    if (result.data && result.data.length > 0) {
                        processTrackingData(result.data, number);
                    } else {
                        emptyState.classList.remove('hidden');
                    }

                } catch (error) {
                    errorMessage.textContent = error.message;
                    errorState.classList.remove('hidden');
                } finally {
                    loadingState.classList.add('hidden');
                    button.disabled = false;
                    button.innerHTML = '<span>{{ __("Track Shipment") }}</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>';
                }
            }

            function processTrackingData(data, number) {
                // Assuming data[0] is the most recent based on typical API responses. 
                // If not, sort here: data.sort((a,b) => new Date(b.date) - new Date(a.date));
                
                const latest = data[0];
                
                // Populate Summary Card
                resultTrackingNumber.textContent = number;
                latestStatusText.textContent = getField(latest, ['status', 'Status']);
                latestLocation.textContent = getField(latest, ['location', 'Location']) || 'N/A';
                
                const dateRaw = getField(latest, ['statusDate', 'created_at', 'date', 'Date']);
                latestDate.textContent = dateRaw ? formatDate(dateRaw) : 'N/A';

                // Render Timeline
                timelineContainer.innerHTML = '';
                data.forEach((item, index) => {
                    const isFirst = index === 0;
                    renderTimelineItem(item, isFirst);
                });

                resultsContainer.classList.remove('hidden');
            }

            function renderTimelineItem(item, isLatest) {
                const status = getField(item, ['status', 'Status']) || 'Status Update';
                const details = getField(item, ['statusDetails', 'statusDetailsCn', 'details', 'remarks', 'Details', 'Remarks']);
                const location = getField(item, ['location', 'Location']);
                const dateStr = getField(item, ['statusDate', 'created_at', 'date', 'Date']);
                
                // Determine Icon based on status keyword
                let iconPath = 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'; // Default clipboard
                let statusLower = status.toLowerCase();
                
                if (statusLower.includes('deliv')) iconPath = 'M5 13l4 4L19 7'; // Check
                else if (statusLower.includes('transit') || statusLower.includes('shipped')) iconPath = 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1m3 0V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1m8 1a1 1 0 021 1v1a1 1 0 01-1 1h-1a1 1 0 00-1 1v1a1 1 0 01-1 1h-1a1 1 0 00-1 1v1a1 1 0 01-1 1H9m4-1V8a1 1 0 00-1-1h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 00-.293.707V16m0 0a1 1 0 001 1h6.586a1 1 0 00.707-.293l2.414-2.414a1 1 0 00.293-.707V8.414a1 1 0 00-.293-.707l-2.414-2.414a1 1 0 00-.707-.293H13'; // Truckish
                else if (statusLower.includes('pick') || statusLower.includes('receiv')) iconPath = 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'; // Box

                // Colors: Latest item gets the Orange Brand Color, others get slate
                const iconBg = isLatest ? 'bg-[#EF7722] ring-[#EF7722]/30' : 'bg-white dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-600';
                const iconColor = isLatest ? 'text-white' : 'text-slate-400';
                const textColor = isLatest ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300';
                const ring = isLatest ? 'ring-4' : '';

                const html = `
                    <div class="relative pl-10 group">
                        <!-- Icon Node -->
                        <div class="absolute -left-[19px] top-1 w-10 h-10 rounded-full flex items-center justify-center ${iconBg} ${ring} transition-all duration-300 z-10">
                            <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
                            </svg>
                        </div>
                        
                        <!-- Content Card -->
                        <div class="bg-white dark:bg-slate-800/50 p-4 rounded-lg border border-slate-100 dark:border-slate-700/50 hover:border-[#EF7722]/30 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold ${textColor}">${escapeHtml(status)}</h4>
                                    ${details ? `<p class="text-sm text-slate-500 mt-1 leading-relaxed">${escapeHtml(details)}</p>` : ''}
                                    ${location ? `
                                        <div class="flex items-center mt-2 text-xs font-medium text-slate-400 uppercase tracking-wide">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            ${escapeHtml(location)}
                                        </div>
                                    ` : ''}
                                </div>
                                <div class="sm:text-right flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                        ${dateStr ? formatDate(dateStr) : ''}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                timelineContainer.insertAdjacentHTML('beforeend', html);
            }

            function resetUI() {
                errorState.classList.add('hidden');
                resultsContainer.classList.add('hidden');
                emptyState.classList.add('hidden');
            }

            function getField(obj, keys) {
                const lowerKeys = keys.map(k => k.toLowerCase());
                for (let key in obj) {
                    if (lowerKeys.includes(key.toLowerCase())) return obj[key];
                }
                return null;
            }

            function formatDate(dateString) {
                if(!dateString) return '';
                // Normalize date string
                const d = new Date(dateString.replace(/-/g, "/")); 
                return d.toLocaleString('en-US', {
                    month: 'short', day: 'numeric', 
                    hour: 'numeric', minute: '2-digit', hour12: true
                });
            }

            function escapeHtml(text) {
                if (!text) return text;
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
            
            // Expose to window for button click
            window.copyTracking = function() {
                const text = document.getElementById('resultTrackingNumber').innerText;
                if(text) {
                    navigator.clipboard.writeText(text);
                    alert("Tracking number copied!");
                }
            }
        });
    </script>
    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @endpush
</x-app-layout>