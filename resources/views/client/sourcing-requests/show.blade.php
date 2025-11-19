<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ $sourcingRequest->product_name }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                                {{ __('Request #') }}{{ $sourcingRequest->id }} • {{ __('Created') }} {{ $sourcingRequest->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-[#fffff] dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Statistics Bar --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 mb-6">
                <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Total Quantity --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total Quantity') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->destinations->sum('quantity')) }}</p>
                            </div>
                        </div>

                        {{-- Destinations --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Destinations') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->destinations->count() }}</p>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Status') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white capitalize">{{ str_replace('_', ' ', $sourcingRequest->status) }}</p>
                            </div>
                        </div>

                        {{-- Last Updated --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Last Updated') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- Product Information Card --}}
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        {{-- Card Header --}}
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Product Information') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Complete product details and specifications') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                {{-- Product Image --}}
                                <div class="sm:col-span-1">
                                    <div class="relative w-full aspect-square bg-[#EBEBEB] dark:bg-slate-700 rounded-lg overflow-hidden border border-[#EBEBEB] dark:border-slate-600 shadow-sm">
                                        @if ($sourcingRequest->product_image)
                                            <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                                 alt="{{ $sourcingRequest->product_name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                <span class="text-2xl font-bold text-[#EF7722]">
                                                    {{ mb_substr($sourcingRequest->product_name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Product Details --}}
                                <div class="sm:col-span-2 space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">{{ __('Category') }}</p>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-[#EF7722]/10 text-[#EF7722]">
                                                {{ $sourcingRequest->category->name }}
                                            </span>
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">{{ __('Source Location') }}</p>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white capitalize">{{ $sourcingRequest->sourcing_location }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">{{ __('Shipping Method') }}</p>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($sourcingRequest->shipping_method === 'air')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m16 0l-8-4m8 4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                @endif
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white capitalize">
                                                {{ $sourcingRequest->shipping_method ?? 'Not specified' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($sourcingRequest->product_url)
                                        <div>
                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">{{ __('Source URL') }}</p>
                                            <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                               class="inline-flex items-center gap-2 text-sm font-semibold text-[#EF7722] hover:text-[#FAA533] transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                {{ __('View Product Source') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Notes Section --}}
                            @if ($sourcingRequest->note)
                                <div class="mt-6 pt-6 border-t border-[#EBEBEB] dark:border-slate-700">
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-3">{{ __('Additional Notes') }}</p>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $sourcingRequest->note }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Destinations Card --}}
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        {{-- Card Header --}}
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Shipping Destinations') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-[#EF7722]">{{ $sourcingRequest->destinations->count() }}</span> {{ Str::plural(__('destination'), $sourcingRequest->destinations->count()) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach ($sourcingRequest->destinations as $destination)
                                    <div class="flex items-center justify-between p-4 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600 hover:border-[#EF7722] dark:hover:border-[#FAA533] transition-colors">
                                        <div class="flex items-center gap-3 flex-1">
                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-xl rounded-sm border border-[#EBEBEB] dark:border-slate-600 shadow-sm"></span>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $destination->country->name }}</p>
                                                <p class="text-xs text-slate-600 dark:text-slate-400">{{ $destination->service->name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('Quantity') }}</p>
                                            <p class="text-lg font-bold text-[#EF7722]">{{ number_format($destination->quantity) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Quotation Section --}}
                    @if ($sourcingRequest->quotation)
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                            {{-- Card Header --}}
                            <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Price Quote') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Detailed pricing and cost breakdown') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                                    <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-1">{{ __('Unit Price') }}</p>
                                        <p class="text-base font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_price, 2) }}</p>
                                    </div>
                                    <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-1">{{ __('Commission') }}</p>
                                        <p class="text-base font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->commission_service, 2) }}</p>
                                    </div>
                                    <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-1">{{ __('Weight (g)') }}</p>
                                        <p class="text-base font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_weight, 2) }}</p>
                                    </div>
                                    <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-1">{{ __('Delivery') }}</p>
                                        <p class="text-base font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->delivery_cost_china, 2) }}</p>
                                    </div>
                                </div>

                                {{-- Grand Total --}}
                                <div class="p-4 sm:p-6 bg-gradient-to-r from-[#EF7722] to-[#FAA533] rounded-xl text-white shadow-lg">
                                    <p class="text-sm font-semibold opacity-90 mb-1">{{ __('Grand Total') }}</p>
                                    <p class="text-3xl sm:text-4xl font-bold">{{ number_format($sourcingRequest->quotation->amount, 2) }}</p>
                                    <p class="text-sm opacity-90 mt-1">{{ $sourcingRequest->quotation->currency }}</p>
                                </div>

                                {{-- Action Buttons --}}
                                @if($sourcingRequest->quotation->order === null)
                                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                        <form action="{{ route('client.quotations.accept', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-3 px-4 bg-[#EF7722] hover:bg-[#FAA533] text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('Accept & Order') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('client.quotations.reject', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-3 px-4 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-[#EBEBEB] dark:border-slate-600 font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- No Quote Available --}}
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Price Quote') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Quote analysis in progress') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-12 text-center">
                                <div class="w-16 h-16 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-slate-900 dark:text-white mb-1">{{ __('Quote Coming Soon') }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('We are analyzing your request and preparing the official quote.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden sticky top-6">
                        <div class="p-6 space-y-4">
                            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('Request Summary') }}
                            </h3>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('Request ID') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">#{{ $sourcingRequest->id }}</span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('Status') }}</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-[#EF7722]/10 text-[#EF7722]">
                                        {{ str_replace('_', ' ', $sourcingRequest->status) }}
                                    </span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('Destinations') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->destinations->count() }}</span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('Total Quantity') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->destinations->sum('quantity')) }}</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700 text-center text-xs text-slate-500 dark:text-slate-400">
                                <p class="mb-1">{{ __('Created') }} {{ $sourcingRequest->created_at->format('M d, Y') }}</p>
                                <p>{{ __('Updated') }} {{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                            </div>

                            {{-- Action Buttons --}}
                            @if ($sourcingRequest->status === 'pending')
                                <a href="{{ route('client.sourcing-requests.edit', $sourcingRequest) }}" 
                                   class="w-full py-2.5 px-4 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-semibold rounded-lg transition-all text-center flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ __('Edit Request') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .fi {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
    </style>
</x-app-layout>