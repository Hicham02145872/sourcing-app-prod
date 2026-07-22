<div class="space-y-8">
    @error('duplicate_categories')
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ $message }}
        </div>
    @enderror

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
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight shrink-0 mr-2">{{ __('Currency') }}</span>
                        <div wire:ignore class="min-w-0 flex-1">
                            <select
                                id="admin-shipping-fee-currency-{{ $country->id }}"
                                data-initial-currency="{{ $currency }}"
                                autocomplete="off"
                                class="w-full text-sm font-bold text-orange-600 border border-slate-200 rounded-lg bg-white"
                            >
                                @foreach($this->currencies as $code => $label)
                                    <option value="{{ $code }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">{{ __('Units by transport') }}</span>
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <span class="font-bold text-orange-600">{{ __('Air Direct') }}</span>
                            <select wire:model="transportUnits.air_direct" class="text-xs font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0 text-right uppercase">
                                <option value="kg">KG</option>
                                <option value="CBM">CBM</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <span class="font-bold text-blue-600">{{ __('Sea') }}</span>
                            <select wire:model="transportUnits.sea" class="text-xs font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0 text-right uppercase">
                                <option value="CBM">CBM</option>
                                <option value="kg">KG</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between gap-2 text-xs">
                            <span class="font-bold text-green-600">{{ __('Air Indirect') }}</span>
                            <select wire:model="transportUnits.air_indirect" class="text-xs font-bold text-slate-700 bg-transparent border-none focus:ring-0 p-0 text-right uppercase">
                                <option value="kg">KG</option>
                                <option value="CBM">CBM</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">{{ __('Air Direct Visible') }}</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="is_air_direct_visible" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-orange-500"></div>
                        </label>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">{{ __('Sea Visible') }}</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="is_sea_visible" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                        </label>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-tight">{{ __('Air Indirect Visible') }}</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="is_air_indirect_visible" class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                        </label>
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
        <div x-data="{ tab: 'air_direct' }" class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden min-h-[500px] flex flex-col">
        {{-- Header & Tabs --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ __('Detailed Tiered Rates') }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ __('Volume based precision pricing') }}</p>
            </div>
            
            <div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-xl shadow-sm">
                <button type="button" @click="tab = 'air_direct'" :class="tab === 'air_direct' ? 'bg-orange-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    {{ __('Air Direct') }}
                </button>
                <button type="button" @click="tab = 'sea'" :class="tab === 'sea' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17l-10-5-10 5L12 22l10-5z"/><path d="M12 12l10-5-10-5-10 5 10 5z"/><path d="M2 12l10 5 10-5"/></svg>
                    {{ __('Sea') }}
                </button>
                <button type="button" @click="tab = 'air_indirect'" :class="tab === 'air_indirect' ? 'bg-green-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="15" x="3" y="4" rx="2"/><path d="M7 11h10"/><path d="M7 15h10"/><path d="M12 4v1"/><path d="M9 19l-2 2"/><path d="M15 19l2 2"/></svg>
                    {{ __('Air Indirect') }}
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
                    @php
                        $activeCount = collect($itemsData[$type])->where('_deleted', '!=', true)->count();
                        $newCount = collect($itemsData[$type])->where('_deleted', '!=', true)->whereNull('id')->count();
                        $deletedCount = collect($itemsData[$type])->where('_deleted', true)->count();
                    @endphp
                    <div class="px-4 py-3 bg-white border-b border-slate-100 flex justify-end">
                        <div class="mr-auto flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest">
                            <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600">{{ __('Active') }}: {{ $activeCount }}</span>
                            @if($newCount > 0)
                                <span class="px-2 py-1 rounded-md bg-emerald-100 text-emerald-700">{{ __('New') }}: {{ $newCount }}</span>
                            @endif
                            @if($deletedCount > 0)
                                <span class="px-2 py-1 rounded-md bg-red-100 text-red-700">{{ __('To Delete') }}: {{ $deletedCount }}</span>
                            @endif
                        </div>
                        <button
                            type="button"
                            wire:click="addCategory('{{ $type }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg border border-slate-200 text-slate-600 hover:text-orange-600 hover:border-orange-200 hover:bg-orange-50 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Add Category') }}
                        </button>
                    </div>
                    @if($type === 'air_indirect')
                    <div class="px-4 py-3 bg-green-50/50 border-b border-green-100 flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-green-700 uppercase tracking-widest">{{ __('Durée') }} {{ __('Chine') }} → {{ __('Dubaï') }}</span>
                            <input wire:model="china_to_dubai_duration" type="text" class="w-24 text-center text-sm font-bold text-green-700 bg-white border border-green-200 rounded-lg py-1.5 focus:ring-2 focus:ring-green-300 focus:border-green-300 outline-none transition-all" placeholder="ex: 5-7">
                            <select wire:model="china_to_dubai_currency" class="text-xs font-bold text-green-700 bg-white border border-green-200 rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-green-300 focus:border-green-300 outline-none transition-all">
                                @foreach($this->currencies as $code => $label)
                                    <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold text-green-700 uppercase tracking-widest">{{ __('Durée') }} {{ __('Dubaï') }} → {{ $country->name }}</span>
                            <input wire:model="dubai_to_destination_duration" type="text" class="w-24 text-center text-sm font-bold text-green-700 bg-white border border-green-200 rounded-lg py-1.5 focus:ring-2 focus:ring-green-300 focus:border-green-300 outline-none transition-all" placeholder="ex: 3-5">
                            <select wire:model="dubai_to_destination_currency" class="text-xs font-bold text-green-700 bg-white border border-green-200 rounded-lg py-1.5 px-2 focus:ring-2 focus:ring-green-300 focus:border-green-300 outline-none transition-all">
                                @foreach($this->currencies as $code => $label)
                                    <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Item Style / Category') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest bg-slate-100/30">{{ __('Price per') }} {{ strtoupper($transportUnits[$type] ?? 'KG') }}</th>
                                @if($type === 'air_indirect')
                                <th class="px-6 py-4 text-center text-xs font-bold text-orange-600 uppercase tracking-widest bg-orange-50/50">{{ __('Indirect') }} ({{ __('Dubai') }})</th>
                                @endif
                                @if($type !== 'air_indirect')
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Estimated Delay') }}</th>
                                @endif
                                <th class="px-4 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            @foreach($itemsData[$type] as $index => $item)
                                @php
                                    $isDeleted = !empty($item['_deleted']);
                                    $isNew = empty($item['id']);
                                @endphp
                                <tr wire:key="shipping-item-{{ $type }}-{{ $index }}" class="transition-colors group {{ $isDeleted ? 'bg-red-50/70' : 'hover:bg-slate-50/50' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-2 h-2 rounded-full {{ $type === 'air_direct' ? 'bg-orange-500' : ($type === 'sea' ? 'bg-blue-500' : 'bg-green-500') }} opacity-40"></div>
                                            @if($isNew && !$isDeleted)
                                                <span class="shrink-0 px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-widest">{{ __('New') }}</span>
                                            @endif
                                            @if($isDeleted)
                                                <span class="shrink-0 px-2 py-1 rounded-md bg-red-100 text-red-700 text-[10px] font-bold uppercase tracking-widest">{{ __('Deleted') }}</span>
                                            @endif
                                            <input wire:model="itemsData.{{ $type }}.{{ $index }}.item_style" 
                                                   type="text" 
                                                   @disabled($isDeleted)
                                                   class="flex-1 min-w-0 text-sm font-bold text-slate-800 bg-white border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-300 focus:border-slate-300 outline-none transition-all {{ $isDeleted ? 'text-slate-400 line-through bg-slate-50' : '' }}" 
                                                   placeholder="{{ __('Describe style...') }}">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 bg-slate-50/10">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-xs font-bold text-slate-400">{{ $currency }}</span>
                                            <input wire:model="itemsData.{{ $type }}.{{ $index }}.price_per_kg" @disabled($isDeleted) type="number" step="0.01" class="w-32 text-center text-sm font-bold text-slate-600 bg-white border border-slate-100 rounded-lg py-2 focus:ring-2 focus:ring-slate-300 focus:border-slate-300 outline-none transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:line-through">
                                        </div>
                                    </td>
                                    @if($type === 'air_indirect')
                                    <td class="px-6 py-4 bg-orange-50/20">
                                        <div class="flex flex-col items-center justify-center gap-1.5">
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-[10px] font-bold text-orange-500 uppercase">{{ __('China') }}→{{ __('Dubai') }}</span>
                                            </div>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-xs font-bold text-slate-400">{{ $china_to_dubai_currency }}</span>
                                                <input wire:model="itemsData.{{ $type }}.{{ $index }}.price_per_kg_china_to_dubai" @disabled($isDeleted) type="number" step="0.01" class="w-28 text-center text-sm font-bold text-orange-700 bg-white border border-orange-100 rounded-lg py-1.5 focus:ring-2 focus:ring-orange-300 focus:border-orange-300 outline-none transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:line-through" placeholder="-">
                                            </div>
                                            <div class="flex items-center justify-center gap-2 mt-1">
                                                <span class="text-[10px] font-bold text-orange-500 uppercase">{{ __('Dubai') }}→{{ __('Destination') }}</span>
                                            </div>
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-xs font-bold text-slate-400">{{ $dubai_to_destination_currency }}</span>
                                                <input wire:model="itemsData.{{ $type }}.{{ $index }}.price_per_kg_dubai_to_africa" @disabled($isDeleted) type="number" step="0.01" class="w-28 text-center text-sm font-bold text-orange-700 bg-white border border-orange-100 rounded-lg py-1.5 focus:ring-2 focus:ring-orange-300 focus:border-orange-300 outline-none transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:line-through" placeholder="-">
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                                    @if($type !== 'air_indirect')
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <input wire:model="itemsData.{{ $type }}.{{ $index }}.estimation_days" @disabled($isDeleted) type="text" class="w-24 text-center text-sm font-bold text-slate-600 bg-slate-50 border border-slate-100 rounded-lg py-2 focus:ring-2 focus:ring-orange-200 outline-none transition-all disabled:bg-slate-100 disabled:text-slate-400 disabled:line-through" placeholder="e.g. 7-9">
                                            <select wire:model="itemsData.{{ $type }}.{{ $index }}.estimation_unit" @disabled($isDeleted) class="text-xs font-bold text-slate-500 bg-transparent border-none focus:ring-0 p-0 disabled:text-slate-400">
                                                <option value="days">{{ __('Days') }}</option>
                                                <option value="months">{{ __('Months') }}</option>
                                            </select>
                                        </div>
                                    </td>
                                    @endif
                                    <td class="px-4 py-4 text-center">
                                        @if($isDeleted)
                                            <button
                                                type="button"
                                                wire:click="restoreCategory('{{ $type }}', {{ $index }})"
                                                class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase tracking-widest rounded-md border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-colors"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ __('Restore') }}
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                wire:click="removeCategory('{{ $type }}', {{ $index }})"
                                                class="inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold uppercase tracking-widest rounded-md border border-red-100 text-red-500 hover:bg-red-50 transition-colors"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                {{ __('Remove') }}
                                            </button>
                                        @endif
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

