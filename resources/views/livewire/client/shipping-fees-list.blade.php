<div>
    {{-- Red Banner: Shipping fees update notice --}}
    <div class="relative bg-gradient-to-r from-red-600 to-red-500 rounded-xl shadow-lg overflow-hidden mb-6 group">
        <div class="absolute inset-0 bg-grid-white/[0.1] bg-[size:16px_16px]"></div>
        <div class="relative px-6 py-4 flex items-center gap-4">
            <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm md:text-base font-bold text-white uppercase tracking-wider">
                    {{ __('Attention: Shipping fees change every 15 days') }}
                </h3>
                <p class="text-xs text-white/80">
                    {{ __('Our rates are updated periodically to reflect real-time market rates.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Welcome/Header Banner --}}
    <div class="relative bg-gradient-to-r from-[#EF7722] to-[#FAA533] rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:16px_16px]"></div>
        <div class="relative px-6 py-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ __('Global Shipping Rates') }}</h1>
                    <p class="text-white/80 text-sm max-w-2xl">
                        {{ __('Current estimated rates per kg/CBM for all supported destinations. Search for your target country below.') }}
                    </p>
                </div>
                
                <div class="relative group w-full md:w-auto">
                    <input type="text" wire:model.live="search"
                           placeholder="{{ __('Search by country...') }}" 
                           class="w-full md:w-80 pl-10 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-sm text-white placeholder-white/60 focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all backdrop-blur-md shadow-sm">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-white/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Shipping Methods Legend Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Air Card --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-[#EBEBEB] dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-xl flex items-center justify-center text-[#EF7722]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-tight">{{ __('By Air') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('Fastest delivery, priced per kg') }}</p>
                </div>
            </div>
        </div>
        
        {{-- Sea Card --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-[#EBEBEB] dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-xl flex items-center justify-center text-[#FAA533]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5V2l-2 2m2-2l2 2"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-tight">{{ __('By Sea') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('Bulk shipping, priced per CBM') }}</p>
                </div>
            </div>
        </div>

        {{-- Train Card --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-[#EBEBEB] dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-xl flex items-center justify-center text-[#0BA6DF]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17h5m6 0h5"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white uppercase tracking-tight">{{ __('By Train') }}</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ __('Eco-friendly alternative transport') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Table Section --}}
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-xl border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#EBEBEB] dark:bg-slate-900/50 text-slate-700 dark:text-slate-300 font-bold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">{{ __('Destination') }}</th>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 text-center">{{ __('Air (Normal)') }}</th>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 text-center">{{ __('Air (Brand)') }}</th>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 text-center">{{ __('Air (Battery)') }}</th>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 text-center">{{ __('Air (Liquid)') }}</th>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 text-center">{{ __('Sea') }}</th>
                        <th class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 text-center">{{ __('Train') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBEBEB] dark:divide-slate-700/50" wire:loading.class="opacity-50">
                    @forelse($countries as $country)
                        <tr wire:key="country-{{ $country->id }}" class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors group">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="fi fi-{{ strtolower($country->code) }} text-lg rounded-sm shadow-sm border border-[#EBEBEB] dark:border-slate-600"></span>
                                    <span class="font-bold text-slate-900 dark:text-white group-hover:text-[#EF7722] transition-colors uppercase">{{ $country->name }}</span>
                                </div>
                            </td>
                            
                            {{-- Air Normal --}}
                            <td class="px-6 py-4 text-center">
                                @if($country->shippingFee?->air_normal_fee)
                                    <span class="font-bold text-slate-900 dark:text-white text-base">{{ number_format($country->shippingFee->air_normal_fee, 2) }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold block">{{ $country->shippingFee->currency }}/{{ $country->shippingFee->unit }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">--</span>
                                @endif
                            </td>

                            {{-- Air Brand --}}
                            <td class="px-6 py-4 text-center">
                                @if($country->shippingFee?->air_brand_fee)
                                    <span class="font-bold text-slate-900 dark:text-white text-base">{{ number_format($country->shippingFee->air_brand_fee, 2) }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold block">{{ $country->shippingFee->currency }}/{{ $country->shippingFee->unit }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">--</span>
                                @endif
                            </td>

                            {{-- Air Battery --}}
                            <td class="px-6 py-4 text-center">
                                @if($country->shippingFee?->air_battery_fee)
                                    <span class="font-bold text-slate-900 dark:text-white text-base">{{ number_format($country->shippingFee->air_battery_fee, 2) }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold block">{{ $country->shippingFee->currency }}/{{ $country->shippingFee->unit }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">--</span>
                                @endif
                            </td>

                            {{-- Air Liquid --}}
                            <td class="px-6 py-4 text-center">
                                @if($country->shippingFee?->air_liquid_fee)
                                    <span class="font-bold text-slate-900 dark:text-white text-base">{{ number_format($country->shippingFee->air_liquid_fee, 2) }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold block">{{ $country->shippingFee->currency }}/{{ $country->shippingFee->unit }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">--</span>
                                @endif
                            </td>

                            {{-- Sea --}}
                            <td class="px-6 py-4 text-center">
                                @if($country->shippingFee?->sea_fee)
                                    <span class="font-bold text-slate-900 dark:text-white text-base">{{ number_format($country->shippingFee->sea_fee, 2) }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold block">{{ $country->shippingFee->currency }}/CBM</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">--</span>
                                @endif
                            </td>

                            {{-- Train --}}
                            <td class="px-6 py-4 text-center">
                                @if($country->shippingFee?->train_fee)
                                    <span class="font-bold text-slate-900 dark:text-white text-base">{{ number_format($country->shippingFee->train_fee, 2) }}</span>
                                    <span class="text-[10px] text-slate-500 font-bold block">{{ $country->shippingFee->currency }}/{{ $country->shippingFee->unit }}</span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600">--</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-[#EF7722]/10 rounded-full flex items-center justify-center mb-4 text-[#EF7722]">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('No rates available') }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs">{{ __('We couldn\'t find any shipping rates matching your search. Try a different country.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Support Footer CTA --}}
    <div class="mt-8 relative overflow-hidden bg-gradient-to-r from-slate-900 to-slate-800 dark:from-slate-800 dark:to-slate-700 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none p-8 text-white border border-slate-700">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-[#EF7722]/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-[#0BA6DF]/20 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-2">{{ __('Need a custom quotation?') }}</h3>
                <p class="text-slate-400 text-sm max-w-xl">
                    {{ __('For large volumes, fragile items, or special commodities not listed here, our logistical experts are ready to assist you personally.') }}
                </p>
            </div>
            <a href="https://wa.me/{{ $socialMediaLinks->whatsapp_number ?? '' }}" target="_blank" class="px-8 py-3 bg-[#EF7722] hover:bg-[#FAA533] text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg shadow-[#EF7722]/20 whitespace-nowrap">
                {{ __('Contact Support via WhatsApp') }}
            </a>
        </div>
    </div>
</div>
