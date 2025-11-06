<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Create Quotation') }}
                    </h2>
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">{{ __('Generate a new quotation for sourcing request') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ now()->format('F d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                {{-- Header --}}
                <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Quotation Details') }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Complete all required fields to generate quotation') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
                            <div class="w-2.5 h-2.5 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Draft') }}</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.quotations.store') }}" class="p-6 space-y-8">
                    @csrf

                    {{-- Selected Request Information --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-700 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Selected Sourcing Request') }}</h4>
                        </div>
                        
                        <input type="hidden" name="sourcing_request_id" value="{{ $sourcingRequest->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Product Name') }}</p>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold">{{ $sourcingRequest->product_name }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category') }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                    {{ $sourcingRequest->category->name }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Client') }}</p>
                                <p class="text-sm text-gray-900 dark:text-white font-semibold">{{ $sourcingRequest->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Basic Information Section --}}
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Basic Information') }}</h4>
                        </div>

                        {{-- Currency Selection --}}
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="currency" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    {{ __('Currency') }} <span class="text-red-500">*</span>
                                </x-input-label>
                                <select id="currency" 
                                    name="currency" 
                                    class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm"
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
                                </select>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('All monetary values will be calculated in this currency') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing Details Section --}}
                    <div class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Pricing Details') }}</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Unit Price --}}
                            <div>
                                <x-input-label for="unit_price" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    {{ __('Unit Price') }} <span class="text-red-500">*</span>
                                </x-input-label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <x-text-input 
                                        id="unit_price"
                                        type="number"
                                        step="0.01"
                                        name="unit_price"
                                        placeholder="0.00"
                                        class="block w-full pl-8 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Price per unit excluding additional fees') }}</p>
                            </div>

                            {{-- Commission Service --}}
                            <div>
                                <x-input-label for="commission_service" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    {{ __('Commission Service') }} <span class="text-red-500">*</span>
                                </x-input-label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <x-text-input 
                                        id="commission_service"
                                        type="number"
                                        step="0.01"
                                        name="commission_service"
                                        placeholder="0.00"
                                        class="block w-full pl-8 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Service commission amount per unit') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Logistics Information Section --}}
                    <div class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Logistics Information') }}</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Unit Weight --}}
                            <div>
                                <x-input-label for="unit_weight" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    {{ __('Unit Weight') }} <span class="text-red-500">*</span>
                                </x-input-label>
                                <div class="relative">
                                    <x-text-input 
                                        id="unit_weight"
                                        type="number"
                                        step="0.01"
                                        name="unit_weight"
                                        placeholder="0.00"
                                        class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm"
                                        required
                                    />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">g</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Weight per unit in grams') }}</p>
                            </div>

                            {{-- Delivery Cost in China --}}
                            <div>
                                <x-input-label for="delivery_cost_china" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                    {{ __('Delivery Cost (China)') }} <span class="text-red-500">*</span>
                                </x-input-label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                    </div>
                                    <x-text-input 
                                        id="delivery_cost_china"
                                        type="number"
                                        step="0.01"
                                        name="delivery_cost_china"
                                        placeholder="0.00"
                                        class="block w-full pl-8 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700 shadow-sm"
                                        required
                                    />
                                </div>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Domestic delivery cost within China') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.quotations.index') }}" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('Cancel') }}
                        </a>
                        
                        <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-700/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Create Quotation') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Information Panel --}}
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Important Information') }}</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ __('Required Fields:') }}</span> 
                                    {{ __('All fields marked with an asterisk (*) are mandatory and must be completed before submission') }}
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ __('Accuracy Check:') }}</span> 
                                    {{ __('Please verify all pricing and currency information for accuracy before creating the quotation') }}
                                </p>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ __('Automatic Linking:') }}</span> 
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
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth transitions for better UX */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Focus styles for better accessibility */
        input:focus, select:focus, textarea:focus {
            outline: none;
            ring-width: 2px;
            ring-color: rgb(59 130 246);
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .grid-cols-1\.5 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-layout>