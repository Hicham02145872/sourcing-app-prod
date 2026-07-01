<div class="space-y-4">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 text-emerald-600">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Financial Pricing') }}</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-5">
        @if(isset($showUnitPrice) && $showUnitPrice)
        <div>
            <label for="unit_price" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Unit Price') }} <span class="text-red-500">*</span></label>
            <div class="relative rounded-xl shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-slate-400 text-sm font-bold currency-symbol">$</span>
                </div>
                <input type="number" step="0.0001" name="unit_price" id="unit_price" required placeholder="0.00" value="{{ old('unit_price', $unitPriceValue ?? '') }}"
                    class="pl-10 block w-full px-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
            </div>
            <p class="mt-1 text-[10px] text-slate-400">{{ __('Price per unit excluding fees') }}</p>
        </div>
        @else
        <input type="hidden" name="unit_price" id="unit_price" value="0">
        @endif

        <div>
            <label for="commission_service" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Service Commission') }} <span class="text-red-500">*</span></label>
            <div class="relative rounded-xl shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-slate-400 text-sm font-bold currency-symbol">$</span>
                </div>
                <input type="number" step="0.01" name="commission_service" id="commission_service" required placeholder="0.00" value="{{ old('commission_service', $commissionValue ?? '') }}"
                    class="pl-10 block w-full px-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
            </div>
            <p class="mt-1 text-[10px] text-slate-400">{{ __('Commission amount per unit') }}</p>
        </div>
    </div>
</div>
