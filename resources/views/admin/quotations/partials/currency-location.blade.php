<div class="space-y-4">
    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-slate-100 text-slate-600">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Currency & Location Settings') }}</h4>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="currency" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Currency') }} <span class="text-red-500">*</span></label>
            <div class="relative">
                <select id="currency" name="currency" required
                    class="block w-full pl-3 pr-10 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all cursor-pointer">
                    <option value="">{{ __('Select currency') }}</option>
                    <option value="USD" {{ old('currency', $currencyValue ?? '') == 'USD' ? 'selected' : '' }}>{{ __('USD - US Dollar') }}</option>
                    <option value="EUR" {{ old('currency', $currencyValue ?? '') == 'EUR' ? 'selected' : '' }}>{{ __('EUR - Euro') }}</option>
                    <option value="GBP" {{ old('currency', $currencyValue ?? '') == 'GBP' ? 'selected' : '' }}>{{ __('GBP - British Pound') }}</option>
                    <option value="MAD" {{ old('currency', $currencyValue ?? '') == 'MAD' ? 'selected' : '' }}>{{ __('MAD - Moroccan Dirham') }}</option>
                    <option value="JPY" {{ old('currency', $currencyValue ?? '') == 'JPY' ? 'selected' : '' }}>{{ __('JPY - Japanese Yen') }}</option>
                    <option value="CNY" {{ old('currency', $currencyValue ?? '') == 'CNY' ? 'selected' : '' }}>{{ __('CNY - Chinese Yuan') }}</option>
                    <option value="CAD" {{ old('currency', $currencyValue ?? '') == 'CAD' ? 'selected' : '' }}>{{ __('CAD - Canadian Dollar') }}</option>
                    <option value="AUD" {{ old('currency', $currencyValue ?? '') == 'AUD' ? 'selected' : '' }}>{{ __('AUD - Australian Dollar') }}</option>
                    <option value="AED" {{ old('currency', $currencyValue ?? '') == 'AED' ? 'selected' : '' }}>{{ __('AED - Dirham Imarati') }}</option>
                </select>
            </div>
        </div>

        <div>
            <label for="actual_sourcing_location" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Actual Sourcing Location') }} <span class="text-red-500">*</span></label>
            <select id="actual_sourcing_location" name="actual_sourcing_location" required
                class="block w-full px-3 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all cursor-pointer capitalize">
                <option value="china" {{ old('actual_sourcing_location', $sourcingLocationValue ?? 'china') == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                <option value="dubai" {{ old('actual_sourcing_location', $sourcingLocationValue ?? 'china') == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
            </select>
            <p class="mt-1 text-[10px] text-slate-400">{{ __('Defaults to the requested location.') }}</p>
        </div>
    </div>
</div>
