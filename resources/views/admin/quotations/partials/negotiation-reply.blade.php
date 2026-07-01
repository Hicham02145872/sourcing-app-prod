@if(isset($negotiationNotes) && $negotiationNotes)
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <label for="admin_negotiation_reply" class="block text-[10px] font-bold text-blue-700 uppercase mb-2">
            {{ __('Admin Reply To Client Negotiation') }}
        </label>
        <p class="mb-2 text-xs text-blue-700 italic">
            "{{ $negotiationNotes }}"
        </p>
        <textarea id="admin_negotiation_reply" name="admin_negotiation_reply" rows="3"
            class="block w-full px-3 py-2 text-sm bg-white border border-blue-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
            placeholder="{{ __('Write a clear response to the client note...') }}">{{ old('admin_negotiation_reply', $negotiationReplyValue ?? '') }}</textarea>
        <p class="mt-1 text-[10px] text-blue-600/80">{{ __('Visible to the client in negotiation details.') }}</p>
    </div>
@endif
