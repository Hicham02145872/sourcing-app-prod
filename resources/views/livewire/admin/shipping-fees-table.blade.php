<div>
    {{-- Main Component Container --}}
    <div class="space-y-6">
        
        {{-- Rates Table Card --}}
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden transition-all duration-200">
            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ __('Shipping Rates per Country') }}</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ __('Logistics Engine Data') }}</p>
                    </div>
                </div>

                <a href="{{ route('admin.shipping-fees.import') }}" class="shrink-0 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    {{ __('Importer Excel') }}
                </a>
                {{-- Dashboard Style Search --}}
                <div class="relative w-full md:w-80 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-orange-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           placeholder="{{ __('Search country or code...') }}" 
                           class="w-full pl-10 pr-10 py-2 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all outline-none">
                    <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Table Area --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Country') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Transportation Status') }}</th>
                            <th scope="col" class="px-6 py-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Currency') }}</th>
                            <th scope="col" class="px-6 py-4 text-right text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($countries as $country)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            @if($country->code)
                                                <span class="fi fi-{{ strtolower($country->code) }} w-6 h-4 shadow-sm rounded-sm"></span>
                                            @else
                                                <div class="w-6 h-4 bg-slate-100 rounded-sm"></div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs font-bold text-slate-800">{{ $country->name }}</span>
                                                @if($country->shippingFee && $country->shippingFee->items->whereNotNull('price_per_kg')->count() > 0)
                                                    <span class="flex h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse" title="{{ __('Rates active') }}"></span>
                                                @endif
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $country->code }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Transportation Status Column --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @php
                                            $airCount = $country->shippingFee?->items->where('transport_type', 'air')->whereNotNull('price_per_kg')->count() ?? 0;
                                            $seaCount = $country->shippingFee?->items->where('transport_type', 'sea')->whereNotNull('price_per_kg')->count() ?? 0;
                                            $trainCount = $country->shippingFee?->items->where('transport_type', 'train')->whereNotNull('price_per_kg')->count() ?? 0;
                                        @endphp

                                        @if($airCount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-orange-50 text-orange-600 border border-orange-100 rounded text-[9px] font-bold uppercase tracking-tighter" title="{{ __('Air Tiered Rates') }}">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                                {{ $airCount }}
                                            </span>
                                        @endif
                                        @if($seaCount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-600 border border-blue-100 rounded text-[9px] font-bold uppercase tracking-tighter" title="{{ __('Sea Tiered Rates') }}">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M22 17l-10-5-10 5L12 22l10-5z"/><path d="M12 12l10-5-10-5-10 5 10 5z"/><path d="M2 12l10 5 10-5"/></svg>
                                                {{ $seaCount }}
                                            </span>
                                        @endif
                                        @if($trainCount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-600 border border-green-100 rounded text-[9px] font-bold uppercase tracking-tighter" title="{{ __('Train Tiered Rates') }}">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect width="18" height="15" x="3" y="4" rx="2"/><path d="M7 11h10"/><path d="M7 15h10"/><path d="M12 4v1"/><path d="M9 19l-2 2"/><path d="M15 19l2 2"/></svg>
                                                {{ $trainCount }}
                                            </span>
                                        @endif
                                        @if($airCount == 0 && $seaCount == 0 && $trainCount == 0)
                                            <span class="text-[9px] text-slate-300 font-bold uppercase tracking-widest italic">{{ __('No rates configured') }}</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="text-xs font-bold text-slate-600">{{ $country->shippingFee->currency ?? '—' }}</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <a href="{{ route('admin.shipping-fees.edit', $country->id) }}" 
                                       wire:navigate
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 text-slate-600 hover:bg-orange-50 hover:text-orange-600 border border-slate-200 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all group-hover:shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center opacity-40">
                                        <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-xs font-bold uppercase tracking-widest">{{ __('No matching countries') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($countries->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $countries->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

