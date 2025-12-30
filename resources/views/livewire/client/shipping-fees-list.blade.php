<div class="space-y-8 animate-fade-in">
    {{-- Urgent Notification Bar --}}
    <div class="relative group">
        <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-orange-600 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
        <div class="relative bg-white dark:bg-slate-900 ring-1 ring-slate-200 dark:ring-slate-800 rounded-2xl p-1 overflow-hidden shadow-xl">
            <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/20 dark:to-orange-950/20 rounded-xl px-6 py-4 flex items-center gap-5">
                <div class="flex-shrink-0 relative">
                    <div class="absolute inset-0 bg-red-500 rounded-full animate-ping opacity-20"></div>
                    <div class="relative w-12 h-12 bg-red-600 rounded-full flex items-center justify-center shadow-lg shadow-red-200/50">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm md:text-base font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">
                        {{ __('Dynamic Market Pricing Notice') }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ __('Logistics rates fluctuate globally. Our fees are automatically re-calculated every 15 days.') }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <span class="px-3 py-1 bg-white/80 dark:bg-slate-800/80 backdrop-blur rounded-full text-[10px] font-bold text-slate-400 border border-slate-200 dark:border-slate-700 uppercase tracking-widest shadow-sm">
                        {{ __('Next Update: Jan 15') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Header Section with Glassmorphism Search --}}
    <div class="relative py-12 px-8 rounded-[2rem] overflow-hidden bg-slate-900 border border-slate-800 shadow-2xl">
        {{-- Background Decorations --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-[#EF7722] opacity-20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-[#0BA6DF] opacity-20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-grid-white/[0.03] bg-[size:20px_20px]"></div>
        
        <div class="relative flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/10 border border-orange-500/20 rounded-full text-orange-400 text-[10px] font-bold uppercase tracking-widest mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                    </span>
                    {{ __('Verified Rates') }}
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tighter">
                    {{ __('Global') }} <span class="bg-gradient-to-r from-white to-white/40 bg-clip-text text-transparent italic">{{ __('Logistics') }}</span> {{ __('Hub') }}
                </h1>
                <p class="text-slate-400 text-sm md:text-base max-w-xl font-medium leading-relaxed uppercase tracking-tight">
                    {{ __('Real-time estimated rates for sea, air, and rail transport. Built for high-volume enterprise sourcing.') }}
                </p>
            </div>

            <div class="w-full md:w-[450px]">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-2 shadow-2xl">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               placeholder="{{ __('SEARCH DESTINATION COUNTRY...') }}" 
                               class="w-full pl-14 pr-16 py-5 bg-transparent border-none focus:ring-0 text-white placeholder-white/20 text-lg font-black tracking-tighter uppercase transition-all">
                        
                        <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-5 flex items-center">
                            <svg class="animate-spin h-6 w-6 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Advanced Transportation Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @php
            $methods = [
                ['title' => 'Air Freight', 'desc' => 'Time-Critical Economy', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8', 'color' => '#EF7722'],
                ['title' => 'Sea Bulk', 'desc' => 'LCL Cost Optimization', 'icon' => 'M3 7h18M3 12h18M3 17h18 M12 5V2', 'color' => '#0BA6DF'],
                ['title' => 'Rail Connect', 'desc' => 'Belt & Road Silk Road', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6', 'color' => '#10B981']
            ];
        @endphp

        @foreach($methods as $method)
            <div class="relative group cursor-default">
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gradient-to-r from-transparent via-[{{ $method['color'] }}] to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm group-hover:shadow-xl group-hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-[{{ $method['color'] }}] opacity-[0.03] rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-transform duration-500 group-hover:rotate-[10deg] shadow-inner" style="background-color: {{ $method['color'] }}15; color: {{ $method['color'] }}">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $method['icon'] }}"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ __($method['title']) }}</h4>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ __($method['desc']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Data Grid --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden mt-12">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950/50 text-[10px] text-slate-500 dark:text-slate-400 font-black uppercase tracking-[0.2em] border-b border-slate-100 dark:border-slate-800">
                        <th class="px-8 py-6">{{ __('Target Destination') }}</th>
                        <th class="px-6 py-6 text-center bg-slate-100/30 dark:bg-slate-900/10">{{ __('Air (Normal)') }}</th>
                        <th class="px-6 py-6 text-center">{{ __('Air (Brand)') }}</th>
                        <th class="px-6 py-6 text-center bg-slate-100/30 dark:bg-slate-900/10">{{ __('Air (Battery)') }}</th>
                        <th class="px-6 py-6 text-center">{{ __('Air (Liquid)') }}</th>
                        <th class="px-6 py-6 text-center bg-slate-100/30 dark:bg-slate-900/10">{{ __('Sea') }}</th>
                        <th class="px-6 py-6 text-center">{{ __('Rail/Train') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50" wire:loading.class="opacity-30 blur-sm transition-all duration-300">
                    @forelse($countries as $country)
                        <tr wire:key="client-country-{{ $country->id }}" class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all group">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="absolute inset-0 bg-slate-900/5 dark:bg-white/5 rounded-full scale-150 blur opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <span class="fi fi-{{ strtolower($country->code) }} text-2xl rounded shadow-sm border border-slate-100 dark:border-slate-700 relative z-10 transition-transform group-hover:scale-110"></span>
                                    </div>
                                    <div>
                                        <span class="block font-black text-slate-900 dark:text-white uppercase tracking-tighter group-hover:text-orange-500 transition-colors">{{ $country->name }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 dark:text-slate-600 uppercase tracking-widest tracking-tighter">IATA: {{ strtoupper($country->code) }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Rates Cells Utility --}}
                            @php
                                $renderRate = function($val, $unit, $curr, $isOdd = false) {
                                    if (!$val) return '<span class="text-slate-200 dark:text-slate-800 font-bold tracking-widest uppercase text-[10px]">'.__('N/A').'</span>';
                                    $bg = $isOdd ? 'bg-slate-50/50 dark:bg-slate-900/30' : '';
                                    return '<div class="py-1 '.$bg.' rounded-xl transition-all group-hover:scale-105">
                                                <span class="block font-mono font-black text-slate-900 dark:text-white text-lg">'.number_format($val, 2).'</span>
                                                <span class="block text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-tighter">'.$curr.'/'.$unit.'</span>
                                            </div>';
                                };
                            @endphp

                            <td class="px-6 py-6 text-center bg-slate-50/10 dark:bg-slate-900/5">{!! $renderRate($country->shippingFee?->air_normal_fee, $country->shippingFee?->unit ?? 'kg', $country->shippingFee?->currency ?? 'USD', true) !!}</td>
                            <td class="px-6 py-6 text-center">{!! $renderRate($country->shippingFee?->air_brand_fee, $country->shippingFee?->unit ?? 'kg', $country->shippingFee?->currency ?? 'USD') !!}</td>
                            <td class="px-6 py-6 text-center bg-slate-50/10 dark:bg-slate-900/5">{!! $renderRate($country->shippingFee?->air_battery_fee, $country->shippingFee?->unit ?? 'kg', $country->shippingFee?->currency ?? 'USD', true) !!}</td>
                            <td class="px-6 py-6 text-center">{!! $renderRate($country->shippingFee?->air_liquid_fee, $country->shippingFee?->unit ?? 'kg', $country->shippingFee?->currency ?? 'USD') !!}</td>
                            <td class="px-6 py-6 text-center bg-slate-50/10 dark:bg-slate-900/5">{!! $renderRate($country->shippingFee?->sea_fee, 'CBM', $country->shippingFee?->currency ?? 'USD', true) !!}</td>
                            <td class="px-6 py-6 text-center">{!! $renderRate($country->shippingFee?->train_fee, $country->shippingFee?->unit ?? 'kg', $country->shippingFee?->currency ?? 'USD') !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-32 text-center">
                                <div class="max-w-xs mx-auto flex flex-col items-center">
                                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800/50 rounded-[2rem] flex items-center justify-center mb-6 text-slate-200 dark:text-slate-700">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">{{ __('Zero results found') }}</h3>
                                    <p class="text-sm text-slate-400 dark:text-slate-500 font-medium mt-1 leading-relaxed">
                                        {{ __('We couldn\'t find any cargo data matching that destination. Try searching by country name or regional code.') }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($countries->hasPages())
            <div class="px-8 py-6 bg-slate-50 dark:bg-slate-950/30 border-t border-slate-100 dark:border-slate-800">
                {{ $countries->links() }}
            </div>
        @endif
    </div>

    {{-- VIP Support Area --}}
    <div class="relative mt-16 group">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-indigo-600 rounded-[3rem] blur-2xl opacity-10 group-hover:opacity-20 transition duration-1000"></div>
        <div class="relative bg-slate-900 border border-slate-800 rounded-[3rem] p-10 md:p-16 overflow-hidden shadow-2xl">
            {{-- Text Content --}}
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 text-center md:text-left">
                <div class="flex-1">
                    <span class="text-orange-500 text-[10px] font-black uppercase tracking-[0.3em] mb-4 block">{{ __('Strategic Logistics') }}</span>
                    <h3 class="text-3xl md:text-4xl font-black text-white mb-6 leading-none tracking-tight">
                        {{ __('Need an') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600 italic">{{ __('Enterprise-Grade') }}</span> {{ __('Quote?') }}
                    </h3>
                    <p class="text-slate-400 text-sm md:text-base font-medium max-w-xl leading-relaxed">
                        {{ __('For container-level loads, specialized equipment, or fragile goods, our global logistics network is ready to optimize your supply chain costs.') }}
                    </p>
                </div>
                
                <a href="https://wa.me/{{ $socialMediaLinks->whatsapp_number ?? '' }}" 
                   target="_blank" 
                   class="group/btn relative inline-flex items-center gap-4 px-10 py-5 bg-orange-600 text-white font-black text-sm uppercase tracking-widest rounded-2xl transition-all hover:bg-orange-500 hover:scale-105 active:scale-95 shadow-2xl shadow-orange-900/20">
                    <svg class="w-6 h-6 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.94 3.659 1.437 5.634 1.437h.005c6.556 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    {{ __('Open Concierge Support') }}
                </a>
            </div>
            
            {{-- Abstract Shapes --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-600/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl -ml-32 -mb-32"></div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .bg-grid-white {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='white' stroke-opacity='0.1'%3E%3Cpath d='M0 .5H31.5V32'/%3E%3C/svg%3E");
        }
    </style>
</div>

