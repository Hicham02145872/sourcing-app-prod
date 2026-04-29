<div class="space-y-6 lg:space-y-8">
    {{-- Status strip --}}
    <div class="relative overflow-hidden rounded-2xl border border-amber-200/80 bg-gradient-to-r from-amber-50/90 to-white dark:from-amber-950/40 dark:to-slate-900 dark:border-amber-800/50 shadow-sm">
        <div class="pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full bg-amber-200/30 dark:bg-amber-600/10 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200">{{ __('Dynamic Market Pricing Status') }}</h3>
                    <p class="mt-1 text-sm text-amber-800/90 dark:text-amber-300/80">{{ __('Global logistics rates are currently volatile. Rates are verified and refreshed every 15 days.') }}</p>
                </div>
            </div>
            <span class="inline-flex w-fit shrink-0 items-center rounded-full border border-amber-200/80 bg-white/80 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest text-amber-800 dark:border-amber-700 dark:bg-slate-800/80 dark:text-amber-200">
                {{ __('Next Update: Jan 15') }}
            </span>
        </div>
    </div>

    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#EF7722] via-[#F07828] to-[#FAA533] shadow-lg shadow-orange-500/20">
        <div class="absolute inset-0 opacity-[0.15] bg-[linear-gradient(to_right,#fff_1px,transparent_1px),linear-gradient(to_bottom,#fff_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        <div class="relative flex flex-col gap-6 p-8 lg:flex-row lg:items-center lg:justify-between lg:p-10">
            <div class="max-w-xl">
                <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">{{ __('Unified Logistics Gateway') }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-white/85 sm:text-base">{{ __('Access institutional-grade freight estimations for air, sea, and hub routing through our global logistics engine.') }}</p>
            </div>
            <div class="hidden shrink-0 text-white/25 sm:block">
                <svg class="h-24 w-24 lg:h-28 lg:w-28" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </div>
        </div>
    </div>

    @if(!$selectedCountry)
        {{-- Destination picker (modern) --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-slate-100 bg-gradient-to-b from-slate-50/80 to-white px-6 py-10 text-center dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800 sm:px-10">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 text-white shadow-lg shadow-blue-500/25">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" class="text-white/30"/>
                        <path stroke-linecap="round" d="M3 12h18M12 3a15 15 0 0110 9 15 15 0 01-10 9 15 15 0 01-10-9 15 15 0 0110-9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-2xl">{{ __('Where do you want to ship?') }}</h3>
                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500 dark:text-slate-400">{{ __('Pick a country to view air and sea rates from China and air rates from the United Arab Emirates.') }}</p>

                <div class="mx-auto mt-8 max-w-lg">
                    <label class="sr-only" for="shipping-fees-search">{{ __('Search destination...') }}</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input id="shipping-fees-search" type="search" autocomplete="off"
                               wire:model.live.debounce.300ms="search"
                               placeholder="{{ __('Search destination...') }}"
                               class="w-full rounded-xl border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm font-medium text-slate-900 shadow-inner placeholder:text-slate-400 focus:border-[#EF7722] focus:outline-none focus:ring-2 focus:ring-[#EF7722]/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-slate-500"/>
                    </div>
                </div>
            </div>

            <div class="max-h-[min(420px,50vh)] overflow-y-auto overscroll-contain px-4 py-2 sm:px-6" wire:loading.class="opacity-60">
                <ul class="divide-y divide-slate-100 dark:divide-slate-700/80" role="list">
                    @forelse($countries as $country)
                        <li wire:key="sf-country-row-{{ $country->id }}">
                            <button type="button" wire:click="selectCountry({{ $country->id }})"
                                    class="group flex w-full items-center gap-4 rounded-xl px-3 py-3.5 text-left transition hover:bg-slate-50 dark:hover:bg-slate-700/40">
                                <span class="fi fi-{{ strtolower($country->code) }} text-2xl rounded-md shadow-sm ring-1 ring-slate-200/80 dark:ring-slate-600" aria-hidden="true"></span>
                                <div class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-bold text-slate-900 group-hover:text-[#EF7722] dark:text-white">{{ $country->name }}</span>
                                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">{{ strtoupper($country->code) }}</span>
                                </div>
                                <svg class="h-5 w-5 shrink-0 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-[#EF7722] dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </li>
                    @empty
                        <li class="py-16 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('No rates indexed for this selection.') }}</li>
                    @endforelse
                </ul>
            </div>

            @if($countries->hasPages())
                <div class="border-t border-slate-100 bg-slate-50/80 px-4 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                    {{ $countries->links() }}
                </div>
            @endif
        </div>
    @else
        {{-- Country detail: all transport blocks inline --}}
        <div wire:key="sf-detail-{{ $selectedCountry->id }}" class="space-y-6">
            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div class="flex items-center gap-4">
                    <span class="fi fi-{{ strtolower($selectedCountry->code) }} text-3xl rounded-lg shadow-md ring-1 ring-slate-200/80 dark:ring-slate-600"></span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $selectedCountry->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Comprehensive Logistics Profile') }}</p>
                    </div>
                </div>
                <button type="button" wire:click="closeCountryDetails"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-[#EF7722]/50 hover:text-[#EF7722] dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-orange-500/50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Change destination') }}
                </button>
            </div>

            @php
                $hasAnyBlock = false;
            @endphp

            @foreach(['air', 'sea', 'train'] as $type)
                @php
                    $items = $selectedCountry->shippingFee?->items->where('transport_type', $type)->filter(fn ($i) => $i->price_per_kg !== null);
                    $sectionTitle = match ($type) {
                        'air' => __('Air freight from China'),
                        'sea' => __('Sea bulk from China'),
                        default => __('Air freight from United Arab Emirates'),
                    };
                    $sectionAccent = match ($type) {
                        'air' => ['bar' => 'from-[#EF7722] to-[#FAA533]', 'badge' => 'bg-orange-100 text-orange-700 dark:bg-orange-950/50 dark:text-orange-300'],
                        'sea' => ['bar' => 'from-[#0BA6DF] to-cyan-500', 'badge' => 'bg-sky-100 text-sky-800 dark:bg-sky-950/50 dark:text-sky-300'],
                        default => ['bar' => 'from-emerald-500 to-teal-500', 'badge' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300'],
                    };
                @endphp

                @if($items && $items->count() > 0)
                    @php $hasAnyBlock = true; @endphp
                    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                        <div class="h-1 bg-gradient-to-r {{ $sectionAccent['bar'] }}"></div>
                        <div class="flex flex-wrap items-center gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-700 sm:px-6">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $sectionAccent['badge'] }}">
                                @if($type === 'sea')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18 M12 5V2"/></svg>
                                @else
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $sectionTitle }}</h4>
                                @if($type === 'train')
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('United Arab Emirates') }}</p>
                                @else
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('China') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                                <thead>
                                    <tr class="bg-slate-50/90 dark:bg-slate-900/50">
                                        <th scope="col" class="px-5 py-3 text-left text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400 sm:px-6">{{ __('Item Style') }}</th>
                                        <th scope="col" class="px-4 py-3 text-center text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('Delay') }}</th>
                                        <th scope="col" class="px-4 py-3 text-center text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ __('Price / ') }}{{ $selectedCountry->shippingFee?->getUnitForTransport($type) ?? 'KG' }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($items as $item)
                                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30">
                                            <td class="px-5 py-3.5 text-xs font-semibold text-slate-800 dark:text-slate-200 sm:px-6">{{ $item->item_style }}</td>
                                            <td class="px-4 py-3.5 text-center">
                                                @if($item->estimation_days)
                                                    <span class="inline-flex rounded-lg bg-orange-50 px-2 py-1 text-[10px] font-bold text-orange-700 dark:bg-orange-950/40 dark:text-orange-300">
                                                        {{ $item->estimation_days }} {{ __($item->estimation_unit ?? 'days') }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-300 dark:text-slate-600">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3.5 text-center">
                                                <span class="font-mono text-sm font-bold text-slate-900 dark:text-white">{{ $item->price_per_kg ? number_format($item->price_per_kg, 2) : '—' }}</span>
                                                <span class="ml-1 text-[10px] font-bold text-slate-400">{{ $selectedCountry->shippingFee->currency ?? 'USD' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach

            @if(!$hasAnyBlock)
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 py-16 text-center dark:border-slate-700 dark:bg-slate-900/30">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('No Shipping Data') }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('We do not have indexed rates for this country yet.') }}</p>
                </div>
            @endif
        </div>
    @endif

    {{-- CTA --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white px-6 py-10 text-center shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:px-10">
        <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Need an Enterprise-Grade Quote?') }}</h4>
        <p class="mx-auto mt-2 max-w-2xl text-sm text-slate-600 dark:text-slate-400">{{ __('For container-level operations, specialized equipment, or fragile goods, our global logistics network is ready to optimize your supply chain costs.') }}</p>
        @if($socialMediaLinks && $socialMediaLinks->whatsapp_number)
            <a href="https://wa.me/{{ $socialMediaLinks->whatsapp_number }}" target="_blank" rel="noopener noreferrer"
               class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#EF7722] px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-[#FAA533]">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.94 3.659 1.437 5.634 1.437h.005c6.556 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                {{ __('Consult Logistics Concierge') }}
            </a>
        @endif
    </div>
</div>
