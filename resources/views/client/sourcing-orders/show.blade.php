<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Sourcing Orders'), 'url' => route('client.sourcing-orders.index')],
    ['label' => __('Details')]
]">
    

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-3 text-sm font-medium shadow-sm">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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
            
            {{-- Status Banner --}}
            @php
                $statusConfig = [
                    'pending_payment' => ['color' => '#FAA533', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Payment Required')],
                    'payment_pending_verification' => ['color' => '#0BA6DF', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('Verification in Progress')],
                    'processing' => ['color' => '#0BA6DF', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => __('Processing Order')],
                    'shipped' => ['color' => '#EF7722', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'title' => __('Shipped')],
                    'delivered' => ['color' => '#0BA6DF', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => __('Delivered')],
                ];
                $statusData = $statusConfig[$sourcingOrder->status] ?? $statusConfig['pending_payment'];
            @endphp
            
            <div class="mb-6 bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                <div class="p-6" style="background-color: {{ $statusData['color'] }}22; border-bottom: 4px solid {{ $statusData['color'] }};">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-lg flex items-center justify-center" style="background-color: {{ $statusData['color'] }}22; border: 2px solid {{ $statusData['color'] }};">
                                <svg class="w-7 h-7" style="color: {{ $statusData['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider mb-1" style="color: {{ $statusData['color'] }}">{{ $statusData['title'] ?? __('Current Status') }}</p>
                                <p class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ __(ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}</p>
                            </div>
                        </div>
                        <div class="text-center sm:text-right bg-white dark:bg-slate-800 rounded-lg px-4 py-3 border border-[#EBEBEB] dark:border-slate-700">
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Last updated') }}</p>
                            <p class="text-sm font-bold text-slate-900 dark:text-white mt-1">{{ $sourcingOrder->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Expédition & Suivi --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden mb-6 relative group">
                        <!-- Coming Soon Overlay -->
                        <div class="absolute inset-0 z-10 flex items-center justify-center p-6 bg-white/10 dark:bg-slate-900/10 backdrop-blur-[2px]">
                            <div class="bg-slate-900/90 text-white px-6 py-3 rounded-xl shadow-2xl flex flex-col items-center gap-2 transform group-hover:scale-105 transition-transform duration-300 border border-white/20">
                                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-orange-400">{{ __('Feature Development') }}</span>
                                <div class="flex items-center gap-3">
                                    <h4 class="text-xl font-black uppercase italic tracking-tighter">{{ __('Tracking') }}</h4>
                                    <span class="h-6 w-px bg-white/20"></span>
                                    <span class="text-lg font-light text-slate-300 italic">{{ __('Soon') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Content (Blurred) -->
                        <div class="filter blur-[4px] opacity-40 select-none pointer-events-none">
                            <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700 flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                     <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Expédition & Suivi') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Track your shipment status') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Numéro de suivi') }}</p>
                                        <p class="text-sm italic text-slate-400">Ex: ME49508327</p>
                                    </div>
                                     <div>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Transporteur') }}</p>
                                        <p class="text-sm italic text-slate-400">Ex: Faster.ae, DHL...</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col sm:flex-row items-center gap-3">
                                    <div class="w-full sm:w-auto px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 text-sm font-bold rounded-lg flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" /></svg>
                                        {{ __('Deep Tracking') }}
                                    </div>
                                    
                                    <div class="w-full sm:w-auto px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 text-sm font-bold rounded-lg flex items-center justify-center gap-2">
                                        {{ __('Enregistrer le suivi') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Information --}}

                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product Information') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Complete product details and specifications') }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EF7722]/10 text-[#EF7722]">
                                    {{ $sourcingOrder->quotation?->sourcingRequest?->category?->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-6">
                                {{-- Product Image - Reduced Size --}}
                                <div class="sm:w-32 sm:flex-shrink-0">
                                    <div class="relative group">
                                        <div class="w-24 h-24 sm:w-32 sm:h-32 bg-[#EBEBEB] dark:bg-slate-700 border-2 border-[#EBEBEB] dark:border-slate-600 rounded-lg overflow-hidden shadow-sm mx-auto">
                                            @if ($sourcingOrder->quotation->sourcingRequest->product_image)
                                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                                     alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name }}" 
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                    <span class="text-lg font-bold text-[#EF7722]">
                                                        {{ mb_substr($sourcingOrder->quotation->sourcingRequest->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Product Details --}}
                                <div class="flex-1 space-y-4">
                                    <div>
                                        <h4 class="text-xl font-bold text-slate-900 dark:text-white leading-tight">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</h4>
                                        @if ($sourcingOrder->quotation->sourcingRequest->product_url)
                                            <a href="{{ $sourcingOrder->quotation->sourcingRequest->product_url }}" target="_blank" 
                                               class="inline-flex items-center gap-2 text-sm font-semibold text-[#EF7722] hover:text-[#FAA533] transition-colors mt-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                {{ __('View Source Product') }}
                                            </a>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Shipping Method') }}</p>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                </svg>
                                                <p class="text-sm font-bold text-slate-900 dark:text-white capitalize">{{ $sourcingOrder->quotation->sourcingRequest->shipping_method ?? __('Not specified') }}</p>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Total Quantity') }}</p>
                                            <p class="text-lg font-bold text-[#EF7722]">{{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }} {{ __('units') }}</p>
                                        </div>
                                    </div>

                                    @if ($sourcingOrder->quotation->sourcingRequest->note)
                                        <div class="p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2">{{ __('Additional Notes') }}</p>
                                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                                {{ $sourcingOrder->quotation->sourcingRequest->note }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quality Control Media --}}
                    @if($sourcingOrder->media->count() > 0)
                        <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden transform transition-all hover:shadow-md">
                            <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Quality Control Media') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Photos and videos from product inspection') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($sourcingOrder->media as $media)
                                        <div class="relative group rounded-lg overflow-hidden border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 aspect-square cursor-pointer transform transition-transform hover:scale-[1.02]" onclick="openMediaModal('{{ asset('storage/' . $media->file_path) }}', '{{ $media->file_type }}')">
                                            @if($media->file_type === 'video')
                                                <video src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover"></video>
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition-colors">
                                                    <svg class="w-12 h-12 text-white opacity-90 drop-shadow-lg" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                </div>
                                                <span class="absolute bottom-2 right-2 px-2 py-1 bg-black/70 text-white text-[10px] font-bold rounded uppercase tracking-wider">Video</span>
                                            @else
                                                <img src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Quotation Details --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Quotation Details') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Detailed pricing and cost breakdown') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Price') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->unit_price, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Commission') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->commission_service, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Unit Weight') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->unit_weight, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->weight_unit ?? __('g') }}</span></p>
                                    </div>
                                    <div class="p-4 bg-[#EBEBEB] dark:bg-slate-700 border border-[#EBEBEB] dark:border-slate-600 rounded-lg">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">{{ __('Local Delivery') }}</p>
                                        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->quotation->delivery_cost_china, 2) }} <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                                    </div>
                                </div>
                                
                                <div class="p-6 bg-gradient-to-r from-[#EF7722] to-[#FAA533] rounded-lg shadow-lg">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-bold text-white uppercase tracking-wider mb-1">{{ __('Grand Total') }}</p>
                                            <p class="text-3xl sm:text-4xl font-extrabold text-white">{{ number_format($sourcingOrder->quotation->amount, 2) }} <span class="text-xl font-bold text-white/90">{{ $sourcingOrder->quotation->currency }}</span></p>
                                        </div>
                                        <svg class="w-14 h-14 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden" x-data="{ selectedMethod: null }">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Available Payment Methods') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Choose your preferred payment method') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($paymentMethods as $paymentMethod)
                                    <button @click="selectedMethod = selectedMethod === '{{ $paymentMethod->name }}' ? null : '{{ $paymentMethod->name }}'" 
                                            class="w-full p-4 text-left border rounded-lg transition-all duration-200"
                                            :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'border-[#EF7722] bg-[#EF7722]/5 shadow-sm' : 'border-[#EBEBEB] dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-[#EF7722] dark:hover:border-[#FAA533] hover:shadow-sm'">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                @if($paymentMethod->logo_path)
                                                    <div class="w-10 h-10 rounded-full border border-[#EBEBEB] bg-white p-1.5 shadow-sm flex items-center justify-center">
                                                        <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-contain">
                                                    </div>
                                                @else
                                                    <div class="w-10 h-10 flex items-center justify-center rounded-full border border-[#EBEBEB] bg-[#EBEBEB] shadow-sm">
                                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="text-base font-semibold text-slate-900 dark:text-white">{{ $paymentMethod->name }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-slate-400 dark:text-slate-500 transition-transform duration-200" :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'rotate-180 text-[#EF7722]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                        <div x-show="selectedMethod === '{{ $paymentMethod->name }}'" 
                                             x-transition.duration.300ms
                                             class="mt-4 pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                            <div class="space-y-2.5">
                                                @foreach($paymentMethod->details as $key => $value)
                                                    <div class="flex justify-between items-center p-2.5 bg-white dark:bg-slate-900 rounded-lg border border-[#EBEBEB] dark:border-slate-700">
                                                        <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">{{ $key }}:</span>
                                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Payment Section --}}
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Payment Status') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Upload and manage payment proof') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            @if ($sourcingOrder->rejection_reason)
                                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 rounded-r-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-bold text-red-700 dark:text-red-300">{{ __('Payment Proof Rejected') }}</p>
                                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $sourcingOrder->rejection_reason }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($sourcingOrder->status === 'pending_payment')
                                <form action="{{ route('client.sourcing-orders.upload-proof-of-payment', $sourcingOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">{{ __('Upload Proof of Payment') }}</label>
                                        <input type="file" 
                                               name="proof_of_payment" 
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
                                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('Accepted formats: PDF, JPG, PNG (Max: 5MB)') }}</p>
                                    </div>
                                    <button type="submit" 
                                            class="w-full py-3 px-4 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Submit Payment Proof') }}
                                    </button>
                                </form>
                            @elseif($sourcingOrder->proof_of_payment_path || in_array($sourcingOrder->status, ['paid', 'shipment_preparing', 'in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'delivered', 'order_completed']))
                                <div class="text-center p-6 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg border-2 border-[#0BA6DF]">
                                    <div class="w-14 h-14 bg-[#0BA6DF]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-7 h-7 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    @if(in_array($sourcingOrder->status, ['paid', 'shipment_preparing', 'in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'delivered', 'order_completed']))
                                        <p class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ __('Payment Verified') }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ __('Your order is being processed.') }}</p>
                                    @else
                                        <p class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ __('Payment Proof Submitted') }}</p>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ __('Your payment proof is under review. We will proceed with the order once verified.') }}</p>
                                    @endif

                                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                        @if($sourcingOrder->proof_of_payment_path)
                                            <a href="{{ route('client.sourcing-orders.download-proof-of-payment', $sourcingOrder) }}" 
                                               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-[#0BA6DF] bg-white dark:bg-slate-700 border-2 border-[#0BA6DF] rounded-lg hover:bg-[#0BA6DF]/5 transition-colors shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ __('View Document') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('client.sourcing-orders.receipt', $sourcingOrder) }}" target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-[#EF7722] bg-white dark:bg-slate-700 border-2 border-[#EF7722] rounded-lg hover:bg-[#EF7722]/5 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ __('Print Receipt') }}
                                        </a>

                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-700 rounded-lg shadow-sm overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Order Summary') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Key information at a glance') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            
                            {{-- Key Details --}}
                            <div class="space-y-3">

                                <div class="flex items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <svg class="w-4 h-4 mr-3 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 flex-1">{{ __('Order ID') }}</span>
                                    <span class="text-sm font-bold text-[#EF7722]">#{{ $sourcingOrder->display_id }}</span>
                                </div>

                                <div class="flex items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <svg class="w-4 h-4 mr-3 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 flex-1">{{ __('Category') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->quotation?->sourcingRequest?->category?->name }}</span>
                                </div>

                                <div class="flex items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                    <svg class="w-4 h-4 mr-3 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 flex-1">{{ __('Destinations') }}</span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->quotation->sourcingRequest->destinations->count() }}</span>
                                </div>
                            </div>
                            
                            {{-- Status --}}
                            <div class="flex justify-between items-center p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('Status') }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold" style="background-color: {{ $statusData['color'] }}22; color: {{ $statusData['color'] }};">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                    </svg>
                                    {{ __(ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}
                                </span>
                            </div>

                            <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                <div class="p-5 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg shadow-sm">
                                    <div class="text-center">
                                        <p class="text-sm font-bold text-white/90 uppercase tracking-wide mb-1">{{ __('Total Amount') }}</p>
                                        <p class="text-3xl font-extrabold text-white mb-1">{{ number_format($sourcingOrder->quotation->amount, 2) }}</p>
                                        <p class="text-base font-bold text-white/90">{{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Refund proof download link moved to the dedicated Refund Status page as per user request --}}

                            @php
                                $lastRefundRequest = $sourcingOrder->refundRequests()->latest()->first();
                            @endphp

                            @if($lastRefundRequest)
                                <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                    <a href="{{ route('client.refund-requests.show', $lastRefundRequest) }}" 
                                       class="w-full py-2.5 px-4 bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-900/10 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30 text-sm font-bold rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('View Refund Status') }}
                                    </a>
                                </div>
                            @elseif(in_array($sourcingOrder->status, ['paid', 'delivered', 'order_completed']))
                                <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                    <div class="w-full py-2.5 px-4 bg-red-50 text-red-400 dark:bg-red-900/10 dark:text-red-500 border border-red-100 dark:border-red-900/20 text-sm font-bold rounded-lg flex items-center justify-center gap-2 cursor-not-allowed group relative transition-colors duration-200">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                         </svg>
                                         {{ __('Request Refund') }}
                                         <span class="ml-auto text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ __('Coming Soon') }}</span>
                                     </div>
                                 </div>

                            @endif

                            <div class="pt-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                <div class="text-center text-xs text-slate-500 dark:text-slate-400 space-y-1">
                                    <p><span class="font-semibold">{{ __('Order Created') }}:</span> {{ $sourcingOrder->created_at->format('M d, Y') }}</p>
                                    <p><span class="font-semibold">{{ __('Last Update') }}:</span> {{ $sourcingOrder->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Media Modal -->
    <div id="mediaModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/95 backdrop-blur-sm transition-opacity duration-300" onclick="closeMediaModal()">
        <button class="absolute top-4 right-4 text-white/70 hover:text-white z-[110] transition-colors bg-white/10 hover:bg-white/20 rounded-full p-2" onclick="closeMediaModal()">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="max-w-7xl max-h-[95vh] p-2 relative flex items-center justify-center" onclick="event.stopPropagation()">
            <img id="modalImage" src="" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl hidden object-contain">
            <video id="modalVideo" src="" controls class="max-w-full max-h-[90vh] rounded-lg shadow-2xl hidden bg-black border border-slate-800"></video>
        </div>
    </div>

    <script>
        function openMediaModal(url, type) {
            const modal = document.getElementById('mediaModal');
            const img = document.getElementById('modalImage');
            const vid = document.getElementById('modalVideo');
            
            modal.classList.remove('hidden');
            // Small trigger animation or opacity fade could be added here
            
            if (type === 'video') {
                img.classList.add('hidden');
                vid.src = url;
                vid.classList.remove('hidden');
                // Auto play if desired
                // vid.play(); 
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
            const img = document.getElementById('modalImage');
            
            modal.classList.add('hidden');
            vid.pause();
            vid.src = '';
            img.src = '';
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeMediaModal();
            }
        });
    </script>

    <style>
        /* Custom scrollbar styling */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #EF7722;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>

    {{-- Enhanced Refund Request Modal --}}
    <x-modal name="refund-request-modal" :show="false" focusable>
        <div class="relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-red-500/10 rounded-full blur-3xl"></div>
            
            <div class="p-8">
                <!-- Modal Header -->
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-200 dark:shadow-none">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ __('Request Refund') }}</h2>
                        <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1 leading-relaxed max-w-sm">{{ __('Submit your claim with details and evidence for our support team to review.') }}</p>
                    </div>
                </div>

                <form action="{{ route('client.sourcing-orders.refund-request', $sourcingOrder) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ type: 'full', fileName: '' }">
                    @csrf
                    
                    <!-- Refund Type Selection -->
                    <div class="space-y-4">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Refund Type') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="relative flex p-5 border-2 rounded-2xl cursor-pointer transition-all duration-300 group" :class="type === 'full' ? 'border-[#EF7722] bg-[#EF7722]/5 shadow-md shadow-[#EF7722]/10 ring-4 ring-[#EF7722]/5' : 'border-[#EBEBEB] dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-[#EF7722]'">
                                <input type="radio" name="type" value="full" class="hidden" x-model="type">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="type === 'full' ? 'bg-[#EF7722] text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 group-hover:bg-[#EF7722]/20 group-hover:text-[#EF7722]'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black transition-colors" :class="type === 'full' ? 'text-[#EF7722]' : 'text-slate-900 dark:text-white'">{{ __('Full Refund') }}</span>
                                        <span class="text-[11px] font-medium text-slate-500 mt-0.5">{{ __('Recover 100% of order cost') }}</span>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex p-5 border-2 rounded-2xl cursor-pointer transition-all duration-300 group" :class="type === 'partial' ? 'border-[#EF7722] bg-[#EF7722]/5 shadow-md shadow-[#EF7722]/10 ring-4 ring-[#EF7722]/5' : 'border-[#EBEBEB] dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-[#EF7722]'">
                                <input type="radio" name="type" value="partial" class="hidden" x-model="type">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="type === 'partial' ? 'bg-[#EF7722] text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-400 group-hover:bg-[#EF7722]/20 group-hover:text-[#EF7722]'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1c.51-.598.401-1.571 0-2.599M12 16h.01"/></svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black transition-colors" :class="type === 'partial' ? 'text-[#EF7722]' : 'text-slate-900 dark:text-white'">{{ __('Partial Refund') }}</span>
                                        <span class="text-[11px] font-medium text-slate-500 mt-0.5">{{ __('Specify a custom amount') }}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Amount Input (Conditional) -->
                    <div x-show="type === 'partial'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Requested Amount') }} ({{ $sourcingOrder->quotation->currency }})</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold">$</span>
                            </div>
                            <input type="number" name="amount_requested" step="0.01" min="0" max="{{ $sourcingOrder->quotation->amount }}" class="block w-full pl-10 pr-4 py-4 bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl focus:ring-4 focus:ring-[#EF7722]/10 focus:border-[#EF7722] font-black text-xl text-slate-900 dark:text-white transition-all outline-none" placeholder="0.00">
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ __('Maximum allowed') }}: <span class="text-[#EF7722]">{{ number_format($sourcingOrder->quotation->amount, 2) }}</span></p>
                    </div>

                    <!-- Category & Description -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1 space-y-3">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Category') }}</label>
                            <select name="reason_category" required class="w-full bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl py-4 px-4 focus:ring-4 focus:ring-[#EF7722]/10 focus:border-[#EF7722] font-black text-sm text-slate-900 dark:text-white transition-all outline-none appearance-none">
                                <option value="">{{ __('Reason...') }}</option>
                                <option value="Damaged Product">{{ __('Damaged Product') }}</option>
                                <option value="Wrong Item">{{ __('Wrong Item') }}</option>
                                <option value="Poor Quality">{{ __('Poor Quality') }}</option>
                                <option value="Shipping Delay">{{ __('Shipping Delay') }}</option>
                                <option value="Quantity Issue">{{ __('Quantity Issue') }}</option>
                                <option value="Other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Details') }}</label>
                            <textarea name="reason_description" rows="1" required class="w-full bg-slate-50 dark:bg-slate-900/50 border-2 border-slate-100 dark:border-slate-800 rounded-2xl py-4 px-4 focus:ring-4 focus:ring-[#EF7722]/10 focus:border-[#EF7722] font-medium text-sm text-slate-900 dark:text-white transition-all outline-none" placeholder="{{ __('Explain the problem in detail...') }}"></textarea>
                        </div>
                    </div>

                    <!-- Enhanced File Upload -->
                    <div class="space-y-4">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ __('Evidence Portfolio') }}</label>
                        <div class="relative">
                            <input type="file" name="evidence[]" multiple accept="image/*,video/*" 
                                @change="fileName = $event.target.files.length + ' files selected'"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-10 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-900/30 transition-all group-hover:border-[#EF7722] group-hover:bg-[#EF7722]/5">
                                <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="text-[13px] font-black text-slate-900 dark:text-white mb-1" x-text="fileName || '{{ __('Drop photos or videos here') }}'"></p>
                                <p class="text-[11px] font-medium text-slate-400 italic">Maximize claim approval by attaching clear media proof</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                             <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-black uppercase tracking-wider">{{ __('Max 50MB/file') }}</span>
                             <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-full text-[10px] font-black uppercase tracking-wider">{{ __('Multiple Files Allowed') }}</span>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6 mt-10 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="$dispatch('close-modal', 'refund-request-modal')" class="w-full sm:w-auto px-8 py-4 text-[11px] font-black text-slate-400 hover:text-slate-900 dark:hover:text-white uppercase tracking-widest transition-colors">
                            {{ __('Dismiss') }}
                        </button>
                        <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-slate-900 dark:bg-[#EF7722] hover:bg-black dark:hover:bg-[#FAA533] text-white text-[11px] font-black rounded-2xl shadow-xl transition-all duration-300 uppercase tracking-[0.2em] transform hover:-translate-y-1 active:scale-95">
                            {{ __('Submit Claim') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-modal>
</x-app-layout>



