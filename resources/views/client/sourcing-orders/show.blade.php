<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Sourcing Orders'), 'url' => route('client.sourcing-orders.index')],
    ['label' => __('Details')]
]">
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
                                {{ __('Order Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Order') }} #{{ $sourcingOrder->id }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.sourcing-orders.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-[#fffff] dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Status Banner --}}
            @php
                $statusConfig = [
                    'pending_payment' => ['color' => '#FAA533', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Payment Required')],
                    'payment_pending_verification' => ['color' => '#0BA6DF', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('Verification in Progress')],
                    'processing' => ['color' => '#0BA6DF', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('Processing Order')],
                    'shipped' => ['color' => '#EF7722', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => __('Shipped')],
                    'delivered' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Delivered')],
                ];
                $statusData = $statusConfig[$sourcingOrder->status] ?? $statusConfig['pending_payment'];
            @endphp
            
            <div class="mb-6 bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                <div class="p-6" style="background-color: {{ $statusData['color'] }}22; border-bottom: 4px solid {{ $statusData['color'] }};">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-lg flex items-center justify-center" style="background-color: {{ $statusData['color'] }}22; border: 2px solid {{ $statusData['color'] }};">
                                <svg class="w-7 h-7" style="color: {{ $statusData['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: {{ $statusData['color'] }}">{{ $statusData['title'] ?? __('Current Status') }}</p>
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ __(ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}</p>
                            </div>
                        </div>
                        <div class="text-center sm:text-right bg-white dark:bg-slate-800 rounded-lg px-4 py-3 border border-[#EBEBEB] dark:border-slate-700">
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Last updated') }}</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white mt-1">{{ $sourcingOrder->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Product Information --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product Information') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Complete product details and specifications') }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EF7722]/10 text-[#EF7722]">
                                    {{ $sourcingOrder->quotation->sourcingRequest->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-6">
                                {{-- Product Image - Reduced Size --}}
                                <div class="sm:w-32 sm:flex-shrink-0">
                                    <div class="relative group">
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 bg-[#EBEBEB] dark:bg-slate-700 border-2 border-[#EBEBEB] dark:border-slate-600 rounded-lg overflow-hidden shadow-sm mx-auto">
                                            @if ($sourcingOrder->quotation->sourcingRequest->product_image)
                                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                                     alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name }}" 
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                    <span class="text-lg font-bold text-[#EF7722]">
                                                        {{ mb_substr($sourcingOrder->quotation->sourcingRequest->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Product Details --}}
                                <div class="flex-1 space-y-4">
                                    <div>
                                        <h4 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</h4>
                                        @if ($sourcingOrder->quotation->sourcingRequest->product_url)
                                            <a href="{{ $sourcingOrder->quotation->sourcingRequest->product_url }}" target="_blank" 
                                               class="inline-flex items-center gap-2 text-sm font-semibold text-[#EF7722] hover:text-[#FAA533] transition-colors mt-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                {{ __('View Source Product') }}
                                            </a>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Shipping Method') }}</p>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                                <p class="text-sm font-bold text-slate-900 dark:text-white capitalize">{{ $sourcingOrder->quotation->sourcingRequest->shipping_method ?? __('Not specified') }}</p>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Total Quantity') }}</p>
                                            <p class="text-lg font-bold text-[#EF7722]">{{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }} {{ __('units') }}</p>
                                        </div>
                                    </div>

                                    @if ($sourcingOrder->quotation->sourcingRequest->note)
                                        <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Additional Notes') }}</p>
                                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                                {{ $sourcingOrder->quotation->sourcingRequest->note }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quotation Details --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Quotation Details') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Detailed pricing and cost breakdown') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Price') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->unit_price, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Commission') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->commission_service, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Weight') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->unit_weight, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('g') }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Local Delivery') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->delivery_cost_china, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                </div>
                                
                                <div class="p-6 bg-gradient-to-r from-[#EF7722] to-[#FAA533] rounded-lg shadow-lg">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-white uppercase tracking-wider mb-1">{{ __('Grand Total') }}</p>
                                            <p class="text-3xl sm:text-4xl font-extrabold text-white">{{ number_format($sourcingOrder->quotation->amount, 2) }} <span class="text-xl font-bold text-white/90">{{ $sourcingOrder->quotation->currency }}</span></p>
                                        </div>
                                        <svg class="w-14 h-14 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden" x-data="{ selectedMethod: null }">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Available Payment Methods') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Choose your preferred payment method') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($paymentMethods as $paymentMethod)
                                    <button @click="selectedMethod = selectedMethod === '{{ $paymentMethod->name }}' ? null : '{{ $paymentMethod->name }}'" 
                                            class="w-full p-4 text-left border rounded-lg transition-all duration-200"
                                            :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'border-[#EF7722] bg-[#EF7722]/5 shadow-sm' : 'border-[#EBEBEB] dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-[#EF7722] dark:hover:border-[#FAA533] hover:shadow-sm'">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                @if($paymentMethod->logo_path)
                                                    <div class="w-10 h-10 rounded-full border border-[#EBEBEB] bg-white p-1.5 shadow-sm flex items-center justify-center">
                                                        <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-contain">
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 flex items-center justify-center rounded-full border border-[#EBEBEB] bg-[#EBEBEB] shadow-sm">
                                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="text-base font-semibold text-slate-900 dark:text-white">{{ $paymentMethod->name }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500 transition-transform duration-200" :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'rotate-180 text-[#EF7722]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                        <div x-show="selectedMethod === '{{ $paymentMethod->name }}'" 
                                             x-transition.duration.300ms
                                             class="mt-4 pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                            <div class="space-y-2.5">
                                                @foreach($paymentMethod->details as $key => $value)
                                                    <div class="flex justify-between items-center p-2.5 bg-white dark:bg-slate-900 rounded-lg border border-[#EBEBEB] dark:border-slate-700">
                                                        <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">{{ $key }}:</span>
                                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Payment Section --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Payment Status') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Upload and manage payment proof') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if ($sourcingOrder->rejection_reason)
                                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 rounded-r-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-bold text-red-700 dark:text-red-300">{{ __('Payment Proof Rejected') }}</p>
                                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $sourcingOrder->rejection_reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sourcingOrder->status === 'pending_payment')
                                <form action="{{ route('client.sourcing-orders.upload-proof-of-payment', $sourcingOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Upload Proof of Payment') }}</label>
                                        <input type="file" 
                                               name="proof_of_payment" 
                                               class="block w-full text-sm text-slate-600 dark:text-slate-400
                                                      file:mr-4 file:py-2.5 file:px-4
                                                      file:rounded-lg file:border-0
                                                      file:text-sm file:font-semibold
                                                      file:bg-[#EF7722] file:text-white
                                                      hover:file:bg-[#FAA533]
                                                      file:cursor-pointer file:transition-colors
                                                      border-2 border-dashed border-[#EBEBEB] dark:border-slate-600 rounded-lg
                                                      hover:border-[#EF7722] dark:hover:border-[#FAA533] transition-colors
                                                      cursor-pointer p-2"
                                               required/>
                                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('Accepted formats: PDF, JPG, PNG (Max: 5MB)') }}</p>
                                    </div>
                                    <button type="submit" 
                                            class="w-full py-3 px-4 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Submit Payment Proof') }}
                                    </button>
                                </form>
                            @elseif($sourcingOrder->proof_of_payment_path)
                                <div class="text-center p-6 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg border-2 border-[#0BA6DF]">
                                    <div class="w-14 h-14 bg-[#0BA6DF]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ __('Payment Proof Submitted') }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ __('Your payment proof is under review. We will proceed with the order once verified.') }}</p>
                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        <a href="{{ route('client.sourcing-orders.download-proof-of-payment', $sourcingOrder) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-[#0BA6DF] bg-white dark:bg-slate-700 border-2 border-[#0BA6DF] rounded-lg hover:bg-[#0BA6DF]/5 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('View Document') }}
                                        </a>
                                        <a href="{{ route('client.sourcing-orders.receipt', $sourcingOrder) }}" target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-[#EF7722] bg-white dark:bg-slate-700 border-2 border-[#EF7722] rounded-lg hover:bg-[#EF7722]/5 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ __('Print Receipt') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Order Summary') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Key information at a glance') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            {{-- Key Details --}}
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <svg class="w-4 h-4 mr-3 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 flex-1">{{ __('Order ID') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">#{{ $sourcingOrder->id }}</span>
                                </div>

                                <div class="flex items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <svg class="w-4 h-4 mr-3 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 flex-1">{{ __('Category') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->quotation->sourcingRequest->category->name }}</span>
                                </div>

                                <div class="flex items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <svg class="w-4 h-4 mr-3 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 flex-1">{{ __('Destinations') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->quotation->sourcingRequest->destinations->count() }}</span>
                                </div>
                            </div>
                            
                            {{-- Status --}}
                            <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('Status') }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold" style="background-color: {{ $statusData['color'] }}22; color: {{ $statusData['color'] }};">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                    </svg>
                                    {{ __(ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}
                                </span>
                            </div>

                            {{-- Grand Total --}}
                            <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                <div class="p-5 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg shadow-sm">
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-white/90 uppercase tracking-wide mb-1">{{ __('Total Amount') }}</p>
                                        <p class="text-3xl font-extrabold text-white mb-1">{{ number_format($sourcingOrder->quotation->amount, 2) }}</p>
                                        <p class="text-base font-bold text-white/90">{{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                <div class="text-center text-xs text-slate-500 dark:text-slate-400 space-y-1">
                                    <p><span class="font-semibold">{{ __('Order Created') }}:</span> {{ $sourcingOrder->created_at->format('M d, Y') }}</p>
                                    <p><span class="font-semibold">{{ __('Last Update') }}:</span> {{ $sourcingOrder->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar styling */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #EF7722;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>