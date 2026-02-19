<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Back Button -->
                        <a href="{{ route('admin.sourcing-orders.index') }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1"></div>

                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight flex items-center gap-2">
                                {{ __('Order') }} #{{ $sourcingOrder->display_id }}
                                @php
                                    $statusColors = [
                                        'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'paid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'shipment_preparing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'processing' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'delivered' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                        'refunded' => 'bg-pink-50 text-pink-700 border-pink-200',
                                    ];
                                    $badgeClass = $statusColors[$sourcingOrder->client_status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border {{ $badgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $sourcingOrder->client_status)) }}
                                    @if($sourcingOrder->status !== $sourcingOrder->client_status)
                                        <span class="ml-1 opacity-50 text-[8px]">({{ __('Internal: ') . ucfirst(str_replace('_', ' ', $sourcingOrder->status)) }})</span>
                                    @endif
                                </span>
                            </h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.sourcing-orders.index') }}" class="hover:text-slate-700">{{ __('Order List') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ $sourcingOrder->quotation->sourcingRequest->product_name ?? __('Unknown product') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-2">
                         <button type="button" 
                            onclick="syncToGoogleSheet({{ $sourcingOrder->display_id }})"
                            class="sync-btn inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors shadow-sm group">
                            <svg class="w-3.5 h-3.5 text-green-600 group-[.loading]:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span class="sync-text">{{ __('Sync Sheet') }}</span>
                        </button>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                        @if($sourcingOrder->proof_of_payment_path || in_array($sourcingOrder->status, ['paid', 'shipment_preparing', 'in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'delivered', 'order_completed']))
                            <a href="{{ route('admin.sourcing-orders.shipping-label', $sourcingOrder) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors shadow-sm">
                                <i class="fas fa-tag"></i>
                                {{ __('Label') }}
                            </a>
                        @endif

                        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-900 text-white hover:bg-slate-800 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            {{ __('Print') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LEFT COLUMN: Main Information (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Order Workflow & Tracking (Livewire) -->
                    <livewire:admin.sourcing-order-workflow :sourcingOrder="$sourcingOrder" />

                    <!-- 2. Financial Control (Admin Only) - ENHANCED -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Internal Financial Control') }}
                            </h3>
                            
                            @if($sourcingOrder->net_profit_or_loss !== null)
                                @php
                                    $profitMargin = $sourcingOrder->total_amount > 0 
                                        ? ($sourcingOrder->net_profit_or_loss / $sourcingOrder->total_amount) * 100 
                                        : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $sourcingOrder->net_profit_or_loss >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $sourcingOrder->net_profit_or_loss >= 0 ? '+' : '' }}${{ number_format($sourcingOrder->net_profit_or_loss, 2) }}
                                    </span>
                                    <span class="text-[10px] text-slate-500">({{ number_format($profitMargin, 1) }}%)</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <!-- Show Initial Estimates if available -->
                            @if($sourcingOrder->initial_estimated_product_cost)
                                <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-lg">
                                    <div class="flex items-start gap-2 mb-3">
                                        <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-blue-900">{{ __('Initial Estimates (from quotation)') }}</p>
                                            <div class="mt-2 grid grid-cols-3 gap-3 text-xs">
                                                <div>
                                                    <span class="text-blue-600">{{ __('Product') }}:</span>
                                                    <span class="font-mono text-blue-900 font-semibold">${{ number_format($sourcingOrder->initial_estimated_product_cost, 2) }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-blue-600">{{ __('Shipping') }}:</span>
                                                    <span class="font-mono text-blue-900 font-semibold">${{ number_format($sourcingOrder->initial_estimated_shipping_cost ?? 0, 2) }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-blue-600">{{ __('Others:') }}</span>
                                                    <span class="font-mono text-blue-900 font-semibold">${{ number_format($sourcingOrder->initial_estimated_other_costs ?? 0, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Editable Real Costs Form -->
                            <form action="{{ route('admin.sourcing-orders.update-financials', $sourcingOrder) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="product_cost_price" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('TOTAL Purchase Cost (Product)') }}
                                            @if($sourcingOrder->initial_estimated_product_cost)
                                                <span class="text-blue-500 font-normal normal-case">({{ __('Est:') }} ${{ number_format($sourcingOrder->initial_estimated_product_cost, 2) }})</span>
                                            @endif
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-slate-400 text-xs">$</span>
                                            <input type="number" step="0.01" name="product_cost_price" id="product_cost_price" 
                                                value="{{ old('product_cost_price', $sourcingOrder->product_cost_price) }}"
                                                class="w-full pl-6 pr-3 py-1.5 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 font-mono text-right" 
                                                placeholder="0.00">
                                        </div>
                                        <div id="unit-cost-preview" class="mt-1 text-[10px] text-slate-500 text-right font-medium"></div>
                                    </div>
                                    
                                    <div>
                                        <label for="shipping_cost_real" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Shipping Cost (Real)') }}
                                            @if($sourcingOrder->initial_estimated_shipping_cost)
                                                <span class="text-blue-500 font-normal normal-case">({{ __('Est:') }} ${{ number_format($sourcingOrder->initial_estimated_shipping_cost, 2) }})</span>
                                            @endif
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-slate-400 text-xs">$</span>
                                            <input type="number" step="0.01" name="shipping_cost_real" id="shipping_cost_real" 
                                                value="{{ old('shipping_cost_real', $sourcingOrder->shipping_cost_real) }}"
                                                class="w-full pl-6 pr-3 py-1.5 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 font-mono text-right" 
                                                placeholder="0.00">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="rejection_loss_cost" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Losses / Rejections') }}
                                            @if($sourcingOrder->initial_estimated_other_costs)
                                                <span class="text-blue-500 font-normal normal-case">({{ __('Est:') }} ${{ number_format($sourcingOrder->initial_estimated_other_costs, 2) }})</span>
                                            @endif
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-slate-400 text-xs">$</span>
                                            <input type="number" step="0.01" name="rejection_loss_cost" id="rejection_loss_cost" 
                                                value="{{ old('rejection_loss_cost', $sourcingOrder->rejection_loss_cost) }}"
                                                class="w-full pl-6 pr-3 py-1.5 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 font-mono text-right" 
                                                placeholder="0.00">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="refund_amount" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Client Refund') }}
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2 text-slate-400 text-xs">$</span>
                                            <input type="number" step="0.01" name="refund_amount" id="refund_amount" 
                                                value="{{ old('refund_amount', $sourcingOrder->refund_amount) }}"
                                                class="w-full pl-6 pr-3 py-1.5 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 font-mono text-right" 
                                                placeholder="0.00">
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        {{-- Moved to Refund Request detail page --}}
                                    </div>
                                </div>

                                <!-- Cost Adjustment Notes -->
                                @if($sourcingOrder->initial_estimated_product_cost)
                                    <div class="mb-4">
                                        <label for="cost_adjustment_notes" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Adjustment notes (optional)') }}
                                        </label>
                                        <textarea name="cost_adjustment_notes" id="cost_adjustment_notes" rows="2"
                                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50"
                                            placeholder="{{ __('Ex: Higher shipping costs due to...') }}">{{ old('cost_adjustment_notes', $sourcingOrder->cost_adjustment_notes) }}</textarea>
                                    </div>
                                @endif

                                <div class="flex justify-end pt-3 border-t border-slate-50">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors">
                                        {{ __('Update Financials') }}
                                    </button>
                                </div>
                            </form>

                            @php
                                $totalQuantity = $sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity');
                                $quotationUnitPrice = $sourcingOrder->quotation->unit_price;
                                $realTotalProductCost = $sourcingOrder->product_cost_price ?? 0;
                                
                                // Avoid division by zero
                                $realUnitProductCost = $totalQuantity > 0 ? ($realTotalProductCost / $totalQuantity) : 0;
                                $unitMargin = $quotationUnitPrice - $realUnitProductCost;

                                $totalRevenue = $sourcingOrder->total_amount;
                                $totalRealCosts = ($sourcingOrder->product_cost_price ?? 0) 
                                                + ($sourcingOrder->shipping_cost_real ?? 0) 
                                                + ($sourcingOrder->rejection_loss_cost ?? 0)
                                                + ($sourcingOrder->refund_amount ?? 0);
                                $globalProfit = $totalRevenue - $totalRealCosts;
                            @endphp

                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <h4 class="text-xs font-bold text-slate-700 uppercase mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 3.666A5.102 5.102 0 0113 15m0 2h1v1h-1a1.002 1.002 0 01-.84-.525L10 13a4.002 4.002 0 01-1.12-1m7-5a5 5 0 01-2 5M5 21V7a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2z"/></svg>
                                    {{ __('Profit Calculation (Detail)') }}
                                </h4>

                                <!-- Calcul Marge Sur Produit -->
                                <div class="bg-indigo-50/50 rounded-lg p-4 border border-indigo-100 mb-4">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-500 mb-2">{{ __('1. Product Margin (Gross Profit)') }}</p>
                                    
                                    <div class="space-y-2 text-xs">
                                        <!-- Vente -->
                                        <div class="flex justify-between items-center text-slate-600">
                                            <span>
                                                {{ __('Sale:') }} ${{ number_format($quotationUnitPrice, 2) }} <span class="text-[10px] text-slate-400">x {{ $totalQuantity }} {{ __('units') }}</span>
                                            </span>
                                            <span class="font-mono font-medium text-slate-900">+${{ number_format($quotationUnitPrice * $totalQuantity, 2) }}</span>
                                        </div>
                                        
                                        <!-- Achat -->
                                        <div class="flex justify-between items-center text-slate-600">
                                            <span>
                                                {{ __('Purchase:') }} ${{ number_format($realUnitProductCost, 2) }} <span class="text-[10px] text-slate-400">x {{ $totalQuantity }} {{ __('units') }}</span>
                                            </span>
                                            <span class="font-mono font-medium text-red-600">-${{ number_format($realTotalProductCost, 2) }}</span>
                                        </div>

                                        <!-- Résultat Intermédiaire -->
                                        <div class="flex justify-between items-center pt-2 border-t border-indigo-200">
                                            <span class="font-semibold text-indigo-800">{{ __('Gross Product Margin') }}</span>
                                            <span class="font-mono font-bold text-indigo-700">
                                                ${{ number_format(($quotationUnitPrice * $totalQuantity) - $realTotalProductCost, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Déduction Frais & Transport -->
                                <div class="bg-white rounded-lg border border-slate-100 p-3 shadow-sm">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2 pl-1">{{ __('2. Net Result') }}</p>
                                    <div class="space-y-1 text-xs px-1">
                                        <div class="flex justify-between items-center py-1">
                                            <span class="text-slate-600">{{ __('Gross Margin (Reported)') }}</span>
                                            <span class="font-mono font-medium text-slate-900">${{ number_format(($quotationUnitPrice * $totalQuantity) - $realTotalProductCost, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1 text-slate-500">
                                            <span>{{ __('- Shipping') }}</span>
                                            <span class="font-mono text-red-400">-${{ number_format($sourcingOrder->shipping_cost_real ?? 0, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center py-1 text-slate-500">
                                            <span>{{ __('- Losses / Others') }}</span>
                                            <span class="font-mono text-red-400">-${{ number_format(($sourcingOrder->rejection_loss_cost ?? 0) + ($sourcingOrder->refund_amount ?? 0), 2) }}</span>
                                        </div>
                                        
                                        <div class="flex justify-between items-center pt-3 mt-2 border-t border-slate-200 bg-slate-50 -mx-4 px-4 pb-2 rounded-b-lg">
                                            <span class="font-bold text-slate-800 uppercase tracking-wide">{{ __('Net Profit (Final)') }}</span>
                                            <span class="font-mono text-xl font-bold {{ $globalProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                ${{ number_format($globalProfit, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                    <!-- 4. Specifications & Details -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-semibold text-slate-900">{{ __('Commercial Details') }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <!-- Amounts -->
                            <div class="col-span-1 md:col-span-2 flex items-center justify-between p-4 bg-orange-50/50 border border-orange-100 rounded-lg">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600 mb-1">{{ __('Total Amount Billed') }}</p>
                                    <p class="text-2xl font-bold text-slate-900">{{ number_format($sourcingOrder->total_amount, 2) }} <span class="text-sm text-slate-500 font-medium">{{ $sourcingOrder->quotation->currency }}</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Order ID') }}</p>
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Internal Order Reference') }} #{{ $sourcingOrder->display_id }}</h3>
                                </div>
                            </div>

                            <!-- Product -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Product') }}</label>
                                <div class="text-sm font-medium text-slate-900">{{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A' }}</div>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Category') }}</label>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs font-semibold text-slate-600">
                                        {{ $sourcingOrder->quotation->sourcingRequest->category->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Shipping Method -->
                             @if ($sourcingOrder->quotation->sourcingRequest->shipping_method)
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Shipping Method') }}</label>
                                    <div class="flex items-center gap-2 text-sm text-slate-700">
                                         @if($sourcingOrder->quotation->sourcingRequest->shipping_method === 'air')
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            Air Freight
                                        @else
                                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Sea Freight
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 4. Destinations Table -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-semibold text-slate-900">{{ __('Destinations & Dispatch') }}</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Country') }}</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Service') }}</th>
                                        <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 bg-white">
                                    @foreach ($sourcingOrder->quotation->sourcingRequest->destinations as $destination)
                                        <tr>
                                            <td class="px-6 py-3 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <span class="fi fi-{{ strtolower($destination->country->code) }} border border-slate-200 rounded-sm"></span>
                                                    <span class="text-sm font-medium text-slate-900">{{ $destination->country->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500">{{ $destination->service->name }}</td>
                                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-mono text-slate-900">{{ number_format($destination->quantity) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>

                <!-- RIGHT COLUMN: Sidebar (1/3) -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Shipping Company Integration -->
                    @if($sourcingOrder->shippingCompany)
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Shipping Company') }}</h3>
                            <!-- Status Indicator -->
                            @if($sourcingOrder->sheet_sync_error)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full border border-red-100" title="{{ $sourcingOrder->sheet_sync_error }}">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ __('Error') }}
                                </span>
                            @elseif($sourcingOrder->sheet_synced_at)
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __('Synced') }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="mb-4">
                                <p class="text-[10px] text-slate-400 uppercase mb-1">{{ __('Assigned Company') }}</p>
                                <a href="{{ route('admin.shipping-companies.index') }}" class="text-sm font-bold text-indigo-600 hover:underline">
                                    {{ $sourcingOrder->shippingCompany->name }}
                                </a>
                            </div>

                            <div class="flex flex-col gap-2">
                                @if($sourcingOrder->shippingCompany->google_sheet_id)
                                    <a href="https://docs.google.com/spreadsheets/d/{{ $sourcingOrder->shippingCompany->google_sheet_id }}" target="_blank" 
                                       class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 text-xs font-bold rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l4 4a1 1 0 01.586 1.414V19a2 2 0 01-2 2z"/></svg>
                                        {{ __('Open Google Sheet') }}
                                    </a>
                                    
                                    <button type="button" 
                                            onclick="syncToShippingSheet({{ $sourcingOrder->id }})"
                                            class="sync-shipping-btn flex items-center justify-center gap-2 w-full px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded transition-colors shadow-sm">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span class="btn-text">{{ __('Force Sync to Sheet') }}</span>
                                    </button>
                                    @if($sourcingOrder->sheet_synced_at)
                                        <p class="text-[10px] text-center text-slate-400 mt-2">
                                            {{ __('Last synced:') }} {{ \Carbon\Carbon::parse($sourcingOrder->sheet_synced_at)->diffForHumans() }}
                                        </p>
                                    @endif
                                @elseif($sourcingOrder->shippingCompany->lark_base_token)
                                    {{-- Lark Integration --}}
                                    @php
                                        $larkUrl = 'https://www.larksuite.com/';
                                        if (filter_var($sourcingOrder->shippingCompany->lark_base_token, FILTER_VALIDATE_URL)) {
                                            $larkUrl = $sourcingOrder->shippingCompany->lark_base_token;
                                        } elseif(str_starts_with($sourcingOrder->shippingCompany->lark_base_token, 'sht') || str_starts_with($sourcingOrder->shippingCompany->lark_base_token, 'wik')) {
                                            $larkUrl = 'https://www.larksuite.com/sheets/' . $sourcingOrder->shippingCompany->lark_base_token;
                                        }
                                    @endphp
                                    <a href="{{ $larkUrl }}" target="_blank" 
                                       class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 text-xs font-bold rounded transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l4 4a1 1 0 01.586 1.414V19a2 2 0 01-2 2z"/></svg>
                                        {{ __('Open Lark Sheet') }}
                                    </a>
                                    
                                    <button type="button" 
                                            onclick="syncToShippingSheet({{ $sourcingOrder->id }})" 
                                            class="sync-shipping-btn flex items-center justify-center gap-2 w-full px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded transition-colors shadow-sm">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span class="btn-text">{{ __('Force Sync to Lark') }}</span>
                                    </button>
                                    @if($sourcingOrder->sheet_synced_at)
                                        <p class="text-[10px] text-center text-slate-400 mt-2">
                                            {{ __('Last synced:') }} {{ \Carbon\Carbon::parse($sourcingOrder->sheet_synced_at)->diffForHumans() }}
                                        </p>
                                    @endif
                                @else
                                    {{-- No Integration Configured --}}
                                    <div class="p-3 bg-orange-50 border border-orange-100 rounded text-center">
                                        <svg class="w-5 h-5 text-orange-500 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <p class="text-xs text-orange-700 font-medium">{{ __('No sheet configuration found') }}</p>
                                        <p class="text-[10px] text-orange-600 mt-1">{{ __('Configure Google Sheets or Lark for this company') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- 1. Product Image -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-1">
                        @if($sourcingOrder->quotation->sourcingRequest->product_image)
                            <div class="relative group aspect-square rounded overflow-hidden bg-slate-100 cursor-pointer">
                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                     alt="Product" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                            </div>
                        @else
                            <div class="aspect-square rounded bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-medium">{{ __('No image') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- 2. Payment Proof Verification -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Proof of Payment') }}</h3>
                        </div>
                        <div class="p-5">
                            @if ($sourcingOrder->proof_of_payment_path)
                                <div class="bg-emerald-50 border border-emerald-100 rounded-md p-3 mb-4 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="text-xs font-semibold text-emerald-800">{{ __('Document received') }}</p>
                                        <p class="text-[10px] text-emerald-600">{{ __('The client uploaded a proof.') }}</p>
                                    </div>
                                </div>
                                
                                <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $sourcingOrder) }}" 
                                   class="block w-full text-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded transition-colors mb-3">
                                    {{ __('Download document') }}
                                </a>

                                <form action="{{ route('admin.sourcing-orders.reject-proof', $sourcingOrder) }}" method="POST" class="pt-3 border-t border-slate-50">
                                    @csrf
                                    <label for="rejection_reason" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Rejection reason (If invalid)') }}</label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="2" class="w-full px-2 py-1.5 text-xs border border-slate-300 rounded focus:ring-1 focus:ring-red-500 focus:border-red-500 mb-2" placeholder="{{ __('Ex: Unreadable, incorrect amount...') }}"></textarea>
                                    <button type="submit" class="w-full px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 hover:text-red-800 text-xs font-medium rounded transition-colors border border-red-100">
                                        {{ __('Reject Proof') }}
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-50 text-slate-400 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-xs font-medium text-slate-900">{{ __('No document') }}</p>
                                    <p class="text-[10px] text-slate-500">{{ __('Awaiting client') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 3. Client Profile -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('Client') }}</h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold border border-orange-200">
                                    {{ substr($sourcingOrder->user->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $sourcingOrder->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate">{{ $sourcingOrder->user->email }}</p>
                                </div>
                            </div>
                            
                            @if($sourcingOrder->user->phone)
                                <div class="flex gap-2 items-center text-xs text-slate-600 mb-3 pl-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $sourcingOrder->user->phone }}
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-50 text-center">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase">{{ __('Member since') }}</p>
                                    <p class="text-xs font-bold text-slate-700">{{ $sourcingOrder->user->created_at->format('M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase">{{ __('Total Orders') }}</p>
                                    <p class="text-xs font-bold text-slate-700">{{ $sourcingOrder->user->sourcingOrders->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Real-time Unit Cost Calculation (Helper for Total Input)
        document.addEventListener('DOMContentLoaded', function() {
            const qty = {{ $sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity') }};
            const productInput = document.getElementById('product_cost_price');
            const unitPreview = document.getElementById('unit-cost-preview');

            function updateUnitCost() {
                const total = parseFloat(productInput.value) || 0;
                if (qty > 0 && total > 0) {
                    const unit = total / qty;
                    // Format to show significant decimals if needed, mostly 2
                    unitPreview.textContent = "{{ __('i.e. ~:amount / unit') }}".replace(':amount', unit.toFixed(2) + ' $'); 
                    unitPreview.className = 'mt-1 text-[10px] text-indigo-600 text-right font-bold';
                } else {
                    unitPreview.textContent = '';
                }
            }

            if (productInput) {
                productInput.addEventListener('input', updateUnitCost);
                // Trigger immediately to show current state
                updateUnitCost();
            }
        });

        function syncToGoogleSheet(orderId) {
            const button = event.currentTarget;
            const originalText = button.querySelector('.sync-text').textContent;
            
            // UI Loading state
            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-not-allowed', 'loading');
            button.querySelector('.sync-text').textContent = "{{ __('Syncing...') }}";
            
            fetch(`/admin/sourcing-orders/${orderId}/sync-to-sheet`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Sync error:', error);
                showToast("{{ __('Error during synchronization') }}", 'error');
            })
            .finally(() => {
                button.disabled = false;
                button.classList.remove('opacity-70', 'cursor-not-allowed', 'loading');
                button.querySelector('.sync-text').textContent = originalText;
            });
        }

        function syncToShippingSheet(orderId) {
            const button = event.currentTarget;
            const textSpan = button.querySelector('.btn-text');
            const originalText = textSpan.textContent;
            
            // UI Loading state
            button.disabled = true;
            button.classList.add('opacity-70', 'cursor-not-allowed', 'loading');
            textSpan.textContent = "{{ __('Syncing...') }}";
            
            fetch(`/admin/sourcing-orders/${orderId}/sync-shipping-sheet`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1500); // Reload to show new status
                } else {
                    showToast(data.message, 'error');
                    button.disabled = false;
                    button.classList.remove('opacity-70', 'cursor-not-allowed', 'loading');
                    textSpan.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Sync error:', error);
                showToast("{{ __('Error during synchronization') }}", 'error');
                button.disabled = false;
                button.classList.remove('opacity-70', 'cursor-not-allowed', 'loading');
                textSpan.textContent = originalText;
            });
        }

        function deleteMedia(button, mediaId) {
            if (!confirm("{{ __('Are you sure you want to delete this media?') }}")) return;

            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');

            fetch(`/admin/media/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    const element = document.getElementById(`media-item-${mediaId}`);
                    if (element) {
                        element.style.transition = 'all 0.3s ease';
                        element.style.opacity = '0';
                        element.style.transform = 'scale(0.9)';
                        setTimeout(() => element.remove(), 300);
                    }
                } else {
                    showToast(data.message || "{{ __('Error during deletion') }}", 'error');
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                showToast("{{ __('Error during deletion') }}", 'error');
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }

        // Drag & Drop Visual Effects
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');

        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('bg-indigo-50', 'border-indigo-400', 'scale-[1.02]');
            }

            function unhighlight(e) {
                dropZone.classList.remove('bg-indigo-50', 'border-indigo-400', 'scale-[1.02]');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (fileInput) fileInput.files = files; 
                handleFiles(files);
            }
        }

        function handleFiles(files) {
            const container = document.getElementById('upload-preview');
            const fileCount = document.getElementById('file-count');
            container.innerHTML = '';
            
            if (files && files.length > 0) {
                container.classList.remove('hidden');
                
                if (fileCount) {
                    fileCount.textContent = "{{ __(':count file(s) selected') }}".replace(':count', files.length);
                    fileCount.classList.remove('hidden');
                }

                Array.from(files).forEach(file => {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                         const div = document.createElement('div');
                         div.className = 'relative aspect-square rounded-lg overflow-hidden border border-slate-200 bg-slate-50 shadow-sm';
                         
                         if (file.type.startsWith('video/')) {
                             div.innerHTML = `
                                <video src="${e.target.result}" class="w-full h-full object-cover"></video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <svg class="w-8 h-8 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                             `;
                         } else {
                             div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                         }
                         
                         container.appendChild(div);
                    }
                    
                    reader.readAsDataURL(file);
                });
            } else {
                container.classList.add('hidden');
                if (fileCount) fileCount.classList.add('hidden');
            }
        }


        // Auto Toast from Session
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
             @if($errors->any())
                showToast("{{ __('Error:') }} {{ $errors->first() }}", 'error');
            @endif
        });
        
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const borderClass = type === 'success' ? 'border-emerald-500' : 'border-red-500';
            const iconColor = type === 'success' ? 'text-emerald-500' : 'text-red-500';
            
            toast.className = `fixed top-20 right-4 bg-white border-l-4 ${borderClass} px-6 py-4 rounded shadow-lg z-50 flex items-center gap-3 animate-slide-in transform transition-all duration-300 max-w-sm`;
            toast.innerHTML = `
                <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${type === 'success' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
                </svg>
                <p class="font-medium text-slate-800 text-sm">${message}</p>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    <style>
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out forwards;
        }
    </style>
    @endpush
</x-app-layout>