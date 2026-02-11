<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <!-- Pen/Document Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Create Quotation') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="hover:text-slate-700">{{ __('Requests') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('New Quote') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded border border-slate-200">
                            <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-orange-500"></span>
                            <span class="text-xs font-semibold text-slate-600">{{ __('Draft Mode') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <form method="POST" action="{{ route('admin.quotations.store') }}" enctype="multipart/form-data">
                @csrf
                
                <!-- Section 1: Selected Request Summary -->
                <div class="bg-orange-50 rounded-lg border border-orange-200 shadow-sm p-5 mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-orange-100 rounded-md text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-orange-900 uppercase tracking-wide">{{ __('Selected Sourcing Request') }}</h3>
                    </div>

                    <input type="hidden" name="sourcing_request_id" value="{{ $sourcingRequest->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600/70 mb-1">{{ __('Request ID') }}</p>
                            <p class="text-sm font-mono font-bold text-orange-600">#{{ $sourcingRequest->display_id }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600/70 mb-1">{{ __('Product Name') }}</p>
                            <p class="text-sm font-bold text-slate-800">{{ $sourcingRequest->product_name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600/70 mb-1">{{ __('Category') }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-white text-orange-600 border border-orange-200 shadow-sm">
                                {{ $sourcingRequest->category?->name }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600/70 mb-1">{{ __('Client') }}</p>
                            <div class="flex items-center gap-2">
                                <div class="h-5 w-5 rounded-full bg-orange-200 flex items-center justify-center text-[10px] font-bold text-orange-700">
                                    {{ substr($sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <p class="text-sm font-medium text-slate-800">{{ $sourcingRequest->user->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Main Form -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Quotation Details') }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Please fill in all financial and logistical data accurately.') }}</p>
                    </div>

                    <div class="p-6 space-y-8">
                        
                        <!-- Subsection: Basic & Currency -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Currency Settings') }}</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="currency" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Currency') }} <span class="text-red-500">*</span></label>
                                    <select id="currency" name="currency" required
                                        class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
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
                                </div>

                                <div>
                                    <label for="actual_sourcing_location" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Actual Sourcing Location') }} <span class="text-red-500">*</span></label>
                                    <select id="actual_sourcing_location" name="actual_sourcing_location" required
                                        class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors capitalize">
                                        <option value="china" {{ old('actual_sourcing_location', $sourcingRequest->sourcing_location) == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                        <option value="dubai" {{ old('actual_sourcing_location', $sourcingRequest->sourcing_location) == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                    </select>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Defaults to the requested location.') }}</p>
                                </div>
                            </div>

                            <!-- Sourcing Note (Shown only when alternative location selected) -->
                            <div id="sourcing_note_container" class="{{ old('actual_sourcing_location', $sourcingRequest->sourcing_location) != $sourcingRequest->sourcing_location ? '' : 'hidden' }} mt-6 p-4 bg-orange-50 border border-orange-100 rounded-lg">
                                <label for="sourcing_note" class="block text-[10px] font-bold text-orange-600 uppercase mb-2">
                                    {{ __('Note about Alternative Sourcing') }}
                                </label>
                                <textarea id="sourcing_note" name="sourcing_note" rows="3"
                                    class="block w-full px-3 py-2 text-sm bg-white border border-orange-200 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                    placeholder="{{ __('Explain why this location was chosen and any impact on delivery...') }}">{{ old('sourcing_note') }}</textarea>
                                <p class="mt-2 text-[10px] text-orange-500/80 italic">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('This note will be visible to the client to help them understand the change.') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Subsection: Real Quality Image -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Real Product Quality Image') }}</h4>
                                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded animate-pulse">{{ __('Action Required') }}</span>
                            </div>

                            <div class="p-4 bg-red-50/50 border border-red-100 rounded-lg">
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div class="space-y-2">
                                                <label for="real_product_image" class="block text-[10px] font-bold text-red-600 uppercase">
                                                    {{ __('Featured Product Photo') }} <span class="text-red-400 font-normal">({{ __('Shows on dashboard/refunds') }})</span>
                                                </label>
                                                <div class="relative group">
                                                    <input type="file" name="real_product_image" id="real_product_image" accept="image/*"
                                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer bg-white border border-slate-200 p-2 rounded-md">
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label for="media_files" class="block text-[10px] font-bold text-slate-600 uppercase">
                                                    {{ __('Additional Photos & Videos') }}
                                                </label>
                                                <div class="relative group">
                                                    <input type="file" name="media_files[]" id="media_files" accept="image/*,video/*" multiple
                                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer bg-white border border-slate-200 p-2 rounded-md">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-[10px] text-red-500/80 font-medium italic">
                                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Upload photos and videos to help the client verify the actual product quality. Max size: 15MB per file. Drag & drop supported.') }}
                                        </p>
                                    </div>
                                    <div id="image-preview-container" class="hidden">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">{{ __('Preview') }}</p>
                                        <div class="h-24 w-24 rounded-lg border-2 border-red-200 border-dashed overflow-hidden bg-white shadow-sm">
                                            <img id="image-preview" src="#" alt="Preview" class="h-full w-full object-cover">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subsection: Pricing -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Financial Details') }}</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="unit_price" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Unit Price') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="unit_price" id="unit_price" required placeholder="0.00"
                                            class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Price per unit excluding fees') }}</p>
                                </div>

                                <div>
                                    <label for="commission_service" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Service Commission') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="commission_service" id="commission_service" required placeholder="0.00"
                                            class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Commission amount per unit') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subsection: Logistics -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Logistics') }}</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="unit_weight" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Unit Weight') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" name="unit_weight" id="unit_weight" required placeholder="0.00"
                                            class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                            <select name="weight_unit" class="h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-slate-500 sm:text-xs font-bold rounded-md focus:ring-0 focus:border-transparent">
                                                <option value="g">g</option>
                                                <option value="kg">kg</option>
                                                <option value="colis">{{ __('package') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Weight in grams (g)') }}</p>
                                </div>

                                <div>
                                    <label for="delivery_cost_china" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Shipping Fees') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="delivery_cost_china" id="delivery_cost_china" required placeholder="0.00"
                                            class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Domestic delivery cost') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subsection: Financial Estimation (Optional) -->
                        <div>
                            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Cost Estimation') }}</h4>
                                    <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-semibold rounded">{{ __('Optional') }}</span>
                                </div>
                                <button type="button" id="toggle-estimates" class="text-xs text-slate-500 hover:text-slate-700 flex items-center gap-1">
                                    <span id="toggle-text">{{ __('Show') }}</span>
                                    <svg id="toggle-icon" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>

                            <div id="estimates-section" class="hidden space-y-4">
                                <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                    <p class="text-xs text-blue-800">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Estimate costs to analyze profitability before sending quotation to client') }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Estimated Product Cost (UNIT) -->
                                    <div>
                                        <label for="estimated_product_cost" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                            {{ __('Estimated Unit Product Cost') }}
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                            </div>
                                            <input type="number" step="0.01" name="estimated_product_cost" id="estimated_product_cost" placeholder="0.00"
                                                class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium border-l-4 border-l-orange-400"
                                                oninput="calculateEstimatedProfit()">
                                        </div>
                                        @php $totalQuantity = $sourcingRequest->destinations->sum('quantity'); @endphp
                                        <p class="mt-1 text-[10px] text-slate-400">
                                            {{ __('Purchase cost per unit for') }} <strong class="text-slate-600">{{ $totalQuantity }}</strong> {{ __('units') }}
                                        </p>
                                        <div id="est-total-cost-preview" class="text-[10px] text-orange-600 font-bold ml-1"></div>
                                    </div>

                                    <!-- Estimated Shipping Cost (TOTAL) -->
                                    <div>
                                        <label for="estimated_shipping_cost" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                            {{ __('Total Estimated Shipping') }}
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                            </div>
                                            <input type="number" step="0.01" name="estimated_shipping_cost" id="estimated_shipping_cost" placeholder="0.00"
                                                class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium"
                                                oninput="calculateEstimatedProfit()">
                                        </div>
                                        <p class="mt-1 text-[10px] text-slate-400">{{ __('Total logistics costs') }}</p>
                                    </div>

                                    <!-- Estimated Other Costs (TOTAL) -->
                                    <div>
                                        <label for="estimated_other_costs" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                            {{ __('Total Other Costs') }}
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                            </div>
                                            <input type="number" step="0.01" name="estimated_other_costs" id="estimated_other_costs" placeholder="0.00"
                                                class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium"
                                                oninput="calculateEstimatedProfit()">
                                        </div>
                                        <p class="mt-1 text-[10px] text-slate-400">{{ __('Total customs, taxes, etc.') }}</p>
                                    </div>
                                </div>

                                <!-- Estimated Profit Display -->
                                <div id="profit-preview" class="hidden mt-4 p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg border border-slate-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-slate-600 uppercase">{{ __('Estimated Net Profit (Total)') }}</span>
                                        <span id="estimated-profit-amount" class="text-xl font-bold text-slate-900">$0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-slate-500 mb-2">
                                        <span>{{ __('Profit Margin') }}</span>
                                        <span id="estimated-profit-margin" class="font-semibold">0%</span>
                                    </div>
                                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div id="profit-margin-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p id="margin-warning" class="mt-2 text-xs hidden"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.quotations.index') }}" 
                           class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium rounded transition-colors shadow-sm">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            {{ __('Create Quotation') }}
                        </button>
                    </div>
                </div>
            </form>

            <!-- Info Box -->
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                         <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Important Information') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>{{ __('Fields marked with * are mandatory.') }}</li>
                                <li>{{ __('Verify pricing accuracy before submission.') }}</li>
                                <li>{{ __('Quotation will be automatically linked to the sourcing request.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Currency signs map
        const currencySymbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'MAD': 'MAD',
            'JPY': '¥',
            'CNY': '¥',
            'CAD': 'CA$',
            'AUD': 'A$',
            'AED': 'AED'
        };

        function getSelectedCurrencySymbol() {
            const currency = document.getElementById('currency').value;
            return currencySymbols[currency] || '$';
        }

        // Toggle estimates section
        document.getElementById('toggle-estimates').addEventListener('click', function() {
            const section = document.getElementById('estimates-section');
            const icon = document.getElementById('toggle-icon');
            const text = document.getElementById('toggle-text');
            
            section.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
            text.textContent = section.classList.contains('hidden') ? '{{ __("Show") }}' : '{{ __("Hide") }}';
        });

        // Update currency symbols across the page
        document.getElementById('currency').addEventListener('change', function() {
            const symbol = getSelectedCurrencySymbol();
            const elements = document.querySelectorAll('.currency-symbol');
            elements.forEach(el => {
                el.textContent = symbol;
            });
            calculateEstimatedProfit();
        });

        // Calculate estimated profit in real-time
        function calculateEstimatedProfit() {
            const totalQuantity = {{ $sourcingRequest->destinations->sum('quantity') }};
            const unitPrice = parseFloat(document.querySelector('[name="unit_price"]')?.value || 0);
            const commission = parseFloat(document.querySelector('[name="commission_service"]')?.value || 0);
            const deliveryCost = parseFloat(document.querySelector('[name="delivery_cost_china"]')?.value || 0);
            
            const unitProductCost = parseFloat(document.getElementById('estimated_product_cost')?.value || 0);
            const shippingCost = parseFloat(document.getElementById('estimated_shipping_cost')?.value || 0);
            const otherCosts = parseFloat(document.getElementById('estimated_other_costs')?.value || 0);

            const symbol = getSelectedCurrencySymbol();

            // Calculate Total Cost Preview (for user feedback)
            const totalCostPreview = document.getElementById('est-total-cost-preview');
            const estimatedTotalProductCost = unitProductCost * totalQuantity;
            
            if (unitProductCost > 0 && totalQuantity > 0) {
                totalCostPreview.textContent = `{{ __('Scale Total') }}: ~${symbol}${estimatedTotalProductCost.toFixed(2)}`;
            } else {
                totalCostPreview.textContent = '';
            }

            // Calculate TOTALS
            // Revenue = Total
            const totalRevenue = (unitPrice * totalQuantity) + commission + deliveryCost;
            
            // Costs = (Unit Cost * Qty) + Shipping + Others
            const totalCosts = estimatedTotalProductCost + shippingCost + otherCosts;
            
            const profit = totalRevenue - totalCosts;
            const margin = totalRevenue > 0 ? (profit / totalRevenue) * 100 : 0;

            // Show/hide profit preview
            const profitPreview = document.getElementById('profit-preview');
            if (unitProductCost > 0 || shippingCost > 0 || otherCosts > 0) {
                profitPreview.classList.remove('hidden');
                
                // Update values
                document.getElementById('estimated-profit-amount').textContent = 
                    symbol + profit.toFixed(2);
                document.getElementById('estimated-profit-margin').textContent = 
                    margin.toFixed(1) + '%';
                
                // Update progress bar
                const bar = document.getElementById('profit-margin-bar');
                bar.style.width = Math.max(0, Math.min(100, margin)) + '%';
                
                // Color coding and warnings
                const warningEl = document.getElementById('margin-warning');
                if (margin < 10) {
                    bar.className = 'h-full bg-red-500 transition-all duration-300';
                    warningEl.textContent = '⚠️ {{ __("Low margin! Consider adjusting prices.") }}';
                    warningEl.className = 'mt-2 text-xs text-red-700 font-semibold';
                    warningEl.classList.remove('hidden');
                } else if (margin < 15) {
                    bar.className = 'h-full bg-amber-500 transition-all duration-300';
                    warningEl.textContent = '⚡ {{ __("Acceptable margin. Review if possible.") }}';
                    warningEl.className = 'mt-2 text-xs text-amber-700 font-semibold';
                    warningEl.classList.remove('hidden');
                } else {
                    bar.className = 'h-full bg-emerald-500 transition-all duration-300';
                    warningEl.textContent = '✓ {{ __("Good profit margin!") }}';
                    warningEl.className = 'mt-2 text-xs text-emerald-700 font-semibold';
                    warningEl.classList.remove('hidden');
                }
            } else {
                profitPreview.classList.add('hidden');
            }
        }

        // Attach listeners to pricing fields
        ['unit_price', 'commission_service', 'delivery_cost_china'].forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                field.addEventListener('input', calculateEstimatedProfit);
            }
        });

        // Toggle sourcing note visibility
        document.getElementById('actual_sourcing_location').addEventListener('change', function() {
            const container = document.getElementById('sourcing_note_container');
            const requestedLocation = "{{ strtolower($sourcingRequest->sourcing_location) }}";
            const selectedLocation = this.value.toLowerCase();
            
            if (selectedLocation !== requestedLocation) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });

        // Image Preview Logic
        const imageInput = document.getElementById('real_product_image');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // 15MB size check
                    if (file.size > 15728640) {
                        window.dispatchEvent(new CustomEvent('show-error-toast', { 
                            detail: '{{ __("File size exceeds 15MB. Please choose a smaller file.") }}' 
                        }));
                        imageInput.value = '';
                        previewContainer.classList.add('hidden');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        }

        // Alpine.js Media Uploader Component
        function mediaUploader() {
            return {
                isDragging: false,
                previews: [],
                files: [],
                
                handleFiles(fileList) {
                    const dt = new DataTransfer();
                    const existingFiles = this.$refs.mediaInput.files;
                    
                    // Add existing files
                    for (let i = 0; i < existingFiles.length; i++) {
                        dt.items.add(existingFiles[i]);
                    }
                    
                    // Add new files
                    for (let i = 0; i < fileList.length; i++) {
                        const file = fileList[i];
                        if (file.size > 15728640) { // 15MB
                            window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                detail: `${file.name} {{ __('is too large. Maximum size is 15MB.') }}` 
                            }));
                            continue;
                        }
                        
                        dt.items.add(file);
                        this.files.push(file);
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push({
                                url: e.target.result,
                                name: file.name,
                                type: file.type.startsWith('video') ? 'video' : 'image'
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                    
                    // Update input files
                    this.$refs.mediaInput.files = dt.files;
                },
                
                handleDrop(e) {
                    this.isDragging = false;
                    this.handleFiles(e.dataTransfer.files);
                },
                
                removeFile(index) {
                    const dt = new DataTransfer();
                    const files = this.$refs.mediaInput.files;
                    
                    for (let i = 0; i < files.length; i++) {
                        if (i !== index) {
                            dt.items.add(files[i]);
                        }
                    }
                    
                    this.$refs.mediaInput.files = dt.files;
                    this.files.splice(index, 1);
                    this.previews.splice(index, 1);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>