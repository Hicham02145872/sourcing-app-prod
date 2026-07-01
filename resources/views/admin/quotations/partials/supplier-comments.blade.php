<div class="space-y-4 pt-2">
    <div>
        <label for="supplier_url" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">
            {{ __('Supplier Product Link') }} <span class="text-slate-400 font-normal lowercase">({{ __('internal administrative use only') }})</span>
        </label>
        <div class="relative rounded-xl shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            </div>
            <input type="url" name="supplier_url" id="supplier_url" value="{{ old('supplier_url', $supplierUrlValue ?? '') }}" placeholder="https://item.taobao.com/..."
                class="w-full pl-10 pr-3 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 block transition-all placeholder:text-slate-400">
        </div>
        @error('supplier_url')
            <p class="text-[10px] text-red-600 mt-1 font-medium">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="comments" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Comments') }}</label>
        <textarea id="comments" name="comments" rows="3"
            class="block w-full px-3.5 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-slate-400"
            placeholder="{{ __('Add any internal notes or clarifications for this quotation...') }}">{{ old('comments', $commentsValue ?? '') }}</textarea>
        <p class="mt-1 text-[10px] text-slate-400">{{ __('Visible to the client in quotation details.') }}</p>
    </div>
</div>
