<div>
    {{-- Main Card --}}
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
        
        {{-- Control Panel --}}
        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Shipping Rates per Country') }}</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            {{ __('Manage air, sea, and train freight costs globally.') }}
                        </p>
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="relative w-full lg:w-72">
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           placeholder="{{ __('Search country...') }}" 
                           class="w-full pl-9 pr-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white shadow-sm transition-all duration-200">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div wire:loading class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-[#EF7722]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="px-6 py-3 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 text-sm font-semibold border-b border-green-100 dark:border-green-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Country') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Air (Normal)') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Air (Brand)') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Air (Battery)') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Air (Liquid)') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Sea') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Train') }}</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700" wire:loading.class="opacity-50 transition-opacity">
                    @forelse($countries as $country)
                        <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="text-xl">
                                        @if($country->code)
                                            <span class="fi fi-{{ strtolower($country->code) }} border border-slate-200 rounded-sm"></span>
                                        @endif
                                    </div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $country->name }}
                                    </div>
                                </div>
                            </td>
                            {{-- Rates Cells --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $country->shippingFee?->air_normal_fee ? number_format($country->shippingFee->air_normal_fee, 2) . ' ' . ($country->shippingFee->currency ?? 'USD') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $country->shippingFee?->air_brand_fee ? number_format($country->shippingFee->air_brand_fee, 2) . ' ' . ($country->shippingFee->currency ?? 'USD') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $country->shippingFee?->air_battery_fee ? number_format($country->shippingFee->air_battery_fee, 2) . ' ' . ($country->shippingFee->currency ?? 'USD') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $country->shippingFee?->air_liquid_fee ? number_format($country->shippingFee->air_liquid_fee, 2) . ' ' . ($country->shippingFee->currency ?? 'USD') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $country->shippingFee?->sea_fee ? number_format($country->shippingFee->sea_fee, 2) . ' ' . ($country->shippingFee->currency ?? 'USD') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-600 dark:text-slate-300">
                                {{ $country->shippingFee?->train_fee ? number_format($country->shippingFee->train_fee, 2) . ' ' . ($country->shippingFee->currency ?? 'USD') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button wire:click="editCountry({{ $country->id }})" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-xs font-bold rounded-lg transition-colors duration-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ __('Edit') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-full flex items-center justify-center text-[#EF7722] mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">{{ __('No countries found') }}</h3>
                                    <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('Try adjusting your search criteria.') }}</p>
                                </div>
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

    <!-- Edit Modal -->
    <div x-data="{ open: @entangle('showEditModal') }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 aria-hidden="true"
                 @click="open = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-[#EBEBEB] dark:border-slate-700">
                
                <div class="bg-white dark:bg-slate-800">
                    <!-- Modal Header -->
                    <div class="px-8 py-6 border-b border-[#EBEBEB] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-[#EF7722]/10 text-[#EF7722] rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-tight" id="modal-title">
                                    {{ __('Edit Shipping Fees') }}: <span class="text-[#EF7722]">{{ $selectedCountry?->name }}</span>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest">{{ __('All rates are in USD unless specified') }}</p>
                            </div>
                        </div>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body Form -->
                    <div class="px-8 py-8">
                        <form wire:submit.prevent="save" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <!-- Air Categories -->
                                <div class="col-span-full border-l-4 border-[#EF7722] pl-4 py-1 bg-[#EF7722]/5 rounded-r-lg">
                                    <h4 class="text-xs font-bold text-[#EF7722] uppercase tracking-wider">{{ __('Air Freight Rates (per kg)') }}</h4>
                                </div>

                                <!-- Air Normal -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Normal Product') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-sm">$</span>
                                        </div>
                                        <input wire:model="air_normal_fee" type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-mono dark:text-white transition-all">
                                    </div>
                                    @error('air_normal_fee') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Air Brand -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Brand Product') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-sm">$</span>
                                        </div>
                                        <input wire:model="air_brand_fee" type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-mono dark:text-white transition-all">
                                    </div>
                                    @error('air_brand_fee') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Air Battery -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Battery Product') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-sm">$</span>
                                        </div>
                                        <input wire:model="air_battery_fee" type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-mono dark:text-white transition-all">
                                    </div>
                                    @error('air_battery_fee') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Air Liquid -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Liquid Product') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-sm">$</span>
                                        </div>
                                        <input wire:model="air_liquid_fee" type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-mono dark:text-white transition-all">
                                    </div>
                                    @error('air_liquid_fee') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Transport Section Title -->
                                <div class="col-span-full border-l-4 border-indigo-500 pl-4 py-1 bg-indigo-50/10 rounded-r-lg mt-4">
                                    <h4 class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">{{ __('Surface & Bulk Shipping') }}</h4>
                                </div>

                                <!-- Sea -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Sea Freight (per CBM)') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-sm">$</span>
                                        </div>
                                        <input wire:model="sea_fee" type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-mono dark:text-white transition-all">
                                    </div>
                                    @error('sea_fee') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Train -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Train Freight (per kg)') }}</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 text-sm">$</span>
                                        </div>
                                        <input wire:model="train_fee" type="number" step="0.01" 
                                               class="w-full pl-7 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-mono dark:text-white transition-all">
                                    </div>
                                    @error('train_fee') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Settings Title -->
                                <div class="col-span-full border-l-4 border-slate-500 pl-4 py-1 bg-slate-100 dark:bg-slate-700/50 rounded-r-lg mt-4">
                                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Currency & Units') }}</h4>
                                </div>

                                <!-- Currency -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Currency') }}</label>
                                    <input wire:model="currency" type="text" 
                                           class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-bold dark:text-white transition-all uppercase">
                                    @error('currency') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Unit -->
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">{{ __('Weight Unit') }}</label>
                                    <input wire:model="unit" type="text" 
                                           class="w-full px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 focus:border-[#EF7722] focus:ring-1 focus:ring-[#EF7722] rounded-lg text-sm font-bold dark:text-white transition-all">
                                    @error('unit') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end gap-3 pt-8 border-t border-slate-100 dark:border-slate-700">
                                <button type="button" @click="open = false" 
                                        class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl transition-all uppercase tracking-wider">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-xs font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-orange-200 dark:shadow-none uppercase tracking-wider">
                                    <div wire:loading wire:target="save" class="hidden">
                                        <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        </svg>
                                    </div>
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>

