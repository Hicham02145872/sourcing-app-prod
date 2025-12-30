<div class="space-y-6">
    {{-- Spam/Notice Banner Mirror --}}
    <div class="relative bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 rounded-r-lg shadow-md p-4 sm:p-5">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <span class="relative flex h-6 w-6">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-6 w-6 bg-amber-100 dark:bg-amber-800 items-center justify-center">
                        <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </span>
            </div>
            <div class="flex-1 min-w-0 pt-0.5">
                <h3 class="text-sm font-bold text-amber-800 dark:text-amber-300 mb-1">
                    {{ __('Dynamic Market Pricing Status') }}
                </h3>
                <p class="text-sm text-amber-700 dark:text-amber-400 leading-relaxed">
                    {{ __('Global logistics rates are currently volatile. Rates are verified and refreshed every 15 days.') }}
                </p>
            </div>
            <div class="hidden md:block">
                <span class="px-3 py-1 bg-amber-100 dark:bg-amber-800/50 rounded-full text-[10px] font-bold text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-700 uppercase tracking-widest">
                    {{ __('Next Update: Jan 15') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Welcome/Hero Banner Mirror --}}
    <div class="relative bg-gradient-to-r from-[#EF7722] to-[#FAA533] rounded-xl shadow-lg overflow-hidden">
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:16px_16px]"></div>
        <div class="relative px-6 py-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-white">
                        {{ __('Unified Logistics Gateway') }}
                    </h3>
                    <p class="text-sm text-white/80 mt-2 max-w-2xl">
                        {{ __('Access institutional-grade freight estimations for air, sea, and rail through our proprietary global logistics engine.') }}
                    </p>
                </div>
                <div class="hidden lg:flex">
                    <svg class="w-16 h-16 text-white opacity-40" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics/Transportation Methods Bar Mirror --}}
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700">
        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Air Freight --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Air Freight') }}</p>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-tighter">{{ __('Time-Critical Economy') }}</p>
                    </div>
                </div>

                {{-- Sea Freight --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18 M12 5V2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Sea Bulk') }}</p>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-tighter">{{ __('LCL Cost Optimization') }}</p>
                    </div>
                </div>

                {{-- Rail Connect --}}
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#10B981]/10 dark:bg-[#10B981]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Rail Connect') }}</p>
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-tighter">{{ __('Silk Road Railway') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Component Mirror --}}
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
        {{-- Control Panel --}}
        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Live Rate Index') }}</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            {{ __('Market Data Base: Unified Sourcing Network') }}
                        </p>
                    </div>
                </div>
                
                <div class="w-full lg:w-auto">
                    <div class="relative flex-1 lg:w-64">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="{{ __('Search destination...') }}" 
                               class="w-full pl-9 pr-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm font-medium">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            {{ __('Destination') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            {{ __('Air (Normal)') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            {{ __('Air (Brand)') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            {{ __('Air (Special)') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            {{ __('Sea (Bulk)') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider border-r-0">
                            {{ __('Rail (Standard)') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700" wire:loading.class="opacity-50">
                    @forelse($countries as $country)
                        <tr wire:key="client-country-{{ $country->id }}" class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="fi fi-{{ strtolower($country->code) }} text-xl rounded-sm shadow-sm border border-[#EBEBEB] dark:border-slate-700"></span>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white uppercase">{{ $country->name }}</div>
                                        <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ strtoupper($country->code) }} REGION</div>
                                    </div>
                                </div>
                            </td>
                            
                            @php
                                $renderRate = function($val, $unit, $curr) {
                                    if (!$val) return '<span class="text-[10px] text-slate-300 dark:text-slate-700 font-bold uppercase">' . __('N/A') . '</span>';
                                    return '<div>
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">' . number_format($val, 2) . '</div>
                                                <div class="text-[10px] text-slate-400 font-semibold">' . $curr . ' / ' . $unit . '</div>
                                            </div>';
                                };
                            @endphp

                            <td class="px-6 py-4 text-center">{!! $renderRate($country->shippingFee?->air_normal_fee, $country->shippingFee?->unit ?? 'KG', $country->shippingFee?->currency ?? 'USD') !!}</td>
                            <td class="px-6 py-4 text-center">{!! $renderRate($country->shippingFee?->air_brand_fee, $country->shippingFee?->unit ?? 'KG', $country->shippingFee?->currency ?? 'USD') !!}</td>
                            <td class="px-6 py-4 text-center">{!! $renderRate($country->shippingFee?->air_battery_fee, $country->shippingFee?->unit ?? 'KG', $country->shippingFee?->currency ?? 'USD') !!}</td>
                            <td class="px-6 py-4 text-center">{!! $renderRate($country->shippingFee?->sea_fee, 'CBM', $country->shippingFee?->currency ?? 'USD') !!}</td>
                            <td class="px-6 py-4 text-center">{!! $renderRate($country->shippingFee?->train_fee, $country->shippingFee?->unit ?? 'KG', $country->shippingFee?->currency ?? 'USD') !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                {{ __('No rates indexed for this selection.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($countries->hasPages())
            <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-t border-[#EBEBEB] dark:border-slate-700">
                {{ $countries->links() }}
            </div>
        @endif
    </div>

    {{-- VIP Support Area Mirror (Stat Card Style) --}}
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700">
        <div class="px-6 py-8 flex flex-col items-center text-center">
            <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('Need an Enterprise-Grade Quote?') }}</h4>
            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-2xl mb-6">
                {{ __('For container-level operations, specialized equipment, or fragile goods, our global logistics network is ready to optimize your supply chain costs.') }}
            </p>
            @if($socialMediaLinks && $socialMediaLinks->whatsapp_number)
                <a href="https://wa.me/{{ $socialMediaLinks->whatsapp_number }}" target="_blank" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.94 3.659 1.437 5.634 1.437h.005c6.556 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    {{ __('Consult Logistics Concierge') }}
                </a>
            @endif
        </div>
    </div>
</div>
