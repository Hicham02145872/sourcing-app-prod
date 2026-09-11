<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-xl font-bold text-slate-900">{{ __('Admin Performance Analytics') }}</h1>
    </div>

    @include('livewire.partials.date-range-filter', ['startDate' => 'startDate', 'endDate' => 'endDate'])

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        @if(count($metrics) === 0)
            <div class="p-8 text-center text-sm text-slate-500">{{ __('No data available') }}</div>
        @else
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Admin') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Reviews') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Responses') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Acceptances') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Acceptance Rate') }}</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-slate-500">{{ __('Avg response time') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($metrics as $row)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $row['admin_name'] ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-700">{{ $row['reviews'] }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-700">{{ $row['responses'] }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-700">{{ $row['acceptances'] }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-700">{{ $row['acceptance_rate'] !== null ? $row['acceptance_rate'] . '%' : '—' }}</td>
                            <td class="px-6 py-4 text-sm text-right text-slate-700">{{ $row['avg_response_hours'] !== null ? $row['avg_response_hours'] . 'h' : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>