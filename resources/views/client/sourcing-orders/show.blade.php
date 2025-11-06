<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('client.dashboard')],
    ['label' => 'Sourcing Orders', 'url' => route('client.sourcing-orders.index')],
    ['label' => 'Details']
]">
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Sourcing Order Details') }}
                    </h2>
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">
                        {{ __('Order') }} #{{ $sourcingOrder->id }}
                    </p>
                </div>
                <a href="{{ route('client.sourcing-orders.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Status Banner --}}
            @php
                $statusConfig = [
                    'pending_payment' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Payment Required')],
                    'payment_pending_verification' => ['color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('Verification in Progress')],
                    'processing' => ['color' => 'purple', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('Processing Order')],
                    'shipped' => ['color' => 'blue', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => __('Shipped')],
                    'delivered' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Delivered')],
                ];
                $statusData = $statusConfig[$sourcingOrder->status] ?? $statusConfig['pending_payment'];
            @endphp
            
            <div class="mb-8 p-6 rounded-xl shadow-sm border {{ "bg-{$statusData['color']}-50 dark:bg-{$statusData['color']}-900/20 border-{$statusData['color']}-200 dark:border-{$statusData['color']}-700" }}">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900 border border-{$statusData['color']}-300 dark:border-{$statusData['color']}-500" }}">
                            <svg class="w-6 h-6 {{ "text-{$statusData['color']}-600 dark:text-{$statusData['color']}-400" }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ "text-{$statusData['color']}-700 dark:text-{$statusData['color']}-300" }} uppercase tracking-wider mb-1">{{ $statusData['title'] ?? __('Current Status') }}</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ __(ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}</p>
                        </div>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Last updated') }}</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $sourcingOrder->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Product Information --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Product Information') }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Complete product details and specifications') }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    {{ $sourcingOrder->quotation->sourcingRequest->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-8">
                                {{-- Product Image --}}
                                <div class="space-y-6">
                                    <div class="relative group">
                                        <div class="w-full aspect-square bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">
                                            @if ($sourcingOrder->quotation->sourcingRequest->product_image)
                                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                                     alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name }}" 
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <svg class="w-20 h-20 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</h4>
                                        @if ($sourcingOrder->quotation->sourcingRequest->product_url)
                                            <a href="{{ $sourcingOrder->quotation->sourcingRequest->product_url }}" target="_blank" 
                                               class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                {{ __('View Source Product') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Product Details --}}
                                <div class="space-y-6">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">{{ __('Shipping Method') }}</p>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base font-semibold text-gray-900 dark:text-white capitalize">{{ $sourcingOrder->quotation->sourcingRequest->shipping_method ?? __('Not specified') }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    @if($sourcingOrder->quotation->sourcingRequest->shipping_method === 'air')
                                                        {{ __('Faster delivery, suitable for small volumes') }}
                                                    @else
                                                        {{ __('Cost-effective, suitable for large volumes') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">{{ __('Additional Notes') }}</p>
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed flex-1">
                                                {{ $sourcingOrder->quotation->sourcingRequest->note ?? __('No additional notes provided.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quotation Details --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Quotation Details') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Detailed pricing and cost breakdown') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('Unit Price') }}</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingOrder->quotation->unit_price, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('Commission') }}</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingOrder->quotation->commission_service, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('Unit Weight') }}</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingOrder->quotation->unit_weight, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">g</span></p>
                                    </div>
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">{{ __('Local Delivery') }}</p>
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingOrder->quotation->delivery_cost_china, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                </div>
                                
                                <div class="p-6 bg-gradient-to-r from-blue-500/10 to-blue-600/10 border-2 border-blue-400 dark:border-blue-600 rounded-xl">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wider mb-1">{{ __('Grand Total') }}</p>
                                            <p class="text-3xl sm:text-4xl font-extrabold text-blue-800 dark:text-blue-400">{{ number_format($sourcingOrder->quotation->amount, 2) }} <span class="text-xl font-bold">{{ $sourcingOrder->quotation->currency }}</span></p>
                                        </div>
                                        <svg class="w-14 h-14 text-blue-500 dark:text-blue-700 opacity-50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Section --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Payment Status') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Upload and manage payment proof') }}</p>
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
                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <form action="{{ route('client.sourcing-orders.upload-proof-of-payment', $sourcingOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Upload Proof of Payment') }}</label>
                                            <input type="file" 
                                                   name="proof_of_payment" 
                                                   class="block w-full text-sm text-gray-600 dark:text-gray-400
                                                          file:mr-4 file:py-2.5 file:px-4
                                                          file:rounded-lg file:border-0
                                                          file:text-sm file:font-semibold
                                                          file:bg-blue-100 dark:file:bg-blue-900/50 file:text-blue-700 dark:file:text-blue-300
                                                          hover:file:bg-blue-200 dark:hover:file:bg-blue-900/70
                                                          file:cursor-pointer file:transition-colors
                                                          border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl
                                                          hover:border-blue-400 dark:hover:border-blue-500 transition-colors
                                                          cursor-pointer p-2"
                                                   required/>
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Accepted formats: PDF, JPG, PNG (Max: 5MB)') }}</p>
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
                            @elseif($sourcingOrder->proof_of_payment_path)
                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-center p-6 bg-green-50 dark:bg-green-900/20 rounded-xl border-2 border-green-300 dark:border-green-700">
                                        <div class="w-14 h-14 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('Payment Proof Submitted') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Your payment proof is under review. We will proceed with the order once verified.') }}</p>
                                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                            <a href="{{ asset('storage/' . $sourcingOrder->proof_of_payment_path) }}" 
                                               target="_blank" 
                                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 border-2 border-green-300 dark:border-green-700 rounded-lg hover:bg-green-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ __('View Document') }}
                                            </a>
                                            <a href="{{ route('client.sourcing-orders.receipt', $sourcingOrder) }}" target="_blank"
                                               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-700 dark:text-blue-300 bg-white dark:bg-gray-700 border-2 border-blue-300 dark:border-blue-700 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ __('Print Receipt') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Order Summary') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Key information at a glance') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            {{-- Key Details --}}
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <svg class="w-4 h-4 mr-3 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex-1">{{ __('Order ID') }}</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">#{{ $sourcingOrder->id }}</span>
                                </div>

                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <svg class="w-4 h-4 mr-3 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h.01M3 7h.01M3 3h.01M17 7h.01M17 3h.01M21 7h.01M21 3h.01M7 17h.01M7 21h.01M3 17h.01M3 21h.01M17 17h.01M17 21h.01M21 17h.01M21 21h.01"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex-1">{{ __('Category') }}</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $sourcingOrder->quotation->sourcingRequest->category->name }}</span>
                                </div>

                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <svg class="w-4 h-4 mr-3 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex-1">{{ __('Total Quantity') }}</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }}</span>
                                </div>
                            </div>
                            
                            {{-- Status --}}
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Status') }}</span>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full
                                    {{ $statusData['color'] === 'amber' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' : 
                                       ($statusData['color'] === 'blue' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : 
                                       ($statusData['color'] === 'purple' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 
                                       ($statusData['color'] === 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 
                                       'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'))) }}">
                                    {{ __(ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}
                                </span>
                            </div>

                            {{-- Grand Total --}}
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="p-5 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl border border-blue-700 shadow-sm">
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-blue-200 uppercase tracking-wide mb-1">{{ __('Total Amount') }}</p>
                                        <p class="text-3xl font-extrabold text-white mb-1">{{ number_format($sourcingOrder->quotation->amount, 2) }}</p>
                                        <p class="text-base font-semibold text-blue-300">{{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-center text-xs text-gray-500 dark:text-gray-400">
                                    <p class="mb-1">{{ __('Order Created') }}: {{ $sourcingOrder->created_at->format('M d, Y') }}</p>
                                    <p>{{ __('Last Update') }}: {{ $sourcingOrder->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>