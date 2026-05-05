<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 15v-1a4 4 0 0 0-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Review Refund Request') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <a href="{{ route('admin.refund-requests.index') }}" class="hover:text-slate-700">{{ __('Refunds') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">#{{ $refundRequest->id }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.refund-requests.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Primary Details -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Status & Basic Info -->
                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-50 border-amber-200 text-amber-700',
                            'under_review' => 'bg-blue-50 border-blue-200 text-blue-700',
                            'approved' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                            'rejected' => 'bg-red-50 border-red-200 text-red-700',
                        ];
                        $statusClass = $statusClasses[$refundRequest->status] ?? 'bg-slate-50 border-slate-200 text-slate-700';
                    @endphp
                    <div class="p-5 rounded-lg border {{ $statusClass }} flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded bg-white flex items-center justify-center text-current border border-current/20">
                                @if($refundRequest->status === 'pending')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @elseif($refundRequest->status === 'approved')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-bold uppercase tracking-tight">{{ __('Status') }}: {{ __(str_replace('_', ' ', $refundRequest->status)) }}</h3>
                                <p class="text-xs opacity-75">{{ __('Requested on') }} {{ $refundRequest->created_at->translatedFormat('M d, Y @ H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Client Detail Card -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Client Information') }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Full Name') }}</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $refundRequest->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Email Address') }}</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-900">{{ $refundRequest->user->email }}</dd>
                            </div>
                        </div>
                    </div>

                    <!-- Claim Detail Card -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Reason & Description') }}</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex flex-wrap gap-8">
                                <div>
                                    <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Refund Type') }}</dt>
                                    <dd class="mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-tight {{ $refundRequest->type === 'full' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ __($refundRequest->type) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Category') }}</dt>
                                    <dd class="mt-2 text-sm font-bold text-slate-800">{{ __($refundRequest->reason_category) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Damaged Quantity') }}</dt>
                                    <dd class="mt-2 text-sm font-bold text-slate-900">
                                        <span class="text-red-600">{{ $refundRequest->damaged_quantity ?? 0 }}</span>
                                        <span class="text-slate-400 mx-1">/</span>
                                        <span class="text-slate-600">{{ $refundRequest->sourcingOrder->quotation->sourcingRequest->countries->sum('pivot.quantity') }}</span>
                                        <span class="text-[10px] text-slate-400 ml-1 font-medium italic">({{ __('Total Ordered') }})</span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Amount Requested') }}</dt>
                                    <dd class="mt-2 text-sm font-bold text-slate-900">{{ number_format($refundRequest->amount_requested, 2) }} {{ $refundRequest->sourcingOrder->quotation->currency }}</dd>
                                </div>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Client Description') }}</dt>
                                <dd class="p-4 bg-slate-50 rounded-lg text-sm text-slate-600 italic border border-slate-100 leading-relaxed whitespace-pre-line">
                                    {{ $refundRequest->reason_description }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    <!-- Product Context Comparison -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-slate-900">{{ __('3-Way Visual Verification') }}</h3>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('Workflow Comparison') }}</span>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- 1. Original Request Image -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block text-center">{{ __('Original Wanted') }}</label>
                                    <div class="relative aspect-square rounded-xl border-2 border-slate-100 bg-slate-50 overflow-hidden group cursor-pointer" onclick="openMediaModal('{{ $refundRequest->sourcingOrder->quotation->sourcingRequest->product_image ? asset('storage/' . $refundRequest->sourcingOrder->quotation->sourcingRequest->product_image) : asset('assets/images/placeholder.png') }}', 'image')">
                                        <img src="{{ $refundRequest->sourcingOrder->quotation->sourcingRequest->product_image ? asset('storage/' . $refundRequest->sourcingOrder->quotation->sourcingRequest->product_image) : asset('assets/images/placeholder.png') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-x-0 bottom-0 bg-black/60 p-2 opacity-0 group-hover:opacity-100 transition-opacity text-center">
                                            <span class="text-white text-[9px] font-black uppercase tracking-widest">{{ __('Enlarge Original') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Actual Sourced Image (Quotation) -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block text-center">{{ __('Actual Sourced') }}</label>
                                    @php
                                        $realImage = $refundRequest->sourcingOrder->quotation->real_product_image;
                                        // Fallback to first image in media collection if real_product_image is null
                                        if (!$realImage && $refundRequest->sourcingOrder->quotation->media->count() > 0) {
                                            $firstImageMedia = $refundRequest->sourcingOrder->quotation->media->where('file_type', 'image')->first();
                                            $realImage = $firstImageMedia ? $firstImageMedia->file_path : null;
                                        }
                                        $realImageUrl = $realImage ? asset('storage/' . $realImage) : null;
                                    @endphp
                                    <div class="relative aspect-square rounded-xl border-2 border-slate-100 bg-slate-50 overflow-hidden group cursor-pointer" @if($realImageUrl) onclick="openMediaModal('{{ $realImageUrl }}', 'image')" @endif>
                                        @if($realImageUrl)
                                            <img src="{{ $realImageUrl }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-x-0 bottom-0 bg-black/60 p-2 opacity-0 group-hover:opacity-100 transition-opacity text-center">
                                                <span class="text-white text-[9px] font-black uppercase tracking-widest">{{ __('Enlarge Sourced') }}</span>
                                            </div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 gap-2">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('No Sourced Image') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- 3. Damage Evidence -->
                                <div class="space-y-3">
                                    <label class="text-[10px] font-bold text-red-400 uppercase tracking-wider block text-center">{{ __('Reported Damage') }}</label>
                                    @php
                                        $firstEvidence = $refundRequest->evidence_paths[0] ?? null;
                                        $evidenceUrl = $firstEvidence ? asset('storage/' . $firstEvidence) : null;
                                        $isVid = $firstEvidence && in_array(strtolower(pathinfo($firstEvidence, PATHINFO_EXTENSION)), ['mp4', 'mov', 'avi', 'webm']);
                                    @endphp
                                    <div class="relative aspect-square rounded-xl border-2 border-red-100 bg-red-50/30 overflow-hidden group cursor-pointer" @if($evidenceUrl) onclick="openMediaModal('{{ $evidenceUrl }}', '{{ $isVid ? 'video' : 'image' }}')" @endif>
                                        @if($evidenceUrl)
                                            @if($isVid)
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-800 bg-slate-900 border-2 border-red-200">
                                                    <svg class="w-12 h-12 mb-1 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    <span class="text-[9px] font-black text-white uppercase tracking-tighter">{{ __('Play Evidence') }}</span>
                                                </div>
                                            @else
                                                <img src="{{ $evidenceUrl }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @endif
                                            <div class="absolute inset-x-0 bottom-0 bg-red-600/80 p-2 opacity-0 group-hover:opacity-100 transition-opacity text-center">
                                                <span class="text-white text-[9px] font-black uppercase tracking-widest">{{ __('Enlarge Evidence') }}</span>
                                            </div>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-red-200 gap-2">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('No Evidence Provided') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client Evidence Gallery -->
                    @if($refundRequest->evidence_paths && count($refundRequest->evidence_paths) > 0)
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900">{{ __('Client Evidence Portfolio') }}</h3>
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-0.5 rounded-full border border-blue-100">{{ count($refundRequest->evidence_paths) }} {{ __('Files') }}</span>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    @foreach($refundRequest->evidence_paths as $path)
                                        @php
                                            $extension = pathinfo($path, PATHINFO_EXTENSION);
                                            $isVideo = in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'webm']);
                                        @endphp
                                        <div class="group relative aspect-square rounded-xl border border-slate-100 bg-slate-50 overflow-hidden cursor-pointer hover:border-orange-500 transition-all duration-200" onclick="openMediaModal('{{ asset('storage/' . $path) }}', '{{ $isVideo ? 'video' : 'image' }}')">
                                            @if($isVideo)
                                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-900">
                                                    <svg class="w-10 h-10 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                    <span class="text-[9px] font-black uppercase tracking-tighter">{{ __('Play Video') }}</span>
                                                </div>
                                            @else
                                                <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @endif
                                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Context & Actions -->
                <div class="space-y-6">
                    
                    <!-- Order Card -->
                    <div class="bg-slate-900 rounded-lg p-6 text-white shadow-lg overflow-hidden relative group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-all"></div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-4">{{ __('Associated Order') }}</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">{{ __('Order ID') }}</span>
                                <a href="{{ route('admin.sourcing-orders.show', $refundRequest->sourcingOrder) }}" class="text-sm font-bold hover:text-orange-400 transition-colors">#{{ $refundRequest->sourcingOrder->display_id }}</a>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">{{ __('Total Price') }}</span>
                                <span class="text-sm font-bold">{{ number_format($refundRequest->sourcingOrder->total_amount, 2) }} USD</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-slate-800">
                                <span class="text-xs text-slate-500">{{ __('Project Status') }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-slate-800 border border-slate-700">
                                    {{ __(str_replace('_', ' ', $refundRequest->sourcingOrder->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Resolution Decision Panel -->
                    @if($refundRequest->status === 'pending' || $refundRequest->status === 'under_review')
                        <div class="bg-white rounded-lg border-t-4 border-t-orange-500 border border-slate-200 shadow-md">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="text-sm font-bold text-slate-900">{{ __('Processing Resolution') }}</h3>
                            </div>
                            <form action="{{ route('admin.refund-requests.update-status', $refundRequest) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6" 
                                x-data="{ 
                                    decision: '', 
                                    amount: '{{ $refundRequest->amount_requested }}',
                                    totalOrder: {{ $refundRequest->sourcingOrder->total_amount }},
                                    totalQty: {{ $refundRequest->sourcingOrder->quotation->sourcingRequest->countries->sum('pivot.quantity') }},
                                    damagedQty: {{ $refundRequest->damaged_quantity ?? 0 }},
                                    templates: {
                                        insufficient: '{{ __('Insufficient evidence provided. Please upload clear photos showing the damage and the shipping label.') }}',
                                        quality: '{{ __('Quality issues fall under standard manufacturer variance and do not qualify for a full refund based on terms.') }}',
                                        damaged: '{{ __('Refund approved for the reported damaged items after visual verification.') }}',
                                        expired: '{{ __('The claim was submitted outside of the allowed 48-hour window after delivery.') }}',
                                        mismatch: '{{ __('The provided evidence does not match the items delivered in this order.') }}'
                                    },
                                    applyTemplate(key) {
                                        $refs.adminNotes.value = this.templates[key];
                                    },
                                    calculateRefund() {
                                        if (this.totalQty > 0) {
                                            this.amount = ((this.totalOrder / this.totalQty) * this.damagedQty).toFixed(2);
                                        }
                                    }
                                }">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Decision') }}</label>
                                    <div class="space-y-2">
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors group" :class="decision === 'approved' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-100 hover:border-slate-200'">
                                            <input type="radio" name="status" value="approved" x-model="decision" class="w-4 h-4 text-emerald-600 border-slate-300 focus:ring-emerald-500" required>
                                            <span class="ml-3 text-xs font-bold" :class="decision === 'approved' ? 'text-emerald-700' : 'text-slate-600'">{{ __('Approve Refund') }}</span>
                                        </label>
                                        <label class="flex items-center p-3 border rounded-lg cursor-pointer transition-colors group" :class="decision === 'rejected' ? 'border-red-500 bg-red-50' : 'border-slate-100 hover:border-slate-200'">
                                            <input type="radio" name="status" value="rejected" x-model="decision" class="w-4 h-4 text-red-600 border-slate-300 focus:ring-red-500" required>
                                            <span class="ml-3 text-xs font-bold" :class="decision === 'rejected' ? 'text-red-700' : 'text-slate-600'">{{ __('Reject Claim') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <div x-show="decision === 'approved'" class="space-y-4 p-4 bg-slate-50 border border-slate-200 rounded-xl" x-transition>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Approved Amount') }} ({{ $refundRequest->sourcingOrder->quotation->currency }})</label>
                                        <button type="button" @click="calculateRefund()" class="text-[10px] font-black text-orange-600 hover:text-orange-700 uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            {{ __('Auto-Calc') }}
                                        </button>
                                    </div>
                                    <input type="number" name="amount_approved" x-model="amount" step="0.01" min="0" max="{{ $refundRequest->sourcingOrder->total_amount }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-lg font-black text-slate-900 focus:ring-orange-500 focus:border-orange-500 transition-all">
                                    <p class="text-[9px] text-slate-400 font-bold italic">{{ __('Formula') }}: (Total Order / Total Qty) * Damaged Qty</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Quick Response Template') }}</label>
                                    <select @change="applyTemplate($event.target.value)" class="w-full text-xs font-bold bg-slate-50 border-slate-200 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                                        <option value="">{{ __('Select a template...') }}</option>
                                        <option value="insufficient">{{ __('Insufficient Evidence') }}</option>
                                        <option value="quality">{{ __('Quality Variance') }}</option>
                                        <option value="damaged">{{ __('Damaged Verification Success') }}</option>
                                        <option value="expired">{{ __('Claim Window Expired') }}</option>
                                        <option value="mismatch">{{ __('Evidence Mismatch') }}</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Resolution Notes') }}</label>
                                    <textarea name="admin_notes" x-ref="adminNotes" rows="4" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-600 focus:ring-orange-500 focus:border-orange-500 leading-relaxed" placeholder="{{ __('Draft your final response to the client...') }}"></textarea>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Proof of Refund') }}</label>
                                    <input type="file" name="refund_proof" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:bg-slate-900 file:text-white hover:file:bg-black cursor-pointer border border-slate-200 p-2 rounded-lg bg-slate-50">
                                </div>

                                <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-black text-white text-xs font-black uppercase tracking-[0.2em] rounded-xl transition-all shadow-lg hover:shadow-orange-500/20 transform active:scale-95 disabled:opacity-50" :disabled="!decision">
                                    {{ __('Finalize Resolution') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- History / Audit Panel -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="text-sm font-bold text-slate-900">{{ __('Decision Results') }}</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="p-4 rounded-lg {{ $refundRequest->status === 'approved' ? 'bg-emerald-50 text-emerald-800 border border-emerald-100' : 'bg-red-50 text-red-800 border border-red-100' }}">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider opacity-60">{{ __('Authorized Amount') }}</dt>
                                    <dd class="text-lg font-bold mt-1">{{ number_format($refundRequest->amount_approved ?? 0, 2) }} USD</dd>
                                </div>

                                @if($refundRequest->refund_proof_path)
                                    <div>
                                        <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Refund Receipt') }}</dt>
                                        <a href="{{ asset('storage/' . $refundRequest->refund_proof_path) }}" target="_blank" class="flex items-center gap-3 p-3 border border-slate-200 rounded bg-slate-50 hover:bg-slate-100 transition-colors text-slate-600">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span class="text-xs font-bold">{{ __('Download Evidence') }}</span>
                                        </a>
                                    </div>
                                @endif

                                @if($refundRequest->admin_notes)
                                    <div>
                                        <dt class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">{{ __('Final Notes') }}</dt>
                                        <dd class="text-xs text-slate-600 italic bg-slate-50 p-3 rounded leading-relaxed border border-slate-100">"{{ $refundRequest->admin_notes }}"</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
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
            <img id="modalImage" src="" class="max-w-full max-h-[85vh] rounded shadow-2xl hidden object-contain">
            <video id="modalVideo" src="" controls class="max-w-full max-h-[85vh] rounded shadow-2xl hidden bg-black"></video>
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

