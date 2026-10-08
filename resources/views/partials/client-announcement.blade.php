{{--
    Client-wide announcement banner (premium style).
    Shows the most recent visible announcement (active + within its date range).
    Expired or disabled announcements disappear automatically.
--}}
@php
    $clientAnnouncement = \App\Models\Announcement::visible()->orderByDesc('id')->first();
    $isSameDay = $clientAnnouncement
        && $clientAnnouncement->start_date->isSameDay($clientAnnouncement->end_date);
@endphp

@if ($clientAnnouncement && auth()->user()?->role === 'client')
    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
        <div id="client-announcement-banner"
             role="status"
             aria-live="polite"
             class="group relative overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700/80 bg-white dark:bg-slate-900 shadow-[0_12px_40px_-16px_rgba(15,23,42,0.25)]">

            {{-- Top accent bar --}}
            <div class="absolute inset-x-0 top-0 h-[3px] bg-gradient-to-r from-orange-500 via-amber-400 to-orange-600"></div>

            {{-- Soft halo --}}
            <div class="pointer-events-none absolute -top-20 -left-12 h-52 w-52 rounded-full bg-orange-400/10 blur-3xl dark:bg-orange-500/10"></div>

            {{-- Left accent --}}
            <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-orange-500 via-orange-400 to-amber-500"></div>

            <div class="relative flex items-start gap-4 px-5 py-4 sm:px-6 sm:py-5 ps-6 sm:ps-7">

                {{-- Icon + live indicator --}}
                <div class="relative flex-shrink-0 mt-0.5">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-lg shadow-orange-500/30 transition-transform duration-300 group-hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.639l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </span>
                    {{-- live dot --}}
                    <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-70"></span>
                        <span class="relative inline-flex h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-500 dark:border-slate-900"></span>
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1 mb-1.5">
                        <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-orange-600 dark:text-orange-400">
                            {{ __('Announcement') }}
                        </span>
                        {{-- period (inline, mobile only) --}}
                        <span class="sm:hidden inline-flex items-center gap-1 rounded-md bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-[10px] font-bold text-slate-600 dark:text-slate-300 ring-1 ring-inset ring-slate-200/80 dark:ring-slate-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $clientAnnouncement->start_date->format('d/m/Y') }}@if(! $isSameDay) — {{ $clientAnnouncement->end_date->format('d/m/Y') }}@endif
                        </span>
                    </div>

                    <p class="text-[15px] sm:text-base font-semibold leading-snug text-slate-800 dark:text-slate-100 whitespace-pre-line">{{ $clientAnnouncement->message }}</p>

                    {{-- period (desktop, inline under label row for long messages) --}}
                    <div class="hidden sm:flex items-center gap-2 mt-2.5">
                        <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 dark:bg-slate-800 px-2 py-1 text-[11px] font-semibold text-slate-600 dark:text-slate-300 ring-1 ring-inset ring-slate-200/80 dark:ring-slate-700">
                            <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $clientAnnouncement->start_date->format('d/m/Y') }}
                            @if(! $isSameDay)
                                <span class="text-slate-400 dark:text-slate-500">→</span>
                                {{ $clientAnnouncement->end_date->format('d/m/Y') }}
                            @endif
                        </span>
                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">
                            @if($isSameDay)
                                {{ __('Today') }}
                            @elseif($clientAnnouncement->end_date->isToday())
                                {{ __('Ends today') }}
                            @elseif($clientAnnouncement->end_date->isTomorrow())
                                {{ __('Ends tomorrow') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
