@php($sla = $order->sla_state)
@if ($sla)
    @if ($sla['restricted'])
        <a href="{{ route('admin.sourcing-orders.show', $order) }}"
           title="{{ __('sla.list_treat') }}"
           class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold text-white bg-red-600 hover:bg-red-700 shadow-sm transition-colors">
            {{ __('sla.badge_overdue') }} · {{ __('sla.list_treat') }}
        </a>
    @elseif ($sla['satisfied'])
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200">
            {{ __('sla.badge_satisfied') }}
        </span>
    @elseif ($sla['minutes'] <= 0)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-red-700 bg-red-50 border border-red-200">
            {{ __('sla.badge_overdue_hours', ['hours' => (int) ceil(-$sla['minutes'] / 60)]) }}
        </span>
    @else
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $sla['minutes'] <= 720 ? 'text-amber-700 bg-amber-50 border-amber-200' : 'text-slate-500 bg-slate-50 border-slate-200' }}">
            {{ __('sla.badge_remaining', ['hours' => (int) ceil($sla['minutes'] / 60)]) }}
        </span>
    @endif
@endif
