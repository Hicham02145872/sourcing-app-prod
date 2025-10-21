<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -m-6 p-6 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Dashboard') }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1.5">{{ __('Welcome back, here\'s what\'s happening today') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <select class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                        <option>{{ __('Last 7 days') }}</option>
                        <option>{{ __('Last 30 days') }}</option>
                        <option>{{ __('Last 90 days') }}</option>
                        <option>{{ __('This year') }}</option>
                    </select>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Export') }}
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-violet-100 dark:bg-violet-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Total Users') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="usersSparkline"></canvas>
                    </div>
                </div>

                <!-- Pending Sourcing Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Pending Sourcing Requests') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingSourcingRequests) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="requestsSparkline"></canvas>
                    </div>
                </div>

                <!-- Pending Payment Sourcing Orders -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m-9 4h8m-10 4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-2.5"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Pending Payment Orders') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingPaymentSourcingOrders) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="ordersSparkline"></canvas>
                    </div>
                </div>

                <!-- Pending Quotations -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('Pending Quotations') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($pendingQuotations) }}</p>
                    <div class="mt-4 h-16">
                        <canvas id="quotationsSparkline"></canvas>
                    </div>
                </div>
            </div>

            <!-- Main Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Sourcing Requests Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
<p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Status distribution') }}</p>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="sourcingRequestsChart"></canvas>
                    </div>
                </div>

                <!-- Sourcing Orders Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Sourcing Orders') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status distribution</p>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="sourcingOrdersChart"></canvas>
                    </div>
                </div>

                <!-- Quotations Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Quotations') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status distribution</p>
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
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Latest system events and updates') }}</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="text-sm text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 font-medium">{{ __('View all') }}</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->data['title'] ?? __('New Activity') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activity->data['body'] ?? __('No description available.') }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No recent activity.') }}</p>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Quick Actions') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Manage settings') }}</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.categories.index') }}" class="group block p-4 bg-gradient-to-br from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-violet-100 dark:border-gray-600 hover:border-violet-300 dark:hover:border-violet-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Categories') }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Manage catalog') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="group block p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-blue-100 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Services') }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Configure offerings') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.countries.index') }}" class="group block p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-green-100 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-500 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Countries') }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Shipping locations') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sourcingRequestsData = @json($sourcingRequestsByStatus);
                const sourcingOrdersData = @json($sourcingOrdersByStatus);
                const quotationsData = @json($quotationsByStatus);

                // Modern color palette
                const colors = {
                    violet: '#8B5CF6',
                    blue: '#3B82F6',
                    green: '#10B981',
                    yellow: '#F59E0B',
                    red: '#EF4444',
                    purple: '#A855F7'
                };

                // Sparkline charts for metric cards
                const sparklineOptions = {
                    type: 'line',
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } },
                        elements: { point: { radius: 0 } },
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        }
                    }
                };

                // Users sparkline
                new Chart(document.getElementById('usersSparkline'), {
                    ...sparklineOptions,
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            data: [65, 72, 68, 85, 82, 90, 95],
                            borderColor: colors.violet,
                            borderWidth: 2,
                            fill: true,
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            tension: 0.4
                        }]
                    }
                });

                // Requests sparkline
                new Chart(document.getElementById('requestsSparkline'), {
                    ...sparklineOptions,
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            data: [45, 52, 48, 65, 62, 70, 75],
                            borderColor: colors.blue,
                            borderWidth: 2,
                            fill: true,
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4
                        }]
                    }
                });

                // Orders sparkline
                new Chart(document.getElementById('ordersSparkline'), {
                    ...sparklineOptions,
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            data: [35, 42, 38, 55, 52, 60, 68],
                            borderColor: colors.green,
                            borderWidth: 2,
                            fill: true,
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4
                        }]
                    }
                });

                // Quotations sparkline
                new Chart(document.getElementById('quotationsSparkline'), {
                    ...sparklineOptions,
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            data: [25, 32, 28, 45, 42, 50, 58],
                            borderColor: colors.purple,
                            borderWidth: 2,
                            fill: true,
                            backgroundColor: 'rgba(168, 85, 247, 0.1)',
                            tension: 0.4
                        }]
                    }
                });

                // Main bar chart options
                const barChartOptions = {
                    type: 'bar',
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' items';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#6B7280', font: { size: 11 } }
                            },
                            y: {
                                grid: { color: '#F3F4F6', drawBorder: false },
                                ticks: { color: '#6B7280', font: { size: 11 }, precision: 0 }
                            }
                        }
                    }
                };

                // Sourcing Requests Chart
                new Chart(document.getElementById('sourcingRequestsChart'), {
                    ...barChartOptions,
                    data: {
                        labels: Object.keys(sourcingRequestsData).map(key => 
                            key.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                        ),
                        datasets: [{
                            data: Object.values(sourcingRequestsData),
                            backgroundColor: colors.blue,
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    }
                });

                // Sourcing Orders Chart
                new Chart(document.getElementById('sourcingOrdersChart'), {
                    ...barChartOptions,
                    data: {
                        labels: Object.keys(sourcingOrdersData).map(key => 
                            key.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                        ),
                        datasets: [{
                            data: Object.values(sourcingOrdersData),
                            backgroundColor: colors.green,
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    }
                });

                // Quotations Chart
                new Chart(document.getElementById('quotationsChart'), {
                    ...barChartOptions,
                    data: {
                        labels: Object.keys(quotationsData).map(key => 
                            key.replace('_', ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                        ),
                        datasets: [{
                            data: Object.values(quotationsData),
                            backgroundColor: colors.violet,
                            borderRadius: 6,
                            borderSkipped: false
                        }]
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>