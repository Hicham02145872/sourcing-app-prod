<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('client.dashboard')],
    ['label' => 'Sourcing Requests', 'url' => route('client.sourcing-requests.index')],
    ['label' => 'Details']
]">
    <x-slot name="header">
        
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ __('Request Details') }}
                        </h2>
                        <p class="mt-0 text-base text-gray-500 dark:text-gray-400"><span class="font-mono font-bold text-black-600 dark:text-black-400">{{ $sourcingRequest->product_name }}</span></p>
                    </div>
                    <a href="{{ route('client.dashboard') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        
    </x-slot>

    <div class="py-3 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Status Banner (Enterprise Styling) --}}
            @php
                $statusMap = [
                    'pending' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Under Review')],
                    'quoted' => ['color' => 'red', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    'in_review' => ['color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                    'accepted' => ['color' => 'purple', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], // Using purple for accepted/in-process
                    'completed' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Completed')]
                ];
                $currentStatus = $sourcingRequest->status;
                $statusData = $statusMap[$currentStatus] ?? $statusMap['pending'];
            @endphp
            
            <div class="mb-6 p-5 rounded-xl shadow-lg border-2 {{ "bg-{$statusData['color']}-50 dark:bg-{$statusData['color']}-900/20 border-{$statusData['color']}-300 dark:border-{$statusData['color']}-700" }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900 border border-{$statusData['color']}-300 dark:border-{$statusData['color']}-500" }}">
                            <svg class="w-6 h-6 {{ "text-{$statusData['color']}-600 dark:text-{$statusData['color']}-400" }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ "text-{$statusData['color']}-700 dark:text-{$statusData['color']}-300" }} uppercase tracking-wider mb-0.5">{{ $statusData['title'] ?? __('Current Status') }}</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ __(ucfirst(str_replace('_', ' ', $currentStatus))) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Last activity') }}</p>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Main Content (lg:col-span-2) --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Product Information --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Product & Logistics Information') }}</h3>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold text-violet-700 dark:text-violet-300 bg-violet-100 dark:bg-violet-900/30 rounded-full">
                                    {{ $sourcingRequest->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Product Image/Details --}}
                                <div class="space-y-4">
                                    <div class="relative group h-64">
                                        <div class="w-full h-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl overflow-hidden">
                                            @if ($sourcingRequest->product_image)
                                                <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                                     alt="{{ $sourcingRequest->product_name }}" 
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
                                    <div class="space-y-1">
                                        <h4 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">{{ $sourcingRequest->product_name }}</h4>
                                        @if ($sourcingRequest->product_url)
                                            <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                               class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                {{ __('View Source Product') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Logistics and Notes --}}
                                <div class="space-y-4 pt-4 md:pt-0">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Shipping Method') }}</p>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                            </svg>
                                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ ucfirst($sourcingRequest->shipping_method ?? __('Not specified')) }}</p>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Sourcing Location') }}</p>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <p class="text-base font-semibold text-gray-900 dark:text-white capitalize">{{ $sourcingRequest->sourcing_location ?? __('Not specified') }}</p>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('Additional Notes') }}</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed max-h-32 overflow-y-auto">
                                            {{ $sourcingRequest->note ?? __('No notes provided by the client.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Destinations (Refined List) --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Shipping Destinations') }}</h3>
                                </div>
                                <span class="px-2.5 py-0.5 text-xs font-semibold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                    {{ $sourcingRequest->destinations->count() }} {{ trans_choice('location', $sourcingRequest->destinations->count()) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($sourcingRequest->destinations as $index => $destination)
                                    <div class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md hover:border-violet-300 dark:hover:border-violet-600 transition-all duration-200">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-2xl rounded-sm border border-gray-300"></span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">{{ $destination->country->name }}</p>
                                                <div class="flex items-center gap-1.5 mt-0.5">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                    </svg>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $destination->service->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right flex items-center gap-3">
                                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                {{ __('Quantity') }}
                                            </div>
                                            <span class="px-3 py-1 text-base font-bold text-violet-700 dark:text-violet-300 bg-violet-100 dark:bg-violet-900/30 rounded-full">
                                                {{ number_format($destination->quantity) }} units
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Quotation & Actions (Highly Differentiated) --}}
                    <div class="bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 border-b border-violet-200 dark:border-violet-800/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Official Price Quotation') }}</h3>
                                </div>
                                @if ($sourcingRequest->quotation)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border
                                        @if($sourcingRequest->quotation->status === 'approved') bg-green-100 text-green-800 border-green-300
                                        @elseif($sourcingRequest->quotation->status === 'pending') bg-amber-100 text-amber-800 border-amber-300
                                        @else bg-gray-100 text-gray-800 border-gray-300
                                        @endif">
                                        {{ ucfirst($sourcingRequest->quotation->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            @if ($sourcingRequest->quotation)
                                <div class="space-y-6">
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('Unit Price') }}</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_price, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('Commission') }}</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingRequest->quotation->commission_service, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('Unit Weight') }}</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_weight, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">g</span></p>
                                        </div>
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">{{ __('Local Delivery') }}</p>
                                            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($sourcingRequest->quotation->delivery_cost_china, 2) }} <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 bg-gradient-to-r from-violet-500/10 to-purple-500/10 border-2 border-violet-400 dark:border-violet-600 rounded-xl shadow-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-semibold text-violet-700 dark:text-violet-300 uppercase tracking-wider mb-1">{{ __('Grand Total Quote') }}</p>
                                                <p class="text-4xl font-extrabold text-violet-800 dark:text-violet-400">{{ number_format($sourcingRequest->quotation->amount, 2) }} <span class="text-xl font-bold">{{ $sourcingRequest->quotation->currency }}</span></p>
                                            </div>
                                            <svg class="w-14 h-14 text-violet-500 dark:text-violet-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>

                                    @if($sourcingRequest->quotation->order === null)
                                        <div class="flex flex-col sm:flex-row gap-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                            <form action="{{ route('client.quotations.accept', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ __('Accept Quotation & Create Order') }}
                                                </button>
                                            </form>
                                            <form action="{{ route('client.quotations.reject', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full py-3 px-4 bg-gray-100 hover:bg-red-50 text-gray-700 hover:text-red-600 border border-gray-300 hover:border-red-300 text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 flex items-center justify-center gap-2">
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
                                <div class="text-center py-12 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-200 dark:border-gray-600">
                                    <div class="w-16 h-16 bg-violet-100 dark:bg-violet-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ __('Quotation Pending') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __("We are currently analyzing your request and preparing the official quote.") }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Methods & Proof --}}
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden" x-data="{ selectedMethod: null }">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Payment & Order Status') }}</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('Available Payment Methods:') }}</p>
                                @foreach($paymentMethods as $paymentMethod)
                                    <button @click="selectedMethod = selectedMethod === '{{ $paymentMethod->name }}' ? null : '{{ $paymentMethod->name }}'" 
                                            class="w-full p-4 text-left border rounded-lg transition-all duration-200"
                                            :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'border-violet-400 dark:border-violet-600 bg-violet-50 dark:bg-violet-900/20 shadow-md' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-sm'">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                @if($paymentMethod->logo_path)
                                                    <div class="w-10 h-10 rounded-full border border-gray-200 bg-white p-1.5 shadow-sm flex items-center justify-center">
                                                        <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-contain">
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 bg-gray-50 shadow-sm">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="text-base font-semibold text-gray-900 dark:text-white">{{ $paymentMethod->name }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform duration-200" :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'rotate-180 text-violet-600 dark:text-violet-400' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                        <div x-show="selectedMethod === '{{ $paymentMethod->name }}'" 
                                             x-transition.duration.300ms
                                             class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <div class="space-y-2.5">
                                                @foreach($paymentMethod->details as $key => $value)
                                                    <div class="flex justify-between items-center p-2.5 bg-white dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-700">
                                                        <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">{{ $key }}:</span>
                                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            {{-- Payment Proof Upload Section (Only visible when order is pending payment) --}}
                            @if($sourcingRequest->quotation && $sourcingRequest->quotation->order && $sourcingRequest->quotation->order->status === 'pending_payment')
                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                       class="block w-full text-sm text-gray-600 dark:text-gray-400
                                                              file:mr-4 file:py-2.5 file:px-4
                                                              file:rounded-lg file:border-0
                                                              file:text-sm file:font-semibold
                                                              file:bg-violet-100 dark:file:bg-violet-900/50 file:text-violet-700 dark:file:text-violet-300
                                                              hover:file:bg-violet-200 dark:hover:file:bg-violet-900/70
                                                              file:cursor-pointer file:transition-colors
                                                              border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl
                                                              hover:border-violet-400 dark:hover:border-violet-500 transition-colors
                                                              cursor-pointer p-2"
                                                       required/>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Accepted formats: PDF, JPG, PNG (Max: 5MB)') }}</p>
                                        </div>
                                        <button type="submit" 
                                                class="w-full py-3 px-4 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Submit Payment Proof') }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($sourcingRequest->quotation && $sourcingRequest->quotation->order && $sourcingRequest->quotation->order->proof_of_payment_path)
                                {{-- Payment Proof Uploaded State --}}
                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-center p-6 bg-green-50 dark:bg-green-900/20 rounded-xl border-2 border-green-300 dark:border-green-700">
                                        <div class="w-14 h-14 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('Payment Proof Submitted') }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ __('Your payment proof is under review. We will proceed with the order once verified.') }}</p>
                                        <a href="{{ asset('storage/' . $sourcingRequest->quotation->order->proof_of_payment_path) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 border-2 border-green-300 dark:border-green-700 rounded-lg hover:bg-green-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
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

                {{-- Sidebar (Order Summary) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-violet-700 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Request Summary') }}</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            {{-- Key Details Grid --}}
                            <div class="space-y-3">
                                @php
                                    $summaryDetails = [
                                        __('Request ID') => ['value' => "#{$sourcingRequest->id}", 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                                        __('Category') => ['value' => $sourcingRequest->category->name, 'icon' => 'M7 7h.01M7 3h.01M3 7h.01M3 3h.01M17 7h.01M17 3h.01M21 7h.01M21 3h.01M7 17h.01M7 21h.01M3 17h.01M3 21h.01M17 17h.01M17 21h.01M21 17h.01M21 21h.01'],
                                        __('Destinations') => ['value' => $sourcingRequest->destinations->count(), 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945'],
                                        __('Total Quantity') => ['value' => number_format($sourcingRequest->destinations->sum('quantity')) . ' units', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                    ];
                                @endphp
                                @foreach($summaryDetails as $label => $data)
                                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <svg class="w-4 h-4 mr-3 text-violet-500 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $data['icon'] }}"/>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 flex-1">{{ $label }}</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $data['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Status --}}
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Status') }}</span>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full
                                    {{ $statusData['color'] === 'amber' ? 'bg-amber-100 text-amber-800' : 
                                       ($statusData['color'] === 'blue' || $statusData['color'] === 'purple' ? 'bg-blue-100 text-blue-800' : 
                                       ($statusData['color'] === 'red' ? 'bg-red-100 text-red-800' : 
                                       'bg-green-100 text-green-800')) }}">
                                    {{ __(ucfirst(str_replace('_', ' ', $currentStatus))) }}
                                </span>
                            </div>

                            {{-- Grand Total Accent --}}
                            @if($sourcingRequest->quotation)
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="p-5 bg-gradient-to-br from-violet-600 to-purple-600 rounded-xl border border-violet-700 shadow-xl">
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-violet-200 uppercase tracking-wide mb-1">{{ __('Order Grand Total') }}</p>
                                        <p class="text-4xl font-extrabold text-white mb-1">{{ number_format($sourcingRequest->quotation->amount, 2) }}</p>
                                        <p class="text-base font-semibold text-violet-300">{{ $sourcingRequest->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-center text-xs text-gray-500 dark:text-gray-400">
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
</x-app-layout>