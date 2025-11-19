<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Sourcing Orders') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Track and manage all sourcing orders') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-start">
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Export Report') }}
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('Refresh') }}
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-[#fffff] dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                {{-- Total Orders --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total Orders') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $sourcingOrders->count() }}</p>
                            <p class="text-xs text-[#0BA6DF] dark:text-[#0BA6DF] font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                {{ __('All time') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pending Payment --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Pending Payment') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $sourcingOrders->where('status', 'pending_payment')->count() }}</p>
                            <p class="text-xs text-[#FAA533] dark:text-[#FAA533] font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Awaiting payment') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#FAA533] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Processing --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Processing') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $sourcingOrders->where('status', 'processing')->count() }}</p>
                            <p class="text-xs text-[#0BA6DF] dark:text-[#0BA6DF] font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ __('Being processed') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#0BA6DF] dark:text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Completed') }}</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $sourcingOrders->where('status', 'completed')->count() }}</p>
                            <p class="text-xs text-[#EF7722] dark:text-[#EF7722] font-medium mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Successfully delivered') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Sourcing Orders') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-[#EF7722]">{{ $sourcingOrders->count() }}</span> {{ Str::plural('order', $sourcingOrders->count()) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                    <form action="{{ route('admin.sourcing-orders.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:justify-between sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                            {{-- Search --}}
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search"
                                       id="searchInput"
                                       placeholder="{{ __('Search orders...') }}" 
                                       value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-4 py-2 border border-[#EBEBEB] dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm font-medium">
                            </div>

                            {{-- Status Filter --}}
                            <div class="w-full sm:w-48">
                                <select name="status" id="statusFilter" class="block w-full px-4 py-2 border border-[#EBEBEB] dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm font-medium">
                                    <option value="all">{{ __('All Status') }}</option>
                                    @foreach (\App\Models\SourcingOrder::STATUSES as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ __(ucfirst(str_replace('_', ' ', $status))) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                {{ __('Apply Filters') }}
                            </button>
                            <a href="{{ route('admin.sourcing-orders.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Content --}}
                @if ($sourcingOrders->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Sourcing Orders') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('There are no sourcing orders available at the moment. New orders will appear here once clients place them.') }}</p>
                    </div>
                @else
                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                            <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Order Details') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Client') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Client Phone') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Total Amount') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Payment Proof') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                @foreach ($sourcingOrders as $order)
                                    <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150">
                                        {{-- Order Details --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg border border-[#EBEBEB] dark:border-slate-600 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-semibold text-slate-900 dark:text-white max-w-xs truncate">
                                                        {{ $order->quotation->sourcingRequest->product_name }}
                                                    </div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Client --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                                        {{ substr($order->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $order->user->name }}</div>
                                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $order->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Client Phone --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-900 dark:text-white">{{ $order->user->phone ?? 'N/A' }}</div>
                                        </td>

                                        {{-- Total Amount --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                {{ number_format($order->total_amount, 2) }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $order->quotation->currency }}</div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('admin.sourcing-orders.update-status', $order) }}" method="POST" class="status-form">
                                                @csrf
                                                @method('PATCH')
                                                @php
                                                    $statusColors = [
                                                        'pending_payment' => 'bg-[#FAA533]/10 dark:bg-[#FAA533]/20 text-[#FAA533] dark:text-[#FAA533] border-[#FAA533]/20 dark:border-[#FAA533]/30',
                                                        'paid' => 'bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 text-[#0BA6DF] dark:text-[#0BA6DF] border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30',
                                                        'processing' => 'bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 text-[#0BA6DF] dark:text-[#0BA6DF] border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30',
                                                        'shipped' => 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] border-[#EF7722]/20 dark:border-[#EF7722]/30',
                                                        'delivered' => 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] border-[#EF7722]/20 dark:border-[#EF7722]/30',
                                                        'completed' => 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] border-[#EF7722]/20 dark:border-[#EF7722]/30',
                                                        'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border-red-300 dark:border-red-700',
                                                    ];
                                                    $currentStatus = $order->status;
                                                @endphp
                                                <select name="status" 
                                                    onchange="if(confirm('Are you sure you want to change the order status?')) { this.form.submit(); } else { this.selectedIndex = Array.from(this.options).findIndex(option => option.value === '{{ $currentStatus }}'); }" 
                                                    class="block w-full px-3 py-1.5 text-xs font-bold border rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent transition-colors shadow-sm {{ $statusColors[$order->status] ?? 'bg-[#EBEBEB] dark:bg-slate-700 text-slate-700 dark:text-slate-300 border-[#EBEBEB] dark:border-slate-600' }}">
                                                    @foreach (App\Models\SourcingOrder::STATUSES as $status)
                                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>

                                        {{-- Payment Proof --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($order->proof_of_payment_path)
                                                <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $order) }}" 
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0BA6DF] dark:text-[#0BA6DF] hover:text-[#0BA6DF] dark:hover:text-[#0BA6DF] transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ __('Download') }}
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 dark:text-slate-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ __('Not uploaded') }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.sourcing-orders.show', $order) }}" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                                                    {{ __('View') }}
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                                <button type="button" onclick="window.print()" 
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 border border-[#EBEBEB] dark:border-slate-600 text-slate-700 dark:text-slate-300 text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                    </svg>
                                                    {{ __('Print') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($sourcingOrders->hasPages())
                        <div class="px-6 py-4 border-t border-[#EBEBEB] dark:border-slate-700 bg-[#EBEBEB] dark:bg-slate-900/50">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ __('Showing') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $sourcingOrders->firstItem() }}</span>
                                    {{ __('to') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $sourcingOrders->lastItem() }}</span>
                                    {{ __('of') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $sourcingOrders->total() }}</span>
                                    {{ __('results') }}
                                </div>
                                <div class="flex gap-1">
                                    {{ $sourcingOrders->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Enterprise table styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Line clamp utilities */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #EF7722;
            border-radius: 4px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #FAA533;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #EF7722;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>