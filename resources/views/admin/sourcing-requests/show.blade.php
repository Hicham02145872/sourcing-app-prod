<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Back Button -->
                        <a href="{{ route('admin.sourcing-requests.index', ['page' => request('page')]) }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1"></div>

                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight flex items-center gap-2">
                                {{ __('Request') }} {{ $sourcingRequest->reference_id }}
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'in_review' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'quoted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'accepted' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                    $badgeClass = $statusColors[$sourcingRequest->status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $sourcingRequest->status)) }}
                                </span>
                            </h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.sourcing-requests.index', ['page' => request('page')]) }}" class="hover:text-slate-700">{{ __('Request List') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ Str::limit($sourcingRequest->product_name, 30) }}</span>
                            </nav>
                            @if($sourcingRequest->order)
                                <div class="mt-2 text-[10px] text-slate-500">
                                    <a href="{{ route('admin.sourcing-orders.show', $sourcingRequest->order) }}" class="inline-flex items-center gap-1 text-orange-600 hover:text-orange-700 font-medium">
                                        {{ __('Sourcing Order created') }} {{ $sourcingRequest->order->reference_id }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-400 hidden sm:inline-block">{{ __('Updated') }} {{ $sourcingRequest->updated_at->diffForHumans() }}</span>
                        
                        @if($sourcingRequest->assigned_to_admin_id == auth()->id() || auth()->user()->isSuperAdmin())
                            <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
                            
                             <!-- Quick Actions Dropdown (Simulated with simple buttons for now) -->
                            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                {{ __('Print') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LEFT COLUMN: Main Information (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- 1. Assignment & Workflow Card (Livewire) -->
                    <livewire:admin.sourcing-request-workflow :sourcingRequest="$sourcingRequest" />

                    @if($sourcingRequest->status === 'negotiating' && $sourcingRequest->quotation?->negotiation_notes)
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-blue-800">{{ __('Negotiation Requested by Client') }}</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p class="italic">"{{ $sourcingRequest->quotation->negotiation_notes }}"</p>
                                    </div>
                                    <div class="mt-4">
                                        <div class="-mx-2 -my-1.5 flex">
                                            <a href="{{ route('admin.quotations.edit', $sourcingRequest->quotation) }}" class="px-2 py-1.5 rounded-md text-sm font-medium text-blue-800 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-50 focus:ring-blue-600 transition-colors">
                                                {{ __('Update Quotation') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 2. Product Details Card -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-slate-900">{{ __('Product Specifications') }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Product Name') }}</label>
                                <div class="text-base font-medium text-slate-900">{{ $sourcingRequest->product_name }}</div>
                            </div>

                             <!-- URL -->
                            @if($sourcingRequest->product_url)
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Reference Link') }}</label>
                                    <a href="{{ $sourcingRequest->product_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        {{ Str::limit($sourcingRequest->product_url, 60) }}
                                    </a>
                                </div>
                            @endif

                             <!-- Category -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Category') }}</label>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-700">
                                        {{ $sourcingRequest->category?->name ?? __('Unclassified') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Sourcing Location -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Sourcing Location') }}</label>
                                <div class="text-sm text-slate-900 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="capitalize @if($sourcingRequest->quotation && $sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location) line-through opacity-50 @endif">
                                        {{ $sourcingRequest->sourcing_location ?? __('Not specified') }}
                                    </span>
                                    @if($sourcingRequest->quotation && $sourcingRequest->quotation->actual_sourcing_location !== $sourcingRequest->sourcing_location)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200 capitalize">
                                            {{ __('Actual Sourcing: ') }} {{ $sourcingRequest->quotation->actual_sourcing_location }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                             <!-- Shipping Method -->
                            @if($sourcingRequest->shipping_method)
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Shipping Method') }}</label>
                                    <div class="text-sm text-slate-900 flex items-center gap-1.5">
                                        @if($sourcingRequest->shipping_method === 'air')
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            {{ __('Air Freight') }}
                                        @else
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            {{ __('Sea Freight') }}
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($sourcingRequest->note)
                                <div class="col-span-1 md:col-span-2 mt-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Additional Notes') }}</label>
                                    <div class="bg-amber-50/50 border border-amber-100 rounded-md p-3 text-sm text-slate-700 leading-relaxed">
                                        {{ $sourcingRequest->note }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                     <!-- 3. Quotation Information Card (If exists) -->
                    @if($sourcingRequest->quotation)
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-slate-900">{{ __('Quotation Information') }}</h3>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.quotations.edit', $sourcingRequest->quotation) }}" class="text-xs text-blue-600 hover:text-blue-700 font-bold uppercase tracking-wider">{{ __('Edit') }}</a>
                                    <span class="text-slate-200">|</span>
                                    <a href="{{ route('admin.quotations.show', $sourcingRequest->quotation) }}" class="text-xs text-orange-600 hover:text-orange-700 font-bold uppercase tracking-wider">{{ __('View full quotation') }}</a>
                                </div>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Unit Price') }}</label>
                                    <div class="text-sm font-bold text-slate-900">{{ number_format($sourcingRequest->quotation->unit_price, 2) }} {{ $sourcingRequest->quotation->currency }}</div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Commission') }}</label>
                                    <div class="text-sm font-bold text-slate-900">{{ number_format($sourcingRequest->quotation->commission_service, 2) }} {{ $sourcingRequest->quotation->currency }}</div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Unit Weight') }}</label>
                                    <div class="text-sm font-bold text-slate-900">
                                        {{ number_format($sourcingRequest->quotation->unit_weight, 2) }} 
                                        <span class="text-xs font-medium text-slate-500">{{ $sourcingRequest->quotation->weight_unit ?? 'g' }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('China Delivery') }}</label>
                                    <div class="text-sm font-bold text-slate-900">{{ number_format($sourcingRequest->quotation->delivery_cost_china, 2) }} {{ $sourcingRequest->quotation->currency }}</div>
                                </div>
                                @if($sourcingRequest->quotation->comments)
                                    <div class="col-span-1 md:col-span-4">
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Comments') }}</label>
                                        <div class="bg-slate-50 border border-slate-100 rounded-md p-3 text-sm text-slate-700 leading-relaxed">
                                            {{ $sourcingRequest->quotation->comments }}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-span-1 md:col-span-4 pt-4 border-t border-slate-50">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-bold text-slate-700">{{ __('Total Amount:') }}</span>
                                        <span class="text-lg font-extrabold text-orange-600">{{ number_format($sourcingRequest->quotation->amount, 2) }} {{ $sourcingRequest->quotation->currency }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                     <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-semibold text-slate-900">{{ __('Destinations & Quantities') }}</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-white">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Country') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Service') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 bg-white">
                                    @foreach ($sourcingRequest->destinations as $destination)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="fi fi-{{ strtolower($destination->country->code) }} border border-slate-200 rounded-sm"></span>
                                                    <span class="text-sm font-medium text-slate-900">{{ $destination->country->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500">
                                                {{ $destination->service->name }}
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-mono text-slate-900">
                                                {{ number_format($destination->quantity, 0, ',', ' ') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Sidebar (1/3) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- 1. Product Image -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-1">
                        @if($sourcingRequest->product_image)
                            <div class="relative group aspect-square rounded overflow-hidden bg-slate-100 cursor-pointer">
                                <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                     alt="{{ $sourcingRequest->product_name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                            </div>
                        @else
                             <div class="aspect-square rounded bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-medium">{{ __('No image') }}</span>
                            </div>
                        @endif
                        @if($sourcingRequest->product_image)
                            <div class="mt-2 text-center">
                                <a href="{{ asset('storage/' . $sourcingRequest->product_image) }}" target="_blank" class="text-xs text-blue-600 hover:underline">{{ __('View full size') }}</a>
                            </div>
                        @endif
                    </div>

                    <!-- 2. Client Profile -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Client') }}</h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold border border-orange-200">
                                    {{ substr($sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $sourcingRequest->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate">{{ $sourcingRequest->user->email }}</p>
                                    @if($sourcingRequest->user->phone)
                                        <p class="text-xs text-orange-600 font-semibold mt-0.5">📞 {{ $sourcingRequest->user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-3 pt-3 border-t border-slate-50">
                                @if($sourcingRequest->phone_number)
                                    <div class="flex gap-3">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span class="text-xs text-slate-600 font-mono">{{ $sourcingRequest->phone_number }}</span>
                                    </div>
                                @endif
                                @if($sourcingRequest->address)
                                    <div class="flex gap-3">
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span class="text-xs text-slate-600 leading-snug">{{ $sourcingRequest->address }}</span>
                                    </div>
                                @endif
                            </div>

                            @if ($sourcingRequest->latitude && $sourcingRequest->longitude)
                                <div class="mt-4 pt-3 border-t border-slate-50">
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $sourcingRequest->latitude }},{{ $sourcingRequest->longitude }}" target="_blank" class="block w-full text-center px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-medium rounded border border-slate-200 transition-colors">
                                        {{ __('View on Google Maps') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 3. Timeline (Vertical) -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('History') }}</h3>
                        </div>
                        <div class="p-5">
                            <ul class="relative border-l border-slate-200 ml-2 space-y-6">
                                 <li class="ml-6">
                                    <span class="absolute -left-1.5 flex h-3 w-3 items-center justify-center rounded-full bg-blue-100 ring-4 ring-white">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                    </span>
                                    <p class="text-xs font-medium text-slate-900">{{ __('Last modified') }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $sourcingRequest->updated_at->format('d M Y') }} {{ __('at') }} {{ $sourcingRequest->updated_at->format('H:i') }}</p>
                                </li>
                                <li class="ml-6">
                                    <span class="absolute -left-1.5 flex h-3 w-3 items-center justify-center rounded-full bg-slate-100 ring-4 ring-white">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    </span>
                                    <p class="text-xs font-medium text-slate-900">{{ __('Creation') }}</p>
                                    <p class="text-[10px] text-slate-500">{{ $sourcingRequest->created_at->format('d M Y') }} {{ __('at') }} {{ $sourcingRequest->created_at->format('H:i') }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

