<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 dark:bg-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Admin Dashboard') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Welcome back, here\'s what\'s happening today') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Key Metrics Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {{-- Total Users Card --}}
                <div class="group relative bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-xl border border-slate-200 dark:border-slate-700 p-6 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">+12%</span>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">{{ __('Total Users') }}</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</p>
                    </div>
                </div>

                {{-- Pending Sourcing Requests Card --}}
                <div class="group relative bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-xl border border-slate-200 dark:border-slate-700 p-6 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="px-2.5 py-1 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                <span class="text-xs font-bold text-amber-700 dark:text-amber-400">{{ __('Pending') }}</span>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">{{ __('Sourcing Requests') }}</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($pendingSourcingRequests) }}</p>
                    </div>
                </div>

                {{-- Pending Payment Orders Card --}}
                <div class="group relative bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-xl border border-slate-200 dark:border-slate-700 p-6 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div class="px-2.5 py-1 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                <span class="text-xs font-bold text-orange-700 dark:text-orange-400">{{ __('Payment') }}</span>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">{{ __('Pending Payment Orders') }}</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($pendingPaymentSourcingOrders) }}</p>
                    </div>
                </div>

                {{-- Pending Quotations Card --}}
                <div class="group relative bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-xl border border-slate-200 dark:border-slate-700 p-6 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="px-2.5 py-1 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                <span class="text-xs font-bold text-red-700 dark:text-red-400">{{ __('Action') }}</span>
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-1">{{ __('Pending Quotations') }}</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($pendingQuotations) }}</p>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                {{-- Sourcing Requests Chart --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Sourcing Requests') }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Status distribution') }}</p>
                        </div>
                        <div class="px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <span class="text-xs font-bold text-blue-700 dark:text-blue-400">{{ __('Live') }}</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="sourcingRequestsChart"></canvas>
                    </div>
                </div>

                {{-- Sourcing Orders Chart --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Sourcing Orders') }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Status distribution') }}</p>
                        </div>
                        <div class="px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400">{{ __('Active') }}</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="sourcingOrdersChart"></canvas>
                    </div>
                </div>

                {{-- Quotations Chart --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Quotations') }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Status distribution') }}</p>
                        </div>
                        <div class="px-3 py-1.5 bg-violet-100 dark:bg-violet-900/30 rounded-lg">
                            <span class="text-xs font-bold text-violet-700 dark:text-violet-400">{{ __('Tracking') }}</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="quotationsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Activity and Quick Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Activity Timeline --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('Latest system events') }}</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-violet-600 dark:text-violet-400 hover:text-white bg-violet-50 dark:bg-violet-900/30 hover:bg-violet-600 rounded-lg transition-all duration-200">
                            {{ __('View all') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentActivities as $activity)
                            <div class="group flex items-start p-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all duration-200 border border-transparent hover:border-slate-200 dark:hover:border-slate-600">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-all duration-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $activity->data['title'] ?? __('New Activity') }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">{{ $activity->data['body'] ?? __('No description available.') }}</p>
                                    <div class="flex items-center mt-2">
                                        <svg class="w-3.5 h-3.5 text-slate-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs font-medium text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('No recent activity.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Quick Actions') }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ __('System configuration') }}</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.categories.index') }}" class="group block p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-violet-300 dark:hover:border-violet-500 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-violet-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-violet-500/30 group-hover:scale-110 transition-all duration-300">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Categories') }}</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('Manage catalog') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-violet-600 group-hover:translate-x-1 transition-all duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="group block p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-500 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-all duration-300">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Services') }}</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('Configure offerings') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.countries.index') }}" class="group block p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-emerald-300 dark:hover:border-emerald-500 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-all duration-300">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Countries') }}</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('Manage regions') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all duration-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                const colors = {
                    violet: { main: '#8B5CF6', gradient: ['#8B5CF6', '#7C3AED'] },
                    blue: { main: '#3B82F6', gradient: ['#3B82F6', '#2563EB'] },
                    emerald: { main: '#10B981', gradient: ['#10B981', '#059669'] },
                    purple: { main: '#A855F7', gradient: ['#A855F7', '#9333EA'] }
                };

                function createBarGradient(ctx, color) {
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, color.gradient[0]);
                    gradient.addColorStop(1, color.gradient[1]);
                    return gradient;
                }

                const barChartOptions = {
                    type: 'bar',
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.96)',
                                padding: 12,
                                titleColor: '#F1F5F9',
                                bodyColor: '#F1F5F9',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748B', font: { size: 11, weight: '600' } }
                            },
                            y: {
                                grid: { color: 'rgba(226, 232, 240, 0.5)' },
                                ticks: { color: '#64748B', font: { size: 11, weight: '600' }, precision: 0 }
                            }
                        },
                        animation: { duration: 1500, easing: 'easeInOutQuart' }
                    }
                };

                // Sourcing Requests Chart
                const requestsCanvas = document.getElementById('sourcingRequestsChart');
                if (requestsCanvas && Object.keys(sourcingRequestsData).length > 0) {
                    const ctx = requestsCanvas.getContext('2d');
                    new Chart(ctx, {
                        ...barChartOptions,
                        data: {
                            labels: Object.keys(sourcingRequestsData).map(key => 
                                key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                            ),
                            datasets: [{
                                data: Object.values(sourcingRequestsData),
                                backgroundColor: createBarGradient(ctx, colors.blue),
                                borderRadius: 8,
                                barThickness: 'flex',
                                maxBarThickness: 50
                            }]
                        }
                    });
                }

                // Sourcing Orders Chart
                const ordersCanvas = document.getElementById('sourcingOrdersChart');
                if (ordersCanvas && Object.keys(sourcingOrdersData).length > 0) {
                    const ctx = ordersCanvas.getContext('2d');
                    new Chart(ctx, {
                        ...barChartOptions,
                        data: {
                            labels: Object.keys(sourcingOrdersData).map(key => 
                                key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                            ),
                            datasets: [{
                                data: Object.values(sourcingOrdersData),
                                backgroundColor: createBarGradient(ctx, colors.emerald),
                                borderRadius: 8,
                                barThickness: 'flex',
                                maxBarThickness: 50
                            }]
                        }
                    });
                }

                // Quotations Chart
                const quotationsCanvas = document.getElementById('quotationsChart');
                if (quotationsCanvas && Object.keys(quotationsData).length > 0) {
                    const ctx = quotationsCanvas.getContext('2d');
                    new Chart(ctx, {
                        ...barChartOptions,
                        data: {
                            labels: Object.keys(quotationsData).map(key => 
                                key.replace(/_/g, ' ').split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')
                            ),
                            datasets: [{
                                data: Object.values(quotationsData),
                                backgroundColor: createBarGradient(ctx, colors.violet),
                                borderRadius: 8,
                                barThickness: 'flex',
                                maxBarThickness: 50
                            }]
                        }
                    });
                }
            });
        </script>
    @endpush

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>