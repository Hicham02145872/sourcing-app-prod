<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Quotations'), 'url' => route('client.quotations.index')],
    ['label' => __('Bulk Payment')]
]">
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="px-4 py-2 border-b border-red-200 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider">{{ __('Validation Errors') }}</span>
                    </div>
                    <ul class="px-4 py-3 list-disc list-inside text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Left/Main: Quotations List Breakdown --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700 flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Payment Summary') }}</h3>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Detailed breakdown of selected requests') }}</p>
                            </div>
                        </div>

                        <div class="p-6 divide-y divide-[#EBEBEB] dark:divide-slate-700">
                            @php
                                $totalSum = 0;
                            @endphp
                            @foreach ($quotations as $quotation)
                                @php
                                    $selectedQuality = request()->query('qualities')[$quotation->id] ?? null;
                                    $unitPrice = $quotation->unit_price;
                                    $amount = $quotation->amount;
                                    $qualityLabel = '';
                                    if ($selectedQuality && isset($quotation->quality_options[$selectedQuality])) {
                                        $unitPrice = $quotation->quality_options[$selectedQuality]['price'];
                                        $totalQuantity = $quotation->sourcingRequest->destinations->sum('quantity');
                                        $subtotal = $unitPrice * $totalQuantity;
                                        $amount = $subtotal + $quotation->commission_service + $quotation->delivery_cost_china;
                                        $qualityLabel = __('(:quality Quality)', ['quality' => ucfirst($selectedQuality)]);
                                    }
                                    $totalSum += $amount;
                                @endphp
                                <div class="py-4 flex gap-4 first:pt-0 last:pb-0">
                                    {{-- Image --}}
                                    <div class="w-16 h-16 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border-2 border-[#EBEBEB] dark:border-slate-600 overflow-hidden flex-shrink-0 shadow-sm">
                                        @if ($quotation->sourcingRequest->product_image)
                                            <img src="{{ asset('storage/' . $quotation->sourcingRequest->product_image) }}" alt="{{ $quotation->sourcingRequest->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                <span class="text-base font-black text-[#EF7722]">
                                                    {{ mb_substr($quotation->sourcingRequest->product_name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Details --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start gap-2">
                                            <div>
                                                <span class="text-xs font-black text-[#EF7722]">{{ $quotation->sourcingRequest->reference_id }}</span>
                                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                                    {{ $quotation->sourcingRequest->product_name }}
                                                </h4>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    {{ __('Category') }}: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $quotation->sourcingRequest->category?->name }}</span>
                                                </p>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <p class="text-sm font-extrabold text-slate-900 dark:text-white">
                                                    {{ number_format($amount, 2) }} {{ $quotation->currency }}
                                                    @if($qualityLabel)
                                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 block">{{ $qualityLabel }}</span>
                                                    @endif
                                                </p>
                                                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 font-semibold">
                                                    {{ __('Unit Price') }}: {{ number_format($unitPrice, 2) }} {{ $quotation->currency }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Column: Payment Methods and Upload --}}
                <div class="space-y-6">
                    {{-- Total Summary Card --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 bg-slate-50 dark:bg-slate-900/30 border-b border-[#EBEBEB] dark:border-slate-700">
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">{{ __('Grand Total') }}</p>
                            <h3 class="text-3xl font-extrabold text-[#EF7722]">
                                {{ number_format($totalSum, 2) }} {{ $quotations->first()->currency }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                {{ __('Paying for :count item(s) in a single transaction.', ['count' => $quotations->count()]) }}
                            </p>
                        </div>
                    </div>

                    {{-- Upload Proof Form --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">{{ __('Submit Payment') }}</h3>
                        </div>
                        <div class="p-6">
                            
                            {{-- Payment Instructions --}}
                            @if ($paymentMethods->isNotEmpty())
                                <div class="mb-6 space-y-4">
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Payment Accounts') }}</label>
                                    @foreach($paymentMethods as $pm)
                                        <div class="p-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $pm->name }}</span>
                                            </div>
                                            <div class="space-y-1.5">
                                                @if (is_array($pm->details))
                                                    @foreach($pm->details as $key => $value)
                                                        <div class="flex justify-between items-center p-2 bg-white dark:bg-slate-800 rounded border border-[#EBEBEB] dark:border-slate-700">
                                                            <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">{{ $key }}:</span>
                                                            <span class="text-xs font-semibold text-slate-900 dark:text-white">{{ $value }}</span>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-xs text-slate-600 dark:text-slate-400 whitespace-pre-line leading-relaxed font-mono">
                                                        {{ $pm->details }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <form id="bulk-payment-proof-form" action="{{ route('client.quotations.bulk-pay') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                @foreach($quotations as $quotation)
                                    <input type="hidden" name="quotation_ids[]" value="{{ $quotation->id }}">
                                    @php
                                        $selectedQuality = request()->query('qualities')[$quotation->id] ?? null;
                                    @endphp
                                    @if($selectedQuality)
                                        <input type="hidden" name="qualities[{{ $quotation->id }}]" value="{{ $selectedQuality }}">
                                    @endif
                                @endforeach

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Upload Proof of Payment') }}</label>
                                    <input type="file" 
                                           id="proof_of_payment"
                                           name="proof_of_payment" 
                                           accept="image/*,application/pdf"
                                           class="block w-full text-sm text-slate-600 dark:text-slate-400
                                                  file:mr-4 file:py-2.5 file:px-4
                                                  file:rounded-lg file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-[#EF7722] file:text-white
                                                  hover:file:bg-[#FAA533]
                                                  file:cursor-pointer file:transition-colors
                                                  border-2 border-dashed border-[#EBEBEB] dark:border-slate-600 rounded-lg
                                                  hover:border-[#EF7722] dark:hover:border-[#FAA533] transition-colors
                                                  cursor-pointer p-2"
                                           required/>
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 italic">
                                        {{ __('Accepted formats: PDF, JPG, PNG. Max 15MB.') }}
                                    </p>
                                </div>

                                <button type="submit" id="submit-proof-btn"
                                        class="w-full py-3 px-4 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="btn-text" class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Submit Payment & Accept All') }}
                                    </span>
                                    <span id="btn-loading" class="hidden items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Optimizing & Uploading...') }}
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('bulk-payment-proof-form').addEventListener('submit', function () {
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            const submitBtn = document.getElementById('submit-proof-btn');
            
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            btnLoading.classList.add('flex');
            submitBtn.disabled = true;
        });
    </script>
</x-app-layout>
