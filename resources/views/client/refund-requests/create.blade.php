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
                            <span class="ml-1 text-xs font-bold text-[#EF7722] uppercase tracking-wider">{{ __('New Claim') }}</span>
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
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Submit Refund Claim') }}</h2>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-semibold text-[#EF7722]">#{{ $sourcingOrder->display_id }}</span>
                                    <span class="text-slate-400 text-xs">•</span>
                                    <span class="text-slate-600 dark:text-slate-400 text-xs font-medium">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</span>
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

                <form action="{{ route('client.sourcing-orders.refund-request', $sourcingOrder) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8" x-data="{ 
                    refundType: 'partial',
                    evidence: [],
                    handleFiles(e) {
                        const files = Array.from(e.target.files);
                        this.evidence = [...this.evidence, ...files];
                    },
                    removeFile(index) {
                        this.evidence.splice(index, 1);
                    }
                }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Refund Type --}}
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Refund Type') }}</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none transition-all duration-200"
                                       :class="refundType === 'full' ? 'border-[#EF7722] ring-1 ring-[#EF7722] bg-[#EF7722]/5' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300'"
                                       @if($remainingAmount < $sourcingOrder->total_amount) style="opacity: 0.5; cursor: not-allowed;" onclick="return false;" @endif>
                                    <input type="radio" name="type" value="full" class="sr-only" x-model="refundType" @if($remainingAmount < $sourcingOrder->total_amount) disabled @endif>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Full Refund') }}</span>
                                            @if($remainingAmount < $sourcingOrder->total_amount)
                                                <span class="text-[8px] bg-red-100 text-red-600 px-1 rounded">{{ __('Unavailable') }}</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-tight">{{ __('Entire order amount') }}</span>
                                    </div>
                                    <svg x-show="refundType === 'full'" class="ml-auto h-5 w-5 text-[#EF7722]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </label>
                                <label class="relative flex cursor-pointer rounded-xl border p-4 shadow-sm focus:outline-none transition-all duration-200"
                                       :class="refundType === 'partial' ? 'border-[#EF7722] ring-1 ring-[#EF7722] bg-[#EF7722]/5' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300'">
                                    <input type="radio" name="type" value="partial" class="sr-only" x-model="refundType">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Partial Refund') }}</span>
                                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-tight">{{ __('Specific items/amount') }}</span>
                                    </div>
                                    <svg x-show="refundType === 'partial'" class="ml-auto h-5 w-5 text-[#EF7722]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </label>
                            </div>
                        </div>

                        {{-- Dynamic Amount Input --}}
                        <div class="space-y-4" x-show="refundType === 'partial'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2">
                            <label for="amount_requested" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Refund Amount') }} ({{ $sourcingOrder->quotation->currency }})</label>
                            <div class="relative mt-1 rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-slate-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="amount_requested" id="amount_requested" step="0.01" min="0.01" max="{{ $remainingAmount }}" 
                                       class="block w-full rounded-xl border-slate-200 dark:border-slate-700 pl-8 focus:border-[#EF7722] focus:ring-[#EF7722] bg-white dark:bg-slate-900 text-slate-900 dark:text-white sm:text-sm"
                                       placeholder="{{ __('Amount placeholder') }}">
                            </div>
                            @if($remainingAmount < $sourcingOrder->total_amount)
                                <p class="text-[10px] text-orange-600 font-bold italic">
                                    {{ __('Note: You can request up to :remaining :currency (Remaining balance)', ['remaining' => number_format($remainingAmount, 2), 'currency' => $sourcingOrder->quotation->currency]) }}
                                </p>
                            @endif
                        </div>
                        <div class="space-y-4" x-show="refundType === 'full'">
                             <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Refund Amount') }}</label>
                             <div class="mt-1 flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                                <span class="text-xl font-black text-slate-900 dark:text-white">{{ number_format($sourcingOrder->total_amount, 2) }}</span>
                                <span class="text-xs font-bold text-slate-500 uppercase">{{ $sourcingOrder->quotation->currency }}</span>
                             </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Damaged Quantity --}}
                        <div class="space-y-4" x-data="{ damagedQty: 0 }">
                            <div class="flex items-center justify-between">
                                <label for="damaged_quantity" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Reported Damaged Quantity') }}
                                </label>
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 bg-[#EBEBEB] dark:bg-slate-900 px-2 py-0.5 rounded-full">
                                    <span x-text="damagedQty || 0" class="text-[#EF7722]"></span> / {{ $sourcingOrder->total_quantity }}
                                </span>
                            </div>
                            <input type="number" name="damaged_quantity" id="damaged_quantity" min="1" max="{{ $sourcingOrder->total_quantity }}" required
                                   x-model="damagedQty"
                                   class="block w-full rounded-xl border-slate-200 dark:border-slate-700 focus:border-[#EF7722] focus:ring-[#EF7722] bg-white dark:bg-slate-900 text-slate-900 dark:text-white sm:text-sm"
                                   placeholder="{{ __('e.g. 5') }}">
                        </div>

                        {{-- Reason Category --}}
                        <div class="space-y-4">
                            <label for="reason_category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Reason Category') }}</label>
                            <select name="reason_category" id="reason_category" required
                                    class="block w-full rounded-xl border-slate-200 dark:border-slate-700 focus:border-[#EF7722] focus:ring-[#EF7722] bg-white dark:bg-slate-900 text-slate-900 dark:text-white sm:text-sm">
                                <option value="">{{ __('Select a reason') }}</option>
                                <option value="damaged_product">{{ __('Damaged Product') }}</option>
                                <option value="wrong_product">{{ __('Wrong Product Received') }}</option>
                                <option value="incomplete_order">{{ __('Incomplete Order (Missing Items)') }}</option>
                                <option value="quality_issue">{{ __('Quality Does Not Match Description') }}</option>
                                <option value="shipping_delayed">{{ __('Significant Shipping Delay') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="space-y-4">
                        <label for="reason_description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Detailed Description') }}</label>
                        <textarea name="reason_description" id="reason_description" rows="4" required minlength="10"
                                  class="block w-full rounded-xl border-slate-200 dark:border-slate-700 focus:border-[#EF7722] focus:ring-[#EF7722] bg-white dark:bg-slate-900 text-slate-900 dark:text-white sm:text-sm placeholder:text-slate-400"
                                  placeholder="{{ __('Please describe the issue in detail. For example, which items are damaged and how.') }}"></textarea>
                    </div>

                    {{-- Evidence Upload --}}
                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Visual Evidence') }} ({{ __('Images / Videos') }})</label>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-2xl bg-slate-50 dark:bg-slate-900/50 hover:bg-white dark:hover:bg-slate-800 transition-colors cursor-pointer relative group"
                             @click="$refs.fileInput.click()">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-[#EF7722] transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                    <span class="relative cursor-pointer bg-transparent rounded-md font-bold text-[#EF7722] focus-within:outline-none">{{ __('Upload files') }}</span>
                                    <p class="pl-1">{{ __('or drag and drop') }}</p>
                                </div>
                                <p class="text-xs text-slate-500 italic">PNG, JPG, MP4 {{ __('up to 50MB') }}</p>
                            </div>
                            <input type="file" name="evidence[]" id="evidence" multiple x-ref="fileInput" @change="handleFiles" class="sr-only">
                        </div>

                        {{-- File Preview --}}
                        <div x-show="evidence.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
                            <template x-for="(file, index) in evidence" :key="index">
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-900">
                                    <div class="absolute inset-0 flex items-center justify-center text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" @click="removeFile(index)" class="p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 p-1 bg-slate-900/80">
                                        <p class="text-[10px] text-white truncate" x-text="file.name"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-8 border-t border-[#EBEBEB] dark:border-slate-700 flex justify-end gap-4">
                        <a href="{{ route('client.refund-requests.index') }}" class="px-6 py-2.5 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-[#EBEBEB] dark:hover:bg-slate-700 transition-colors uppercase tracking-wider">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-xs font-black rounded-lg shadow-sm transition-all duration-200 uppercase tracking-widest">
                            {{ __('Submit Claim') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
