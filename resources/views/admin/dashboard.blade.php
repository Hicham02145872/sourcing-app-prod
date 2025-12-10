<x-app-layout :adminSourcingRequestCount="$totalSourcingRequests">
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <!-- Brand Icon Box -->
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-[#EF7722] to-[#FAA533] flex items-center justify-center shadow-md shadow-[#EF7722]/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                            {{ __('Admin Dashboard') }}
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">
                            {{ __('Business overview & analytics') }}
                        </p>
                    </div>
                </div>
                <!-- Date Indicator (Visual) -->
                <div class="hidden sm:flex items-center gap-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-md px-3 py-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 dark:bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- 1. Key Metrics Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Users --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Users') }}</p>
                        <div class="p-2 bg-[#0BA6DF]/10 rounded-md">
                            <svg class="w-4 h-4 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalUsers) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Active accounts') }}</p>
                </div>

                {{-- Pending Sourcing --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pending Requests') }}</p>
                        <div class="p-2 bg-[#FAA533]/10 rounded-md">
                            <svg class="w-4 h-4 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($pendingSourcingRequests) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Requires review') }}</p>
                </div>

                {{-- Pending Payments --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Pending Payments') }}</p>
                        <div class="p-2 bg-[#EF7722]/10 rounded-md">
                            <svg class="w-4 h-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($pendingPaymentSourcingOrders) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Awaiting verification') }}</p>
                </div>

                {{-- Pending Quotations --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Open Quotations') }}</p>
                        <div class="p-2 bg-[#FAA533]/10 rounded-md">
                            <svg class="w-4 h-4 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">{{ number_format($pendingQuotations) }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Awaiting response') }}</p>
                </div>
            </div>

            {{-- 2. Charts Section - Intelligent & Smart --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- CHART 1: Sourcing Requests (COMBO: Line + Bar) --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="mb-1">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Sourcing Requests') }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Volume & Trend') }}</p>
                        </div>
                    </div>
                    
                    {{-- Container ID for Smart Logic --}}
                    <div id="sourcingRequestsContainer" class="flex-1 w-full min-h-[200px] py-4 flex items-center justify-center">
                        <canvas id="sourcingRequestsChart"></canvas>
                    </div>

                    <div class="flex items-center gap-2 font-medium leading-none text-slate-500 dark:text-slate-400 text-sm mt-2">
                        {{ __('Real-time Overview') }} 
                        <svg class="h-4 w-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>

                {{-- CHART 2: Orders (Bar) --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Sourcing Orders') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Processing flow') }}</p>
                    </div>
                    <div id="sourcingOrdersContainer" class="flex-1 min-h-[200px] relative w-full flex items-center justify-center">
                        <canvas id="sourcingOrdersChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-slate-500">
                        <div class="w-2 h-2 rounded-full bg-[#0BA6DF]"></div>
                        {{ __('Tracked orders') }}
                    </div>
                </div>

                {{-- CHART 3: Quotations (Bar) --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm flex flex-col">
                    <div class="mb-6">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Quotations') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Response analysis') }}</p>
                    </div>
                    <div id="quotationsContainer" class="flex-1 min-h-[200px] relative w-full flex items-center justify-center">
                        <canvas id="quotationsChart"></canvas>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs text-slate-500">
                        <div class="w-2 h-2 rounded-full bg-[#EF7722]"></div>
                        {{ __('Active quotes') }}
                    </div>
                </div>
            </div>

            {{-- 3. Activity & Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Activity Timeline --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('System logs and user actions') }}</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-[#EF7722] hover:text-[#d66616] transition-colors">
                            {{ __('View all') }} &rarr;
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="relative space-y-0">
                            {{-- Vertical line --}}
                            <div class="absolute top-2 left-[19px] h-full w-px bg-slate-200 dark:bg-slate-800"></div>

                            @forelse($recentActivities as $activity)
                                <div class="relative pl-10 pb-8 last:pb-0 group">
                                    {{-- Dot --}}
                                    <div class="absolute left-0 top-1.5 h-[10px] w-[10px] rounded-full border-2 border-white dark:border-slate-900 bg-[#EF7722] ring-4 ring-slate-50 dark:ring-slate-900 group-hover:ring-[#EF7722]/10 transition-all"></div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-200">
                                                {{ $activity->data['title'] ?? __('System Event') }}
                                            </p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5 max-w-xl">
                                                {{ $activity->data['body'] ?? __('No details available') }}
                                            </p>
                                        </div>
                                        <span class="text-xs font-medium text-slate-400 whitespace-nowrap bg-slate-50 dark:bg-slate-800 px-2 py-1 rounded">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-sm text-slate-500">{{ __('No recent activity recorded.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-sm h-fit">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('Quick Configuration') }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Manage system entities') }}</p>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-[#EF7722]/10 flex items-center justify-center text-[#EF7722] group-hover:bg-[#EF7722] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Categories') }}</p>
                                <p class="text-xs text-slate-500">{{ __('Product taxonomy') }}</p>
                            </div>
                            <svg class="ml-auto w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-[#0BA6DF]/10 flex items-center justify-center text-[#0BA6DF] group-hover:bg-[#0BA6DF] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Services') }}</p>
                                <p class="text-xs text-slate-500">{{ __('Service offerings') }}</p>
                            </div>
                            <svg class="ml-auto w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('admin.countries.index') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all group">
                            <div class="w-10 h-10 rounded-lg bg-[#FAA533]/10 flex items-center justify-center text-[#FAA533] group-hover:bg-[#FAA533] group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ __('Countries') }}</p>
                                <p class="text-xs text-slate-500">{{ __('Geographic regions') }}</p>
                            </div>
                            <svg class="ml-auto w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Configuration Globale
                Chart.defaults.font.family = "'Inter', 'ui-sans-serif', 'system-ui', sans-serif";
                Chart.defaults.color = '#64748b'; // slate-500
                
                // Mode Sombre Support
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    Chart.defaults.scale.grid.color = '#1e293b'; // slate-800
                    Chart.defaults.color = '#94a3b8'; // slate-400
                } else {
                    Chart.defaults.scale.grid.color = '#f1f5f9'; // slate-100
                }

                const colors = {
                    primary: '#EF7722',   // Orange
                    secondary: '#FAA533', // Orange Clair
                    info: '#0BA6DF',      // Bleu
                };

                // --- SMART CHART FUNCTION ---
                // Supporte: 'bar', 'line', 'combo' (Barres + Ligne)
                // Gère automatiquement: Pas de données, 1 donnée (Barre forcée), ou N données.
                const createSmartChart = (canvasId, containerId, rawData, colorHex, preferredType = 'bar') => {
                    const container = document.getElementById(containerId);
                    const canvas = document.getElementById(canvasId);
                    
                    // CAS 1 : Pas de données (Affichage SVG "Empty State")
                    if (!rawData || Object.keys(rawData).length === 0) {
                        if (container) {
                            container.innerHTML = `
                                <div class="flex flex-col items-center justify-center text-center p-6 opacity-60 w-full h-full">
                                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">{{ __('No data available') }}</p>
                                </div>`;
                        }
                        return;
                    }

                    const keys = Object.keys(rawData).map(key => 
                        key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                    );
                    const values = Object.values(rawData);
                    const dataCount = keys.length;

                    // CAS 2 : Une seule donnée -> Forcer 'bar' pour un rendu correct
                    // CAS 3 : Plusieurs données -> Utiliser le type préféré (combo, line, etc.)
                    let chartType = (dataCount === 1) ? 'bar' : preferredType;

                    const ctx = canvas.getContext('2d');
                    let datasets = [];

                    // --- LOGIQUE COMBO (Ligne + Barres) ---
                    if (chartType === 'combo') {
                        // 1. Dataset Ligne (Premier plan)
                        datasets.push({
                            type: 'line',
                            label: 'Trend',
                            data: values,
                            borderColor: colorHex,
                            borderWidth: 2,
                            tension: 0.4, // Courbe lissée
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: colorHex,
                            pointBorderWidth: 2,
                            fill: false,
                            order: 1 // Dessiné au dessus
                        });

                        // 2. Dataset Barres (Arrière plan - Opacité réduite)
                        datasets.push({
                            type: 'bar',
                            label: 'Volume',
                            data: values,
                            backgroundColor: colorHex + '20', // Opacité 20%
                            borderColor: 'transparent',
                            borderRadius: 4,
                            barThickness: 24,
                            order: 2 // Dessiné en dessous
                        });
                    } 
                    // --- LOGIQUE STANDARD (Line ou Bar simple) ---
                    else {
                        // Dégradé pour le Line Chart simple
                        let background = colorHex;
                        if (chartType === 'line') {
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, colorHex + '50'); 
                            gradient.addColorStop(1, colorHex + '00'); 
                            background = gradient;
                        }

                        datasets.push({
                            type: chartType,
                            data: values,
                            backgroundColor: background,
                            borderColor: chartType === 'line' ? colorHex : 'transparent',
                            borderWidth: chartType === 'line' ? 2 : 0,
                            borderRadius: chartType === 'line' ? 0 : 6,
                            barThickness: 32,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: colorHex,
                            pointBorderWidth: 2,
                        });
                    }

                    // Création du Graphique
                    new Chart(ctx, {
                        type: chartType === 'combo' ? 'bar' : chartType, // Le type principal pour Combo doit être 'bar'
                        data: {
                            labels: keys,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 1)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    intersect: chartType === 'bar', // Intersect pour les barres
                                    mode: (chartType === 'line' || chartType === 'combo') ? 'index' : 'nearest',
                                    callbacks: {
                                        label: function(context) {
                                            // Évite le double tooltip dans le graphique combo
                                            if (chartType === 'combo' && context.dataset.type === 'bar') return null;
                                            return 'Count: ' + context.parsed.y;
                                        }
                                    },
                                    filter: function(tooltipItem) {
                                        // Cache le tooltip des barres en mode combo pour ne pas l'avoir en double
                                        return tooltipItem.datasetIndex === 0; 
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11 } }
                                },
                                y: {
                                    // Affiche l'axe Y pour Bar et Combo, le cache pour Line simple
                                    display: chartType !== 'line', 
                                    grid: { 
                                        borderDash: [4, 4], 
                                        drawBorder: false,
                                        display: true 
                                    },
                                    min: 0,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                };

                // Chargement des données PHP
                const sourcingRequests = @json($sourcingRequestsByStatus ?? []);
                const sourcingOrders = @json($sourcingOrdersByStatus ?? []);
                const quotations = @json($quotationsByStatus ?? []);

                // INITIALISATION DES GRAPHIQUES
                
                // 1. Sourcing Requests : Mode COMBO (Barres + Ligne) avec Orange Secondaire
                createSmartChart('sourcingRequestsChart', 'sourcingRequestsContainer', sourcingRequests, colors.secondary, 'combo');
                
                // 2. Orders : Mode Barres Classique avec Bleu
                createSmartChart('sourcingOrdersChart', 'sourcingOrdersContainer', sourcingOrders, colors.info, 'bar');
                
                // 3. Quotations : Mode Barres Classique avec Orange Primaire
                createSmartChart('quotationsChart', 'quotationsContainer', quotations, colors.primary, 'bar');
            });
        </script>
    @endpush
</x-app-layout>