<div class="mb-6 p-4 bg-white rounded-lg border border-slate-200 shadow-sm">
    <div class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('From date') }}</label>
            <input type="date" wire:model="startDate" class="px-3 py-2 text-sm border border-slate-300 rounded" />
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('To date') }}</label>
            <input type="date" wire:model="endDate" class="px-3 py-2 text-sm border border-slate-300 rounded" />
        </div>
        <button type="button" wire:click="applyDateRange" class="px-4 py-2 text-xs font-bold text-white bg-orange-500 hover:bg-orange-600 rounded">
            {{ __('Apply') }}
        </button>
        <button type="button" wire:click="resetDateRange" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded">
            {{ __('Reset') }}
        </button>
    </div>
</div>