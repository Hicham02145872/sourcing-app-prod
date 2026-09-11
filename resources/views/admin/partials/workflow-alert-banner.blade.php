<!-- Persistent workflow alert banner (Meta Ads account-restriction pattern).
     Reused by the SLA module via the same component. -->
@if ($showInReviewLimitBanner ?? false)
    <div class="bg-amber-50 border-b border-amber-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-amber-100 text-amber-700 shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">{{ __('workflow.in_review_banner_title') }}</p>
                        <p class="text-xs text-amber-700 mt-0.5">
                            {{ __('workflow.in_review_banner_message', ['count' => $inReviewLimit ?? config('fsb.workflow.in_review_limit', 5)]) }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.sourcing-requests.index', ['status' => 'in_review', 'admin_id' => 'me']) }}"
                   class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-md shadow-sm transition-colors shrink-0">
                    {{ __('workflow.in_review_banner_cta') }}
                    <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
        </div>
    </div>
@endif

@if (($showSlaOverdueBanner ?? false) && ! empty($slaOverdueByStatus ?? []))
    <div class="bg-red-50 border-b border-red-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-100 text-red-700 shrink-0 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-red-800">{{ __('sla.banner_title') }}</p>
                        <p class="text-xs text-red-700 mt-0.5">{{ __('sla.banner_message', ['count' => $slaOverdueCount ?? 0]) }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    @foreach ($slaOverdueByStatus as $slaStatus => $slaCount)
                        <a href="{{ route('admin.sourcing-requests.index', ['status' => $slaStatus, 'overdue' => 1]) }}"
                           class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-md shadow-sm transition-colors">
                            {{ __('sla.banner_cta', ['status' => __($slaStatus), 'count' => $slaCount]) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif