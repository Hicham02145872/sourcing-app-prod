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

                            <!-- Status Filter -->
                            <div class="w-full md:w-48">
                                <select name="status" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
                                    <option value="all">{{ __('All Statuses') }}</option>
                                    @foreach (\App\Models\SourcingOrder::STATUSES as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
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

            <!-- Section 3: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('Order List') }}</h3>
                    <div class="text-xs text-slate-500">
                        <span class="font-medium text-slate-900">{{ $sourcingOrders->count() }}</span> {{ __('results') }}
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
                    <div class="overflow-x-auto">
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
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach ($sourcingOrders as $order)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <!-- Order Info -->
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 text-xs font-mono">
                                                    {{ $order->id }}
                                                </div>
                                                @if($order->quotation->sourcingRequest->product_image)
                                                    <img src="{{ Storage::url($order->quotation->sourcingRequest->product_image) }}" class="h-10 w-10 rounded object-cover border border-slate-200 shrink-0" alt="{{ __('Product') }}">
                                                @else
                                                    <div class="h-10 w-10 rounded bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                                                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    </div>
                                                @endif
                                                <div class="min-w-0 max-w-[180px]">
                                                    <div class="text-sm font-medium text-slate-900 truncate" title="{{ $order->quotation->sourcingRequest->product_name }}">
                                                        {{ $order->quotation->sourcingRequest->product_name }}
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
                                        <td class="px-6 py-3 whitespace-nowrap">
                                            <form action="{{ route('admin.sourcing-orders.update-status', $order) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                @php
                                                    $statusColors = [
                                                        'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                        'paid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'processing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                                        'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                        'delivered' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                                    ];
                                                    $currentClass = $statusColors[$order->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                                @endphp
                                                 <select name="status" 
                                                    onchange="if(confirm('{{ __('Change status of order #:id?', ['id' => $order->id]) }}')) { this.form.submit(); } else { location.reload(); }" 
                                                    class="block w-full text-center px-2 py-1 text-[11px] font-bold rounded border uppercase tracking-wide cursor-pointer focus:ring-1 focus:ring-offset-1 focus:ring-slate-400 {{ $currentClass }}">
                                                    @foreach (App\Models\SourcingOrder::STATUSES as $status)
                                                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }} class="bg-white text-slate-700">
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>

                                        <!-- Payment Proof -->
                                        <td class="px-6 py-3 whitespace-nowrap text-center">
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
                                        <td class="px-6 py-3 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <!-- View -->
                                                <a href="{{ route('admin.sourcing-orders.show', $order) }}" 
                                                    class="p-1.5 text-slate-500 hover:text-orange-600 hover:bg-orange-50 rounded transition-colors" title="{{ __('View Details') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                
                                                <!-- Sync -->
                                                <button type="button" 
                                                    onclick="syncToGoogleSheet({{ $order->id }})"
                                                    class="sync-btn p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded transition-colors group"
                                                    title="{{ __('Sync to Google Sheet') }}">
                                                    <svg class="w-4 h-4 group-[.loading]:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                </button>
 
                                                 <!-- Print -->
                                                <button type="button" onclick="window.print()" 
                                                    class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded transition-colors" title="{{ __('Print') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
