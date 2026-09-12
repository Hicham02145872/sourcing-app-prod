@props([
    'start' => 'date_debut',
    'end' => 'date_fin',
])

<div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
    <div class="w-full sm:w-40">
        <label for="{{ $start }}" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Start date') }}</label>
        <input type="date" name="{{ $start }}" id="{{ $start }}" value="{{ request($start) }}"
               class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
    </div>
    <div class="w-full sm:w-40">
        <label for="{{ $end }}" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('End date') }}</label>
        <input type="date" name="{{ $end }}" id="{{ $end }}" value="{{ request($end) }}"
               class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
    </div>
</div>