<x-app-layout>
    

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
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
                        @php
                            $statusConfig = [
                                'pending' => ['color' => '#FAA533', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'quoted' => ['color' => '#EF7722', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                                'in_review' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'accepted' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'completed' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'negotiating' => ['color' => '#3B82F6', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                                'rejected' => ['color' => '#EF4444', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                'cancelled' => ['color' => '#64748B', 'icon' => 'M6 18L18 6M6 6l12 12'],
                            ];
                            $statusData = $statusConfig[$sourcingRequest->status] ?? $statusConfig['pending'];
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $statusData['color'] }}22;">
                                <svg class="w-5 h-5" style="color: {{ $statusData['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Status') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white capitalize">{{ __(str_replace('_', ' ', $sourcingRequest->status)) }}</p>
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
                                                {{ $sourcingRequest->category?->name }}
                                            </span>
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">{{ __('Source Location') }}</p>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="text-sm font-semibold text-slate-900 dark:text-white capitalize @if($sourcingRequest->quotation && $sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location) line-through opacity-50 @endif">
                                                    {{ $sourcingRequest->sourcing_location }}
                                                </p>
                                                @if($sourcingRequest->quotation && $sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location)
                                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                                    </svg>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 uppercase border border-blue-200 dark:border-blue-800">
                                                        {{ $sourcingRequest->quotation->actual_sourcing_location }}
                                                    </span>
                                                @endif
                                            </div>
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
                            <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Price Quote') }}</h3>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Detailed pricing and cost breakdown') }}</p>
                                        </div>
                                    </div>
                                    @if($sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location)
                                        <div class="flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-full">
                                            <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-tight">
                                                {{ __('Alternative Sourcing: ') }} {{ $sourcingRequest->quotation->actual_sourcing_location }}
                                            </span>
                                        </div>
                                        @if($sourcingRequest->quotation->sourcing_note)
                                            <div class="mt-2 p-3 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/50 rounded-lg">
                                                <p class="text-xs text-amber-800 dark:text-amber-300 italic">
                                                    <span class="font-bold not-italic mr-1">{{ __('Note:') }}</span>
                                                    {{ $sourcingRequest->quotation->sourcing_note }}
                                                </p>
                                            </div>
                                        @endif
                                    @endif
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
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-1">{{ __('Unit Weight') }}</p>
                                        <p class="text-base font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_weight, 2) }} <span class="text-xs font-medium text-slate-500">{{ $sourcingRequest->quotation->weight_unit ?? 'g' }}</span></p>
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
                                @if($sourcingRequest->quotation->order === null && !in_array($sourcingRequest->quotation->status, ['negotiating', 'rejected', 'accepted']))
                                    <div class="mt-6 flex flex-col sm:flex-row gap-3" x-data="{ showNegotiateModal: false }">
                                        <form action="{{ route('client.quotations.accept', $sourcingRequest->quotation) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-3 px-4 bg-[#EF7722] hover:bg-[#FAA533] text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('Accept & Order') }}
                                            </button>
                                        </form>

                                        <button type="button" 
                                                @click="showNegotiateModal = true"
                                                class="flex-1 py-3 px-4 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-[#EBEBEB] dark:border-slate-600 font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-all flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                            {{ __('Negotiate') }}
                                        </button>

                                        <form action="{{ route('client.quotations.reject', $sourcingRequest->quotation) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('Are you sure you want to definitively reject this quotation? This action cannot be undone.') }}');">
                                            @csrf
                                            <button type="submit" class="w-full py-3 px-4 bg-white dark:bg-slate-700 text-red-600 dark:text-red-400 border border-[#EBEBEB] dark:border-slate-600 font-semibold rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Definitive Rejection') }}
                                            </button>
                                        </form>

                                        {{-- Negotiation Modal --}}
                                        <div x-show="showNegotiateModal" 
                                             class="fixed inset-0 z-50 overflow-y-auto" 
                                             style="display: none;"
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0">
                                            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showNegotiateModal = false">
                                                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
                                                </div>

                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
                                                    <form action="{{ route('client.quotations.negotiate', $sourcingRequest->quotation) }}" method="POST">
                                                        @csrf
                                                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Request Changes / Negotiate') }}</h3>
                                                            <button type="button" @click="showNegotiateModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>

                                                        <div class="p-6">
                                                            <div class="space-y-4">
                                                                <div>
                                                                    <label for="negotiation_notes" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">
                                                                        {{ __('Reason for Negotiation') }}
                                                                    </label>
                                                                    <textarea name="negotiation_notes" 
                                                                              id="negotiation_notes" 
                                                                              rows="4" 
                                                                              required
                                                                              placeholder="{{ __('Example: The price is too high for my budget, I would like a discount of 5% or more information about shipping times...') }}"
                                                                              class="w-full px-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-slate-50 dark:bg-slate-700 dark:text-white transition-all"></textarea>
                                                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                                                        {{ __('Your request will be sent to the administrator assigned to your order.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row gap-3">
                                                            <button type="button" @click="showNegotiateModal = false" class="flex-1 py-2 px-4 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-600 font-bold rounded-lg hover:bg-slate-50 dark:hover:bg-slate-600 transition-all text-sm">
                                                                {{ __('Cancel') }}
                                                            </button>
                                                            <button type="submit" class="flex-1 py-2 px-4 bg-[#EF7722] hover:bg-[#FAA533] text-white font-bold rounded-lg transition-all shadow-sm hover:shadow text-sm">
                                                                {{ __('Submit Request') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($sourcingRequest->quotation->status === 'negotiating')
                                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                                {{ __('Negotiation in progress. We are reviewing your request.') }}
                                            </p>
                                        </div>
                                        @if($sourcingRequest->quotation->negotiation_notes)
                                            <div class="mt-3 text-xs text-blue-700 dark:text-blue-400 italic">
                                                "{{ $sourcingRequest->quotation->negotiation_notes }}"
                                            </div>
                                        @endif
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
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('ID') }}</span>
                                    <span class="text-sm font-bold text-[#EF7722]">#{{ $sourcingRequest->display_id }}</span>
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