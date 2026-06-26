<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Dashboard') }}</h1>
                            <p class="text-xs text-slate-500 hidden sm:block">{{ __('Performance overview') }}</p>
                        </div>
                    </div>
                    
                    <!-- Date & Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end mr-2">
                            <span class="text-xs font-bold text-slate-700">{{ now()->format('l, d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ __('Casablanca (GMT+1)') }}</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                        
                        <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-orange-600 transition-colors" title="{{ __('Refresh') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Section 1: Key Performance Indicators (KPIs) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Users -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Users') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span> {{ __('Active accounts') }}
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Pending Requests') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($pendingSourcingRequests) }}</h3>
                        </div>
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-orange-600 font-medium">
                        @if($pendingSourcingRequests > 0)
                            <span class="animate-pulse w-1.5 h-1.5 rounded-full bg-orange-500 mr-2"></span> {{ __('Action required') }}
                        @else
                            <span class="text-slate-400">{{ __('Everything is up to date') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-amber-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Pending Payments') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($pendingPaymentSourcingOrders) }}</h3>
                        </div>
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        {{ __('Waiting for validation') }}
                    </div>
                </div>

                <!-- Quotations -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-purple-300 transition-all duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Open Quotations') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($pendingQuotations) }}</h3>
                        </div>
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center text-xs text-slate-400">
                        {{ __('Quotes in progress') }}
                    </div>
                </div>
            </div>

            @if(auth()->user()->isSuperAdmin() && isset($notificationStats))
            <!-- Section Extension: Notification Health (Super Admin Only) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-900 rounded-lg shadow-sm p-4 flex flex-col gap-3 group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-slate-800 text-orange-500 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">FCM REACH</p>
                                <h4 class="text-lg font-bold text-white">{{ $notificationStats['users_with_fcm'] }} <span class="text-[10px] font-normal text-slate-500">TOKENS</span></h4>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Without Token') }}</p>
                            <h4 class="text-sm font-bold text-slate-500">{{ $notificationStats['users_without_fcm'] }}</h4>
                        </div>
                    </div>
                    
                    <!-- Progress Bar / Ratio Visualization -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-[9px] font-bold">
                            <span class="text-emerald-500 uppercase">{{ __('Covered') }} ({{ $totalUsers > 0 ? round(($notificationStats['users_with_fcm'] / $totalUsers) * 100) : 0 }}%)</span>
                            <span class="text-slate-500 uppercase">{{ __('Pending') }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-800 rounded-full overflow-hidden flex">
                            @php 
                                $fcmPercentage = $totalUsers > 0 ? ($notificationStats['users_with_fcm'] / $totalUsers) * 100 : 0;
                            @endphp
                            <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $fcmPercentage }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-lg shadow-sm p-4 flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-slate-800 text-blue-500 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">TOTAL TRAFFIC</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($notificationStats['total_notifications']) }}</h4>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-slate-500">NOTIFICATIONS</span>
                    </div>
                </div>

                <div class="bg-emerald-600 rounded-lg shadow-sm p-4 flex items-center justify-between group">
                    <div class="flex items-center gap-4">
                        <div class="p-2.5 bg-emerald-500 text-white rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-100">SYSTEM STATUS</p>
                            <h4 class="text-xl font-bold text-white">HEALTHY</h4>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold text-emerald-200">99.9% UPTIME</span>
                    </div>
                </div>
            </div>

            <!-- FCM Diagnostic Detailed View -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- With Tokens -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center font-bold">
                        <h5 class="text-xs text-slate-700 uppercase tracking-wider flex items-center gap-2">
                             <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                             {{ __('Registered Devices') }}
                        </h5>
                        <a href="{{ route('admin.users.index', ['fcm_status' => 'has_token']) }}" class="text-[10px] text-blue-600 hover:underline">{{ __('View All') }}</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($notificationStats['samples_with_token'] as $sample)
                            <div class="px-4 py-2.5 flex items-center justify-between group hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-blue-100 bg-blue-50 flex items-center justify-center text-[10px] font-bold text-blue-600 shadow-sm flex-shrink-0">
                                        @if ($sample->profile_photo_path)
                                            <img class="h-full w-full object-cover" src="{{ media_url($sample->profile_photo_path) }}" alt="{{ $sample->name }}" />
                                        @else
                                            {{ substr($sample->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-800">{{ $sample->name }}</span>
                                        <span class="text-[10px] text-slate-500">{{ $sample->email }}</span>
                                    </div>
                                </div>
                                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">{{ __('TOKEN OK') }}</span>
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-slate-400 italic">{{ __('No devices registered yet.') }}</div>
                        @endforelse
                    </div>
                </div>

                <!-- Missing Tokens -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center font-bold">
                        <h5 class="text-xs text-slate-700 uppercase tracking-wider flex items-center gap-2">
                             <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                             {{ __('Missing Push Tokens') }}
                        </h5>
                        <a href="{{ route('admin.users.index', ['fcm_status' => 'no_token']) }}" class="text-[10px] text-blue-600 hover:underline">{{ __('View All') }}</a>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($notificationStats['samples_without_token'] as $sample)
                            <div class="px-4 py-2.5 flex items-center justify-between group hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center text-[10px] font-bold text-slate-400 shadow-sm flex-shrink-0">
                                        @if ($sample->profile_photo_path)
                                            <img class="h-full w-full object-cover" src="{{ media_url($sample->profile_photo_path) }}" alt="{{ $sample->name }}" />
                                        @else
                                            {{ substr($sample->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-800">{{ $sample->name }}</span>
                                        <span class="text-[10px] text-slate-500">{{ $sample->email }}</span>
                                    </div>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">{{ __('NO TOKEN') }}</span>
                            </div>
                        @empty
                             <div class="py-10 text-center text-xs text-slate-400 italic">{{ __('All active users have tokens!') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

            <!-- Section 1.5: Performance Score (Super Admin Only) -->
            @if(auth()->user()->isSuperAdmin())
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                    <div class="px-6 py-4 bg-slate-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                {{ __('Operational Performance Score') }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">{{ __('Productivity analysis by account manager') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                           <span class="inline-flex items-center px-2 py-1 rounded bg-slate-800 text-slate-300 text-[10px] font-bold uppercase tracking-wider">
                               {{ __('Real Time') }}
                           </span>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Left side: Stats Table (Livewire) -->
                        <div class="lg:col-span-8 space-y-4">
                            @livewire('admin.admin-performance-table')
                        </div>

                        <!-- Right side: Chart Area -->
                        <div class="lg:col-span-4 flex flex-col justify-center">
                            <div class="bg-slate-50 rounded-xl p-6 border border-slate-100 h-full min-h-[300px] flex flex-col">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Load distribution') }}</span>
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span><span class="text-[10px] font-bold text-slate-600">{{ __('REQ') }}</span></div>
                                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span><span class="text-[10px] font-bold text-slate-600">{{ __('ORD') }}</span></div>
                                    </div>
                                </div>
                                <div class="flex-1 relative w-full">
                                    <canvas id="adminPerformanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Section 2: Charts (Smart Logic) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Chart 1: Requests (Combo) -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Sourcing Requests') }}</h3>
                        <span class="text-[10px] uppercase bg-slate-100 text-slate-500 px-2 py-0.5 rounded">Volume</span>
                    </div>
                    <div id="requestsChartContainer" class="flex-1 w-full min-h-[220px] relative">
                        <canvas id="sourcingRequestsChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Orders (Bar) -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Sourcing Orders') }}</h3>
                        <span class="text-[10px] uppercase bg-blue-50 text-blue-600 px-2 py-0.5 rounded">Conversion</span>
                    </div>
                    <div id="ordersChartContainer" class="flex-1 w-full min-h-[220px] relative">
                        <canvas id="sourcingOrdersChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Quotations (Bar) -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Quotations') }}</h3>
                        <span class="text-[10px] uppercase bg-purple-50 text-purple-600 px-2 py-0.5 rounded">Status</span>
                    </div>
                    <div id="quotationsChartContainer" class="flex-1 w-full min-h-[220px] relative">
                        <canvas id="quotationsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Section 3: Activity & Shortcuts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Activity Timeline -->
                <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Recent Activity') }}</h3>
                        <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-orange-600 hover:text-orange-700 hover:underline">{{ __('View all') }}</a>
                    </div>
                    <div class="p-6">
                        <div class="relative space-y-0 pl-3">
                            <!-- Vertical Line -->
                            <div class="absolute top-2 left-[6px] h-full w-px bg-slate-200"></div>

                            @forelse($recentActivities as $activity)
                                <div class="relative pl-8 pb-6 last:pb-0 group">
                                    <!-- Dot -->
                                    <div class="absolute left-0 top-1.5 w-3 h-3 bg-white border-2 border-slate-300 rounded-full group-hover:border-orange-500 transition-colors z-10"></div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1">
                                        <div>
                                            <p class="text-xs font-bold text-slate-800">
                                                {{ $activity->data['title'] ?? __('System Event') }}
                                            </p>
                                            <p class="text-xs text-slate-500 mt-0.5 max-w-lg">
                                                {{ $activity->data['body'] ?? __('No details available') }}
                                            </p>
                                        </div>
                                        <span class="text-[10px] font-medium text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100 whitespace-nowrap">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-50 mb-2">
                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ __('No recent activity.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Actions / Navigation -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm h-fit">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Quick Management') }}</h3>
                    </div>
                    <div class="p-2 space-y-1">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center p-3 rounded-md hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="w-8 h-8 rounded bg-orange-50 text-orange-600 flex items-center justify-center mr-3 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ __('Categories') }}</p>
                                <p class="text-[10px] text-slate-400">{{ __('Manage taxonomy') }}</p>
                            </div>
                            <svg class="w-3 h-3 text-slate-300 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('admin.services.index') }}" class="flex items-center p-3 rounded-md hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ __('Services') }}</p>
                                <p class="text-[10px] text-slate-400">{{ __('Logistics offers') }}</p>
                            </div>
                            <svg class="w-3 h-3 text-slate-300 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('admin.countries.index') }}" class="flex items-center p-3 rounded-md hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all group">
                            <div class="w-8 h-8 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center mr-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ __('Countries') }}</p>
                                <p class="text-[10px] text-slate-400">{{ __('Geographic zones') }}</p>
                            </div>
                            <svg class="w-3 h-3 text-slate-300 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
                // Enterprise Chart Config
                Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
                Chart.defaults.color = '#64748b'; 
                
                const colors = {
                    primary: '#EF7722',   // Orange
                    secondary: '#FAA533', // Orange Light
                    info: '#0BA6DF',      // Blue
                    success: '#10b981'    // Emerald
                };

                // Admin Performance Chart (Super Admin Only)
                @if(auth()->user()->isSuperAdmin() && isset($allAdminsPerformance))
                    const perfData = @json($allAdminsPerformance);
                    const perfCanvas = document.getElementById('adminPerformanceChart');
                    
                    if (perfCanvas && perfData.length > 0) {
                        new Chart(perfCanvas.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: perfData.map(a => a.name),
                                datasets: [
                                    {
                                        label: '{{ __('Requests') }}',
                                        data: perfData.map(a => a.assigned_sourcing_requests_count),
                                        backgroundColor: colors.primary,
                                        borderRadius: 4,
                                        categoryPercentage: 0.6,
                                        barPercentage: 0.7
                                    },
                                    {
                                        label: '{{ __('Orders') }}',
                                        data: perfData.map(a => a.assigned_sourcing_orders_count),
                                        backgroundColor: colors.info,
                                        borderRadius: 4,
                                        categoryPercentage: 0.6,
                                        barPercentage: 0.7
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'top', align: 'end', labels: { boxWidth: 10, usePointStyle: true } }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { borderDash: [4, 4], drawBorder: false },
                                        stacked: true,
                                        ticks: { font: { size: 10 }, precision: 0 }
                                    },
                                    x: {
                                        grid: { display: false },
                                        stacked: true,
                                        ticks: { font: { size: 10 } }
                                    }
                                }
                            }
                        });
                    }
                @endif

                // Smart Chart Logic: Handles 0, 1, or N data points gracefully
                const createSmartChart = (canvasId, containerId, rawData, mainColor, type = 'bar') => {
                    const container = document.getElementById(containerId);
                    const canvas = document.getElementById(canvasId);
                    
                    if (!rawData || Object.keys(rawData).length === 0) {
                        // EMPTY STATE (SVG)
                        container.innerHTML = `
                            <div class="flex flex-col items-center justify-center h-full opacity-50">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-xs text-slate-500 font-medium">{{ __('No data') }}</p>
                            </div>`;
                        return;
                    }

                    const labels = Object.keys(rawData).map(k => k.replace(/_/g, ' ').toUpperCase());
                    const data = Object.values(rawData);
                    
                    // Force 'bar' if only 1 data point (Line chart looks broken with 1 point)
                    const finalType = (data.length === 1) ? 'bar' : type;
                    const ctx = canvas.getContext('2d');

                    let datasets = [];

                    if (finalType === 'combo') {
                        // Combo: Line on top, Bars below
                        datasets = [
                            {
                                type: 'line',
                                label: 'Trend',
                                data: data,
                                borderColor: mainColor,
                                borderWidth: 2,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: mainColor,
                                pointRadius: 4,
                                tension: 0.3,
                                order: 1
                            },
                            {
                                type: 'bar',
                                label: 'Volume',
                                data: data,
                                backgroundColor: mainColor + '20', // 20% opacity
                                borderRadius: 4,
                                barThickness: 20,
                                order: 2
                            }
                        ];
                    } else {
                        // Standard Bar or Line
                        datasets = [{
                            data: data,
                            backgroundColor: finalType === 'bar' ? mainColor : 'transparent',
                            borderColor: mainColor,
                            borderWidth: 2,
                            borderRadius: 4,
                            barThickness: 24,
                            tension: 0.3,
                            pointRadius: finalType === 'line' ? 4 : 0,
                            pointBackgroundColor: '#fff'
                        }];
                    }

                    new Chart(ctx, {
                        type: finalType === 'combo' ? 'bar' : finalType,
                        data: { labels: labels, datasets: datasets },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { borderDash: [4, 4], drawBorder: false },
                                    ticks: { font: { size: 10 }, padding: 8, precision: 0 }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 9, weight: '600' } }
                                }
                            }
                        }
                    });
                };

                // Data Injection
                const reqData = @json($sourcingRequestsByStatus ?? []);
                const ordData = @json($sourcingOrdersByStatus ?? []);
                const quoData = @json($quotationsByStatus ?? []);

                // Initialize Charts
                createSmartChart('sourcingRequestsChart', 'requestsChartContainer', reqData, colors.primary, 'combo');
                createSmartChart('sourcingOrdersChart', 'ordersChartContainer', ordData, colors.info, 'bar');
                createSmartChart('quotationsChart', 'quotationsChartContainer', quoData, '#8b5cf6', 'bar'); // Purple for quotes
            });
        </script>
    @endpush
</x-app-layout>