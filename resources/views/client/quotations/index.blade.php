<x-app-layout>
    

    <div class="py-12 pb-24 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            @if ($sourcingRequests->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700">
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Pending Quotations') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Quotations for your sourcing requests will appear here once they are created by our team') }}</p>
                        <a href="{{ route('client.sourcing-requests.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __('View Sourcing Requests') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Control Panel --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 mb-6">
                    <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Active Quotations') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                            <span class="font-semibold text-[#EF7722]">{{ $sourcingRequests->count() }}</span> {{ Str::plural(__('quotation'), $sourcingRequests->count()) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <select class="px-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white font-medium shadow-sm">
                                    <option>{{ __('All Quotations') }}</option>
                                    <option>{{ __('Ready to Accept') }}</option>
                                    <option>{{ __('Under Review') }}</option>
                                </select>
                                <button class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('Export') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Statistics Bar --}}
                    <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                            {{-- Total --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->count() }}</p>
                                </div>
                            </div>

                            {{-- Ready --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Ready') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->whereNotNull('quotation_id')->count() }}</p>
                                </div>
                            </div>

                            {{-- Pending --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Pending') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->whereNull('quotation_id')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quotations List --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                    <div class="divide-y divide-[#EBEBEB] dark:divide-slate-700">
                        @foreach ($sourcingRequests as $request)
                            @php
                                $firstQuality = null;
                                $initialUnitPrice = $request->quotation ? $request->quotation->unit_price : 0;
                                $initialAmount = $request->quotation ? $request->quotation->amount : 0;
                                
                                if ($request->quotation && $request->quotation->quality_options) {
                                    foreach (['low', 'medium', 'good'] as $k) {
                                        if (!empty($request->quotation->quality_options[$k]['price'])) {
                                            $firstQuality = $k;
                                            $initialUnitPrice = $request->quotation->quality_options[$k]['price'];
                                            $totalQuantity = $request->destinations->sum('quantity');
                                            $subtotal = $initialUnitPrice * $totalQuantity;
                                            $initialAmount = $subtotal + $request->quotation->commission_service + $request->quotation->delivery_cost_china;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                                    <div class="group p-6 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150 {{ $request->quotation ? 'bg-red-50/30 dark:bg-red-900/10 border-l-4 border-red-500 shadow-inner' : '' }}">
                                        <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                            {{-- Request Info --}}
                                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                                @if ($request->quotation)
                                                    <div class="flex items-center justify-center self-center pr-2">
                                                        <input type="checkbox" name="selected_quotations[]" value="{{ $request->quotation->id }}" 
                                                               data-amount="{{ $initialAmount }}" 
                                                               data-currency="{{ $request->quotation->currency }}"
                                                               class="quotation-checkbox w-6 h-6 text-[#EF7722] border-slate-300 dark:border-slate-600 rounded focus:ring-[#EF7722] focus:ring-2 bg-white dark:bg-slate-700 cursor-pointer">
                                                    </div>
                                                @endif
                                                {{-- Product Image --}}
                                                <div class="flex-shrink-0 relative">
                                                    <div class="w-16 h-16 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border-2 border-[#EBEBEB] dark:border-slate-600 overflow-hidden shadow-sm">
                                                        @if ($request->product_image)
                                                            <img src="{{ media_url($request->product_image) }}"
                                                                 alt="{{ $request->product_name }}"
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                                <span class="text-lg font-bold text-[#EF7722]">
                                                                    {{ mb_substr($request->product_name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if($request->quotation)
                                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Request Details --}}
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2 mb-0.5">
                                                                <span class="text-xs font-bold text-[#EF7722]">{{ $request->reference_id }}</span>
                                                                @if($request->quotation)
                                                                    <span class="px-2 py-0.5 bg-red-500 text-white text-[10px] font-black rounded uppercase tracking-widest animate-pulse">{{ __('Action Required') }}</span>
                                                                @endif
                                                                <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-[#EF7722] transition-colors truncate">
                                                                    {{ $request->product_name }}
                                                                </h3>
                                                            </div>
                                                            <div class="flex items-center gap-2 mt-1.5">
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EF7722]/10 text-[#EF7722]">
                                                                    {{ $request->category?->name }}
                                                                </span>
                                                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                                                    {{ $request->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        {{-- Status Badge --}}
                                                        <div class="flex items-center gap-2">
                                                            @if ($request->quotation)
                                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-black shadow-sm" style="background-color: #ef4444; color: #ffffff;">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                                    </svg>
                                                                    <span class="uppercase tracking-widest">{{ __('Ready to Accept') }}</span>
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-bold" style="background-color: #FAA53322; color: #FAA533;">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    {{ __('Under Review') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                            {{-- Metadata --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                                {{-- Quotation Status --}}
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span class="font-semibold text-slate-600 dark:text-slate-400">{{ __('Quotation') }}:</span>
                                                    <span class="{{ $request->quotation ? 'text-[#0BA6DF]' : 'text-[#FAA533]' }} font-bold">
                                                        {{ $request->quotation ? __('Ready') : __('Pending') }}
                                                    </span>
                                                </div>

                                                {{-- Destinations --}}
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-semibold text-slate-600 dark:text-slate-400">{{ __('Destinations') }}:</span>
                                                    <div class="flex items-center gap-1">
                                                        @foreach($request->destinations->take(3) as $destination)
                                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-base border border-[#EBEBEB] dark:border-slate-600 rounded-sm" title="{{ $destination->country->name }}"></span>
                                                        @endforeach
                                                        @if($request->destinations->count() > 3)
                                                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 ml-1">
                                                                +{{ $request->destinations->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Total Amount --}}
                                                @if ($request->quotation)
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-semibold text-slate-600 dark:text-slate-400">{{ __('Total') }}:</span>
                                                    <span class="text-slate-900 dark:text-white font-bold">
                                                        <span id="display-amount-{{ $request->quotation->id }}">{{ number_format($initialAmount, 2) }}</span> {{ $request->quotation->currency }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Quotation Details --}}
                                            @if ($request->quotation)
                                            <div class="mt-4 p-4 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg border border-[#0BA6DF]">
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-slate-600 dark:text-slate-400 font-semibold">{{ __('Unit Price') }}:</span>
                                                        <span class="font-bold text-slate-900 dark:text-white ml-2"><span id="display-unit-price-{{ $request->quotation->id }}">{{ number_format($initialUnitPrice, 2) }}</span> {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-600 dark:text-slate-400 font-semibold">{{ __('Commission') }}:</span>
                                                        <span class="font-bold text-slate-900 dark:text-white ml-2">{{ number_format($request->quotation->commission_service, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            {{-- Product Quality Options Selector --}}
                                            @if($request->quotation && $request->quotation->quality_options && count(array_filter($request->quotation->quality_options, fn($opt) => !empty($opt['price']))) > 0)
                                                <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-[#EBEBEB] dark:border-slate-700">
                                                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-2">{{ __('Select Quality') }}</span>
                                                    <div class="flex flex-wrap gap-2" x-data="{ selectedVal: '{{ $firstQuality }}' }">
                                                        @foreach(['low' => __('Low'), 'medium' => __('Medium'), 'good' => __('Good')] as $key => $label)
                                                            @if(!empty($request->quotation->quality_options[$key]['price']))
                                                                @php 
                                                                    $opt = $request->quotation->quality_options[$key]; 
                                                                    $totalQuantity = $request->destinations->sum('quantity');
                                                                    $subtotal = $opt['price'] * $totalQuantity;
                                                                    $optAmount = $subtotal + $request->quotation->commission_service + $request->quotation->delivery_cost_china;
                                                                @endphp
                                                                <button type="button" 
                                                                        @click="selectedVal = '{{ $key }}'; 
                                                                               const checkbox = document.querySelector('input[name=\'selected_quotations[]\'][value=\'{{ $request->quotation->id }}\']');
                                                                               if (checkbox) {
                                                                                   checkbox.dataset.amount = '{{ $optAmount }}';
                                                                               }
                                                                               document.getElementById('display-unit-price-{{ $request->quotation->id }}').textContent = '{{ number_format($opt['price'], 2) }}';
                                                                               document.getElementById('display-amount-{{ $request->quotation->id }}').textContent = '{{ number_format($optAmount, 2) }}';
                                                                               if (typeof window.updateStickyBar === 'function') { window.updateStickyBar(); }
                                                                               document.getElementById('quality-input-{{ $request->quotation->id }}').value = '{{ $key }}';
                                                                               const formInput = document.getElementById('form-quality-input-{{ $request->quotation->id }}');
                                                                               if (formInput) { formInput.value = '{{ $key }}'; }
                                                                               "
                                                                        class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all"
                                                                        :class="selectedVal === '{{ $key }}' ? 'bg-[#EF7722] text-white border-[#EF7722]' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-[#EBEBEB] dark:border-slate-700 hover:border-[#EF7722]/50'">
                                                                    {{ $label }} ({{ number_format($opt['price'], 2) }} {{ $request->quotation->currency }})
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <input type="hidden" id="quality-input-{{ $request->quotation->id }}" class="selected-quality-input-field" data-quotation-id="{{ $request->quotation->id }}" value="{{ $firstQuality }}">
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:pl-4 lg:border-l lg:border-[#EBEBEB] lg:dark:border-slate-700">
                                        <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 border border-[#EBEBEB] dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto lg:w-full whitespace-nowrap">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>{{ __('View Details') }}</span>
                                        </a>
                                        @if ($request->quotation)
                                            <form action="{{ route('client.quotations.accept', $request->quotation) }}" method="POST" class="w-full">
                                                @csrf
                                                <input type="hidden" name="selected_quality" id="form-quality-input-{{ $request->quotation->id }}" value="{{ $firstQuality }}">
                                                <button type="submit" 
                                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg transition-all duration-200 shadow-sm hover:shadow whitespace-nowrap">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ __('Accept Quotation') }}</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    <style>
        /* Enterprise table styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Custom scrollbar for table */
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

        /* Line clamp utility */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    {{-- Sticky Bottom Action Bar --}}
    <div id="sticky-payment-bar" class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-700 shadow-2xl transition-all duration-300 transform translate-y-full opacity-0">
        <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">
                            <span id="selected-count">0</span> {{ __('Quotations Selected') }}
                        </p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            {{ __('Total') }}: <span id="selected-total" class="font-extrabold text-[#EF7722] text-base">0.00</span> <span id="selected-currency" class="font-bold text-slate-700 dark:text-slate-300">USD</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button id="clear-selections-btn" class="flex-1 sm:flex-initial px-4 py-2.5 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-lg transition-colors">
                        {{ __('Clear') }}
                    </button>
                    <a id="bulk-pay-btn" href="#" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ __('Proceed to Bulk Payment') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.quotation-checkbox');
            const stickyBar = document.getElementById('sticky-payment-bar');
            const countSpan = document.getElementById('selected-count');
            const totalSpan = document.getElementById('selected-total');
            const currencySpan = document.getElementById('selected-currency');
            const bulkPayBtn = document.getElementById('bulk-pay-btn');
            const clearBtn = document.getElementById('clear-selections-btn');

            function updateStickyBar() {
                const checked = Array.from(checkboxes).filter(cb => cb.checked);
                
                if (checked.length > 0) {
                    const count = checked.length;
                    let total = 0;
                    let currency = '';

                    checked.forEach(cb => {
                        total += parseFloat(cb.dataset.amount || 0);
                        currency = cb.dataset.currency || 'USD';
                    });

                    countSpan.textContent = count;
                    totalSpan.textContent = total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    currencySpan.textContent = currency;

                    const ids = checked.map(cb => cb.value).join(',');
                    
                    // Retrieve quality selections
                    let qualityQuery = '';
                    checked.forEach(cb => {
                        const qualityInput = document.getElementById(`quality-input-${cb.value}`);
                        if (qualityInput) {
                            qualityQuery += `&qualities[${cb.value}]=${qualityInput.value}`;
                        }
                    });

                    const pathSegments = window.location.pathname.split('/');
                    const locale = pathSegments[1] || 'eng';
                    bulkPayBtn.href = `/${locale}/client/quotations/bulk-payment?ids=${ids}${qualityQuery}`;

                    stickyBar.classList.remove('translate-y-full', 'opacity-0');
                    stickyBar.classList.add('translate-y-0', 'opacity-100');
                } else {
                    stickyBar.classList.remove('translate-y-0', 'opacity-100');
                    stickyBar.classList.add('translate-y-full', 'opacity-0');
                }
            }

            // Expose globally so button clicks can update it
            window.updateStickyBar = updateStickyBar;

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateStickyBar);
            });

            clearBtn.addEventListener('click', function () {
                checkboxes.forEach(cb => cb.checked = false);
                updateStickyBar();
            });
        });
    </script>
</x-app-layout>