{{--
    Client-wide announcement banner.
    Shows the most recent visible announcement (active + within its date range).
    Expired or disabled announcements disappear automatically.
--}}
@php
    $clientAnnouncement = \App\Models\Announcement::visible()->orderByDesc('id')->first();
@endphp

@if ($clientAnnouncement && auth()->user()?->role === 'client')
    <div class="mx-4 sm:mx-6 lg:mx-8 mt-4"
         x-data
         x-init="$nextTick(() => { const el = document.getElementById('client-announcement-banner'); if (el) el.scrollIntoView({ block: 'nearest' }); })">
        <div id="client-announcement-banner"
             role="status"
             class="relative overflow-hidden rounded-xl border-s-4 border-orange-500 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-950/40 dark:to-amber-950/30 shadow-md px-4 sm:px-5 py-4">
            <div class="flex items-start gap-3 sm:gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-orange-100 dark:bg-orange-900/60 text-orange-600 dark:text-orange-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </span>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-orange-600 dark:text-orange-400">
                            {{ __('Announcement') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 dark:bg-orange-900/60 text-orange-700 dark:text-orange-300 border border-orange-200 dark:border-orange-800">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $clientAnnouncement->start_date->format('d/m/Y') }} — {{ $clientAnnouncement->end_date->format('d/m/Y') }}
                        </span>
                    </div>
                    <p class="text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-100 leading-relaxed whitespace-pre-line">{{ $clientAnnouncement->message }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
