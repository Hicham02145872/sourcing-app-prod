<x-app-layout>
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Breadcrumbs --}}
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('client.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#EF7722] dark:text-slate-400 transition-colors uppercase tracking-wider">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('client.refund-requests.index') }}" class="ml-1 text-xs font-semibold text-slate-500 hover:text-[#EF7722] dark:text-slate-400 transition-colors uppercase tracking-wider">
                                {{ __('Refunds') }}
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-xs font-bold text-[#EF7722] uppercase tracking-wider">{{ __('Claim Details') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden mb-8">
                <div class="px-6 py-6 border-b border-[#EBEBEB] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center text-[#EF7722]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Refund Claim') }} #{{ $refundRequest->id }}</h2>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-semibold text-[#EF7722]">#{{ $refundRequest->sourcingOrder->display_id }}</span>
                                    <span class="text-slate-400 text-xs">•</span>
                                    <span class="text-slate-600 dark:text-slate-400 text-xs font-medium">{{ $refundRequest->sourcingOrder->quotation->sourcingRequest->product_name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex shrink-0">
                            <a href="{{ route('client.refund-requests.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#EF7722] transition-colors uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <!-- Left Column: Primary Details -->
                        <div class="lg:col-span-2 space-y-8">
                            
                            <!-- Status Header -->
                            @php
                                $statusConfigs = [
                                    'pending' => ['class' => 'bg-amber-50 border-amber-200 text-amber-700 dark:bg-amber-900/10 dark:border-amber-900/30 dark:text-amber-400', 'label' => __('Awaiting Review')],
                                    'under_review' => ['class' => 'bg-blue-50 border-blue-200 text-blue-700 dark:bg-blue-900/10 dark:border-blue-900/30 dark:text-blue-400', 'label' => __('Under Review')],
                                    'approved' => ['class' => 'bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-900/10 dark:border-emerald-900/30 dark:text-emerald-400', 'label' => __('Claim Approved')],
                                    'rejected' => ['class' => 'bg-red-50 border-red-200 text-red-700 dark:bg-red-900/10 dark:border-red-900/30 dark:text-red-400', 'label' => __('Claim Rejected')],
                                ];
                                $config = $statusConfigs[$refundRequest->status] ?? $statusConfigs['pending'];
                            @endphp
                            
                            <div class="p-5 rounded-lg border {{ $config['class'] }} flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded bg-white dark:bg-slate-800 flex items-center justify-center text-current border border-current/20">
                                        @if($refundRequest->status === 'pending')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($refundRequest->status === 'approved')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-xs font-bold uppercase tracking-widest">{{ __('Resolution Status') }}: {{ $refundRequest->status_label }}</h3>
                                        <p class="text-[10px] opacity-75 uppercase tracking-tight">{{ __('Last updated') }} {{ $refundRequest->updated_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Administrator Feedback (Conditional) -->
                            @if($refundRequest->admin_notes && ($refundRequest->status === 'approved' || $refundRequest->status === 'rejected'))
                                <div class="bg-white dark:bg-slate-900 rounded-lg border border-[#EBEBEB] dark:border-slate-700 shadow-sm overflow-hidden">
                                    <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Support Team Message') }}</h3>
                                    </div>
                                    <div class="p-6">
                                        <p class="text-sm text-slate-600 dark:text-slate-400 italic leading-relaxed border-l-4 border-[#EF7722] pl-4 py-1">
                                            "{{ $refundRequest->admin_notes }}"
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Claim Details -->
                            <div class="bg-white dark:bg-slate-900 rounded-lg border border-[#EBEBEB] dark:border-slate-700 shadow-sm overflow-hidden">
                                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex justify-between items-center">
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Claim Submission') }}</h3>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $refundRequest->created_at->format('d M, Y') }}</span>
                                </div>
                                <div class="p-6 space-y-8">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Refund Type') }}</dt>
                                            <dd class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-tight {{ $refundRequest->type === 'full' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                                    {{ $refundRequest->type }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Category') }}</dt>
                                            <dd class="mt-1 text-sm font-bold text-slate-800 dark:text-slate-200">{{ __($refundRequest->reason_category) }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ __('Amount Requested') }}</dt>
                                            <dd class="mt-1 text-sm font-bold text-slate-900 dark:text-white">{{ number_format($refundRequest->amount_requested, 2) }} {{ $refundRequest->sourcingOrder->quotation->currency }}</dd>
                                        </div>
                                    </div>

                                    <div>
                                        <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">{{ __('Statement of Claim') }}</dt>
                                        <dd class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg text-sm text-slate-600 dark:text-slate-400 italic border border-[#EBEBEB] dark:border-slate-700 leading-relaxed">
                                            "{{ $refundRequest->reason_description }}"
                                        </dd>
                                    </div>

                                    <!-- Attached Evidence -->
                                    @if($refundRequest->evidence_paths && count($refundRequest->evidence_paths) > 0)
                                        <div>
                                            <dt class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">{{ __('Attached Evidence') }} ({{ count($refundRequest->evidence_paths) }})</dt>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-4">
                                                @foreach($refundRequest->evidence_paths as $path)
                                                    @php
                                                        $extension = pathinfo($path, PATHINFO_EXTENSION);
                                                        $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'webm']);
                                                    @endphp
                                                    <div class="group relative aspect-square rounded-lg border border-[#EBEBEB] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 overflow-hidden cursor-pointer hover:border-[#EF7722] transition-all duration-200" onclick="openMediaModal('{{ media_url($path) }}', '{{ $isVideo ? 'video' : 'image' }}')">
                                                        @if($isVideo)
                                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                            </div>
                                                        @else
                                                            <img src="{{ media_url($path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                                        @endif
                                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Sidebar Context -->
                        <div class="space-y-8">
                            
                            <!-- Financial Summary -->
                            @if($refundRequest->status === 'approved')
                                <div class="bg-[#EF7722] rounded-lg p-6 text-white shadow-lg overflow-hidden relative group">
                                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                                    <h3 class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-6">{{ __('Authorized Refund') }}</h3>
                                    <div class="text-center">
                                        <span class="text-4xl font-black">{{ number_format($refundRequest->amount_approved, 2) }}</span>
                                        <span class="text-xs font-bold text-white/80 uppercase ml-1">{{ $refundRequest->sourcingOrder->quotation->currency }}</span>
                                    </div>
                                    
                                    @if($refundRequest->refund_proof_path)
                                        <div class="mt-6 pt-6 border-t border-white/10 text-center">
                                            <a href="{{ media_url($refundRequest->refund_proof_path) }}" target="_blank" class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-white/70 hover:text-white transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                {{ __('Download Receipt') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="bg-white dark:bg-slate-800 rounded-lg border-2 border-dashed border-[#EBEBEB] dark:border-slate-700 p-6 text-center">
                                    <h3 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">{{ __('Refund Valuation') }}</h3>
                                    <div class="text-2xl font-black text-slate-300 dark:text-slate-600">
                                        {{ number_format($refundRequest->amount_requested, 2) }}
                                        <span class="text-xs uppercase">{{ $refundRequest->sourcingOrder->quotation->currency }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 italic uppercase tracking-tight">{{ __('Awaiting administrative authorization') }}</p>
                                </div>
                            @endif

                            <!-- Linked Order Context -->
                            <div class="bg-white dark:bg-slate-900 rounded-lg border border-[#EBEBEB] dark:border-slate-700 shadow-sm overflow-hidden">
                                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Order Context') }}</h3>
                                </div>
                                <div class="p-6 space-y-4">

                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-slate-500 uppercase tracking-tight font-medium">{{ __('Paid Total') }}</span>
                                        <span class="font-black text-slate-900 dark:text-white">{{ number_format($refundRequest->sourcingOrder->total_amount, 2) }} {{ $refundRequest->sourcingOrder->quotation->currency }}</span>
                                    </div>
                                    <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                        <a href="{{ route('client.sourcing-orders.show', $refundRequest->sourcingOrder) }}" class="flex items-center justify-center gap-2 w-full py-2.5 bg-slate-900 dark:bg-slate-800 hover:bg-black dark:hover:bg-slate-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all">
                                            {{ __('Order Details') }}
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Priority Support Info -->
                            <div class="p-5 bg-[#EF7722]/5 dark:bg-[#EF7722]/10 rounded-lg border border-[#EF7722]/20 flex items-start gap-4">
                                <div class="text-[#EF7722] shrink-0 mt-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-bold text-[#EF7722] uppercase tracking-widest mb-1">{{ __('Case Reference') }}</h4>
                                    <p class="text-[10px] font-medium text-slate-600 dark:text-slate-400 leading-relaxed uppercase tracking-tight">{{ __('Your claim is currently handled by our priority support team. We will notify you of any updates.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Viewer Modal -->
    <div id="mediaModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/95 backdrop-blur-sm" onclick="closeMediaModal()">
        <button class="absolute top-6 right-6 text-white/50 hover:text-white" onclick="closeMediaModal()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="max-w-6xl max-h-[90vh] p-4 relative" onclick="event.stopPropagation()">
            <img id="modalImage" src="" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl hidden object-contain">
            <video id="modalVideo" src="" controls class="max-w-full max-h-[85vh] rounded-lg shadow-2xl hidden bg-black"></video>
        </div>
    </div>

    @push('scripts')
    <script>
        function openMediaModal(url, type) {
            const modal = document.getElementById('mediaModal');
            const img = document.getElementById('modalImage');
            const vid = document.getElementById('modalVideo');
            modal.classList.remove('hidden');
            if (type === 'video') {
                img.classList.add('hidden');
                vid.src = url;
                vid.classList.remove('hidden');
            } else {
                vid.classList.add('hidden');
                vid.pause();
                img.src = url;
                img.classList.remove('hidden');
            }
        }
        function closeMediaModal() {
            const modal = document.getElementById('mediaModal');
            const vid = document.getElementById('modalVideo');
            modal.classList.add('hidden');
            vid.pause();
            vid.src = '';
        }
    </script>
    @endpush
</x-app-layout>
