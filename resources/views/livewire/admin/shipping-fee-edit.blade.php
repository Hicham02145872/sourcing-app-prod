<div class="space-y-8">
    {{-- Top Section: Quick Summary & Base Fees --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Country Details --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 relative">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <span class="fi fi-{{ strtolower($country->code) }} text-6xl"></span>
                </div>
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-4">{{ __('Location Overview') }}</h3>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center p-2 shadow-inner">
                        <span class="fi fi-{{ strtolower($country->code) }} w-full h-full shadow-sm rounded-sm"></span>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-900 leading-none">{{ $country->name }}</p>
                        <p class="text-sm text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $country->code }}</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">{{ __('Currency') }}</span>
                        <div class="flex items-center gap-2">
                            <select wire:model="currency" class="text-sm font-bold text-orange-600 bg-transparent border-none focus:ring-0 p-0 text-right">
                                <option value="USD">USD ($)</option>
                                <option value="EUR">EUR (€)</option>
                                <option value="MAD">MAD (DH)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">{{ __('Unit') }}</span>
                        <div class="flex items-center gap-2">
                            <select wire:model="unit" class="text-sm font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0 text-right lowercase">
                                <option value="kg">kg</option>
                                <option value="colis">colis</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-2">{{ __('Method Delays') }}</h4>
                    <div class="space-y-2">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">{{ __('Air Delay') }}</label>
                            <input wire:model="air_arrival_time" type="text" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-100 rounded-lg py-2 px-3 focus:ring-2 focus:ring-orange-200 outline-none transition-all" placeholder="e.g. 7-9">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">{{ __('Sea Delay') }}</label>
                            <input wire:model="sea_arrival_time" type="text" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-100 rounded-lg py-2 px-3 focus:ring-2 focus:ring-blue-200 outline-none transition-all" placeholder="e.g. 30-45">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">{{ __('Train Delay') }}</label>
                            <input wire:model="train_arrival_time" type="text" class="w-full text-xs font-bold text-slate-700 bg-slate-50 border border-slate-100 rounded-lg py-2 px-3 focus:ring-2 focus:ring-green-200 outline-none transition-all" placeholder="e.g. 15-20">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configuration Info --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-blue-900 leading-tight mb-1">{{ __('Pricing Engine') }}</h4>
                        <p class="text-xs text-blue-700 leading-relaxed font-medium">
                            {{ __('Define tiered rates for different transport modes. These rates will be used to calculate shipping costs based on weight/volume.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Tiered Rates Section (Injected into the grid) --}}
        <div x-data="{ tab: 'air' }" class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
        {{-- Header & Tabs --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ __('Detailed Tiered Rates') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ __('Volume based precision pricing') }}</p>
            </div>
            
            <div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-xl shadow-sm">
                <button type="button" @click="tab = 'air'" :class="tab === 'air' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    {{ __('Air') }}
                </button>
                <button type="button" @click="tab = 'sea'" :class="tab === 'sea' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17l-10-5-10 5L12 22l10-5z"/><path d="M12 12l10-5-10-5-10 5 10 5z"/><path d="M2 12l10 5 10-5"/></svg>
                    {{ __('Sea') }}
                </button>
                <button type="button" @click="tab = 'train'" :class="tab === 'train' ? 'bg-green-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="15" x="3" y="4" rx="2"/><path d="M7 11h10"/><path d="M7 15h10"/><path d="M12 4v1"/><path d="M9 19l-2 2"/><path d="M15 19l2 2"/></svg>
                    {{ __('Train') }}
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="p-6">
            @foreach($transportTypes as $type)
                <div x-show="tab === '{{ $type }}'" 
                     x-transition:enter="transition-opacity ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Item Style / Category') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest bg-slate-100/30">{{ __('Price per') }} {{ $unit }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @foreach($itemsData[$type] as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-2 h-2 rounded-full {{ $type === 'air' ? 'bg-orange-500' : ($type === 'sea' ? 'bg-blue-500' : 'bg-green-500') }} opacity-40"></div>
                                            <input wire:model="itemsData.{{ $type }}.{{ $index }}.item_style" 
                                                   type="text" 
                                                   class="w-full text-sm font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0 group-hover:text-slate-900 transition-colors" 
                                                   placeholder="{{ __('Describe style...') }}">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 bg-slate-50/10">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-xs font-bold text-slate-400">{{ $currency }}</span>
                                            <input wire:model="itemsData.{{ $type }}.{{ $index }}.price_per_kg" type="number" step="0.01" class="w-32 text-center text-sm font-bold text-slate-600 bg-white border border-slate-100 rounded-lg py-2 focus:ring-2 focus:ring-slate-300 focus:border-slate-300 outline-none transition-all">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        {{-- Footer Save Area --}}
        <div class="px-8 py-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between mt-auto">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                </svg>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Data Persistence Tier') }}</span>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.shipping-fees.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">{{ __('Discard Changes') }}</a>
                <button wire:click="save" class="px-8 py-3 bg-slate-900 hover:bg-orange-600 active:scale-95 text-white text-xs font-extrabold uppercase tracking-widest rounded-xl shadow-lg shadow-slate-200 transition-all flex items-center gap-2">
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    {{ __('Synchronize Infrastructure') }}
                </button>
            </div>
        </div>
    </div>
</div>
