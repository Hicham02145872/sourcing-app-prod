<x-app-layout>
    <x-slot name="header">
        
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1.5">{{ __('Welcome back, here\'s what\'s happening today') }}</p>
                </div>
           
            </div>
        
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="px-2.5 py-1 bg-green-50 dark:bg-green-900/20 rounded-md">
                            <span class="text-xs font-semibold text-green-700 dark:text-green-400">+12%</span>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Total Users') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="usersSparkline"></canvas>
                    </div>
                </div>

                <!-- Pending Sourcing Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="px-2.5 py-1 bg-yellow-50 dark:bg-yellow-900/20 rounded-md">
                            <span class="text-xs font-semibold text-yellow-700 dark:text-yellow-400">{{ __('Pending') }}</span>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Sourcing Requests') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingSourcingRequests) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="requestsSparkline"></canvas>
                    </div>
                </div>

                <!-- Pending Payment Sourcing Orders -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div class="px-2.5 py-1 bg-orange-50 dark:bg-orange-900/20 rounded-md">
                            <span class="text-xs font-semibold text-orange-700 dark:text-orange-400">{{ __('Payment') }}</span>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Pending Payment Orders') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingPaymentSourcingOrders) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="ordersSparkline"></canvas>
                    </div>
                </div>

                <!-- Pending Quotations -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="px-2.5 py-1 bg-red-50 dark:bg-red-900/20 rounded-md">
                            <span class="text-xs font-semibold text-red-700 dark:text-red-400">{{ __('Action') }}</span>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('Pending Quotations') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingQuotations) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="quotationsSparkline"></canvas>
                    </div>
                </div>
            </div>

            <!-- Main Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Sourcing Requests Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Sourcing Requests') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Status distribution overview') }}</p>
                        </div>
                        <div class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-xs font-medium text-blue-700 dark:text-blue-400">{{ __('Live') }}</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="sourcingRequestsChart"></canvas>
                    </div>
                </div>

                <!-- Sourcing Orders Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Sourcing Orders') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Status distribution overview') }}</p>
                        </div>
                        <div class="px-3 py-1.5 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <span class="text-xs font-medium text-green-700 dark:text-green-400">{{ __('Active') }}</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="sourcingOrdersChart"></canvas>
                    </div>
                </div>

                <!-- Quotations Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Quotations') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Status distribution overview') }}</p>
                        </div>
                        <div class="px-3 py-1.5 bg-violet-50 dark:bg-violet-900/20 rounded-lg">
                            <span class="text-xs font-medium text-violet-700 dark:text-violet-400">{{ __('Tracking') }}</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="quotationsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Activity Timeline and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Activity Timeline -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Latest system events and updates') }}</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 bg-violet-50 dark:bg-violet-900/20 hover:bg-violet-100 dark:hover:bg-violet-900/30 rounded-lg transition-colors duration-200">
                            {{ __('View all') }}
                            <svg class="ml-1.5 w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white leading-snug">{{ $activity->data['title'] ?? __('New Activity') }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $activity->data['body'] ?? __('No description available.') }}</p>
                                    <div class="flex items-center mt-1.5">
                                        <svg class="w-3 h-3 text-gray-400 dark:text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('No recent activity.') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('Activity will appear here once available') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Quick Actions') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ __('System configuration') }}</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.categories.index') }}" class="group block p-4 bg-white dark:bg-gray-700 rounded-lg border-2 border-gray-100 dark:border-gray-600 hover:border-violet-200 dark:hover:border-violet-500/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Categories') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('Manage product catalog') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-violet-600 dark:group-hover:text-violet-400 group-hover:translate-x-1 transition-all duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="group block p-4 bg-white dark:bg-gray-700 rounded-lg border-2 border-gray-100 dark:border-gray-600 hover:border-blue-200 dark:hover:border-blue-500/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Services') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('Configure service offerings') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-1 transition-all duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.countries.index') }}" class="group block p-4 bg-white dark:bg-gray-700 rounded-lg border-2 border-gray-100 dark:border-gray-600 hover:border-green-200 dark:hover:border-green-500/50 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Countries') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ __('Manage shipping regions') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 group-hover:text-green-600 dark:group-hover:text-green-400 group-hover:translate-x-1 transition-all duration-200 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
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
                const sourcingRequestsData = @json($sourcingRequestsByStatus ?? []);
                const sourcingOrdersData = @json($sourcingOrdersByStatus ?? []);
                const quotationsData = @json($quotationsByStatus ?? []);

                // Professional enterprise color palette
                const colors = {
                    violet: {
                        main: '#8B5CF6',
                        light: '#A78BFA',
                        gradient: ['#8B5CF6', '#7C3AED']
                    },
                    blue: {
                        main: '#3B82F6',
                        light: '#60A5FA',
                        gradient: ['#3B82F6', '#2563EB']
                    },
                    green: {
                        main: '#10B981',
                        light: '#34D399',
                        gradient: ['#10B981', '#059669']
                    },
                    purple: {
                        main: '#A855F7',
                        light: '#C084FC',
                        gradient: ['#A855F7', '#9333EA']
                    }
                };

                // Enhanced sparkline configuration
                const sparklineOptions = {
                    type: 'line',
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false }, 
                            tooltip: { enabled: false } 
                        },
                        elements: { 
                            point: { radius: 0 },
                            line: { borderWidth: 2.5 }
                        },
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                };

                // Create gradient for sparklines
                function createGradient(ctx, color) {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 80);
                    gradient.addColorStop(0, color + '40');
                    gradient.addColorStop(1, color + '00');
                    return gradient;
                }

                // Users sparkline
                const usersCanvas = document.getElementById('usersSparkline');
                if (usersCanvas) {
                    const usersCtx = usersCanvas.getContext('2d');
                    new Chart(usersCtx, {
                        ...sparklineOptions,
                        data: {
                            labels: ['@lang('Mon')', '@lang('Tue')', '@lang('Wed')', '@lang('Thu')', '@lang('Fri')', '@lang('Sat')', '@lang('Sun')'],
                            datasets: [{
                                data: [65, 72, 68, 85, 82, 90, 95],
                                borderColor: colors.violet.main,
                                borderWidth: 2.5,
                                fill: true,
                                backgroundColor: createGradient(usersCtx, colors.violet.main),
                                tension: 0.4
                            }]
                        }
                    });
                }

                // Requests sparkline
                const requestsCanvas = document.getElementById('requestsSparkline');
                if (requestsCanvas) {
                    const requestsCtx = requestsCanvas.getContext('2d');
                    new Chart(requestsCtx, {
                        ...sparklineOptions,
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            datasets: [{
                                data: [45, 52, 48, 65, 62, 70, 75],
                                borderColor: colors.blue.main,
                                borderWidth: 2.5,
                                fill: true,
                                backgroundColor: createGradient(requestsCtx, colors.blue.main),
                                tension: 0.4
                            }]
                        }
                    });
                }

                // Orders sparkline
                const ordersCanvas = document.getElementById('ordersSparkline');
                if (ordersCanvas) {
                    const ordersCtx = ordersCanvas.getContext('2d');
                    new Chart(ordersCtx, {
                        ...sparklineOptions,
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            datasets: [{
                                data: [35, 42, 38, 55, 52, 60, 68],
                                borderColor: colors.green.main,
                                borderWidth: 2.5,
                                fill: true,
                                backgroundColor: createGradient(ordersCtx, colors.green.main),
                                tension: 0.4
                            }]
                        }
                    });
                }

                // Quotations sparkline
                const quotationsCanvas = document.getElementById('quotationsSparkline');
                if (quotationsCanvas) {
                    const quotationsCtx = quotationsCanvas.getContext('2d');
                    new Chart(quotationsCtx, {
                        ...sparklineOptions,
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                            datasets: [{
                                data: [25, 32, 28, 45, 42, 50, 58],
                                borderColor: colors.purple.main,
                                borderWidth: 2.5,
                                fill: true,
                                backgroundColor: createGradient(quotationsCtx, colors.purple.main),
                                tension: 0.4
                            }]
                        }
                    });
                }

                // Enhanced bar chart configuration
                const barChartOptions = {
                    type: 'bar',
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                                padding: 12,
                                titleColor: '#F9FAFB',
                                bodyColor: '#F9FAFB',
                                titleFont: {
                                    size: 13,
                                    weight: '600'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                displayColors: true,
                                boxWidth: 8,
                                boxHeight: 8,
                                boxPadding: 6,
                                caretPadding: 8,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return ' ' + context.parsed.y + ' ' + "{{ __('items') }}";
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { 
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: { 
                                    color: '#6B7280', 
                                    font: { 
                                        size: 11,
                                        weight: '500'
                                    },
                                    padding: 8
                                }
                            },
                            y: {
                                grid: { 
                                    color: '#F3F4F6',
                                    drawBorder: false,
                                    lineWidth: 1
                                },
                                ticks: { 
                                    color: '#6B7280', 
                                    font: { 
                                        size: 11,
                                        weight: '500'
                                    },
                                    precision: 0,
                                    padding: 8
                                },
                                border: {
                                    display: false
                                }
                            }
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                };

                // Helper function to create gradient bars
                function createBarGradient(ctx, color) {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                    gradient.addColorStop(0, color.gradient[0]);
                    gradient.addColorStop(1, color.gradient[1]);
                    return gradient;
                }

                // Sourcing Requests Chart
                const requestsChartCanvas = document.getElementById('sourcingRequestsChart');
                if (requestsChartCanvas && Object.keys(sourcingRequestsData).length > 0) {
                    const requestsChartCtx = requestsChartCanvas.getContext('2d');
                    new Chart(requestsChartCtx, {
                        ...barChartOptions,
                        data: {
                            labels: Object.keys(sourcingRequestsData).map(key => 
                                key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                            ),
                            datasets: [{
                                data: Object.values(sourcingRequestsData),
                                backgroundColor: createBarGradient(requestsChartCtx, colors.blue),
                                borderRadius: 8,
                                borderSkipped: false,
                                barThickness: 'flex',
                                maxBarThickness: 50
                            }]
                        }
                    });
                }

                // Sourcing Orders Chart
                const ordersChartCanvas = document.getElementById('sourcingOrdersChart');
                if (ordersChartCanvas && Object.keys(sourcingOrdersData).length > 0) {
                    const ordersChartCtx = ordersChartCanvas.getContext('2d');
                    new Chart(ordersChartCtx, {
                        ...barChartOptions,
                        data: {
                            labels: Object.keys(sourcingOrdersData).map(key => 
                                key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                            ),
                            datasets: [{
                                data: Object.values(sourcingOrdersData),
                                backgroundColor: createBarGradient(ordersChartCtx, colors.green),
                                borderRadius: 8,
                                borderSkipped: false,
                                barThickness: 'flex',
                                maxBarThickness: 50
                            }]
                        }
                    });
                }

                // Quotations Chart
                const quotationsChartCanvas = document.getElementById('quotationsChart');
                if (quotationsChartCanvas && Object.keys(quotationsData).length > 0) {
                    const quotationsChartCtx = quotationsChartCanvas.getContext('2d');
                    new Chart(quotationsChartCtx, {
                        ...barChartOptions,
                        data: {
                            labels: Object.keys(quotationsData).map(key => 
                                key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                            ),
                            datasets: [{
                                data: Object.values(quotationsData),
                                backgroundColor: createBarGradient(quotationsChartCtx, colors.violet),
                                borderRadius: 8,
                                borderSkipped: false,
                                barThickness: 'flex',
                                maxBarThickness: 50
                            }]
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>