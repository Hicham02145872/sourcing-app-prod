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
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Air (Normal)') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Air (Brand)') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Air (Battery)') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Air (Liquid)') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Sea') }}</th>
                            <th scope="col" class="px-6 py-4 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Train') }}</th>
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
                                            <span class="text-xs font-bold text-slate-800">{{ $country->name }}</span>
                                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $country->code }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- Rate Columns --}}
                                @php
                                    $fee = $country->shippingFee;
                                    $rates = [
                                        $fee?->air_normal_fee,
                                        $fee?->air_brand_fee,
                                        $fee?->air_battery_fee,
                                        $fee?->air_liquid_fee,
                                        $fee?->sea_fee,
                                        $fee?->train_fee
                                    ];
                                @endphp

                                @foreach($rates as $rate)
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-600">
                                        @if($rate)
                                            <span class="text-[10px] text-slate-400 mr-0.5">$</span>{{ number_format($rate, 2) }}
                                        @else
                                            <span class="text-slate-300 font-normal">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button wire:click="editCountry({{ $country->id }})" 
                                            class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all group-hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
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

    {{-- Enterprise Style Modal --}}
    <div x-data="{ open: @entangle('showEditModal') }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-[60] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Overlay --}}
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-200">
                
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-tight">{{ __('Edit Rates') }}: <span class="text-orange-600">{{ $selectedCountry?->name }}</span></h3>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ __('Pricing Precision Update') }}</p>
                        </div>
                    </div>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6">
                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Field Group: Air --}}
                            <div class="col-span-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 block pb-1 mb-3">{{ __('Air Freight ($/kg)') }}</span>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">{{ __('Normal') }}</label>
                                <input wire:model="air_normal_fee" type="number" step="0.01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">{{ __('Brand') }}</label>
                                <input wire:model="air_brand_fee" type="number" step="0.01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">{{ __('Battery') }}</label>
                                <input wire:model="air_battery_fee" type="number" step="0.01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">{{ __('Liquid') }}</label>
                                <input wire:model="air_liquid_fee" type="number" step="0.01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            </div>

                            {{-- Field Group: Bulk --}}
                            <div class="col-span-2 pt-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 block pb-1 mb-3">{{ __('Bulk & Surface') }}</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">{{ __('Sea ($/CBM)') }}</label>
                                <input wire:model="sea_fee" type="number" step="0.01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1.5">{{ __('Train ($/kg)') }}</label>
                                <input wire:model="train_fee" type="number" step="0.01" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-bold text-slate-700 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                            <button type="button" @click="open = false" class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="px-6 py-2 bg-slate-900 hover:bg-orange-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg shadow-sm shadow-slate-200 transition-all flex items-center gap-2">
                                <span wire:loading wire:target="save">
                                    <svg class="animate-spin h-3 w-3" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                {{ __('Validate Update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>

