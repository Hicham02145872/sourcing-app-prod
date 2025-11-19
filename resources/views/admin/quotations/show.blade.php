<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Quotation Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('View and manage quotation information') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto justify-center sm:justify-start">
                    <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('Back') }}
                    </a>
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        {{ __('Print') }}
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Status Badge & ID --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#EF7722]/10 to-[#FAA533]/10 rounded-lg flex items-center justify-center">
                        <span class="text-xl font-bold text-[#EF7722] dark:text-[#FAA533]">#{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $quotation->sourcingRequest->product_name }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Created on') }} {{ $quotation->created_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                @php
                    $statusConfig = [
                        'pending' => ['badge' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'approved' => ['badge' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400', 'icon' => 'M5 13l4 4L19 7'],
                        'rejected' => ['badge' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400', 'icon' => 'M6 18L18 6M6 6l12 12'],
                    ];
                    $config = $statusConfig[$quotation->status] ?? $statusConfig['pending'];
                @endphp
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $config['badge'] }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                    </svg>
                    <span class="font-semibold text-sm">{{ ucfirst($quotation->status) }}</span>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column - Details --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Quotation Details Card --}}
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Quotation Information') }}
                            </h4>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Quotation ID') }}</label>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">#{{ str_pad($quotation->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Product Name') }}</label>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $quotation->sourcingRequest->product_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Amount') }}</label>
                                    <p class="text-lg font-bold text-[#EF7722] dark:text-[#FAA533]">{{ number_format($quotation->amount, 2) }} {{ $quotation->currency }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Status') }}</label>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold border rounded-lg {{ $config['badge'] }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                        </svg>
                                        {{ ucfirst($quotation->status) }}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Category') }}</label>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $quotation->sourcingRequest->category->name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Created Date') }}</label>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $quotation->created_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Description Card --}}
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                {{ __('Product Details') }}
                            </h4>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Description') }}</label>
                                <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $quotation->sourcingRequest->description ?? __('No description provided') }}</p>
                            </div>
                            @if($quotation->sourcingRequest->product_url)
                            <div>
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Product URL') }}</label>
                                <a href="{{ $quotation->sourcingRequest->product_url }}" target="_blank" class="text-[#EF7722] dark:text-[#FAA533] hover:underline">{{ $quotation->sourcingRequest->product_url }}</a>
                            </div>
                            @endif
                            @if($quotation->sourcingRequest->product_image)
                            <div>
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Product Image') }}</label>
                                <img src="{{ asset('storage/' . $quotation->sourcingRequest->product_image) }}" alt="Product Image" class="w-32 h-32 object-cover rounded-lg shadow">
                            </div>
                            @endif
                            @if($quotation->sourcingRequest->note)
                            <div>
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Note') }}</label>
                                <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $quotation->sourcingRequest->note }}</p>
                            </div>
                            @endif
                            @if($quotation->sourcingRequest->shipping_method)
                            <div>
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Shipping Method') }}</label>
                                <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $quotation->sourcingRequest->shipping_method }}</p>
                            </div>
                            @endif
                            @if($quotation->sourcingRequest->sourcing_location)
                            <div>
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Sourcing Location') }}</label>
                                <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $quotation->sourcingRequest->sourcing_location }}</p>
                            </div>
                            @endif
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Quantity') }}</label>
                                    <p class="text-slate-900 dark:text-white font-semibold">{{ $quotation->sourcingRequest->quantity }} {{ $quotation->sourcingRequest->unit }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide block mb-2">{{ __('Minimum Order') }}</label>
                                    <p class="text-slate-900 dark:text-white font-semibold">{{ $quotation->sourcingRequest->minimum_order_quantity ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column - Client Info --}}
                <div class="space-y-6">
                    
                    {{-- Client Card --}}
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <h4 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16a4 4 0 11-8 0 4 4 0 018 0zM9 12a4 4 0 100-8 4 4 0 000 8zm9-2h6m-3-3v6"/>
                                </svg>
                                {{ __('Client Information') }}
                            </h4>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg font-bold text-slate-600 dark:text-slate-300">
                                        {{ substr($quotation->sourcingRequest->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $quotation->sourcingRequest->user->name }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 truncate">{{ $quotation->sourcingRequest->user->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Email') }}</label>
                                <a href="mailto:{{ $quotation->sourcingRequest->user->email }}" class="text-[#EF7722] dark:text-[#FAA533] hover:text-[#FAA533] dark:hover:text-[#EF7722] text-sm font-medium break-all">
                                    {{ $quotation->sourcingRequest->user->email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Amount Summary Card --}}
                    <div class="bg-gradient-to-br from-[#EF7722]/10 to-[#FAA533]/10 shadow-sm rounded-lg border border-[#EF7722]/20 dark:border-[#FAA533]/20 overflow-hidden">
                        <div class="px-6 py-4">
                            <h4 class="text-lg font-bold text-[#EF7722] dark:text-[#FAA533] flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Amount Summary') }}
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-[#EF7722] dark:text-[#FAA533]">{{ __('Subtotal') }}</span>
                                    <span class="font-semibold text-[#EF7722] dark:text-[#FAA533]">{{ number_format($quotation->amount, 2) }} {{ $quotation->currency }}</span>
                                </div>
                                <div class="border-t border-[#EF7722]/20 dark:border-[#FAA533]/20 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-[#EF7722] dark:text-[#FAA533]">{{ __('Total') }}</span>
                                        <span class="text-2xl font-bold text-[#EF7722] dark:text-[#FAA533]">{{ number_format($quotation->amount, 2) }} {{ $quotation->currency }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions Card --}}
                    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 space-y-3">
                            <button type="button" onclick="window.print()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                {{ __('Print Quotation') }}
                            </button>
                            @if($quotation->status === 'pending')
                            <div class="grid grid-cols-2 gap-3">
                                <form action="{{ route('admin.quotations.approve', $quotation->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Approve') }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.quotations.reject', $quotation->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('Reject') }}
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enterprise table styling */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        @media print {
            .no-print {
                display: none;
            }
            
            .bg-gradient-to-r {
                background: #EF7722 !important;
                -webkit-print-color-adjust: exact;
            }
            
            .text-\[\#EF7722\] {
                color: #EF7722 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</x-app-layout>