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
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Sourcing Orders') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition-colors">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Order Management') }}</span>
                            </nav>
                        </div>
                    </div>

                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            {{ __('Refresh') }}
                        </button>
                        <a href="{{ route('admin.sourcing-orders.export-pdf', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-900 border border-transparent text-white hover:bg-slate-800 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ __('Export PDF') }}
                        </a>
                        <form action="{{ route('admin.sourcing-orders.duplicate-last') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-600 border border-transparent text-white hover:bg-orange-700 text-xs font-medium rounded transition-colors shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7l4-4m-4 4l-4-4"/></svg>
                                {{ __('Duplicate Last') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Flash Messages -->
            @if (session('success'))
                <div id="flash-message" class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Section 1: KPIs (Summary Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Orders -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Orders') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingOrders->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-slate-100 rounded text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Total volume') }}</div>
                </div>

                <!-- Pending Payment -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-amber-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Pending Payment') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingOrders->where('status', 'pending_payment')->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-amber-50 rounded text-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-amber-600 font-medium">{{ __('Requires follow-up') }}</div>
                </div>

                <!-- Processing -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Processing') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingOrders->where('status', 'processing')->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-blue-50 rounded text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Production / Logistics') }}</div>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-emerald-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Delivered') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $sourcingOrders->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-emerald-50 rounded text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Cycle completed') }}</div>
                </div>
            </div>

            <!-- Section 2: Filters Bar (Sticky) -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 sticky top-20 z-10">
                <form action="{{ route('admin.sourcing-orders.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        
                        <div class="flex-1 w-full md:w-auto flex flex-col md:flex-row gap-3">
                            <!-- Search -->
                            <div class="relative w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ID, Client or Product...') }}"
                                    class="w-full pl-9 pr-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors placeholder:text-slate-400">
                            </div>

                            <!-- Admin Filter (Super Admin only) -->
                            @if(auth()->user()->isSuperAdmin())
                            <div class="w-full md:w-48">
                                <select name="admin_id" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
                                    <option value="all">{{ __('All Admins') }}</option>
                                    <option value="unassigned" {{ request('admin_id') == 'unassigned' ? 'selected' : '' }}>{{ __('Unassigned') }}</option>
                                    @foreach ($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            @include('admin.partials.date-range-fields')

                             <button type="submit" class="w-full md:w-auto px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm">
                                {{ __('Filter') }}
                            </button>
                        </div>

                        <!-- Reset -->
                        <div>
                             <a href="{{ route('admin.sourcing-orders.index') }}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-orange-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Section 2b: Status Toggle Bar -->
            @php
                $statusColors = [
                    'pending_payment'                      => ['active' => 'bg-amber-500 text-white border-amber-500', 'inactive' => 'bg-white text-amber-700 border-amber-200 hover:bg-amber-50'],
                    'paid'                                 => ['active' => 'bg-blue-500 text-white border-blue-500', 'inactive' => 'bg-white text-blue-700 border-blue-200 hover:bg-blue-50'],
                    'shipment_preparing'                   => ['active' => 'bg-cyan-500 text-white border-cyan-500', 'inactive' => 'bg-white text-cyan-700 border-cyan-200 hover:bg-cyan-50'],
                    'in_transit_china'                     => ['active' => 'bg-teal-500 text-white border-teal-500', 'inactive' => 'bg-white text-teal-700 border-teal-200 hover:bg-teal-50'],
                    'arrival_uae'                          => ['active' => 'bg-sky-500 text-white border-sky-500', 'inactive' => 'bg-white text-sky-700 border-sky-200 hover:bg-sky-50'],
                    'customs_clearance_uae'                => ['active' => 'bg-indigo-500 text-white border-indigo-500', 'inactive' => 'bg-white text-indigo-700 border-indigo-200 hover:bg-indigo-50'],
                    'in_transit_uae'                       => ['active' => 'bg-violet-500 text-white border-violet-500', 'inactive' => 'bg-white text-violet-700 border-violet-200 hover:bg-violet-50'],
                    'arrival_destination_country'          => ['active' => 'bg-purple-500 text-white border-purple-500', 'inactive' => 'bg-white text-purple-700 border-purple-200 hover:bg-purple-50'],
                    'customs_clearance_destination_country' => ['active' => 'bg-fuchsia-500 text-white border-fuchsia-500', 'inactive' => 'bg-white text-fuchsia-700 border-fuchsia-200 hover:bg-fuchsia-50'],
                    'out_for_delivery'                     => ['active' => 'bg-pink-500 text-white border-pink-500', 'inactive' => 'bg-white text-pink-700 border-pink-200 hover:bg-pink-50'],
                    'delivered'                            => ['active' => 'bg-emerald-500 text-white border-emerald-500', 'inactive' => 'bg-white text-emerald-700 border-emerald-200 hover:bg-emerald-50'],
                    'delivery_failed'                      => ['active' => 'bg-red-500 text-white border-red-500', 'inactive' => 'bg-white text-red-700 border-red-200 hover:bg-red-50'],
                    'shipment_delayed'                     => ['active' => 'bg-orange-500 text-white border-orange-500', 'inactive' => 'bg-white text-orange-700 border-orange-200 hover:bg-orange-50'],
                    'shipment_returned'                    => ['active' => 'bg-rose-500 text-white border-rose-500', 'inactive' => 'bg-white text-rose-700 border-rose-200 hover:bg-rose-50'],
                    'shipment_canceled'                    => ['active' => 'bg-slate-500 text-white border-slate-500', 'inactive' => 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'],
                    'order_completed'                      => ['active' => 'bg-green-500 text-white border-green-500', 'inactive' => 'bg-white text-green-700 border-green-200 hover:bg-green-50'],
                    'waiting_for_refund'                   => ['active' => 'bg-amber-600 text-white border-amber-600', 'inactive' => 'bg-white text-amber-800 border-amber-200 hover:bg-amber-50'],
                    'refund_approved'                      => ['active' => 'bg-lime-500 text-white border-lime-500', 'inactive' => 'bg-white text-lime-700 border-lime-200 hover:bg-lime-50'],
                    'refunded'                             => ['active' => 'bg-teal-600 text-white border-teal-600', 'inactive' => 'bg-white text-teal-800 border-teal-200 hover:bg-teal-50'],
                    'refund_rejected'                      => ['active' => 'bg-red-600 text-white border-red-600', 'inactive' => 'bg-white text-red-800 border-red-200 hover:bg-red-50'],
                ];
                $statusLabels = [
                    'pending_payment'                      => 'Pending Payment',
                    'paid'                                 => 'Paid',
                    'shipment_preparing'                   => 'Shipment Preparing',
                    'in_transit_china'                     => 'In Transit (CN)',
                    'arrival_uae'                          => 'Arrival UAE',
                    'customs_clearance_uae'                => 'Customs UAE',
                    'in_transit_uae'                       => 'In Transit (UAE)',
                    'arrival_destination_country'          => 'Arrival Dest.',
                    'customs_clearance_destination_country' => 'Customs Dest.',
                    'out_for_delivery'                     => 'Out for Delivery',
                    'delivered'                            => 'Delivered',
                    'delivery_failed'                      => 'Delivery Failed',
                    'shipment_delayed'                     => 'Delayed',
                    'shipment_returned'                    => 'Returned',
                    'shipment_canceled'                    => 'Canceled',
                    'order_completed'                      => 'Completed',
                    'waiting_for_refund'                   => 'Waiting Refund',
                    'refund_approved'                      => 'Refund Approved',
                    'refunded'                             => 'Refunded',
                    'refund_rejected'                      => 'Refund Rejected',
                ];
            @endphp
            <div class="flex flex-wrap gap-1.5">
                @foreach (\App\Models\SourcingOrder::STATUSES as $status)
                    @php
                        $isSlaNavLocked = ($slaNavigationLocked ?? false) && ! auth()->user()?->isSuperAdmin();
                        $slaOrderLockStatuses = \Illuminate\Support\Facades\View::shared('slaLockedOrderStatuses', []);
                        if ($isSlaNavLocked && ! empty($slaOrderLockStatuses) && ! in_array($status, $slaOrderLockStatuses, true)) continue;
                        $count = $statusCounts[$status] ?? 0;
                        $isActive = $activeStatus === $status;
                        if ($count === 0 && !$isActive) continue;
                        $style = $isActive ? $statusColors[$status]['active'] : ($statusColors[$status]['inactive'] ?? 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50');
                        $params = array_merge(request()->except('status', 'page'), ['status' => $status]);
                        $url = $isActive ? route('admin.sourcing-orders.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) : route('admin.sourcing-orders.index', $params);
                    @endphp
                    <a href="{{ $url }}"
                       class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold rounded-full border transition-all {{ $style }}">
                        {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                        <span class="inline-flex items-center justify-center min-w-[16px] h-[16px] px-1 text-[9px] font-bold rounded-full {{ $isActive ? 'bg-white/25 text-white' : 'bg-slate-100 text-slate-600' }}">
                            {{ $count }}
                        </span>
                    </a>
                @endforeach
            </div>

            <!-- Section 3: Data Table / Cards -->
            <div x-data="{
                    view: (() => { try { return localStorage.getItem('sourcingOrdersView') || 'list'; } catch (e) { return 'list'; } })(),
                    init() { this.$watch('view', (v) => { try { localStorage.setItem('sourcingOrdersView', v); } catch (e) {} }); }
                }" class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Order List') }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="text-xs text-slate-500 hidden sm:block">
                            <span class="font-medium text-slate-900">{{ $sourcingOrders->count() }}</span> {{ __('results') }}
                        </div>
                        <!-- View Toggle: List / Cards -->
                        <div class="inline-flex items-center gap-0.5 bg-slate-100 border border-slate-200 rounded-lg p-0.5">
                            <button type="button" @click="view = 'list'" title="{{ __('List view') }}"
                                :class="view === 'list' ? 'bg-white text-orange-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 border-transparent'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md border transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <span class="hidden sm:inline">{{ __('List') }}</span>
                            </button>
                            <button type="button" @click="view = 'cards'" title="{{ __('Card view') }}"
                                :class="view === 'cards' ? 'bg-white text-orange-600 shadow-sm border-slate-200' : 'text-slate-500 hover:text-slate-700 border-transparent'"
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-md border transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h4a2 2 0 012 2v4a2 2 0 01-2 2h-4a2 2 0 01-2-2v-4z"/></svg>
                                <span class="hidden sm:inline">{{ __('Cards') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if ($sourcingOrders->isEmpty())
                     <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3 border border-slate-100">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-sm font-medium text-slate-900">{{ __('No orders found') }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ __('New orders will appear here.') }}</p>
                    </div>
                @else
                    <div x-show="view === 'list'" x-cloak class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                 <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Order') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Client') }}</th>
                                    <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Manager') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Total Amount') }}</th>
                                    <th scope="col" class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Payment Proof') }}</th>
                                    <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200" x-data="{ expandedId: null }">
                                @foreach ($sourcingOrders as $order)
                                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer"
                                        @click="expandedId = (expandedId === {{ $order->id }} ? null : {{ $order->id }});"
                                        wire:key="order-row-{{ $order->id }}">
                                        <!-- Order Info -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 text-xs font-mono">
                                                    {{ $order->reference_id }}
                                                </div>
                                                @php
                                                    $listPhotoUrl = '';
                                                    if (is_image_file($order->quotation?->real_product_image)) {
                                                        $listPhotoUrl = $order->quotation->real_product_image;
                                                    } elseif (is_image_file($order->quotation?->sourcingRequest?->product_image)) {
                                                        $listPhotoUrl = $order->quotation->sourcingRequest->product_image;
                                                    } else {
                                                        $listMedia = $order->media->firstWhere('file_type', 'image');
                                                        if (!$listMedia || !is_image_file($listMedia->file_path)) {
                                                            $listMedia = $order->quotation?->media?->firstWhere('file_type', 'image');
                                                            if (!$listMedia || !is_image_file($listMedia->file_path)) {
                                                                $listMedia = null;
                                                            }
                                                        }
                                                        $listPhotoUrl = is_image_file($listMedia?->file_path) ? $listMedia->file_path : '';
                                                    }
                                                @endphp
                                                @if($listPhotoUrl)
                                                    <img src="{{ media_url($listPhotoUrl) }}" class="h-10 w-10 rounded object-cover border border-slate-200 shrink-0" alt="{{ __('Product') }}">
                                                @else
                                                    <div class="h-10 w-10 rounded bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    </div>
                                                @endif
                                                <div class="min-w-0 max-w-[180px]">
                                                    <div class="text-sm font-medium text-slate-900 truncate" title="{{ $order->quotation->sourcingRequest->product_name }}">
                                                        {{ $order->quotation->sourcingRequest->product_name }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-400 font-mono">
                                                        {{ __('Request') }} #{{ $order->sourcing_request_id ?? $order->quotation->sourcing_request_id }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-400">
                                                        {{ $order->created_at->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Client -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">
                                                    {{ substr($order->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-sm text-slate-700">{{ $order->user->name }}</span>
                                                    @if($order->user->phone)
                                                        <span class="text-[10px] text-slate-400">{{ $order->user->phone }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Responsable -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            @if($order->assignedAdmin)
                                                <div class="flex items-center gap-2">
                                                    <div class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 border border-white shadow-sm" title="{{ $order->assignedAdmin->name }}">
                                                        {{ substr($order->assignedAdmin->name, 0, 1) }}
                                                    </div>
                                                    <span class="text-xs text-slate-600 truncate max-w-[100px]">{{ $order->assignedAdmin->name }}</span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                                    {{ __('Unassigned') }}
                                                </span>
                                            @endif
                                        </td>

                                        <!-- Amount -->
                                        <td class="px-6 py-3 whitespace-nowrap text-right">
                                            <div class="text-sm font-bold text-slate-900">{{ number_format($order->total_amount, 2) }}</div>
                                            <div class="text-[10px] text-slate-500">{{ $order->quotation->currency }}</div>
                                        </td>

                                        <!-- Status Selector -->
                                        <td class="px-6 py-3 whitespace-nowrap" @click.stop>
                                            <form action="{{ route('admin.sourcing-orders.update-status', $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                @php
                                                    $statusColors = [
                                                        'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        'paid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'shipment_preparing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                                        'processing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                                        'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                        'delivered' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                                    ];
                                                    $currentClass = $statusColors[$order->client_status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                                @endphp
                                                 <select name="status" 
                                                    onchange="if(confirm('{{ __('Change status of order #:id?', ['id' => $order->display_id]) }}')) { this.form.submit(); } else { location.reload(); }" 
                                                    class="block w-full text-center px-2 py-1 text-[11px] font-bold rounded border uppercase tracking-wide cursor-pointer focus:ring-1 focus:ring-offset-1 focus:ring-slate-400 {{ $currentClass }}">
                                                    @foreach (App\Models\SourcingOrder::STATUSES as $status)
                                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }} class="bg-white text-slate-700">
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }} 
                                                            @if(in_array($status, ['in_transit_china', 'arrival_uae'])) ({{ __('Masked') }}) @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>

                                        <!-- Payment Proof -->
                                        <td class="px-6 py-3 whitespace-nowrap text-center" @click.stop>
                                            @if ($order->proof_of_payment_path)
                                                <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $order) }}" 
                                                    class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    {{ __('Download') }}
                                                </a>
                                            @else
                                                <span class="text-[10px] text-slate-400 italic">{{ __('Not received') }}</span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-3 whitespace-nowrap text-right" @click.stop>
                                            <div class="flex items-center justify-end gap-1">
                                                <!-- View -->
                                                <a href="{{ route('admin.sourcing-orders.show', [$order, 'page' => request('page')]) }}" 
                                                    class="p-1.5 text-slate-500 hover:text-orange-600 hover:bg-orange-50 rounded transition-colors" title="{{ __('View Details') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                
                                                <!-- Sync -->
                                                <button type="button" 
                                                    onclick="syncToGoogleSheet({{ $order->display_id }})"
                                                    class="sync-btn p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors group"
                                                    title="{{ __('Sync to Google Sheet') }}">
                                                    <svg class="w-4 h-4 group-[.loading]:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                </button>

                                                <!-- Shipping Label -->
                                                @if($order->proof_of_payment_path || in_array($order->status, ['paid', 'shipment_preparing', 'in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'delivered', 'order_completed']))
                                                    <a href="{{ route('admin.sourcing-orders.shipping-label', $order) }}" target="_blank"
                                                       class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition-colors" 
                                                       title="{{ __('Shipping Label') }}">
                                                        <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </a>
                                                @endif
 
                                                 <!-- Print -->
                                                <button type="button" onclick="window.print()" 
                                                    class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded transition-colors" title="{{ __('Print') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </button>

                                                 <!-- Delete -->
                                                 <form action="{{ route('admin.sourcing-orders.destroy', $order) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="{{ __('Delete') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Expandable detail row (photos + tracking) --}}
                                    <tr x-cloak x-show="expandedId === {{ $order->id }}" class="bg-slate-50 border-x border-slate-200 shadow-inner" wire:key="order-expanded-{{ $order->id }}">
                                        <td colspan="7" class="px-6 py-4 border-0">
                                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                                <!-- Photos -->
                                                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
                                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">{{ __('Colis photos') }}</p>
                                                    <div class="grid grid-cols-2 gap-3">
                                                        @if($order->package_label_photo_path)
                                                            <a href="{{ media_url($order->package_label_photo_path) }}" target="_blank" class="group">
                                                                <img src="{{ media_url($order->package_label_photo_path) }}" alt="{{ __('Package label photo') }}" class="w-full h-24 object-cover rounded border border-slate-200 group-hover:ring-2 group-hover:ring-orange-400 transition-all">
                                                                <p class="text-[10px] text-slate-400 mt-1">{{ __('Label') }}</p>
                                                            </a>
                                                        @else
                                                            <div class="h-24 flex items-center justify-center bg-slate-50 border border-dashed border-slate-200 rounded">
                                                                <span class="text-[10px] text-slate-400 italic">{{ __('No label photo') }}</span>
                                                            </div>
                                                        @endif
                                                        @if($order->parcel_photo_path)
                                                            <a href="{{ media_url($order->parcel_photo_path) }}" target="_blank" class="group">
                                                                <img src="{{ media_url($order->parcel_photo_path) }}" alt="{{ __('Parcel photo') }}" class="w-full h-24 object-cover rounded border border-slate-200 group-hover:ring-2 group-hover:ring-orange-400 transition-all">
                                                                <p class="text-[10px] text-slate-400 mt-1">{{ __('Colis') }}</p>
                                                            </a>
                                                        @else
                                                            <div class="h-24 flex items-center justify-center bg-slate-50 border border-dashed border-slate-200 rounded">
                                                                <span class="text-[10px] text-slate-400 italic">{{ __('No colis photo') }}</span>
                                                            </div>
                                                        @endif
                                                        @if($order->proof_of_payment_path)
                                                            <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $order) }}" target="_blank" class="group">
                                                                <img src="{{ media_url($order->proof_of_payment_path) }}" alt="{{ __('Payment proof') }}" class="w-full h-24 object-cover rounded border border-slate-200 group-hover:ring-2 group-hover:ring-orange-400 transition-all">
                                                                <p class="text-[10px] text-slate-400 mt-1">{{ __('Proof') }}</p>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Tracking -->
                                                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
                                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">{{ __('Tracking') }}</p>
                                                    <dl class="space-y-2 text-xs">
                                                        <div class="flex justify-between gap-2">
                                                            <dt class="text-slate-500 font-semibold">{{ __('FSB number') }}</dt>
                                                            <dd class="text-slate-900 font-mono font-bold">{{ $order->fsb_tracking_number }}</dd>
                                                        </div>
                                                        <div class="flex justify-between gap-2">
                                                            <dt class="text-slate-500 font-semibold">{{ __('China tracking') }}</dt>
                                                            <dd class="text-slate-900 font-mono font-semibold">{{ $order->china_tracking_number ?: __('—') }}</dd>
                                                        </div>
                                                        <div class="flex justify-between gap-2">
                                                            <dt class="text-slate-500 font-semibold">{{ __('Local / real tracking') }}</dt>
                                                            <dd class="text-slate-900 font-mono font-semibold">{{ $order->tracking_number ?: __('—') }}</dd>
                                                        </div>
                                                        <div class="flex justify-between gap-2">
                                                            <dt class="text-slate-500 font-semibold">{{ __('Carrier') }}</dt>
                                                            <dd class="text-slate-900 font-semibold">{{ $order->tracking_carrier ?: $order->shippingCompany?->name ?: __('—') }}</dd>
                                                        </div>
                                                        @if($order->hasMultipleDestinations())
                                                            @foreach($order->destinationShipments as $ds)
                                                                <div class="flex justify-between gap-2 border-t border-slate-100 pt-2">
                                                                    <dt class="text-slate-500 font-semibold">{{ __('Destination') }}</dt>
                                                                    <dd class="text-slate-900 font-mono font-semibold text-right">{{ $ds->tracking_number ?: __('—') }}</dd>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </dl>
                                                </div>

                                                <!-- Status / Link -->
                                                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
                                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">{{ __('Status') }}</p>
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold"
                                                          style="background-color: #EF772222; color: #EF7722;">
                                                        {{ str_replace('_', ' ', $order->status) }}
                                                    </span>
                                                    @if($order->client_status !== $order->status)
                                                        <p class="text-[10px] text-slate-400 mt-2">{{ __('Client sees') }}: <span class="font-semibold text-slate-500">{{ str_replace('_', ' ', $order->client_status) }}</span></p>
                                                    @endif
                                                    @php
                                                        $hasPhoto = $order->package_label_photo_path || $order->parcel_photo_path;
                                                        $hasTracking = $order->china_tracking_number || $order->tracking_number;
                                                    @endphp
                                                    @if($order->status === 'in_transit_china' && (!$hasPhoto || !$hasTracking))
                                                        <p class="mt-2 text-[10px] font-bold text-orange-600 flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            {{ __('In-transit evidence incomplete') }}
                                                        </p>
                                                    @endif
                                                    <div class="mt-4 pt-3 border-t border-slate-100 flex justify-end">
                                                        <a href="{{ route('admin.sourcing-orders.show', [$order, 'page' => request('page')]) }}"
                                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-md shadow-sm transition-colors">
                                                            {{ __('Open order') }}
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Cards view -->
                    <div x-show="view === 'cards'" x-cloak class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-4">
                        @foreach ($sourcingOrders as $order)
                            @php
                                $statusColors = [
                                    'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'paid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'shipment_preparing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    'processing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'delivered' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                $currentClass = $statusColors[$order->client_status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <div class="flex flex-col border border-slate-200 rounded-lg bg-white shadow-sm hover:shadow-md hover:border-orange-200 transition-all overflow-hidden">
                                <!-- Product Image -->
                                <div class="relative h-40 w-full flex-shrink-0 bg-slate-100 overflow-hidden">
                                    @php
                                        $cardPhotoUrl = '';
                                        if (is_image_file($order->quotation?->real_product_image)) {
                                            $cardPhotoUrl = $order->quotation->real_product_image;
                                        } elseif (is_image_file($order->quotation?->sourcingRequest?->product_image)) {
                                            $cardPhotoUrl = $order->quotation->sourcingRequest->product_image;
                                        } else {
                                            $cardMedia = $order->media->firstWhere('file_type', 'image');
                                            if (!$cardMedia || !is_image_file($cardMedia->file_path)) {
                                                $cardMedia = $order->quotation?->media?->firstWhere('file_type', 'image');
                                                if (!$cardMedia || !is_image_file($cardMedia->file_path)) {
                                                    $cardMedia = null;
                                                }
                                            }
                                            $cardPhotoUrl = is_image_file($cardMedia?->file_path) ? $cardMedia->file_path : '';
                                        }
                                    @endphp
                                    @if($cardPhotoUrl)
                                        <img src="{{ media_url($cardPhotoUrl) }}" class="h-full w-full object-cover" alt="{{ __('Product') }}">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <!-- Card Header -->
                                <div class="p-4 flex items-start gap-3 border-b border-slate-100">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="text-sm font-mono font-medium text-orange-600">{{ $order->reference_id }}</div>
                                            <div class="text-right shrink-0">
                                                <div class="text-sm font-bold text-slate-900">{{ number_format($order->total_amount, 2) }}</div>
                                                <div class="text-[10px] text-slate-500">{{ $order->quotation->currency }}</div>
                                            </div>
                                        </div>
                                        <div class="text-sm font-medium text-slate-900 truncate mt-1" title="{{ $order->quotation->sourcingRequest->product_name }}">
                                            {{ $order->quotation->sourcingRequest->product_name }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono">
                                            {{ __('Request') }} #{{ $order->sourcing_request_id ?? $order->quotation->sourcing_request_id }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="p-4 space-y-3 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="h-7 w-7 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 shrink-0">
                                                {{ substr($order->user->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm text-slate-700 truncate">{{ $order->user->name }}</div>
                                                @if($order->user->phone)
                                                    <div class="text-[10px] text-slate-400">{{ $order->user->phone }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">{{ __('Manager') }}</span>
                                            @if($order->assignedAdmin)
                                                <span class="text-xs text-slate-600 truncate max-w-[100px] inline-block" title="{{ $order->assignedAdmin->name }}">{{ $order->assignedAdmin->name }}</span>
                                            @else
                                                <span class="text-[10px] text-slate-400 italic">{{ __('Unassigned') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">{{ __('Status') }}</span>
                                        <form action="{{ route('admin.sourcing-orders.update-status', $order) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status"
                                                onchange="if(confirm('{{ __('Change status of order #:id?', ['id' => $order->display_id]) }}')) { this.form.submit(); } else { location.reload(); }"
                                                class="block w-full text-center px-2 py-1 text-[11px] font-bold rounded border uppercase tracking-wide cursor-pointer focus:ring-1 focus:ring-offset-1 focus:ring-slate-400 {{ $currentClass }}">
                                                @foreach (App\Models\SourcingOrder::STATUSES as $status)
                                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }} class="bg-white text-slate-700">
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        @if(in_array($status, ['in_transit_china', 'arrival_uae'])) ({{ __('Masked') }}) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 text-xs">
                                        <div>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">{{ __('Date') }}</span>
                                            <div class="text-sm text-slate-600">{{ $order->created_at->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">{{ __('Payment Proof') }}</span>
                                            @if ($order->proof_of_payment_path)
                                                <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $order) }}" class="text-[11px] font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                    {{ __('Download') }}
                                                </a>
                                            @else
                                                <span class="text-[10px] text-slate-400 italic">{{ __('Not received') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Footer -->
                                <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.sourcing-orders.show', [$order, 'page' => request('page')]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ __('View') }}
                                    </a>
                                    <button type="button"
                                        onclick="syncToGoogleSheet({{ $order->display_id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-emerald-700 bg-white border border-emerald-200 hover:bg-emerald-50 transition-colors"
                                        title="{{ __('Sync to Google Sheet') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        {{ __('Sync') }}
                                    </button>
                                    @if($order->proof_of_payment_path || in_array($order->status, ['paid', 'shipment_preparing', 'in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'delivered', 'order_completed']))
                                        <a href="{{ route('admin.sourcing-orders.shipping-label', $order) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-indigo-700 bg-white border border-indigo-200 hover:bg-indigo-50 transition-colors"
                                           title="{{ __('Shipping Label') }}">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ __('Label') }}
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.sourcing-orders.destroy', $order) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold text-red-600 bg-white border border-red-200 hover:bg-red-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                        {{ $sourcingOrders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function syncToGoogleSheet(orderId) {
            const button = event.currentTarget;
            const svg = button.querySelector('svg');
            
            // UI Loading state
            button.disabled = true;
            button.classList.add('loading', 'opacity-50', 'cursor-not-allowed');
            
            fetch(`/admin/sourcing-orders/${orderId}/sync-to-sheet`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    // Visual feedback success
                    button.classList.add('text-emerald-600', 'bg-emerald-50');
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Sync error:', error);
                showToast("{{ __('Sync error') }}", 'error');
            })
            .finally(() => {
                // Reset state
                button.disabled = false;
                button.classList.remove('loading', 'opacity-50', 'cursor-not-allowed');
            });
        }
        
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            // Enterprise style toast (Slate/White) instead of bright colors
            const borderClass = type === 'success' ? 'border-emerald-500' : 'border-red-500';
            const iconColor = type === 'success' ? 'text-emerald-500' : 'text-red-500';
            
            toast.className = `fixed top-20 right-4 bg-white border-l-4 ${borderClass} px-6 py-4 rounded shadow-lg z-50 flex items-center gap-3 animate-slide-in transform transition-all duration-300 translate-y-0 opacity-100 max-w-sm`;
            toast.innerHTML = `
                <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
                </svg>
                <div>
                    <p class="font-medium text-slate-800 text-sm">${message}</p>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto dismiss
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>
    <style>
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out forwards;
        }
    </style>
    @endpush
</x-app-layout>
