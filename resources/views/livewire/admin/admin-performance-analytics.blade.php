<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">{{ __('Admin Performance Analytics') }}</h1>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        @if(count($metrics) === 0)
            <div class="p-8 text-center text-sm text-slate-500">{{ __('No data available') }}</div>
        @else
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Admin') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Pending Payment') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Paid') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('In Transit') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Delivered') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Issues') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Refund') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($metrics as $row)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $row['admin_name'] ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $row['pending_payment'] > 0 ? 'text-amber-700 font-semibold' : 'text-slate-700' }}">{{ $row['pending_payment'] }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $row['paid'] > 0 ? 'text-blue-700 font-semibold' : 'text-slate-700' }}">{{ $row['paid'] }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $row['transit'] > 0 ? 'text-cyan-700 font-semibold' : 'text-slate-700' }}">{{ $row['transit'] }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $row['delivered'] > 0 ? 'text-emerald-700 font-semibold' : 'text-slate-700' }}">{{ $row['delivered'] }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $row['issues'] > 0 ? 'text-red-700 font-semibold' : 'text-slate-700' }}">{{ $row['issues'] }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $row['refund'] > 0 ? 'text-purple-700 font-semibold' : 'text-slate-700' }}">{{ $row['refund'] }}</td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-slate-900">{{ $row['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>