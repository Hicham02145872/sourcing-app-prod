<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('17TRACK Tracking')]
]">
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            
            <!-- Header Section -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-slate-900 dark:text-white sm:truncate sm:text-3xl sm:tracking-tight">
                        {{ __('17TRACK Global Tracking') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Real-time monitoring via 17TRACK network.') }}
                    </p>
                </div>
            </div>

            <!-- Search Section -->
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
                                   placeholder="e.g. JTE300387065227" required>
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
                    <p class="text-slate-500 font-medium animate-pulse">{{ __('Querying 17TRACK servers...') }}</p>
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
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">{{ __('Tracking Alert') }}</h3>
                        <div class="mt-1 text-sm text-red-700 dark:text-red-300">
                            <p id="errorMessage"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Container: Premium Simplified View -->
            <div id="resultsContainer" class="hidden animate-fade-in max-w-2xl mx-auto">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                    <!-- Top Ribbon -->
                    <div class="h-2 bg-gradient-to-r from-orange-400 to-orange-600"></div>
                    
                    <div class="p-8">
                        <div class="flex flex-col items-center text-center space-y-6">
                            <!-- Tracking ID Badge -->
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-[11px] font-bold text-slate-500 tracking-wider">
                                <span>{{ __('TRACKING ID') }}:</span>
                                <span id="resultTrackingNumber" class="text-slate-900 dark:text-white font-mono"></span>
                                <button onclick="copyTracking()" class="hover:text-orange-500 transition-colors ml-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>

                            <!-- Large Status Display -->
                            <div class="space-y-2">
                                <div id="statusIconContainer" class="mx-auto w-20 h-20 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 mb-4 transition-transform hover:scale-110 duration-500">
                                    <svg id="mainStatusIcon" class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h1 id="latestStatusText" class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">--</h1>
                                <p class="text-slate-400 text-sm font-medium uppercase tracking-[0.2em]">{{ __('Current Status') }}</p>
                            </div>

                            <!-- Divider with pulse -->
                            <div class="w-full flex items-center justify-between gap-4 py-2">
                                <div class="h-px flex-1 bg-slate-100 dark:bg-slate-800"></div>
                                <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                                <div class="h-px flex-1 bg-slate-100 dark:bg-slate-800"></div>
                            </div>

                            <!-- Latest Place -->
                            <div class="w-full flex flex-col items-center space-y-2">
                                <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-xs font-bold uppercase tracking-widest">{{ __('Last Location Found') }}</span>
                                </div>
                                <p id="latestLocation" class="text-xl font-bold text-slate-800 dark:text-slate-200">--</p>
                            </div>

                            <!-- Last Update Timestamp -->
                            <div class="pt-6 border-t border-slate-50 dark:border-slate-800/50 w-full flex justify-center items-center gap-2 text-slate-400 text-[11px] font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ __('LAST SYNCED') }}:</span>
                                <span id="latestDate">--</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Branding -->
                    <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-center">
                        <div class="flex items-center gap-2 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            <span class="text-[10px] uppercase tracking-tighter text-slate-400 font-bold">Powered by</span>
                            <img src="https://static.17track.net/res/www/img/common/logo.png" alt="17TRACK" class="h-4 object-contain">
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
                <p class="mt-1 text-sm text-slate-500">{{ __('17TRACK has no record of this tracking number yet.') }}</p>
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
                resetUI();
                loadingState.classList.remove('hidden');
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Searching...';

                try {
                    const response = await fetch(`{{ route('client.tracking.17track.data') }}?number=${encodeURIComponent(number)}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const result = await response.json();
                    
                    if (!response.ok) throw new Error(result.error || 'Unable to fetch data');

                    if (result.data && result.data.accepted && result.data.accepted.length > 0) {
                        const trackInfo = result.data.accepted[0].track_info;
                        
                        if (!trackInfo) {
                            emptyState.classList.remove('hidden');
                        } else {
                             processTrackingData(trackInfo, number);
                        }
                    } else if (result.data && result.data.rejected && result.data.rejected.length > 0) {
                        throw new Error(result.data.rejected[0].error.message || 'Tracking number rejected');
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

            function processTrackingData(info, number) {
                const latest = info.latest_event || {};
                const statusInfo = info.latest_status || {};
                
                // Populate UI
                resultTrackingNumber.textContent = number;
                latestStatusText.textContent = statusInfo.status || 'Active';
                latestLocation.textContent = latest.location || 'In Transit';
                latestDate.textContent = latest.time_iso ? new Date(latest.time_iso).toLocaleString() : 'Just now';

                // Dynamic Icon based on status
                const status = (statusInfo.status || '').toLowerCase();
                const iconContainer = document.getElementById('statusIconContainer');
                const mainStatusIcon = document.getElementById('mainStatusIcon');
                
                if (status.includes('delivered')) {
                    iconContainer.className = 'mx-auto w-20 h-20 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 mb-4 transition-transform hover:scale-110 duration-500';
                    mainStatusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                } else if (status.includes('transit') || status.includes('shipped')) {
                    iconContainer.className = 'mx-auto w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 mb-4 transition-transform hover:scale-110 duration-500';
                    mainStatusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1m3 0V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9a1 1 0 01-1-1m8 1a1 1 0 021 1v1a1 1 0 01-1 1h-1a1 1 0 00-1 1v1a1 1 0 01-1 1h-1a1 1 0 00-1 1v1a1 1 0 01-1 1H9m4-1V8a1 1 0 00-1-1h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 00-.293.707V16m0 0a1 1 0 001 1h6.586a1 1 0 00.707-.293l2.414-2.414a1 1 0 00.293-.707V8.414a1 1 0 00-.293-.707l-2.414-2.414a1 1 0 00-.707-.293H13"/>';
                } else if (status.includes('pickup') || status.includes('arrived')) {
                    iconContainer.className = 'mx-auto w-20 h-20 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 mb-4 transition-transform hover:scale-110 duration-500';
                    mainStatusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>';
                }

                resultsContainer.classList.remove('hidden');
            }


            function resetUI() {
                errorState.classList.add('hidden');
                resultsContainer.classList.add('hidden');
                emptyState.classList.add('hidden');
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
