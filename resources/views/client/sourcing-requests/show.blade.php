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
                                <p class="text-lg font-bold text-slate-900 dark:text-white capitalize">{{ $sourcingRequest->status_label }}</p>
                            </div>
                        </div>

                        {{-- Event Date --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                @if($sourcingRequest->status === 'negotiating' && $sourcingRequest->negotiated_at)
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Negotiation Date') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->negotiated_at->format('M d, Y') }}</p>
                                @elseif($sourcingRequest->status === 'accepted' && $sourcingRequest->accepted_at)
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Payment Date') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->accepted_at->format('M d, Y') }}</p>
                                @else
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Request Date') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->created_at->format('M d, Y') }}</p>
                                @endif
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
                                                    <div class="w-full mt-2 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2 animate-pulse">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                        </svg>
                                                        <span class="font-bold uppercase tracking-wide">
                                                            {{ __('Alternative Sourcing: ') }} {{ $sourcingRequest->quotation->actual_sourcing_location }}
                                                        </span>
                                                    </div>
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
                                                {{ $sourcingRequest->shipping_method_label }}
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
                            @can('updateDestinationQuantities', $sourcingRequest)
                                <form method="POST" action="{{ route('client.sourcing-requests.update-destination-quantities', $sourcingRequest) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    @if ($errors->any())
                                        <div class="rounded-lg border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 px-4 py-3 text-sm text-red-800 dark:text-red-200">
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">
                                        {{ __('Adjust quantities per destination. Your quotation total, shipping fees, and internal estimates update proportionally to the new total quantity.') }}
                                    </p>
                                    <div class="space-y-3">
                                        @foreach ($sourcingRequest->destinations as $destination)
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600 hover:border-[#EF7722] dark:hover:border-[#FAA533] transition-colors">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <span class="fi fi-{{ strtolower($destination->country->code) }} text-xl rounded-sm border border-[#EBEBEB] dark:border-slate-600 shadow-sm"></span>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $destination->country->name }}</p>
                                                        <p class="text-xs text-slate-600 dark:text-slate-400">{{ $destination->service->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 sm:justify-end">
                                                    <label class="sr-only" for="qty-{{ $destination->id }}">{{ __('Quantity') }}</label>
                                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase whitespace-nowrap">{{ __('Quantity') }}</span>
                                                    <input type="hidden" name="destinations[{{ $loop->index }}][id]" value="{{ $destination->id }}">
                                                    <input id="qty-{{ $destination->id }}"
                                                           type="number"
                                                           name="destinations[{{ $loop->index }}][quantity]"
                                                           value="{{ old('destinations.'.$loop->index.'.quantity', $destination->quantity) }}"
                                                           min="1"
                                                           step="1"
                                                           required
                                                           class="w-24 sm:w-28 rounded-lg border border-[#EBEBEB] dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-center font-bold text-[#EF7722] py-2 focus:ring-2 focus:ring-[#EF7722] focus:border-transparent">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="pt-2 flex justify-end">
                                        <button type="submit" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-[#EF7722] hover:bg-[#e06a15] text-white text-sm font-bold shadow-sm transition-colors">
                                            {{ __('Update quantities & recalculate quotation') }}
                                        </button>
                                    </div>
                                </form>
                            @else
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
                            @endcan
                        </div>
                    </div>

                    {{-- Simplified Quotation Section --}}
                    @if ($sourcingRequest->quotation)
                        @php
                            $firstQuality = null;
                            $initialUnitPrice = $sourcingRequest->quotation->unit_price;
                            $initialAmount = $sourcingRequest->quotation->amount;
                            
                            if ($sourcingRequest->quotation->quality_options) {
                                foreach (['low', 'medium', 'good'] as $k) {
                                    if (!empty($sourcingRequest->quotation->quality_options[$k]['price'])) {
                                        $firstQuality = $k;
                                        $initialUnitPrice = $sourcingRequest->quotation->quality_options[$k]['price'];
                                        $totalQuantity = $sourcingRequest->destinations->sum('quantity');
                                        $subtotal = $initialUnitPrice * $totalQuantity;
                                        $initialAmount = $subtotal + $sourcingRequest->quotation->commission_service + $sourcingRequest->quotation->delivery_cost_china;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                            {{-- Header --}}
                            <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700 flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Official Quotation') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">#{{ strtoupper(substr($sourcingRequest->quotation->id, -8)) }}</p>
                                    </div>
                                </div>

                                @if($sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location)
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-600 text-white rounded-full shadow-lg animate-pulse">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                        </span>
                                        <span class="text-xs font-black uppercase tracking-tighter">
                                            {{ __('Alternative Hub') }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                {{-- Financial Parameters Grid --}}
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Price') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white"><span id="dynamic-unit-price">{{ number_format($initialUnitPrice, 2) }}</span> <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Commission') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->commission_service, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Weight') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->unit_weight, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->weight_unit ?? 'g' }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Shipping fees') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingRequest->quotation->delivery_cost_china, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingRequest->quotation->currency }}</span></p>
                                    </div>
                                </div>

                                {{-- Product Media Gallery --}}
                                @if($sourcingRequest->quotation->media->count() > 0)
                                    <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-[#EBEBEB] dark:border-slate-700">
                                        <h4 class="text-base font-bold text-slate-900 dark:text-white mb-4">{{ __('Product Quality Verification') }}</h4>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($sourcingRequest->quotation->media as $media)
                                                <div class="relative group rounded-lg overflow-hidden bg-white dark:bg-slate-800 shadow-md hover:shadow-xl transition-shadow">
                                                    @if($media->isImage())
                                                        {{-- Image Display --}}
                                                        <div class="aspect-square">
                                                            <img src="{{ asset('storage/' . $media->file_path) }}" 
                                                                 alt="Product verification {{ $loop->iteration }}" 
                                                                 class="w-full h-full object-cover cursor-pointer"
                                                                 onclick="openMediaModal('{{ asset('storage/' . $media->file_path) }}', 'image')">
                                                        </div>
                                                    @elseif($media->isVideo())
                                                        {{-- Video Display --}}
                                                        <div class="aspect-square bg-black relative">
                                                            <video src="{{ asset('storage/' . $media->file_path) }}" 
                                                                   class="w-full h-full object-cover"
                                                                   controls
                                                                   preload="metadata">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                            <div class="absolute top-2 left-2 px-2 py-1 bg-black/70 text-white text-xs rounded">
                                                                {{ __('VIDEO') }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Media counter badge --}}
                                                    <div class="absolute bottom-2 right-2 px-2 py-1 bg-orange-600 text-white text-xs font-bold rounded shadow-lg">
                                                        {{ $loop->iteration }} / {{ $sourcingRequest->quotation->media->count() }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <p class="mt-4 text-xs text-slate-500 dark:text-slate-400 italic">
                                            {{ __('Click on images to view full size. Videos can be played inline.') }}
                                        </p>
                                    </div>
                                @elseif($sourcingRequest->quotation->real_product_image)
                                    {{-- Fallback for old single image --}}
                                    <div class="mb-6 p-4 bg-slate-50 dark:bg-slate-900 rounded-xl border border-[#EBEBEB] dark:border-slate-700">
                                        <div class="flex flex-col md:flex-row items-center gap-6">
                                            <div class="flex-1">
                                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ __('Physical Product Verification') }}</h4>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                                    {{ __('Our inspectors have verified that the product matches your requirements. See the actual unit below.') }}
                                                </p>
                                            </div>
                                            <div class="w-full md:w-32 aspect-square rounded-lg overflow-hidden border-2 border-white dark:border-slate-700 shadow-sm">
                                                <img src="{{ asset('storage/' . $sourcingRequest->quotation->real_product_image) }}" 
                                                     alt="Verified Product" 
                                                     class="w-full h-full object-cover transition-transform hover:scale-110 cursor-pointer"
                                                     onclick="window.open(this.src, '_blank')">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Grand Total Bar --}}
                                <div class="p-6 bg-gradient-to-r from-[#EF7722] to-[#FAA533] rounded-lg shadow-lg mb-8">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-white uppercase tracking-wider mb-1">{{ __('Total Investment') }}</p>
                                            <p class="text-3xl sm:text-4xl font-extrabold text-white"><span id="dynamic-grand-total">{{ number_format($initialAmount, 2) }}</span> <span class="text-xl font-bold text-white/90">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="hidden sm:block">
                                            <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hub Note Banner --}}
                                @if($sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location && $sourcingRequest->quotation->sourcing_note)
                                    <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/10 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-6 h-6 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-bold text-red-800 dark:text-red-400 uppercase mb-1">{{ __('Important Admin Note') }}</p>
                                                <p class="text-base text-red-700 dark:text-red-300 italic font-medium">
                                                    "{{ $sourcingRequest->quotation->sourcing_note }}"
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($sourcingRequest->quotation->comments)
                                    <div class="mb-8 p-4 bg-amber-50 dark:bg-amber-900/10 border-l-4 border-amber-500 rounded-r-lg shadow-sm">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-6 h-6 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-bold text-amber-800 dark:text-amber-400 uppercase mb-1">{{ __('Admin Comment') }}</p>
                                                <p class="text-base text-amber-700 dark:text-amber-300 italic font-medium">
                                                    "{{ $sourcingRequest->quotation->comments }}"
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($sourcingRequest->quotation->negotiation_notes || $sourcingRequest->quotation->admin_negotiation_reply)
                                    <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/10 border-l-4 border-blue-500 rounded-r-lg shadow-sm space-y-3">
                                        <p class="text-sm font-bold text-blue-800 dark:text-blue-400 uppercase">{{ __('Negotiation Exchange') }}</p>

                                        @if($sourcingRequest->quotation->negotiation_notes)
                                            <div>
                                                <div class="flex items-center justify-between mb-1">
                                                    <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase">{{ __('Your Note') }}</p>
                                                    <span class="text-[10px] font-bold text-blue-600/70">{{ $sourcingRequest->negotiated_at ? $sourcingRequest->negotiated_at->format('M d, Y h:i A') : '' }}</span>
                                                </div>
                                                <p class="text-sm text-blue-700 dark:text-blue-300 italic">"{{ $sourcingRequest->quotation->negotiation_notes }}"</p>
                                            </div>
                                        @endif

                                        @if($sourcingRequest->quotation->admin_negotiation_reply)
                                            <div>
                                                <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 uppercase mb-1">{{ __('Admin Reply') }}</p>
                                                <p class="text-sm text-blue-700 dark:text-blue-300 italic">"{{ $sourcingRequest->quotation->admin_negotiation_reply }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                {{-- Decision Center --}}
                                @if($sourcingRequest->quotation->order === null && !in_array($sourcingRequest->quotation->status, ['rejected', 'accepted']))
                                    <div x-data="{ 
                                        showNegotiateModal: false,
                                        selectedQuality: '{{ $firstQuality }}',
                                        totalQuantity: {{ (int)$sourcingRequest->destinations->sum('quantity') }},
                                        commission: {{ (float)$sourcingRequest->quotation->commission_service }},
                                        deliveryCost: {{ (float)$sourcingRequest->quotation->delivery_cost_china }},
                                        qualityPrices: {
                                            low: {{ (float)($sourcingRequest->quotation->quality_options['low']['price'] ?? 0) }},
                                            medium: {{ (float)($sourcingRequest->quotation->quality_options['medium']['price'] ?? 0) }},
                                            good: {{ (float)($sourcingRequest->quotation->quality_options['good']['price'] ?? 0) }}
                                        },
                                        init() {
                                            this.$watch('selectedQuality', value => {
                                                if (value && this.qualityPrices[value]) {
                                                    const price = this.qualityPrices[value];
                                                    const total = (price * this.totalQuantity) + this.commission + this.deliveryCost;
                                                    document.getElementById('dynamic-unit-price').textContent = price.toFixed(2);
                                                    document.getElementById('dynamic-grand-total').textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                                }
                                            });
                                        }
                                    }">
                                        {{-- Negotiation Banner inside Decision Center --}}
                                        @if($sourcingRequest->quotation->status === 'negotiating')
                                            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-r-lg shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                                        {{ __('Negotiation in progress. Our team is reviewing your feedback, but you can still accept the current quotation if you decide to proceed.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Product Quality Options Selector --}}
                                        @if($sourcingRequest->quotation->quality_options && count(array_filter($sourcingRequest->quotation->quality_options, fn($opt) => !empty($opt['price']))) > 0)
                                            <div class="mb-8 p-6 bg-slate-50 dark:bg-slate-900/40 border border-[#EBEBEB] dark:border-slate-700 rounded-xl shadow-inner">
                                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                                    </svg>
                                                    {{ __('Select Product Quality Option') }}
                                                </h4>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 font-medium">
                                                    {{ __('Choose your preferred quality level below. The pricing and totals will update automatically.') }}
                                                </p>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    @foreach(['low' => __('Low Quality'), 'medium' => __('Medium Quality'), 'good' => __('Good Quality')] as $key => $label)
                                                        @if(!empty($sourcingRequest->quotation->quality_options[$key]['price']))
                                                            @php 
                                                                $opt = $sourcingRequest->quotation->quality_options[$key]; 
                                                            @endphp
                                                            <label class="relative flex flex-col bg-white dark:bg-slate-800 border-2 rounded-xl p-4 cursor-pointer focus:outline-none transition-all hover:border-[#EF7722]/50 shadow-sm"
                                                                   :class="selectedQuality === '{{ $key }}' ? 'border-[#EF7722] ring-2 ring-[#EF7722]/20' : 'border-[#EBEBEB] dark:border-slate-700'">
                                                                <input type="radio" name="quality_selector" value="{{ $key }}" class="sr-only" 
                                                                       :checked="selectedQuality === '{{ $key }}'"
                                                                       @change="selectedQuality = '{{ $key }}'; document.querySelectorAll('.selected-quality-input').forEach(i => i.value = '{{ $key }}')">
                                                                
                                                                {{-- Image preview --}}
                                                                <div class="aspect-video w-full rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 mb-3 border border-slate-100 dark:border-slate-600 flex items-center justify-center">
                                                                    @if(!empty($opt['image_path']))
                                                                        <img src="{{ asset('storage/' . $opt['image_path']) }}" alt="{{ $label }}" class="w-full h-full object-cover">
                                                                    @else
                                                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 dark:bg-slate-900/50">
                                                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                            </svg>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                {{-- Label & Price --}}
                                                                <div class="flex flex-col mt-auto">
                                                                    <span class="block text-sm font-black text-slate-900 dark:text-white">{{ $label }}</span>
                                                                    <div class="flex justify-between items-end mt-2">
                                                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                                                            {{ __('Unit Price') }}
                                                                        </span>
                                                                        <span class="text-sm font-extrabold text-[#EF7722]">
                                                                            {{ number_format($opt['price'], 2) }} {{ $sourcingRequest->quotation->currency }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <form action="{{ route('client.quotations.accept', $sourcingRequest->quotation) }}" method="POST" class="flex-[2]">
                                                @csrf
                                                <input type="hidden" name="selected_quality" class="selected-quality-input" :value="selectedQuality">
                                                <button type="submit" class="w-full h-14 bg-[#EF7722] hover:bg-[#FAA533] text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ __('Authorize & Initialize Order') }}
                                                </button>
                                            </form>

                                            <button type="button" @click="showNegotiateModal = true" class="flex-1 h-14 bg-white dark:bg-slate-700 text-slate-700 dark:text-white border-2 border-[#EBEBEB] dark:border-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                                </svg>
                                                {{ __('Negotiate') }}
                                            </button>

                                            <form action="{{ route('client.quotations.reject', $sourcingRequest->quotation) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('Confirm permanent rejection of this quote?') }}');">
                                                @csrf
                                                <button type="submit" class="w-full h-14 bg-white dark:bg-slate-700 text-red-500 border-2 border-[#EBEBEB] dark:border-slate-600 font-bold rounded-xl hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    {{ __('Decline') }}
                                                </button>
                                            </form>
                                        </div>

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
                                            <div class="flex items-center justify-center min-h-screen px-4">
                                                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showNegotiateModal = false"></div>

                                                <div class="relative bg-white dark:bg-slate-800 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border border-[#EBEBEB] dark:border-slate-700">
                                                    <form action="{{ route('client.quotations.negotiate', $sourcingRequest->quotation) }}" method="POST">
                                                        @csrf
                                                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 flex items-center justify-between">
                                                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Negotiation Request') }}</h3>
                                                            <button type="button" @click="showNegotiateModal = false" class="text-slate-400 hover:text-slate-600">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-6">
                                                            <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                                                <p class="text-sm text-blue-800 dark:text-blue-300">
                                                                    {{ __('Please specify your target terms or budgetary constraints below. Our team will review it promptly.') }}
                                                                </p>
                                                            </div>
                                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">{{ __('Your Feedback') }}</label>
                                                            <textarea name="negotiation_notes" rows="4" required class="w-full px-4 py-3 text-sm border-2 border-[#EBEBEB] dark:border-slate-700 rounded-xl focus:border-[#EF7722] dark:bg-slate-900 dark:text-white transition-all resize-none" placeholder="{{ __('Example: Seeking a volume discount for 500+ units...') }}"></textarea>
                                                        </div>
                                                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-[#EBEBEB] dark:border-slate-700 flex gap-3">
                                                            <button type="button" @click="showNegotiateModal = false" class="flex-1 py-3 px-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-white border border-[#EBEBEB] dark:border-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all">
                                                                {{ __('Cancel') }}
                                                            </button>
                                                            <button type="submit" class="flex-[1.5] py-3 px-4 bg-[#EF7722] hover:bg-[#FAA533] text-white font-bold rounded-xl shadow-md transition-all">
                                                                {{ __('Submit Request') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
                                    <span class="text-sm font-bold text-[#EF7722]">{{ $sourcingRequest->reference_id }}</span>
                                </div>

                                <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">{{ __('Status') }}</span>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-[#EF7722]/10 text-[#EF7722]">
                                        {{ $sourcingRequest->status_label }}
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
                                @if($sourcingRequest->status === 'negotiating' && $sourcingRequest->negotiated_at)
                                    <p class="mb-1">{{ __('Negotiated on') }} {{ $sourcingRequest->negotiated_at->format('M d, Y') }}</p>
                                @elseif($sourcingRequest->status === 'accepted' && $sourcingRequest->accepted_at)
                                    <p class="mb-1">{{ __('Paid on') }} {{ $sourcingRequest->accepted_at->format('M d, Y') }}</p>
                                @else
                                    <p class="mb-1">{{ __('Created on') }} {{ $sourcingRequest->created_at->format('M d, Y') }}</p>
                                @endif
                                <p>{{ __('Last updated') }} {{ $sourcingRequest->updated_at->diffForHumans() }}</p>
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

    {{-- Image Modal for Full Size Viewing --}}
    <div id="mediaModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="closeMediaModal()">
        <div class="relative max-w-7xl w-full h-full flex items-center justify-center">
            <button onclick="closeMediaModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img id="modalImage" src="" alt="Full size" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
        </div>
    </div>

    <script>
        function openMediaModal(src, type) {
            if (type === 'image') {
                document.getElementById('modalImage').src = src;
                document.getElementById('mediaModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMediaModal() {
            document.getElementById('mediaModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMediaModal();
        });
    </script>
</x-app-layout>