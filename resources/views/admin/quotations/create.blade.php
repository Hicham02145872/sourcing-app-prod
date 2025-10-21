<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-violet-200 dark:border-violet-900 -m-6 p-6 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Create Quotation') }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1.5">Generate a new quotation for sourcing request</p>
                </div>
                <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ now()->format('F d, Y') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <!-- Form Header -->
                    <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quotation Details</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Complete all required fields to proceed</p>
                                </div>
                            </div>
                            <div class="hidden md:block">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300">
                                    Draft
                                </span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.quotations.store') }}" class="space-y-8">
                        @csrf

                        <!-- Section 1: Basic Information -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Basic Information</h4>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Sourcing Request -->
                                <div>
                                    <label for="sourcing_request_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Sourcing Request') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select id="sourcing_request_id" name="sourcing_request_id" 
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        required>
                                        <option value="">Select a sourcing request</option>
                                        @foreach ($sourcingRequests as $request)
                                            <option value="{{ $request->id }}">{{ $request->product_name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Link this quotation to an existing sourcing request</p>
                                </div>

                                <!-- Currency -->
                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Currency') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select id="currency" 
                                        name="currency" 
                                        class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        required>
                                        <option value="">Select currency</option>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <option value="MAD">MAD - Moroccan Dirham</option>
                                        <option value="JPY">JPY - Japanese Yen</option>
                                        <option value="CNY">CNY - Chinese Yuan</option>
                                        <option value="CAD">CAD - Canadian Dollar</option>
                                        <option value="AUD">AUD - Australian Dollar</option>
                                    </select>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">All monetary values will be in this currency</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Pricing Details -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Pricing Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Unit Price -->
                                <div>
                                    <label for="unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Unit Price') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                        </div>
                                        <input id="unit_price" 
                                            class="block w-full pl-8 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700" 
                                            type="number" 
                                            step="0.01"
                                            name="unit_price" 
                                            placeholder="0.00"
                                            required />
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Price per unit excluding fees</p>
                                </div>

                                <!-- Commission Service -->
                                <div>
                                    <label for="commission_service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Commission Service') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                        </div>
                                        <input id="commission_service" 
                                            class="block w-full pl-8 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700" 
                                            type="number" 
                                            step="0.01"
                                            name="commission_service" 
                                            placeholder="0.00"
                                            required />
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Service commission amount</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Logistics Information -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Logistics Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Unit Weight -->
                                <div>
                                    <label for="unit_weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Unit Weight') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="unit_weight" 
                                            class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700" 
                                            type="number" 
                                            step="0.01"
                                            name="unit_weight" 
                                            placeholder="0.00"
                                            required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">g</span>
                                        </div>
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Weight per unit in grams</p>
                                </div>

                                <!-- Delivery Cost in China -->
                                <div>
                                    <label for="delivery_cost_china" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Delivery Cost (China)') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">$</span>
                                        </div>
                                        <input id="delivery_cost_china" 
                                            class="block w-full pl-8 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition-colors text-gray-900 dark:text-white dark:bg-gray-700" 
                                            type="number" 
                                            step="0.01"
                                            name="delivery_cost_china" 
                                            placeholder="0.00"
                                            required />
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Domestic delivery cost within China</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-8 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.quotations.index') }}" 
                                class="inline-flex items-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </a>
                            
                            <button type="submit" 
                                class="inline-flex items-center px-6 py-2.5 bg-violet-600 hover:bg-violet-700 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Create Quotation') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-violet-100 dark:bg-violet-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Important Information</h3>
                        <ul class="mt-2.5 text-sm text-gray-600 dark:text-gray-400 space-y-2 leading-relaxed">
                            <li class="flex items-start">
                                <span class="text-violet-600 dark:text-violet-400 mr-2">•</span>
                                <span>All fields marked with an asterisk (*) are mandatory and must be completed before submission</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-violet-600 dark:text-violet-400 mr-2">•</span>
                                <span>Please verify all pricing and currency information for accuracy before creating the quotation</span>
                            </li>
                            <li class="flex items-start">
                                <span class="text-violet-600 dark:text-violet-400 mr-2">•</span>
                                <span>The quotation will be automatically linked to the selected sourcing request upon creation</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>