@push('scripts')
<script>
(function () {
    function initShippingFeeCurrencyTomSelect() {
        if (typeof TomSelect === 'undefined') return;
        document.querySelectorAll('select[id^="admin-shipping-fee-currency-"]').forEach(function (el) {
            var root = el.closest('[wire\\:id]');
            if (!root) return;
            var comp = Livewire.find(root.getAttribute('wire:id'));
            if (!comp) return;
            if (el.dataset.tsReady === '1' && el.tomselect) {
                var cur = comp.get('currency');
                if (cur && el.tomselect.getValue() !== cur) {
                    el.tomselect.setValue(cur, true);
                }
                return;
            }
            if (el.tomselect) {
                el.tomselect.destroy();
            }
            el.dataset.tsReady = '1';
            var ts = new TomSelect(el, {
                create: false,
                allowEmptyOption: false,
                sortField: { field: 'text', direction: 'asc' },
                placeholder: 'Search currency...',
                onChange: function (value) {
                    comp.set('currency', value);
                },
            });
            var initial = comp.get('currency') || el.getAttribute('data-initial-currency') || 'USD';
            ts.setValue(initial, true);
        });
    }
    function resetShippingFeeCurrencySelects() {
        document.querySelectorAll('select[id^="admin-shipping-fee-currency-"]').forEach(function (el) {
            el.dataset.tsReady = '0';
            if (el.tomselect) {
                el.tomselect.destroy();
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(initShippingFeeCurrencyTomSelect, 150);
    });
    document.addEventListener('livewire:init', function () {
        setTimeout(initShippingFeeCurrencyTomSelect, 50);
    });
    document.addEventListener('livewire:navigated', function () {
        resetShippingFeeCurrencySelects();
        setTimeout(initShippingFeeCurrencyTomSelect, 100);
    });
})();
</script>
@endpush
