<x-app-layout :adminSourcingRequestCount="$totalSourcingRequests">
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-5">
                        <div class="relative w-16 h-16 bg-gradient-to-br from-[#EF7722] via-[#FAA533] to-[#EF7722] dark:from-[#EF7722] dark:via-[#FAA533] dark:to-[#EF7722] rounded-2xl flex items-center justify-center shadow-2xl shadow-[#EF7722]/25">
                            <div class="absolute inset-0 bg-[#FAA533] dark:bg-[#EF7722] rounded-2xl blur-xl opacity-20 animate-pulse"></div>
                            <svg class="w-8 h-8 text-white relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Admin Dashboard') }}
                            </h2>
                            <p class="mt-1.5 text-sm font-bold text-slate-600 dark:text-slate-400">{{ __('Welcome back, here\'s your business overview') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-gradient-to-br from-[#fffff] via-[#fffff]/80 to-[#EF7722]/10 dark:from-slate-900 dark:via-slate-900/95 dark:to-slate-800/50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Key Metrics Grid - Advanced Minimalist --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Users Card --}}
                <div class="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#EF7722]/[0.03] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#EF7722]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-2xl flex items-center justify-center shadow-xl shadow-[#EF7722]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-xl border border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30">
                                <svg class="w-3.5 h-3.5 text-[#0BA6DF] dark:text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                <span class="text-xs font-bold text-[#0BA6DF] dark:text-[#0BA6DF]">+12%</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Total Users') }}</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ number_format($totalUsers) }}</p>
                            <div class="pt-3 border-t border-[#EBEBEB] dark:border-slate-700/50">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ __('Active accounts') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Sourcing Requests Card --}}
                <div class="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#FAA533]/[0.03] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#FAA533]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#FAA533] to-[#EF7722] rounded-2xl flex items-center justify-center shadow-xl shadow-[#FAA533]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-xl border border-[#FAA533]/20 dark:border-[#FAA533]/30">
                                <div class="w-2 h-2 bg-[#FAA533] rounded-full animate-pulse"></div>
                                <span class="text-xs font-bold text-[#FAA533] dark:text-[#FAA533]">{{ __('Pending') }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Sourcing Requests') }}</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ number_format($pendingSourcingRequests) }}</p>
                            <div class="pt-3 border-t border-[#EBEBEB] dark:border-slate-700/50">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ __('Awaiting review') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Payment Orders Card --}}
                <div class="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#0BA6DF]/[0.03] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#0BA6DF]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#0BA6DF] to-[#0987B8] rounded-2xl flex items-center justify-center shadow-xl shadow-[#0BA6DF]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-xl border border-[#EF7722]/20 dark:border-[#EF7722]/30">
                                <svg class="w-3.5 h-3.5 text-[#EF7722] dark:text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-bold text-[#EF7722] dark:text-[#EF7722]">{{ __('Payment') }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Payment Orders') }}</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ number_format($pendingPaymentSourcingOrders) }}</p>
                            <div class="pt-3 border-t border-[#EBEBEB] dark:border-slate-700/50">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ __('Requires attention') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending Quotations Card --}}
                <div class="group relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-[#EF7722]/[0.03] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#EF7722]/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="relative">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#EF7722] to-[#D2691E] rounded-2xl flex items-center justify-center shadow-xl shadow-[#EF7722]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-xl border border-[#FAA533]/20 dark:border-[#FAA533]/30">
                                <svg class="w-3.5 h-3.5 text-[#FAA533] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="text-xs font-bold text-[#FAA533] dark:text-[#FAA533]">{{ __('Action') }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Quotations') }}</p>
                            <p class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ number_format($pendingQuotations) }}</p>
                            <div class="pt-3 border-t border-[#EBEBEB] dark:border-slate-700/50">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ __('Pending response') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Section - Minimalist Design --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Sourcing Requests Chart --}}
                <div class="group bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500">
                    <div class="flex items-center justify-between mb-8 pb-5 border-b border-[#EBEBEB] dark:border-slate-700/50">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('Sourcing Requests') }}</h3>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1.5">{{ __('Status distribution') }}</p>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-xl border border-[#FAA533]/20 dark:border-[#FAA533]/30">
                            <div class="w-2 h-2 bg-[#FAA533] rounded-full animate-pulse"></div>
                            <span class="text-xs font-bold text-[#FAA533] dark:text-[#FAA533]">{{ __('Live') }}</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="sourcingRequestsChart"></canvas>
                    </div>
                </div>

                {{-- Sourcing Orders Chart --}}
                <div class="group bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500">
                    <div class="flex items-center justify-between mb-8 pb-5 border-b border-[#EBEBEB] dark:border-slate-700/50">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('Sourcing Orders') }}</h3>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1.5">{{ __('Status distribution') }}</p>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-xl border border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30">
                            <div class="w-2 h-2 bg-[#0BA6DF] rounded-full animate-pulse"></div>
                            <span class="text-xs font-bold text-[#0BA6DF] dark:text-[#0BA6DF]">{{ __('Active') }}</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="sourcingOrdersChart"></canvas>
                    </div>
                </div>

                {{-- Quotations Chart --}}
                <div class="group bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg hover:shadow-2xl border border-[#EBEBEB] dark:border-slate-700/50 p-7 transition-all duration-500">
                    <div class="flex items-center justify-between mb-8 pb-5 border-b border-[#EBEBEB] dark:border-slate-700/50">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('Quotations') }}</h3>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1.5">{{ __('Status distribution') }}</p>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-xl border border-[#EF7722]/20 dark:border-[#EF7722]/30">
                            <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                            <span class="text-xs font-bold text-[#EF7722] dark:text-[#EF7722]">{{ __('Tracking') }}</span>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="quotationsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Activity and Quick Actions - Refined Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Activity Timeline --}}
                <div class="lg:col-span-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg border border-[#EBEBEB] dark:border-slate-700/50 p-7">
                    <div class="flex items-center justify-between mb-8 pb-5 border-b border-[#EBEBEB] dark:border-slate-700/50">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('Recent Activity') }}</h3>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1.5">{{ __('Latest system events and updates') }}</p>
                        </div>
                        <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-[#EF7722] dark:text-[#FAA533] hover:text-white bg-[#EF7722]/10 dark:bg-[#EF7722]/20 hover:bg-gradient-to-r hover:from-[#EF7722] hover:to-[#FAA533] rounded-xl border border-[#EF7722]/20 dark:border-[#EF7722]/30 hover:border-transparent transition-all duration-300 shadow-sm hover:shadow-lg">
                            {{ __('View all') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentActivities as $activity)
                            <div class="group flex items-start p-5 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all duration-300 border border-transparent hover:border-[#EBEBEB] dark:hover:border-slate-600/50 hover:shadow-md">
                                <div class="relative flex-shrink-0">
                                    <div class="absolute inset-0 bg-[#EF7722] dark:bg-[#FAA533] rounded-2xl blur-lg opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
                                    <div class="relative w-12 h-12 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-2xl flex items-center justify-center shadow-lg shadow-[#EF7722]/20 group-hover:scale-110 transition-all duration-300">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1 min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white leading-snug">{{ $activity->data['title'] ?? __('New Activity') }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed">{{ $activity->data['body'] ?? __('No description available.') }}</p>
                                    <div class="flex items-center mt-3 pt-3 border-t border-[#EBEBEB] dark:border-slate-700/50">
                                        <svg class="w-3.5 h-3.5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20">
                                <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-5 shadow-lg">
                                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-600 dark:text-slate-400">{{ __('No recent activity.') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-2">{{ __('Activity will appear here as events occur') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Actions - Minimalist Cards --}}
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl shadow-lg border border-[#EBEBEB] dark:border-slate-700/50 p-7">
                    <div class="mb-8 pb-5 border-b border-[#EBEBEB] dark:border-slate-700/50">
                        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ __('Quick Actions') }}</h3>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mt-1.5">{{ __('System configuration') }}</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.categories.index') }}" class="group block p-5 bg-gradient-to-br from-slate-50 to-slate-50/50 dark:from-slate-700/30 dark:to-slate-700/10 rounded-2xl border border-[#EBEBEB] dark:border-slate-600/50 hover:border-[#EF7722] dark:hover:border-[#FAA533] hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="relative flex-shrink-0">
                                        <div class="absolute inset-0 bg-[#EF7722] rounded-xl blur-lg opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
                                        <div class="relative w-11 h-11 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-xl flex items-center justify-center shadow-lg shadow-[#EF7722]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0">
                                        <p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ __('Categories') }}</p>
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Manage catalog') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#EF7722] dark:group-hover:text-[#FAA533] group-hover:translate-x-1 transition-all duration-300 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="group block p-5 bg-gradient-to-br from-slate-50 to-slate-50/50 dark:from-slate-700/30 dark:to-slate-700/10 rounded-2xl border border-[#EBEBEB] dark:border-slate-600/50 hover:border-[#0BA6DF] dark:hover:border-[#0BA6DF] hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="relative flex-shrink-0">
                                        <div class="absolute inset-0 bg-[#0BA6DF] rounded-xl blur-lg opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
                                        <div class="relative w-11 h-11 bg-gradient-to-br from-[#0BA6DF] to-[#0987B8] rounded-xl flex items-center justify-center shadow-lg shadow-[#0BA6DF]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0">
                                        <p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ __('Services') }}</p>
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Configure offerings') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#0BA6DF] dark:group-hover:text-[#0BA6DF] group-hover:translate-x-1 transition-all duration-300 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('admin.countries.index') }}" class="group block p-5 bg-gradient-to-br from-slate-50 to-slate-50/50 dark:from-slate-700/30 dark:to-slate-700/10 rounded-2xl border border-[#EBEBEB] dark:border-slate-600/50 hover:border-[#FAA533] dark:hover:border-[#FAA533] hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="relative flex-shrink-0">
                                        <div class="absolute inset-0 bg-[#FAA533] rounded-xl blur-lg opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
                                        <div class="relative w-11 h-11 bg-gradient-to-br from-[#FAA533] to-[#EF7722] rounded-xl flex items-center justify-center shadow-lg shadow-[#FAA533]/20 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 min-w-0">
                                        <p class="text-sm font-extrabold text-slate-900 dark:text-white">{{ __('Countries') }}</p>
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Manage regions') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#FAA533] dark:group-hover:text-[#FAA533] group-hover:translate-x-1 transition-all duration-300 flex-shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    orange: { main: '#EF7722', gradient: ['rgba(239, 119, 34, 0.9)', 'rgba(250, 165, 51, 0.9)'], border: 'rgba(239, 119, 34, 1)' },
                    lightOrange: { main: '#FAA533', gradient: ['rgba(250, 165, 51, 0.9)', 'rgba(239, 119, 34, 0.9)'], border: 'rgba(250, 165, 51, 1)' },
                    blue: { main: '#0BA6DF', gradient: ['rgba(11, 166, 223, 0.9)', 'rgba(9, 135, 184, 0.9)'], border: 'rgba(11, 166, 223, 1)' }
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
                                backgroundColor: 'rgba(15, 23, 42, 0.98)',
                                padding: 16,
                                titleColor: '#F8FAFC',
                                titleFont: { size: 13, weight: 'bold' },
                                bodyColor: '#E2E8F0',
                                bodyFont: { size: 12, weight: '600' },
                                borderColor: 'rgba(148, 163, 184, 0.2)',
                                borderWidth: 1,
                                cornerRadius: 12,
                                displayColors: false
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: { 
                                    color: '#94A3B8', 
                                    font: { size: 11, weight: '600' },
                                    padding: 8
                                }
                            },
                            y: {
                                grid: { 
                                    color: 'rgba(226, 232, 240, 0.3)',
                                    drawBorder: false
                                },
                                border: { display: false },
                                ticks: { 
                                    color: '#94A3B8', 
                                    font: { size: 11, weight: '600' }, 
                                    precision: 0,
                                    padding: 8
                                }
                            }
                        },
                        animation: { 
                            duration: 1800, 
                            easing: 'easeInOutQuart' 
                        }
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
                                backgroundColor: createBarGradient(ctx, colors.lightOrange),
                                borderColor: colors.lightOrange.border,
                                borderWidth: 0,
                                borderRadius: 10,
                                barThickness: 'flex',
                                maxBarThickness: 40
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
                                backgroundColor: createBarGradient(ctx, colors.blue),
                                borderColor: colors.blue.border,
                                borderWidth: 0,
                                borderRadius: 10,
                                barThickness: 'flex',
                                maxBarThickness: 40
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
                                backgroundColor: createBarGradient(ctx, colors.orange),
                                borderColor: colors.orange.border,
                                borderWidth: 0,
                                borderRadius: 10,
                                barThickness: 'flex',
                                maxBarThickness: 40
                            }]
                        }
                    });
                }
            });
        </script>
    @endpush

    <style>
        /* Premium minimalist scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, rgba(148, 163, 184, 0.4), rgba(148, 163, 184, 0.6));
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, rgba(71, 85, 105, 0.4), rgba(71, 85, 105, 0.6));
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, rgba(239, 119, 34, 0.6), rgba(250, 165, 51, 0.8));
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform, filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Backdrop blur support */
        @supports (backdrop-filter: blur(20px)) {
            .backdrop-blur-xl {
                backdrop-filter: blur(20px);
            }
        }

        /* Animation keyframes */
        @keyframes subtle-float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-4px); }
        }

        .group:hover .group-hover\:scale-110 {
            animation: subtle-float 2s ease-in-out infinite;
        }
    </style>
</x-app-layout>