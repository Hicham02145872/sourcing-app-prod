<div id="sourcing_note_container" class="{{ old('actual_sourcing_location', $sourcingLocationValue ?? 'china') != $requestedLocation ? '' : 'hidden' }} p-4 bg-orange-50/50 border border-orange-100 rounded-xl space-y-3 shadow-inner shadow-orange-500/5">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <label for="sourcing_note" class="text-[10px] font-bold text-orange-800 uppercase tracking-wider">
            {{ __('Note about Alternative Sourcing') }}
        </label>
    </div>
    <textarea id="sourcing_note" name="sourcing_note" rows="3"
        class="block w-full px-3 py-2 text-sm bg-white border border-orange-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-slate-400"
        placeholder="{{ __('Explain why this location was chosen and any impact on delivery...') }}">{{ old('sourcing_note', $sourcingNoteValue ?? '') }}</textarea>
    <p class="text-[10px] text-orange-600/90 italic flex items-center gap-1">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ __('This note will be visible to the client to help them understand the change.') }}
    </p>
</div>
