<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Create Quotation') }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Generate a professional quotation for sourcing request') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-start">
                    <div class="hidden sm:flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 rounded-lg border border-slate-300 dark:border-slate-700 shadow-sm">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                
                {{-- HEADER --}}
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Quotation Form') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Complete all required fields to generate quotation') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-100 dark:bg-amber-900/50 rounded-lg border border-amber-300 dark:border-amber-700">
                            <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                            <span class="text-xs font-bold text-amber-700 dark:text-amber-300">{{ __('Draft') }}</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.quotations.store') }}" class="p-6 space-y-8">
                    @csrf

                    {{-- SELECTED REQUEST INFORMATION --}}
                    <div class="bg-[#EF7722]/5 rounded-lg border border-[#EF7722]/20 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Selected Sourcing Request') }}</h4>
                        </div>
                        
                        <input type="hidden" name="sourcing_request_id" value="{{ $sourcingRequest->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Product Name') }}</p>
                                <p class="text-sm text-slate-900 dark:text-white font-semibold">{{ $sourcingRequest->product_name }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Category') }}</p>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-[#EF7722]/10 text-[#EF7722]">
                                    {{ $sourcingRequest->category?->name }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Client') }}</p>
                                <p class="text-sm text-slate-900 dark:text-white font-semibold">{{ $sourcingRequest->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- BASIC INFORMATION SECTION --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Basic Information') }}</h4>
                        </div>

                        {{-- CURRENCY SELECTION --}}
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="currency" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                                    {{ __('Currency') }} <span class="text-red-600">*</span>
                                </label>
                                <select id="currency" 
                                    name="currency" 
                                    class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-semibold"
                                    required>
                                    <option value="">{{ __('Select currency') }}</option>
                                    <option value="USD">{{ __('USD - US Dollar') }}</option>
                                    <option value="EUR">{{ __('EUR - Euro') }}</option>
                                    <option value="GBP">{{ __('GBP - British Pound') }}</option>
                                    <option value="MAD">{{ __('MAD - Moroccan Dirham') }}</option>
                                    <option value="JPY">{{ __('JPY - Japanese Yen') }}</option>
                                    <option value="CNY">{{ __('CNY - Chinese Yuan') }}</option>
                                    <option value="CAD">{{ __('CAD - Canadian Dollar') }}</option>
                                    <option value="AUD">{{ __('AUD - Australian Dollar') }}</option>
                                    <option value="AED">{{ __('AED - Dirham Imarati') }}</option>
                                </select>
                                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">{{ __('All monetary values will be calculated in this currency') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- PRICING DETAILS SECTION --}}
                    <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Pricing Details') }}</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- UNIT PRICE --}}
                            <div>
                                <label for="unit_price" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                                    {{ __('Unit Price') }} <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 text-sm font-semibold">$</span>
                                    </div>
                                    <input 
                                        id="unit_price"
                                        type="number"
                                        step="0.01"
                                        name="unit_price"
                                        placeholder="0.00"
                                        class="block w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-semibold"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">{{ __('Price per unit excluding additional fees') }}</p>
                            </div>

                            {{-- COMMISSION SERVICE --}}
                            <div>
                                <label for="commission_service" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                                    {{ __('Commission Service') }} <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 text-sm font-semibold">$</span>
                                    </div>
                                    <input 
                                        id="commission_service"
                                        type="number"
                                        step="0.01"
                                        name="commission_service"
                                        placeholder="0.00"
                                        class="block w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-semibold"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">{{ __('Service commission amount per unit') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- LOGISTICS INFORMATION SECTION --}}
                    <div class="space-y-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Logistics Information') }}</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- UNIT WEIGHT --}}
                            <div>
                                <label for="unit_weight" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                                    {{ __('Unit Weight') }} <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        id="unit_weight"
                                        type="number"
                                        step="0.01"
                                        name="unit_weight"
                                        placeholder="0.00"
                                        class="block w-full px-4 pr-12 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-semibold"
                                        required
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 text-sm font-semibold">g</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">{{ __('Weight per unit in grams') }}</p>
                            </div>

                            {{-- DELIVERY COST IN CHINA --}}
                            <div>
                                <label for="delivery_cost_china" class="block text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                                    {{ __('Shiping Fees') }} <span class="text-red-600">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400 text-sm font-semibold">$</span>
                                    </div>
                                    <input 
                                        id="delivery_cost_china"
                                        type="number"
                                        step="0.01"
                                        name="delivery_cost_china"
                                        placeholder="0.00"
                                        class="block w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-semibold"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-xs text-slate-600 dark:text-slate-400">{{ __('Domestic delivery cost within China') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('admin.quotations.index') }}" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('Cancel') }}
                        </a>
                        
                        <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-[#EF7722]/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Create Quotation') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- INFORMATION PANEL --}}
            <div class="mt-6 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-bold text-slate-900 dark:text-white mb-4">{{ __('Important Information') }}</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 bg-[#EF7722] rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ __('Required Fields:') }}</span> 
                                    {{ __('All fields marked with an asterisk (*) are mandatory and must be completed before submission') }}
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 bg-[#EF7722] rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ __('Accuracy Check:') }}</span> 
                                    {{ __('Please verify all pricing and currency information for accuracy before creating the quotation') }}
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 bg-[#EF7722] rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-sm text-slate-600 dark:text-slate-400">
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ __('Automatic Linking:') }}</span> 
                                    {{ __('The quotation will be automatically linked to the selected sourcing request upon creation') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</x-app-layout>