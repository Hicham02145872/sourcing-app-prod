<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20 print:hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Back Button -->
                        <a href="{{ route('admin.quotations.index') }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors" title="{{ __('Back') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight flex items-center gap-2">
                                {{ __('Quotation') }} <span class="text-slate-400 font-normal">#{{ $quotation->display_id }}</span>
                            </h1>
                            <nav class="hidden sm:flex text-xs text-slate-500 mt-0.5" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-600 transition-colors">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5 text-slate-300">/</span>
                                <a href="{{ route('admin.quotations.index') }}" class="hover:text-orange-600 transition-colors">{{ __('Quotations') }}</a>
                                <span class="mx-1.5 text-slate-300">/</span>
                                <span class="font-medium text-slate-700">{{ __('Details') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-bold uppercase tracking-wide rounded shadow-sm transition-all">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            {{ __('Print') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Main Details (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card 1: Quotation Info -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ __('Quotation Information') }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('General request details') }}</p>
                            </div>
                            <!-- Dynamic Status Badge -->
                            @php
                                $statusConfig = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'sent' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'accepted' => 'bg-green-50 text-green-700 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                    'expired' => 'bg-slate-50 text-slate-700 border-slate-200',
                                    'negotiating' => 'bg-blue-50 text-blue-700 border-blue-200',
                                ];
                                $statusClass = $statusConfig[$quotation->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border uppercase tracking-wider {{ $statusClass }}">
                                {{ __(ucwords(str_replace('_', ' ', $quotation->status))) }}
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Product Name') }}</label>
                                    <p class="text-sm font-bold text-slate-900 leading-tight">{{ $quotation->sourcingRequest->product_name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Category') }}</label>
                                    <div class="flex items-center">
                                        <span class="inline-block w-2 h-2 rounded-full bg-orange-400 mr-2"></span>
                                        <p class="text-sm font-medium text-slate-700">{{ $quotation->sourcingRequest?->category?->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Assignment') }}</label>
                                    <p class="text-sm font-medium text-slate-700">
                                        @if($quotation->assignedAdmin)
                                            <span class="text-orange-600 font-bold">{{ $quotation->assignedAdmin->name }}</span>
                                        @else
                                            <span class="text-slate-400 italic">{{ __('Unassigned') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Financial Estimation -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ __('Financial Breakdown') }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">{{ __('Profitability and cost analysis') }}</p>
                            </div>
                            <div class="px-2 py-1 bg-slate-900 text-white rounded text-[10px] font-bold uppercase tracking-widest">
                                {{ __('Operational view') }}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                <!-- Buying Side -->
                                <div class="space-y-4">
                                    <h4 class="text-[10px] font-bold text-slate-400 border-b pb-2 uppercase tracking-widest">{{ __('Costs Side') }}</h4>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Product Cost (Total)') }}</label>
                                        <p class="text-sm font-bold text-slate-900">
                                            {{ number_format($quotation->estimated_product_cost, 2) }} <span class="text-xs font-normal text-slate-500">{{ $quotation->currency }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Shipping Cost') }}</label>
                                        <p class="text-sm font-bold text-slate-900">
                                            {{ number_format($quotation->estimated_shipping_cost, 2) }} <span class="text-xs font-normal text-slate-500">{{ $quotation->currency }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Operational Side -->
                                <div class="space-y-4">
                                    <h4 class="text-[10px] font-bold text-slate-400 border-b pb-2 uppercase tracking-widest">{{ __('Revenue Side') }}</h4>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Unit Price') }}</label>
                                        <p class="text-sm font-bold text-slate-900">
                                            {{ number_format($quotation->unit_price, 2) }} <span class="text-xs font-normal text-slate-500">{{ $quotation->currency }}</span>
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Commission Service') }}</label>
                                        <p class="text-sm font-bold text-slate-900">
                                            {{ number_format($quotation->commission_service, 2) }} <span class="text-xs font-normal text-slate-500">{{ $quotation->currency }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Result Side -->
                                <div class="p-4 bg-slate-900 rounded-lg text-white space-y-4">
                                    <h4 class="text-[10px] font-bold text-slate-400 border-b border-slate-700 pb-2 uppercase tracking-widest">{{ __('Net Profit (Est.)') }}</h4>
                                    <div>
                                        <p class="text-2xl font-bold text-orange-500">
                                            {{ number_format($quotation->estimated_net_profit, 2) }} <span class="text-sm font-normal text-slate-400">{{ $quotation->currency }}</span>
                                        </p>
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            {{ __('Margin') }}: <span class="text-emerald-400 font-bold">{{ $quotation->getEstimatedProfitMarginPercentage() }}%</span>
                                        </p>
                                    </div>
                                    <div class="pt-2 border-t border-slate-700">
                                        <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Total Amount (to client)') }}</label>
                                        <p class="text-lg font-bold">
                                            {{ number_format($quotation->amount, 2) }} <span class="text-xs font-normal text-slate-400">{{ $quotation->currency }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Client Negotiation (when negotiating or has notes) -->
                    @if($quotation->status === 'negotiating' || $quotation->negotiation_notes)
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden {{ $quotation->status === 'negotiating' ? 'border-blue-200 ring-1 ring-blue-100' : '' }}">
                            <div class="px-6 py-4 border-b {{ $quotation->status === 'negotiating' ? 'bg-blue-50/50 border-blue-100' : 'border-slate-100 bg-slate-50/50' }} flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    <h3 class="text-sm font-bold text-slate-900">{{ __('Client Negotiation') }}</h3>
                                </div>
                                @if($quotation->status === 'negotiating')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase tracking-wider">
                                        {{ __('Awaiting your update') }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-6">
                                @if($quotation->negotiation_notes)
                                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Client message') }}</label>
                                        <p class="text-sm text-slate-700 dark:text-slate-300 italic leading-relaxed">"{{ $quotation->negotiation_notes }}"</p>
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500 italic">{{ __('No negotiation notes from the client.') }}</p>
                                @endif
                                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700">
                                    <a href="{{ route('admin.quotations.edit', $quotation) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#d66616] text-white text-xs font-bold uppercase tracking-widest rounded shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $quotation->status === 'negotiating' ? __('Update quotation (reply to negotiation)') : __('Edit quotation') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Card 3: Logistics Details -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Logistics & Logistics Request') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Image & Desc -->
                                <div class="space-y-4">
                                    @if($quotation->sourcingRequest->product_image)
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 text-center md:text-left">{{ __('Product Visual') }}</label>
                                            <div class="rounded-lg border border-slate-200 p-2 bg-slate-50 flex justify-center md:justify-start">
                                                <img src="{{ asset('storage/' . $quotation->sourcingRequest->product_image) }}" alt="Product" class="max-h-48 rounded shadow-sm">
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Request Details') }}</label>
                                        <div class="p-3 bg-slate-50 rounded text-xs text-slate-600 italic">
                                            {{ $quotation->sourcingRequest->description ?? __('No detailed specifications.') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Logistics Grid -->
                                <div class="grid grid-cols-2 gap-6">
                                    <div class="col-span-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                        <label class="block text-[10px] font-bold text-blue-600 uppercase tracking-wider mb-1">{{ __('Quantity & Weight') }}</label>
                                        <p class="text-sm font-bold text-slate-900">
                                            {{ number_format($quotation->sourcingRequest->destinations->sum('quantity')) }} {{ $quotation->sourcingRequest->unit ?? __('pcs') }}
                                            <span class="mx-2 text-slate-300">|</span>
                                            {{ number_format($quotation->unit_weight, 2) }} {{ $quotation->weight_unit ?? __('kg') }} / {{ __('unit') }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Shipping Method') }}</label>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                                            <p class="text-sm font-medium text-slate-700 capitalize">{{ __(ucfirst(str_replace('_', ' ', $quotation->sourcingRequest->shipping_method ?? 'Unknown'))) }}</p>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Destinations') }}</label>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($quotation->sourcingRequest->destinations as $dest)
                                                <span class="px-1.5 py-0.5 bg-slate-100 text-[10px] font-bold rounded text-slate-600 border border-slate-200">
                                                    {{ $dest->country->name }} ({{ $dest->quantity }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if($quotation->sourcingRequest->product_url)
                                        <div class="col-span-2">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ __('Source Link') }}</label>
                                            <a href="{{ $quotation->sourcingRequest->product_url }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                {{ Str::limit($quotation->sourcingRequest->product_url, 60) }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Sidebar (1/3) -->
                <div class="space-y-6">
                    
                    <!-- Client Profile -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Client Profile') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="h-12 w-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-lg font-bold border border-orange-200 shadow-sm">
                                    {{ substr($quotation->sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ $quotation->sourcingRequest->user->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __('Verified Business') }}</p>
                                </div>
                            </div>
                            <div class="space-y-4 pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded bg-slate-50 flex items-center justify-center text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Email') }}</p>
                                        <a href="mailto:{{ $quotation->sourcingRequest->user->email }}" class="text-xs font-medium text-slate-700 hover:text-orange-600 truncate block">
                                            {{ $quotation->sourcingRequest->user->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Actions -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                            <h3 class="text-xs font-bold text-slate-600 uppercase tracking-widest">{{ __('Review Status') }}</h3>
                            @if($quotation->status === 'pending')
                                <span class="animate-pulse flex h-2 w-2 rounded-full bg-orange-500"></span>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            @if($quotation->status === 'pending')
                                <div class="space-y-4">
                                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-lg">
                                        <p class="text-xs text-amber-800 leading-relaxed font-medium">
                                            {{ __('Please verify costs and margin before approving shipment to the client.') }}
                                        </p>
                                    </div>
                                    
                                    <form action="{{ route('admin.quotations.approve', $quotation->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 border border-transparent text-white text-xs font-bold uppercase tracking-widest rounded shadow hover:bg-slate-800 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ __('Approve & Send') }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.quotations.reject', $quotation->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-white border border-red-200 text-red-600 text-[10px] font-bold uppercase tracking-widest rounded hover:bg-red-50 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            {{ __('Reject') }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($quotation->status === 'approved' || $quotation->status === 'sent')
                                <div class="text-center py-4">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 mb-3 border-4 border-emerald-50 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ __('Quotation Finalized') }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('The request has been processed successfully.') }}</p>
                                </div>
                            @elseif($quotation->status === 'rejected')
                                <div class="text-center py-4">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 text-red-600 mb-3 border-4 border-red-50 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ __('Quotation Rejected') }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('This quotation will not be sent to the client.') }}</p>
                                </div>
                            @elseif($quotation->status === 'negotiating')
                                <div class="text-center py-4">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 mb-3 border-4 border-blue-50 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900">{{ __('Client requested negotiation') }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ __('Update the quotation to reply to the client.') }}</p>
                                    <a href="{{ route('admin.quotations.edit', $quotation) }}" class="mt-4 inline-flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-[#EF7722] hover:bg-[#d66616] text-white text-xs font-bold uppercase tracking-widest rounded shadow-sm transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ __('Update quotation (reply to negotiation)') }}
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <span class="text-xs font-bold text-slate-400 italic">{{ __('Status') }}: {{ __(ucfirst($quotation->status)) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>