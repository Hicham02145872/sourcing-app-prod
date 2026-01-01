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
                <button wire:click="selectCategory('air')" 
                        class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $selectedCategory === 'air' ? 'bg-[#EF7722] text-white shadow-md scale-105' : 'hover:bg-[#EF7722]/5 text-slate-600 dark:text-slate-400' }}">
                    <div class="w-10 h-10 {{ $selectedCategory === 'air' ? 'bg-white/20' : 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20' }} rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $selectedCategory === 'air' ? 'text-white' : 'text-[#EF7722]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs font-bold uppercase tracking-wide {{ $selectedCategory === 'air' ? 'text-white' : 'text-slate-700 dark:text-slate-300' }}">{{ __('Air Freight') }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-tighter {{ $selectedCategory === 'air' ? 'text-white/80' : 'text-slate-400' }}">{{ __('Time-Critical') }}</p>
                    </div>
                </button>
 
                {{-- Sea Freight --}}
                <button wire:click="selectCategory('sea')" 
                        class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $selectedCategory === 'sea' ? 'bg-[#0BA6DF] text-white shadow-md scale-105' : 'hover:bg-[#0BA6DF]/5 text-slate-600 dark:text-slate-400' }}">
                    <div class="w-10 h-10 {{ $selectedCategory === 'sea' ? 'bg-white/20' : 'bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20' }} rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $selectedCategory === 'sea' ? 'text-white' : 'text-[#0BA6DF]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18 M12 5V2"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs font-bold uppercase tracking-wide {{ $selectedCategory === 'sea' ? 'text-white' : 'text-slate-700 dark:text-slate-300' }}">{{ __('Sea Bulk') }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-tighter {{ $selectedCategory === 'sea' ? 'text-white/80' : 'text-slate-400' }}">{{ __('Cost Optimization') }}</p>
                    </div>
                </button>
 
                {{-- Rail Connect --}}
                <button wire:click="selectCategory('train')" 
                        class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $selectedCategory === 'train' ? 'bg-[#10B981] text-white shadow-md scale-105' : 'hover:bg-[#10B981]/5 text-slate-600 dark:text-slate-400' }}">
                    <div class="w-10 h-10 {{ $selectedCategory === 'train' ? 'bg-white/20' : 'bg-[#10B981]/10 dark:bg-[#10B981]/20' }} rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 {{ $selectedCategory === 'train' ? 'text-white' : 'text-[#10B981]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6"/>
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="text-xs font-bold uppercase tracking-wide {{ $selectedCategory === 'train' ? 'text-white' : 'text-slate-700 dark:text-slate-300' }}">{{ __('Rail Connect') }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-tighter {{ $selectedCategory === 'train' ? 'text-white/80' : 'text-slate-400' }}">{{ __('Silk Road Railway') }}</p>
                    </div>
                </button>
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
            @if($selectedCategory)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                        <thead class="bg-slate-100 dark:bg-slate-900 shadow-sm transition-all border-b-2 border-[#EF7722]/20">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-widest min-w-[200px]">
                                    {{ __('DESTINATION') }}
                                </th>
                                @foreach($itemStyles as $style)
                                    <th scope="col" class="px-4 py-4 text-center text-[10px] font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-widest max-w-[120px] whitespace-normal">
                                        {{ $style }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700" wire:loading.class="opacity-50">
                            @forelse($countries as $country)
                                <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <span class="fi fi-{{ strtolower($country->code) }} text-sm rounded-sm shadow-sm"></span>
                                            <button wire:click="selectCountry({{ $country->id }})" class="text-xs font-black text-slate-900 dark:text-white uppercase hover:text-[#EF7722] transition-colors border-b border-dotted border-slate-300">
                                                {{ $country->name }}
                                            </button>
                                        </div>
                                    </td>
                                    

                                    @foreach($itemStyles as $style)
                                        <td class="px-4 py-4 text-center">
                                            @php
                                                $item = $country->shippingFee?->items
                                                    ->where('transport_type', $selectedCategory)
                                                    ->firstWhere('item_style', $style);
                                            @endphp
                                            @if($item && $item->price_per_kg)
                                                <div class="inline-flex flex-col items-center">
                                                    <span class="text-[11px] font-black text-slate-900 dark:text-white">{{ number_format($item->price_per_kg, 2) }}</span>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase">{{ $country->shippingFee->currency ?? 'USD' }} / {{ strtoupper($country->shippingFee->unit ?? 'KG') }}</span>
                                                    @if($item->estimation_days)
                                                        <span class="mt-1 text-[8px] font-black text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/20 px-1.5 py-0.5 rounded shadow-sm">
                                                            {{ $item->estimation_days }} {{ __($item->estimation_unit ?? 'days') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-slate-300 dark:text-slate-600">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($itemStyles) + 2 }}" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs font-bold uppercase tracking-widest">{{ __('No rates indexed for this selection.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                    <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                {{ __('Destination') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                {{ __('Available Transport Modes') }}
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
                                            <div class="text-sm font-bold text-slate-900 dark:text-white uppercase">
                                                <button wire:click="selectCountry({{ $country->id }})" class="hover:text-[#EF7722] transition-colors text-left">
                                                    {{ $country->name }}
                                                </button>
                                            </div>
                                            <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">{{ strtoupper($country->code) }} REGION</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @php
                                            $airCount = $country->shippingFee?->items->where('transport_type', 'air')->whereNotNull('price_16_49')->count() ?? 0;
                                            $seaCount = $country->shippingFee?->items->where('transport_type', 'sea')->whereNotNull('price_16_49')->count() ?? 0;
                                            $trainCount = $country->shippingFee?->items->where('transport_type', 'train')->whereNotNull('price_16_49')->count() ?? 0;
                                        @endphp

                                        @if($airCount > 0)
                                            <button wire:click="selectCategory('air')" class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 border border-orange-100 dark:border-orange-800 rounded-full text-[10px] font-bold uppercase tracking-wide hover:bg-orange-100 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                                {{ __('Air') }}
                                            </button>
                                        @endif
                                        @if($seaCount > 0)
                                            <button wire:click="selectCategory('sea')" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800 rounded-full text-[10px] font-bold uppercase tracking-wide hover:bg-blue-100 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 17l-10-5-10 5L12 22l10-5z"/><path d="M12 12l10-5-10-5-10 5 10 5z"/><path d="M2 12l10 5 10-5"/></svg>
                                                {{ __('Sea') }}
                                            </button>
                                        @endif
                                        @if($trainCount > 0)
                                            <button wire:click="selectCategory('train')" class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-800 rounded-full text-[10px] font-bold uppercase tracking-wide hover:bg-green-100 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="15" x="3" y="4" rx="2"/><path d="M7 11h10"/><path d="M7 15h10"/><path d="M12 4v1"/><path d="M9 19l-2 2"/><path d="M15 19l2 2"/></svg>
                                                {{ __('Train') }}
                                            </button>
                                        @endif
                                        @if($airCount == 0 && $seaCount == 0 && $trainCount == 0)
                                            <span class="text-[10px] text-slate-300 dark:text-slate-600 font-bold uppercase tracking-widest italic">{{ __('Rates Pending') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-12 text-center text-slate-500">
                                    {{ __('No rates indexed for this selection.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
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
    @if($selectedCountry)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
             wire:click.self="closeCountryDetails">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl transform transition-all sm:w-full sm:max-w-4xl max-h-[90vh] flex flex-col overflow-hidden border border-[#EBEBEB] dark:border-slate-700">
                
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 flex items-center justify-between bg-slate-50 dark:bg-slate-900/50">
                    <div class="flex items-center gap-3">
                        <span class="fi fi-{{ strtolower($selectedCountry->code) }} text-2xl rounded shadow-sm"></span>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white uppercase">{{ $selectedCountry->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Comprehensive Logistics Profile') }}</p>
                        </div>
                    </div>
                    <button wire:click="closeCountryDetails" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-8">
                    @foreach(['air', 'sea', 'train'] as $type)
                        @php
                            $items = $selectedCountry->shippingFee?->items->where('transport_type', $type);
                        @endphp
                        
                        @if($items && $items->count() > 0)
                            <div class="rounded-xl border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                                <div class="bg-slate-50 dark:bg-slate-900/30 px-4 py-3 border-b border-[#EBEBEB] dark:border-slate-700 flex items-center gap-2">
                                    <div class="p-1.5 rounded-md 
                                        {{ $type === 'air' ? 'bg-orange-100 text-orange-600' : '' }}
                                        {{ $type === 'sea' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $type === 'train' ? 'bg-green-100 text-green-600' : '' }}">
                                        @if($type === 'air')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        @elseif($type === 'sea')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 17l-10-5-10 5L12 22l10-5z"/><path d="M12 12l10-5-10-5-10 5 10 5z"/><path d="M2 12l10 5 10-5"/></svg>
                                        @elseif($type === 'train')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="15" x="3" y="4" rx="2"/><path d="M7 11h10"/><path d="M7 15h10"/><path d="M12 4v1"/><path d="M9 19l-2 2"/><path d="M15 19l2 2"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-slate-800 dark:text-slate-200 uppercase text-sm">{{ ucfirst($type) }} {{ __('Freight') }}</h4>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                    <thead class="bg-white dark:bg-slate-800">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">{{ __('Item Style') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">{{ __('Delay') }}</th>
                                            <th class="px-4 py-3 text-center text-xs font-extrabold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">{{ __('Price / ') }}{{ strtoupper($selectedCountry->shippingFee->unit ?? 'KG') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#EBEBEB] dark:divide-slate-700 bg-white dark:bg-slate-800">
                                        @foreach($items as $item)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                                <td class="px-4 py-3 text-xs font-bold text-slate-700 dark:text-slate-300">{{ $item->item_style }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    @if($item->estimation_days)
                                                        <span class="text-[10px] font-black text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 px-2 py-0.5 rounded">
                                                            {{ $item->estimation_days }} {{ __($item->estimation_unit ?? 'days') }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-300 dark:text-slate-600">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-700">
                                                        <span class="text-[9px] font-bold text-slate-400">{{ $selectedCountry->shippingFee->currency ?? 'USD' }}</span>
                                                        <span class="text-xs font-black text-slate-900 dark:text-white">{{ $item->price_per_kg ? number_format($item->price_per_kg, 2) : '-' }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach

                    @if((!$selectedCountry->shippingFee) || ($selectedCountry->shippingFee->items->count() == 0))
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-white">{{ __('No Shipping Data') }}</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('We do not have indexed rates for this country yet.') }}</p>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-[#EBEBEB] dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end">
                    <button wire:click="closeCountryDetails" class="px-4 py-2 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg shadow-sm text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
