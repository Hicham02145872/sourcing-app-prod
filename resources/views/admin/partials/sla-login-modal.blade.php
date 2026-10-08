{{-- Login SLA popup: shows the admin's blocked folders/orders once per
     session, right after logging in to the admin dashboard. Rendered only
     when the auto-lock feature flag is on, the user is not a super admin and
     at least one item is overdue. --}}
@php
    $slaPopupUser = auth()->user();
    $slaPopupItems = [];
    $slaPopupTotal = 0;
    $slaPopupUrl = null;
    $slaPopupShow = false;

    if ($slaPopupUser
        && ! $slaPopupUser->isSuperAdmin()
        && request()->routeIs('admin.dashboard')
        && ! session()->has('sla_login_modal_shown')
        && app(\App\Services\FeatureFlagService::class)->isEnabled('sla_deadlines_autolock', $slaPopupUser)
    ) {
        $slaPopupService = app(\App\Services\SlaOverdueService::class);
        $slaPopupStats = $slaPopupService->statsForUser($slaPopupUser);
        $slaPopupTotal = (int) ($slaPopupStats['requestCount'] ?? 0) + (int) ($slaPopupStats['orderCount'] ?? 0);

        if ($slaPopupTotal > 0) {
            $slaPopupItems = $slaPopupService->overdueItems($slaPopupUser);
            $slaPopupUrl = $slaPopupService->mostUrgentTargetUrl($slaPopupStats);
            $slaPopupShow = $slaPopupItems !== [];
        }
    }
@endphp

@if ($slaPopupShow)
    @php session(['sla_login_modal_shown' => true]); @endphp
    <x-modal name="sla-login" :show="true" :centered="true" focusable max-width="md">
        <div class="px-6 pt-6 pb-5 border-b border-red-100 bg-red-50/70 flex items-start gap-3">
            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
            </span>
            <div class="min-w-0">
                <h2 id="sla-login-title" class="text-base font-bold text-red-800">{{ __('sla.modal_title') }}</h2>
                <p class="mt-0.5 text-sm text-red-700">{{ __('sla.modal_subtitle', ['count' => $slaPopupTotal]) }}</p>
            </div>
        </div>

        <div class="px-6 py-2 max-h-72 overflow-y-auto divide-y divide-slate-100" aria-labelledby="sla-login-title">
            @foreach ($slaPopupItems as $item)
                <a href="{{ $item['url'] }}"
                   class="group flex items-center gap-3 py-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400 rounded">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg shrink-0 {{ $item['type'] === 'order' ? 'bg-orange-50 text-orange-600' : 'bg-blue-50 text-blue-600' }}">
                        @if ($item['type'] === 'order')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                        @endif
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-semibold text-slate-800 truncate">
                            {{ $item['reference'] !== '' ? $item['reference'] : '#'.$item['id'] }}
                            <span class="font-normal text-slate-500">
                                · {{ $item['type'] === 'order' ? __('sla.modal_type_order') : __('sla.modal_type_request') }} · {{ __($item['status']) }}
                            </span>
                        </span>
                        <span class="block text-xs text-slate-500 truncate">
                            @if (! empty($item['title']))
                                {{ $item['title'] }}
                                @if (($item['amount'] ?? 0) > 0)
                                    · {{ number_format($item['amount'], 2) }} {{ $item['currency'] ?? 'USD' }}
                                @endif
                                ·
                            @endif
                            {{ $item['age'] }}
                        </span>
                    </span>
                    <span class="shrink-0 inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700">
                        {{ __('sla.modal_blocked') }}
                    </span>
                    <svg class="w-4 h-4 shrink-0 text-slate-300 group-hover:text-red-500 transition-colors rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            @endforeach

            @if ($slaPopupTotal > count($slaPopupItems))
                <p class="py-3 text-xs text-slate-500">{{ __('sla.modal_more', ['count' => $slaPopupTotal - count($slaPopupItems)]) }}</p>
            @endif
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
            <button type="button"
                    x-on:click="$dispatch('close-modal', 'sla-login')"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                {{ __('sla.modal_close') }}
            </button>
            @if ($slaPopupUrl)
                <a href="{{ $slaPopupUrl }}"
                   class="inline-flex items-center justify-center px-5 py-2 rounded-md text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400 focus-visible:ring-offset-2">
                    {{ __('sla.modal_cta') }}
                    <svg class="w-4 h-4 ms-2 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            @endif
        </div>
    </x-modal>
@endif
