<div class="space-y-4">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-blue-50 text-blue-600">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </span>
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Logistics & Logistics Costs') }}</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="delivery_cost_china" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Shipping Fees') }} <span class="text-red-500">*</span></label>
            <div class="relative rounded-xl shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-slate-400 text-sm font-bold currency-symbol">$</span>
                </div>
                <input type="number" step="0.01" name="delivery_cost_china" id="delivery_cost_china" required placeholder="0.00" value="{{ old('delivery_cost_china', $deliveryCostValue ?? '') }}"
                    class="pl-10 block w-full px-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
            </div>
            <p class="mt-1 text-[10px] text-slate-400">{{ __('Domestic delivery cost') }}</p>
        </div>
    </div>
</div>
