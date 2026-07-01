<div class="bg-gradient-to-tr from-slate-900 to-slate-950 text-white rounded-lg p-5 space-y-4 shadow-xl shadow-slate-900/15 relative overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-orange-100 text-orange-600">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ __('Live Cost & Profit Analyzer') }}</h4>
        </div>
        <button type="button" id="toggle-estimates" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 bg-white px-2.5 py-1 rounded border border-slate-300 shadow-sm transition-all">
            <span id="toggle-text">{{ __('Show') }}</span>
            <svg id="toggle-icon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>

    <div id="estimates-section" class="hidden space-y-4">
        <div class="p-3 bg-slate-100 border border-slate-200 rounded-lg">
            <p class="text-[10px] text-slate-600 flex items-start gap-1.5 leading-relaxed">
                <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ __('Estimate costs to analyze profitability before sending quotation to client.') }}</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1.5">
                <label for="estimated_product_cost" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                    {{ __('Est. Unit Product Cost') }}
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-slate-500 text-xs font-semibold currency-symbol">$</span>
                    </div>
                    <input type="number" step="0.01" name="estimated_product_cost" id="estimated_product_cost" placeholder="0.00" value="{{ old('estimated_product_cost', $estProductCostValue ?? '') }}"
                        class="pl-7 block w-full px-2.5 py-2 text-xs bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 block transition-all font-semibold"
                        oninput="calculateEstimatedProfit()">
                </div>
                <div class="flex items-center justify-between text-[9px] text-slate-500">
                    <span>{{ __('For') }} {{ $totalQuantity }} {{ __('units') }}</span>
                    <span id="est-total-cost-preview" class="text-orange-600 font-bold"></span>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="estimated_shipping_cost" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                    {{ __('Total Est. Shipping') }}
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-slate-500 text-xs font-semibold currency-symbol">$</span>
                    </div>
                    <input type="number" step="0.01" name="estimated_shipping_cost" id="estimated_shipping_cost" placeholder="0.00" value="{{ old('estimated_shipping_cost', $estShippingCostValue ?? '') }}"
                        class="pl-7 block w-full px-2.5 py-2 text-xs bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 block transition-all font-semibold"
                        oninput="calculateEstimatedProfit()">
                </div>
                <p class="text-[9px] text-slate-500">{{ __('Logistics sum total') }}</p>
            </div>

            <div class="space-y-1.5">
                <label for="estimated_other_costs" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                    {{ __('Total Other Costs') }}
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-slate-500 text-xs font-semibold currency-symbol">$</span>
                    </div>
                    <input type="number" step="0.01" name="estimated_other_costs" id="estimated_other_costs" placeholder="0.00" value="{{ old('estimated_other_costs', $estOtherCostsValue ?? '') }}"
                        class="pl-7 block w-full px-2.5 py-2 text-xs bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 block transition-all font-semibold"
                        oninput="calculateEstimatedProfit()">
                </div>
                <p class="text-[9px] text-slate-500">{{ __('Customs, clearance, taxes') }}</p>
            </div>
        </div>

        <div id="profit-preview" class="hidden mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3.5">
            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Estimated Net Profit') }}</span>
                <span id="estimated-profit-amount" class="text-2xl font-black text-emerald-600 tracking-tight">$0.00</span>
            </div>
            <div class="space-y-1.5">
                <div class="flex justify-between items-center text-xs font-semibold">
                    <span class="text-slate-500">{{ __('Profit Margin') }}</span>
                    <span id="estimated-profit-margin" class="text-slate-900">0%</span>
                </div>
                <div class="h-2.5 bg-slate-200 rounded-full overflow-hidden p-0.5">
                    <div id="profit-margin-bar" class="h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            <div id="margin-warning" class="text-xs font-bold flex items-center gap-1.5"></div>
        </div>
    </div>
</div>
