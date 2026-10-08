<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">

        {{-- Sticky header --}}
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('sla.guide.title') }}</h1>
                            <p class="text-xs text-slate-500 hidden sm:block">{{ __('sla.guide.subtitle') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 text-red-700 border border-red-100 text-[10px] font-bold uppercase tracking-wider">
                            {{ $restrictedOrders }} {{ __('sla.guide.badge_restricted_orders') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded bg-amber-50 text-amber-700 border border-amber-100 text-[10px] font-bold uppercase tracking-wider">
                            {{ $restrictedRequests }} {{ __('sla.guide.badge_restricted_requests') }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded bg-slate-800 text-slate-300 text-[10px] font-bold uppercase tracking-wider">
                            {{ count($rules['requests'] ?? []) + count($rules['orders'] ?? []) }} {{ __('sla.guide.badge_rules') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

            {{-- Intro --}}
            <div class="rounded-xl border-2 border-[#EF7722]/30 bg-[#EF7722]/5 p-5 flex flex-col sm:flex-row gap-4">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-[#EF7722] text-white shrink-0 self-start">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                </span>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-1">{{ __('sla.guide.intro_title') }}</h2>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ __('sla.guide.intro_body') }}</p>
                </div>
            </div>

            {{-- 1. Rules in force --}}
            <section>
                <div class="mb-4">
                    <h2 class="text-base font-bold text-slate-900">{{ __('sla.guide.rules_title') }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('sla.guide.rules_subtitle') }}</p>
                    <p class="text-xs text-[#EF7722] font-semibold mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5 3.35V6a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 6v12a2.25 2.25 0 0 1-2.25 2.25H4.5A2.25 2.25 0 0 1 2.25 18Z"/></svg>
                        {{ __('sla.guide.ex_hint') }}
                    </p>
                </div>

                @php
                    $statusLabels = [
                        'in_review' => __('In Review'),
                        'negotiating' => __('Negotiating'),
                        'paid' => __('Paid'),
                        'in_transit_china' => __('in_transit_china'),
                    ];
                    $statusActions = [
                        'in_review' => __('sla.guide.action_in_review'),
                        'negotiating' => __('sla.guide.action_negotiating'),
                        'paid' => __('sla.guide.action_paid'),
                        'in_transit_china' => __('sla.guide.action_in_transit_china'),
                    ];
                @endphp

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach (['requests' => 'request', 'orders' => 'order'] as $group => $typeKey)
                        @foreach ($rules[$group] ?? [] as $status => $hours)
                            <div role="button" tabindex="0"
                                 x-on:click="$dispatch('open-modal', 'sla-example-{{ $status }}')"
                                 x-on:keydown.enter.prevent="$dispatch('open-modal', 'sla-example-{{ $status }}')"
                                 class="group bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-orange-400 hover:shadow-md cursor-pointer transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $typeKey === 'order' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-violet-50 text-violet-700 border border-violet-100' }}">
                                            {{ $typeKey === 'order' ? __('sla.guide.rule_order') : __('sla.guide.rule_request') }}
                                        </span>
                                        <h3 class="text-sm font-bold text-slate-900 mt-2">{{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}</h3>
                                    </div>
                                    <span class="inline-flex items-center justify-center h-11 w-11 rounded-full bg-orange-50 text-[#EF7722] text-sm font-black border border-orange-100">
                                        {{ $hours }}h
                                    </span>
                                </div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">{{ __('sla.guide.must_do') }}</p>
                                <p class="text-sm text-slate-600">{{ $statusActions[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}</p>
                                <p class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-[#EF7722]">
                                    <span>{{ __('sla.guide.ex_card_hint') }}</span>
                                    <span class="transition-transform group-hover:translate-x-1">→</span>
                                </p>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </section>

            {{-- 2. Lifecycle --}}
            <section>
                <div class="mb-4">
                    <h2 class="text-base font-bold text-slate-900">{{ __('sla.guide.lifecycle_title') }}</h2>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ([1, 2, 3, 4, 5] as $step)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 relative">
                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full {{ $step === 3 ? 'bg-red-600' : 'bg-[#EF7722]' }} text-white text-xs font-black mb-3">{{ $step }}</span>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">{{ __('sla.guide.step'.$step.'_t') }}</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ __('sla.guide.step'.$step.'_b') }}</p>
                            @if($step < 5)
                                <span class="hidden lg:block absolute top-1/2 -right-3 text-slate-300">→</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- 3. Demo --}}
            <section>
                <div class="mb-4">
                    <h2 class="text-base font-bold text-slate-900">{{ __('sla.guide.demo_title') }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('sla.guide.demo_subtitle') }}</p>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6">
                    {{-- Timeline --}}
                    <div class="overflow-x-auto pb-2 -mx-1 px-1">
                        <div class="flex items-start min-w-[720px]">
                            @php
                                $demoSteps = [
                                    ['key' => 't0', 'color' => 'bg-slate-500', 'icon' => 'M12 6v6h4.5'],
                                    ['key' => 't24', 'color' => 'bg-amber-500', 'icon' => 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0'],
                                    ['key' => 't48', 'color' => 'bg-red-600', 'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z'],
                                    ['key' => 'proof', 'color' => 'bg-emerald-600', 'icon' => 'm4.5 12.75 6 6 9-13.5'],
                                    ['key' => 'air', 'color' => 'bg-blue-600', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                                    ['key' => 'arrival', 'color' => 'bg-slate-800', 'icon' => 'M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM19.5 10.5a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                                ];
                            @endphp
                            @foreach ($demoSteps as $i => $step)
                                <div class="flex-1 min-w-[110px] flex flex-col items-center text-center relative">
                                    @if($i > 0)
                                        <span class="absolute top-5 right-1/2 w-full h-0.5 {{ $i <= 3 ? 'bg-red-300' : 'bg-emerald-300' }}"></span>
                                    @endif
                                    <span class="relative z-10 inline-flex items-center justify-center h-10 w-10 rounded-full {{ $step['color'] }} text-white shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/></svg>
                                    </span>
                                    <p class="text-[11px] font-semibold text-slate-600 mt-2 leading-tight px-1">{{ __('sla.guide.demo_'.$step['key']) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Block callout --}}
                    <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                            <div>
                                <p class="text-sm font-bold text-red-800 mb-0.5">{{ __('sla.guide.demo_block_title') }}</p>
                                <p class="text-xs text-red-700 leading-relaxed">{{ __('sla.guide.demo_block_body') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Live CTA --}}
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        @if($demoOrder)
                            <a href="{{ route('admin.sourcing-orders.show', $demoOrder) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-orange-600 text-white text-sm font-bold rounded-md shadow-sm transition-colors">
                                {{ __('sla.guide.demo_cta', ['ref' => $demoOrder->reference_id]) }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-500 text-sm font-medium rounded-md border border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                {{ __('sla.guide.demo_none') }}
                            </span>
                        @endif
                    </div>
                </div>
            </section>

            {{-- 4. Evidence + Where --}}
            <section class="grid gap-4 lg:grid-cols-2">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('sla.guide.evidence_title') }}</h2>
                    <ul class="space-y-3">
                        @foreach ([1, 2, 3] as $n)
                            <li class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black shrink-0 mt-0.5">{{ $n }}</span>
                                <span class="text-sm text-slate-600">{{ __('sla.guide.evidence_'.$n) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 p-3 flex gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                        <p class="text-xs text-amber-800 font-medium">{{ __('sla.guide.evidence_note') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">{{ __('sla.guide.where_title') }}</h2>
                    <ol class="space-y-3">
                        @foreach ([1, 2, 3] as $n)
                            <li class="flex items-start gap-3">
                                <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black shrink-0 mt-0.5">{{ $n }}</span>
                                <span class="text-sm text-slate-600">{{ __('sla.guide.where_'.$n) }}</span>
                            </li>
                        @endforeach
                    </ol>
                    <div class="mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3 flex gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                        <p class="text-xs text-emerald-800 font-medium">{{ __('sla.guide.step5_b') }}</p>
                    </div>
                </div>
            </section>

            {{-- 5. FAQ --}}
            <section>
                <div class="mb-4">
                    <h2 class="text-base font-bold text-slate-900">{{ __('sla.guide.faq_title') }}</h2>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ([1, 2, 3, 4] as $n)
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                            <div class="flex items-start gap-2 mb-2">
                                <span class="text-[#EF7722] font-black text-sm shrink-0">Q{{ $n }}</span>
                                <h3 class="text-sm font-bold text-slate-900">{{ __('sla.guide.q'.$n) }}</h3>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed pl-6">{{ __('sla.guide.a'.$n) }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <p class="text-[11px] text-slate-400 text-center pt-2 border-t border-slate-200">{{ __('sla.guide.footer') }}</p>
        </div>
    </div>

    {{-- Example modals: one per rule card, same design as the real SLA login popup --}}
    @php
        $examples = [];
        foreach (['requests' => 'request', 'orders' => 'order'] as $group => $typeKey) {
            foreach ($rules[$group] ?? [] as $status => $hours) {
                $isDemo = $typeKey === 'order' && $status === 'in_transit_china' && isset($demoOrder) && $demoOrder;
                $cta = $typeKey === 'order'
                    ? ($isDemo
                        ? route('admin.sourcing-orders.show', $demoOrder)
                        : route('admin.sourcing-orders.index', ['overdue' => 1]))
                    : route('admin.sourcing-requests.index', ['overdue' => 1]);
                $examples[$status] = [
                    'typeKey' => $typeKey,
                    'hours' => (int) $hours,
                    'ageHours' => (int) $hours + 6,
                    'cta' => $cta,
                    'fakeRef' => $typeKey === 'order'
                        ? ($isDemo ? $demoOrder->reference_id : 'FSB000219')
                        : 'FSB000204',
                ];
            }
        }
    @endphp

    @foreach ($examples as $status => $ex)
        <x-modal name="sla-example-{{ $status }}" :show="false" :centered="true" focusable max-width="md">
            <div class="px-6 pt-6 pb-5 border-b border-red-100 bg-red-50/70 flex items-start gap-3">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                    </svg>
                </span>
                <div class="min-w-0">
                    <h2 id="sla-example-{{ $status }}-title" class="text-base font-bold text-red-800">{{ __('sla.guide.ex_title', ['status' => $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status))]) }}</h2>
                    <p class="mt-0.5 text-sm text-red-700">{{ __('sla.guide.ex_subtitle', ['hours' => $ex['hours']]) }}</p>
                </div>
            </div>

            <div class="px-6 py-4" aria-labelledby="sla-example-{{ $status }}-title">
                {{-- Mock restricted item, identical to the real popup item --}}
                <a href="{{ $ex['cta'] }}" class="group flex items-center gap-3 py-3 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400 rounded">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg shrink-0 {{ $ex['typeKey'] === 'order' ? 'bg-orange-50 text-orange-600' : 'bg-blue-50 text-blue-600' }}">
                        @if ($ex['typeKey'] === 'order')
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
                            {{ $ex['fakeRef'] }}
                            <span class="font-normal text-slate-500">
                                · {{ $ex['typeKey'] === 'order' ? __('sla.modal_type_order') : __('sla.modal_type_request') }} · {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </span>
                        <span class="block text-xs text-slate-500 truncate">
                            {{ __('sla.guide.ex_age', ['hours' => $ex['ageHours']]) }}
                        </span>
                    </span>
                    <span class="shrink-0 inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-red-700">
                        {{ __('sla.modal_blocked') }}
                    </span>
                    <svg class="w-4 h-4 shrink-0 text-slate-300 group-hover:text-red-500 transition-colors rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>

                {{-- What happens when overdue --}}
                <div class="mt-2 rounded-lg bg-red-50 border border-red-200 p-3 flex gap-2">
                    <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-red-800 mb-0.5">{{ __('sla.guide.ex_blocked_title') }}</p>
                        <p class="text-xs text-red-700 leading-relaxed">{{ __('sla.guide.ex_body_'.$status) }}</p>
                    </div>
                </div>

                {{-- How to unlock --}}
                <div class="mt-2 rounded-lg bg-emerald-50 border border-emerald-200 p-3 flex gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/></svg>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-emerald-800 mb-0.5">{{ __('sla.guide.ex_unlock_label') }}</p>
                        <p class="text-xs text-emerald-700 leading-relaxed">{{ __('sla.guide.ex_unlock_'.$status) }}</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                <button type="button"
                        x-on:click="$dispatch('close-modal', 'sla-example-{{ $status }}')"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                    {{ __('sla.modal_close') }}
                </button>
                <a href="{{ $ex['cta'] }}"
                   class="inline-flex items-center justify-center px-5 py-2 rounded-md text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-sm transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400 focus-visible:ring-offset-2">
                    {{ __('sla.modal_cta') }}
                    <svg class="w-4 h-4 ms-2 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>
        </x-modal>
    @endforeach
</x-app-layout>
