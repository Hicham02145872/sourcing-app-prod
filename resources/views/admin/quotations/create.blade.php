<x-app-layout>
    <!-- Main Container: Clean, premium dashboard gradient background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area with soft shadow and backdrop blur -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-20 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center h-11 w-11 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md shadow-orange-500/20">
                            <!-- Pen/Document Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </span>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Create Quotation') }}</h1>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded border border-slate-200 uppercase tracking-wider">
                                    {{ __('Request ID') }}: #{{ $sourcingRequest->id }}
                                </span>
                            </div>
                            <nav class="flex items-center text-xs text-slate-500 mt-1" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700 transition-colors cursor-pointer">{{ __('Dashboard') }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="hover:text-slate-700 transition-colors cursor-pointer">{{ __('Requests') }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="font-semibold text-orange-600">{{ __('New Quote') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Status Indicators -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-50/50 border border-orange-100 rounded-lg text-orange-700 shadow-sm shadow-orange-500/5">
                            <span class="inline-flex items-center justify-center w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider">{{ __('Draft Mode') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form id="quotation-create-form" method="POST" action="{{ route('admin.quotations.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sourcing_request_id" value="{{ $sourcingRequest->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Left Column: Sourcing Request Details (cols: 5) -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Product Specs Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Product Specifications') }}</h3>
                                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                            </div>
                            <div class="p-6 space-y-5 text-sm">
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Product Name') }}</label>
                                    <div class="text-base font-bold text-slate-800 leading-snug">{{ $sourcingRequest->product_name }}</div>
                                </div>
                                
                                @if($sourcingRequest->product_url)
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">{{ __('Reference Link') }}</label>
                                    <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100/80 text-blue-600 hover:text-blue-700 font-medium rounded-lg text-xs transition-all border border-blue-100 max-w-full overflow-hidden truncate">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span class="truncate block">{{ $sourcingRequest->product_url }}</span>
                                    </a>
                                </div>
                                @endif
                                
                                <div class="grid grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                                    <div>
                                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Category') }}</label>
                                        <div class="font-semibold text-slate-700 bg-slate-100/60 px-2.5 py-1 rounded-md inline-block text-xs">{{ $sourcingRequest->category?->name ?? __('Unclassified') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Requested Location') }}</label>
                                        <div class="font-semibold text-slate-700 capitalize flex items-center gap-1.5 text-xs">
                                            <span class="inline-block w-2 h-2 rounded-full bg-slate-400"></span>
                                            {{ $sourcingRequest->sourcing_location }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($sourcingRequest->note)
                                <div class="pt-2 border-t border-slate-100">
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">{{ __('Client Notes') }}</label>
                                    <div class="p-4 bg-orange-50/30 rounded-xl border border-orange-100/60 text-slate-700 italic relative leading-relaxed text-xs">
                                        <span class="absolute right-3 bottom-1.5 text-orange-200 select-none text-2xl font-serif leading-none">”</span>
                                        {{ $sourcingRequest->note }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Image Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('Requested Product Image') }}</label>
                            @if($sourcingRequest->product_image)
                                <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-50 max-h-80 flex items-center justify-center shadow-inner">
                                    <img src="{{ media_url($sourcingRequest->product_image) }}" 
                                         class="w-full h-auto object-cover max-h-80 transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="px-3 py-1.5 bg-white/90 backdrop-blur text-xs font-semibold rounded-lg text-slate-700 shadow-sm">
                                            {{ __('Zoom View') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="h-36 bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center text-slate-400 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium">{{ __('No image uploaded') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Destinations Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Destinations & Quantities') }}</h3>
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-extrabold rounded-md shadow-sm">
                                    {{ $sourcingRequest->destinations->count() }} {{ __('Routes') }}
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Country') }}</th>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Service') }}</th>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Address') }}</th>
                                            <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Qty') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($sourcingRequest->destinations as $dest)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-3.5 text-sm text-slate-800 font-semibold">
                                                <span class="fi fi-{{ strtolower($dest->country->code) }} w-5 h-4 inline-block align-middle rounded-sm shadow-sm mr-2 border border-slate-100"></span>
                                                <span class="align-middle">{{ $dest->country->name }}</span>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm text-slate-500">
                                                <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200/60">
                                                    {{ $dest->service->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm max-w-[200px]">
                                                @if($dest->address)
                                                    <div class="text-xs text-slate-700 leading-tight">
                                                        @if($dest->label_address)
                                                            <span class="font-semibold text-slate-800">{{ $dest->label_address }}</span><br>
                                                        @endif
                                                        {{ $dest->address }}
                                                    </div>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3.5 text-sm text-right font-mono font-bold text-slate-800">
                                                {{ number_format($dest->quantity) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Client Profile Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <h3 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-4">{{ __('Client Profile') }}</h3>
                            <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="h-11 w-11 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-orange-700 flex items-center justify-center font-extrabold text-base border border-orange-200 shadow-sm">
                                    {{ substr($sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $sourcingRequest->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $sourcingRequest->user->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sourcing Quotation Form (cols: 7) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Quotation Details') }}</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Please fill in all financial and logistical data accurately to create the quote.') }}</p>
                            </div>

                            <div class="p-6 space-y-8">
                                
                                @include('admin.quotations.partials.currency-location', [
                                    'currencyValue' => '',
                                    'sourcingLocationValue' => $sourcingRequest->sourcing_location,
                                ])

                                @include('admin.quotations.partials.sourcing-note', [
                                    'sourcingLocationValue' => $sourcingRequest->sourcing_location,
                                    'requestedLocation' => $sourcingRequest->sourcing_location,
                                    'sourcingNoteValue' => '',
                                ])

                                @include('admin.quotations.partials.supplier-comments', [
                                    'supplierUrlValue' => '',
                                    'commentsValue' => '',
                                ])
                                
                                @include('admin.quotations.partials.quality-options', [
                                    'qualityPriceValues' => [],
                                    'qualityWeightValues' => [],
                                    'qualityWeightUnits' => [],
                                ])



                                @include('admin.quotations.partials.financial-pricing', [
                                    'showUnitPrice' => false,
                                    'commissionValue' => '',
                                ])

                                @include('admin.quotations.partials.logistics', [
                                    'deliveryCostValue' => '',
                                ])

                                @php $totalQuantity = $sourcingRequest->destinations->sum('quantity'); @endphp
                                @include('admin.quotations.partials.cost-estimation', [
                                    'totalQuantity' => $totalQuantity,
                                    'estProductCostValue' => '',
                                    'estShippingCostValue' => '',
                                    'estOtherCostsValue' => '',
                                ])

                            </div>
                            
                            <!-- Footer Actions with elegant border and spacing -->
                            <div class="px-6 py-5 bg-slate-50 border-t border-slate-200/80 flex items-center justify-end gap-3 rounded-b-2xl">
                                <a href="{{ route('admin.quotations.index') }}" 
                                   class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" 
                                    class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-slate-900/10 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    {{ __('Create Quotation') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Important Information footer banner -->
            <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 p-1 bg-blue-100 rounded-lg text-blue-600">
                         <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">{{ __('Important Information') }}</h3>
                        <div class="mt-2 text-xs text-blue-700/95 leading-relaxed">
                            <ul class="list-disc pl-5 space-y-1.5">
                                <li>{{ __('Fields marked with * are mandatory.') }}</li>
                                <li>{{ __('Verify pricing accuracy before submission. Quotations cannot be edited directly after submission without client reject.') }}</li>
                                <li>{{ __('Quotation will be automatically linked to the sourcing request and notify the user via web app notification.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Global photo viewer modal --}}
    <div x-data="{ viewerOpen: false, viewerSrc: '' }"
         @open-viewer.window="viewerSrc = $event.detail.src; viewerOpen = true"
         @keydown.window.escape="viewerOpen = false">
        <template x-teleport="body">
            <div x-show="viewerOpen" x-cloak
                 class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-4"
                 @click="viewerOpen = false">
                <div class="relative max-w-[90vw] max-h-[90vh]" @click.stop>
                    <button type="button" @click="viewerOpen = false"
                        class="absolute -top-3 -right-3 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-slate-700 hover:text-slate-900 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <img :src="viewerSrc" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
                </div>
            </div>
        </template>
    </div>

    @push('scripts')
    <script>
        // Currency signs map
        const currencySymbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'MAD': 'MAD',
            'JPY': '¥',
            'CNY': '¥',
            'CAD': 'CA$',
            'AUD': 'A$',
            'AED': 'AED'
        };

        function getSelectedCurrencySymbol() {
            const currencyEl = document.getElementById('currency');
            const currency = currencyEl ? currencyEl.value : 'USD';
            return currencySymbols[currency] || '$';
        }

        // Calculate estimated profit in real-time
        function calculateEstimatedProfit() {
            const totalQuantity = {{ $sourcingRequest->destinations->sum('quantity') }};
            const commission = parseFloat(document.querySelector('[name="commission_service"]')?.value || 0);
            const deliveryCost = parseFloat(document.querySelector('[name="delivery_cost_china"]')?.value || 0);
            
            const unitProductCost = parseFloat(document.getElementById('estimated_product_cost')?.value || 0);
            const shippingCost = parseFloat(document.getElementById('estimated_shipping_cost')?.value || 0);
            const otherCosts = parseFloat(document.getElementById('estimated_other_costs')?.value || 0);

            const symbol = getSelectedCurrencySymbol();

            // Calculate Total Cost Preview (for user feedback)
            const totalCostPreview = document.getElementById('est-total-cost-preview');
            const estimatedTotalProductCost = unitProductCost * totalQuantity;
            
            if (totalCostPreview) {
                if (unitProductCost > 0 && totalQuantity > 0) {
                    totalCostPreview.textContent = `~${symbol}${estimatedTotalProductCost.toFixed(2)}`;
                } else {
                    totalCostPreview.textContent = '';
                }
            }

            // Revenue = Total
            const totalRevenue = commission + deliveryCost;
            
            // Costs = (Unit Cost * Qty) + Shipping + Others
            const totalCosts = estimatedTotalProductCost + shippingCost + otherCosts;
            
            const profit = totalRevenue - totalCosts;
            const margin = totalRevenue > 0 ? (profit / totalRevenue) * 100 : 0;

            // Show/hide profit preview
            const profitPreview = document.getElementById('profit-preview');
            if (profitPreview) {
                if (unitProductCost > 0 || shippingCost > 0 || otherCosts > 0) {
                    profitPreview.classList.remove('hidden');
                    
                    // Update values
                    const profitAmtEl = document.getElementById('estimated-profit-amount');
                    const profitMarginEl = document.getElementById('estimated-profit-margin');
                    if (profitAmtEl) profitAmtEl.textContent = symbol + profit.toFixed(2);
                    if (profitMarginEl) profitMarginEl.textContent = margin.toFixed(1) + '%';
                    
                    // Update progress bar
                    const bar = document.getElementById('profit-margin-bar');
                    if (bar) bar.style.width = Math.max(0, Math.min(100, margin)) + '%';
                    
                    // Color coding and warnings
                    const warningEl = document.getElementById('margin-warning');
                    if (warningEl) {
                        if (margin < 10) {
                            if (bar) bar.className = 'h-full rounded-full bg-gradient-to-r from-red-500 to-rose-600 transition-all duration-500';
                            warningEl.innerHTML = '⚠️ {{ __("Low margin! Consider adjusting prices.") }}';
                            warningEl.className = 'text-xs text-red-400 font-bold mt-2 flex items-center gap-1';
                            warningEl.classList.remove('hidden');
                        } else if (margin < 15) {
                            if (bar) bar.className = 'h-full rounded-full bg-gradient-to-r from-amber-500 to-orange-500 transition-all duration-500';
                            warningEl.innerHTML = '⚡ {{ __("Acceptable margin. Review if possible.") }}';
                            warningEl.className = 'text-xs text-amber-400 font-bold mt-2 flex items-center gap-1';
                            warningEl.classList.remove('hidden');
                        } else {
                            if (bar) bar.className = 'h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-all duration-500';
                            warningEl.innerHTML = '✓ {{ __("Good profit margin!") }}';
                            warningEl.className = 'text-xs text-emerald-400 font-bold mt-2 flex items-center gap-1';
                            warningEl.classList.remove('hidden');
                        }
                    }
                } else {
                    profitPreview.classList.add('hidden');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle estimates section
            const toggleEstimatesBtn = document.getElementById('toggle-estimates');
            if (toggleEstimatesBtn) {
                toggleEstimatesBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = document.getElementById('estimates-section');
                    const icon = document.getElementById('toggle-icon');
                    const text = document.getElementById('toggle-text');
                    
                    if (section) section.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                    if (text) {
                        text.textContent = (section && section.classList.contains('hidden')) 
                            ? '{{ __("Show") }}' 
                            : '{{ __("Hide") }}';
                    }
                });
            }

            // Update currency symbols across the page
            const currencySelect = document.getElementById('currency');
            if (currencySelect) {
                currencySelect.addEventListener('change', function() {
                    const symbol = getSelectedCurrencySymbol();
                    const elements = document.querySelectorAll('.currency-symbol');
                    elements.forEach(el => {
                        el.textContent = symbol;
                    });
                    calculateEstimatedProfit();
                });
            }

            // Attach listeners to pricing fields
            ['commission_service', 'delivery_cost_china'].forEach(name => {
                const field = document.querySelector(`[name="${name}"]`);
                if (field) {
                    field.addEventListener('input', calculateEstimatedProfit);
                }
            });

            // Toggle sourcing note visibility
            const actualSourcingLoc = document.getElementById('actual_sourcing_location');
            if (actualSourcingLoc) {
                actualSourcingLoc.addEventListener('change', function() {
                    const container = document.getElementById('sourcing_note_container');
                    const requestedLocation = "{{ strtolower($sourcingRequest->sourcing_location) }}";
                    const selectedLocation = this.value.toLowerCase();
                    
                    if (container) {
                        if (selectedLocation !== requestedLocation) {
                            container.classList.remove('hidden');
                        } else {
                            container.classList.add('hidden');
                        }
                    }
                });
            }

            // Prevent submit if any file exceeds 10MB (avoids 413 Entity Too Large)
            const quotationForm = document.getElementById('quotation-create-form');
            if (quotationForm) {
                quotationForm.addEventListener('submit', function(e) {
                    const maxFileSize = 10485760; // 10MB
                    const fileInputs = [
                        { selector: 'input[name="real_product_image"]', label: '{{ __('Featured Photo') }}' },
                        ...['low', 'medium', 'good'].map(k => ({ selector: `input[name="quality_options_images[${k}][]"]`, label: `{{ __('Qualité') }} ${k}` })),
                        { selector: 'input[name="media_files[]"]', label: '{{ __('Media') }}' },
                    ];
                    for (const { selector, label } of fileInputs) {
                        const input = document.querySelector(selector);
                        if (input && input.files.length) {
                            for (let i = 0; i < input.files.length; i++) {
                                const file = input.files[i];
                                if (file.size > maxFileSize) {
                                    e.preventDefault();
                                    const fileSizeMB = (file.size / 1048576).toFixed(2);
                                    window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                        detail: `"${file.name}" (${label}) {{ __('dépasse 10 MB') }} (${fileSizeMB} MB). {{ __('Veuillez choisir des fichiers plus petits.') }}`
                                    }));
                                    return false;
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
