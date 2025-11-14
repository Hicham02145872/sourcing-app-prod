<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Sourcing Requests'), 'url' => route('client.sourcing-requests.index')],
    ['label' => __('Details')]
]">
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 dark:bg-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Request Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                <span class="font-mono font-semibold">{{ $sourcingRequest->product_name }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
                @if ($sourcingRequest->status === 'pending')
                    <a href="{{ route('client.sourcing-requests.edit', $sourcingRequest) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-700 border-2 border-blue-200 dark:border-blue-700 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ __('Edit Request') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Status Banner --}}
            @php
                $statusMap = [
                    'pending' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Under Review')],
                    'quoted' => ['color' => 'red', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'title' => __('Action Required')],
                    'in_review' => ['color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('In Review')],
                    'accepted' => ['color' => 'purple', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Accepted')],
                    'completed' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Completed')]
                ];
                $currentStatus = $sourcingRequest->status;
                $statusData = $statusMap[$currentStatus] ?? $statusMap['pending'];
            @endphp
            
            <div class="mb-6 p-6 rounded-xl shadow-sm border bg-{{ $statusData['color'] }}-50 dark:bg-{{ $statusData['color'] }}-900/20 border-{{ $statusData['color'] }}-200 dark:border-{{ $statusData['color'] }}-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-{{ $statusData['color'] }}-100 dark:bg-{{ $statusData['color'] }}-900 border border-{{ $statusData['color'] }}-300 dark:border-{{ $statusData['color'] }}-500">
                            <svg class="w-6 h-6 text-{{ $statusData['color'] }}-600 dark:text-{{ $statusData['color'] }}-400" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-{{ $statusData['color'] }}-700 dark:text-{{ $statusData['color'] }}-300 uppercase tracking-wider mb-1">{{ $statusData['title'] ?? __('Current Status') }}</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ __(ucfirst(str_replace('_', ' ', $currentStatus))) }}</p>
                        </div>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Last activity') }}</p>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Product Information --}}
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8-4m8 4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product & Logistics Information') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Complete product details and shipping information') }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    {{ $sourcingRequest->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Product Image/Details --}}
                                <div class="space-y-4">
                                    <div class="relative group">
                                        <div class="w-full aspect-square bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl overflow-hidden">
                                            @if ($sourcingRequest->product_image)
                                                <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                                     alt="{{ $sourcingRequest->product_name }}" 
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-20 h-20 text-slate-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <h4 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">{{ $sourcingRequest->product_name }}</h4>
                                        @if ($sourcingRequest->product_url)
                                            <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                               class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                {{ __('View Source Product') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Logistics and Notes --}}
                                <div class="space-y-4">
                                    <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{{ __('Shipping Method') }}</p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-slate-900 dark:text-white">{{ ucfirst($sourcingRequest->shipping_method ?? __('Not specified')) }}</p>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                                    @if($sourcingRequest->shipping_method === 'air')
                                                        {{ __('Faster delivery, suitable for small volumes') }}
                                                    @else
                                                        {{ __('Cost-effective, suitable for large volumes') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{{ __('Sourcing Location') }}</p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-slate-900 dark:text-white capitalize">{{ $sourcingRequest->sourcing_location ?? __('Not specified') }}</p>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Primary sourcing market') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{{ __('Additional Notes') }}</p>
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed flex-1">
                                                {{ $sourcingRequest->note ?? __('No additional notes provided by the client.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Destinations --}}
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Shipping Destinations') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Countries and quantities for delivery') }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    {{ $sourcingRequest->destinations->count() }} {{ trans_choice('location', $sourcingRequest->destinations->count()) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach ($sourcingRequest->destinations as $destination)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 gap-4">
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="flex-shrink-0">
                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-2xl rounded-sm border border-slate-300"></span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-slate-900 dark:text-white">{{ $destination->country->name }}</p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                    </svg>
                                                    <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">{{ $destination->service->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="text-sm font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                                {{ __('Quantity') }}
                                            </div>
                                            <span class="px-3 py-1 text-base font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full whitespace-nowrap">
                                                {{ number_format($destination->quantity) }} {{ __('units') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Quotation & Actions --}}
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden" x-data="{ selectedMethod: null }">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Official Price Quotation') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Detailed pricing and cost breakdown') }}</p>
                                    </div>
                                </div>
                                @if ($sourcingRequest->quotation)
                                    <span class="px-3 py-1 text-sm font-medium rounded-full border
                                        @if($sourcingRequest->quotation->status === 'approved') bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-700
                                        @elseif($sourcingRequest->quotation->status === 'pending') bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-700
                                        @else bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600
                                        @endif">
                                        {{ ucfirst($sourcingRequest->quotation->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            @if ($sourcingRequest->quotation)
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div class="p-4 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg">
                                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Price') }}</p>
                                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_price, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="p-4 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg">
                                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Commission') }}</p>
                                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->commission_service, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="p-4 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg">
                                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Weight') }}</p>
                                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_weight, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('g') }}</span></p>
                                        </div>
                                        <div class="p-4 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg">
                                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Local Delivery') }}</p>
                                            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->delivery_cost_china, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 bg-gradient-to-r from-blue-500/10 to-blue-600/10 border-2 border-blue-400 dark:border-blue-600 rounded-xl">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wider mb-1">{{ __('Grand Total Quote') }}</p>
                                                <p class="text-3xl sm:text-4xl font-extrabold text-blue-800 dark:text-blue-400">{{ number_format($sourcingRequest->quotation->amount, 2) }} <span class="text-xl font-bold">{{ $sourcingRequest->quotation->currency }}</span></p>
                                            </div>
                                            <svg class="w-14 h-14 text-blue-500 dark:text-blue-700 opacity-50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>

                                    @if($sourcingRequest->quotation->order === null)
                                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                                            <form action="{{ route('client.quotations.accept', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ __('Accept Quotation & Create Order') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('client.quotations.reject', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full py-3 px-4 bg-white dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-700 dark:text-slate-300 hover:text-red-600 border border-slate-300 dark:border-slate-600 hover:border-red-300 text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ __('Reject Quotation') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @else
                                @if($sourcingRequest->status === 'pending' || $sourcingRequest->status === 'in_review')
                                    <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
                                        <form action="{{ route('client.sourcing-requests.cancel', $sourcingRequest) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-3 px-4 bg-white dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-700 dark:text-slate-300 hover:text-red-600 border border-slate-300 dark:border-slate-600 hover:border-red-300 text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Cancel Request') }}
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <div class="text-center py-12 bg-slate-50 dark:bg-slate-700 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-600">
                                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-base font-bold text-slate-900 dark:text-white mb-1">{{ __('Quotation Pending') }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __("We are currently analyzing your request and preparing the official quote.") }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Methods & Proof --}}
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden" x-data="{ selectedMethod: null }">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Payment & Order Status') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Available payment methods and transaction status') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">{{ __('Available Payment Methods:') }}</p>
                                @foreach($paymentMethods as $paymentMethod)
                                    <button @click="selectedMethod = selectedMethod === '{{ $paymentMethod->name }}' ? null : '{{ $paymentMethod->name }}'" 
                                            class="w-full p-4 text-left border rounded-lg transition-all duration-200"
                                            :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'border-blue-400 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20 shadow-sm' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-slate-300 dark:hover:border-slate-600 hover:shadow-sm'">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                @if($paymentMethod->logo_path)
                                                    <div class="w-10 h-10 rounded-full border border-slate-200 bg-white p-1.5 shadow-sm flex items-center justify-center">
                                                        <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-contain">
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 bg-slate-50 shadow-sm">
                                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="text-base font-semibold text-slate-900 dark:text-white">{{ $paymentMethod->name }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500 transition-transform duration-200" :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'rotate-180 text-blue-600 dark:text-blue-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                        <div x-show="selectedMethod === '{{ $paymentMethod->name }}'" 
                                             x-transition.duration.300ms
                                             class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                            <div class="space-y-2.5">
                                                @foreach($paymentMethod->details as $key => $value)
                                                    <div class="flex justify-between items-center p-2.5 bg-white dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-700">
                                                        <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">{{ $key }}:</span>
                                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            {{-- Payment Proof Upload Section --}}
                            @if($sourcingRequest->quotation && $sourcingRequest->quotation->order && $sourcingRequest->quotation->order->status === 'pending_payment')
                                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                                    <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        {{ __('Submit Proof of Payment') }}
                                    </h4>
                                    <form action="{{ route('client.sourcing-orders.upload-proof-of-payment', $sourcingRequest->quotation->order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <div class="relative">
                                                <input type="file" 
                                                       name="proof_of_payment" 
                                                       class="block w-full text-sm text-slate-600 dark:text-slate-400
                                                              file:mr-4 file:py-2.5 file:px-4
                                                              file:rounded-lg file:border-0
                                                              file:text-sm file:font-semibold
                                                              file:bg-blue-100 dark:file:bg-blue-900/50 file:text-blue-700 dark:file:text-blue-300
                                                              hover:file:bg-blue-200 dark:hover:file:bg-blue-900/70
                                                              file:cursor-pointer file:transition-colors
                                                              border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl
                                                              hover:border-blue-400 dark:hover:border-blue-500 transition-colors
                                                              cursor-pointer p-2"
                                                       required/>
                                            </div>
                                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('Accepted formats: PDF, JPG, PNG (Max: 5MB)') }}</p>
                                        </div>
                                        <button type="submit" 
                                                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Submit Payment Proof') }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($sourcingRequest->quotation && $sourcingRequest->quotation->order && $sourcingRequest->quotation->order->proof_of_payment_path)
                                <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                                    <div class="text-center p-6 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border-2 border-emerald-300 dark:border-emerald-700">
                                        <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ __('Payment Proof Submitted') }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ __('Your payment proof is under review. We will proceed with the order once verified.') }}</p>
                                        <a href="{{ asset('storage/' . $sourcingRequest->quotation->order->proof_of_payment_path) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-300 bg-white dark:bg-slate-700 border-2 border-emerald-300 dark:border-emerald-700 rounded-lg hover:bg-emerald-50 dark:hover:bg-slate-600 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('View Document') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Request Summary') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Key information at a glance') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            <div class="space-y-3">
                                @php
                                    $summaryDetails = [
                                        __('Request ID') => ['value' => "#{$sourcingRequest->id}", 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                                        __('Category') => ['value' => $sourcingRequest->category->name, 'icon' => 'M7 7h.01M7 3h.01M3 7h.01M3 3h.01M17 7h.01M17 3h.01M21 7h.01M21 3h.01M7 17h.01M7 21h.01M3 17h.01M3 21h.01M17 17h.01M17 21h.01M21 17h.01M21 21h.01'],
                                        __('Destinations') => ['value' => $sourcingRequest->destinations->count(), 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945'],
                                        __('Total Quantity') => ['value' => number_format($sourcingRequest->destinations->sum('quantity')) . ' ' . __('units'), 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                    ];
                                @endphp
                                @foreach($summaryDetails as $label => $data)
                                    <div class="flex items-center p-3 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                        <svg class="w-4 h-4 mr-3 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $data['icon'] }}"/>
                                        </svg>
                                        <span class="text-sm font-medium text-slate-600 dark:text-slate-400 flex-1">{{ $label }}</span>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $data['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Status') }}</span>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full
                                    bg-{{ $statusData['color'] }}-100 text-{{ $statusData['color'] }}-800 dark:bg-{{ $statusData['color'] }}-900/30 dark:text-{{ $statusData['color'] }}-300">
                                    {{ __(ucfirst(str_replace('_', ' ', $currentStatus))) }}
                                </span>
                            </div>

                            @if($sourcingRequest->quotation)
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                                <div class="p-5 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl border border-blue-700 shadow-sm">
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-blue-200 uppercase tracking-wide mb-1">{{ __('Order Grand Total') }}</p>
                                        <p class="text-3xl sm:text-4xl font-extrabold text-blue-800 dark:text-blue-400">{{ number_format($sourcingRequest->quotation->amount, 2) }}</p>
                                        <p class="text-base font-semibold text-blue-300">{{ $sourcingRequest->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                                <div class="text-center text-xs text-slate-500 dark:text-slate-400">
                                    <p class="mb-1">{{ __('Request Created') }}: {{ $sourcingRequest->created_at->format('M d, Y') }}</p>
                                    <p>{{ __('Last Update') }}: {{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>