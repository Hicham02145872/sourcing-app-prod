<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Order Details') }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Order ID') }}: #{{ str_pad($sourcingOrder->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.sourcing-orders.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Orders') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Current Status') }}</h3>
                            @php
                                $statusConfig = [
                                    'pending_payment' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Pending Payment'],
                                    'paid' => ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7', 'label' => 'Paid'],
                                    'processing' => ['color' => 'blue', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Processing'],
                                    'shipped' => ['color' => 'purple', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'label' => 'Shipped'],
                                    'delivered' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Delivered'],
                                    'completed' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Completed'],
                                    'cancelled' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12', 'label' => 'Cancelled'],
                                ];
                                $statusData = $statusConfig[$sourcingOrder->status] ?? $statusConfig['pending_payment'];
                            @endphp
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900/50 dark:to-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 mb-6">
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">Status</p>
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-bold {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900/50 text-{$statusData['color']}-700 dark:text-{$statusData['color']}-300" }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                    </svg>
                                    {{ ucfirst(str_replace('_', ' ', $sourcingOrder->status)) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Last Updated</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- UPDATE STATUS FORM --}}
                        <form action="{{ route('admin.sourcing-orders.update-status', $sourcingOrder) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Update Status') }}</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    @foreach (App\Models\SourcingOrder::STATUSES as $statusOption)
                                        <option value="{{ $statusOption }}" {{ $sourcingOrder->status == $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                {{ __('Update Status') }}
                            </button>
                        </form>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">{{ __('Order Specifications') }}</h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">Order ID</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">#{{ str_pad($sourcingOrder->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-[#EF7722]/5 to-[#FAA533]/5 rounded-lg border border-[#EF7722]/20">
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">Total Amount</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($sourcingOrder->total_amount, 2) }} <span class="text-sm font-normal text-slate-600 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span></p>
                            </div>
                        </div>

                        {{-- PRODUCT INFO --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wide">Product Details</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-blue-50 dark:bg-blue-900/50 rounded-lg border border-blue-200 dark:border-blue-700">
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">Product Name</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A' }}</p>
                                </div>
                                <div class="p-4 bg-[#EF7722]/5 rounded-lg border border-[#EF7722]/20">
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">Category</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->quotation->sourcingRequest->category->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- SHIPPING METHOD --}}
                        @if ($sourcingOrder->quotation->sourcingRequest->shipping_method)
                        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-3">Shipping Method</p>
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-bold {{ $sourcingOrder->quotation->sourcingRequest->shipping_method === 'air' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700' : 'bg-cyan-100 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-700' }}">
                                @if($sourcingOrder->quotation->sourcingRequest->shipping_method === 'air')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    {{ __('Air Freight') }}
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15l5.12-5.12A3 3 0 0110.24 9H13a2 2 0 012 2v1a2 2 0 002 2h3.28a1 1 0 01.948 1.316l-1.4 4.2A2 2 0 0118.36 21H5.64a2 2 0 01-1.946-1.484l-1.4-4.2A1 1 0 013.28 14H6a2 2 0 002-2v-1a2 2 0 00-2-2H4.76a3 3 0 01-2.12-.879L3 15z"/>
                                    </svg>
                                    {{ __('Sea Freight') }}
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- CLIENT INFORMATION --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">{{ __('Client Information') }}</h3>
                        
                        <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#FAA533] to-[#EF7722] rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                <span class="text-2xl font-bold text-white">{{ substr($sourcingOrder->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->user->name }}</h4>
                                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $sourcingOrder->user->email }}</p>
                                @if($sourcingOrder->user->phone)
                                <a href="tel:{{ $sourcingOrder->user->phone }}" class="flex items-center gap-2 mt-1 text-sm text-slate-600 dark:text-slate-400 hover:text-[#EF7722] dark:hover:text-[#FAA533]">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>{{ $sourcingOrder->user->phone }}</span>
                                </a>
                                @endif
                                <div class="flex gap-6 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Member Since</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->user->created_at->format('M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">Total Orders</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingOrder->user->sourcingOrders->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">{{ __('Shipping Destinations') }}</h3>
                        
                        <div class="space-y-3">
                            @foreach ($sourcingOrder->quotation->sourcingRequest->destinations as $destination)
                                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-[#EF7722] transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white dark:bg-slate-700 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-600 flex-shrink-0">
                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-xl"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $destination->country->name }}</p>
                                            <p class="text-xs text-slate-600 dark:text-slate-400">{{ $destination->service->name }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#EF7722]/10 text-[#EF7722] rounded-lg text-xs font-bold border border-[#EF7722]/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4v-10m0 0l8 4m-8-4v10l8-4v-10"/>
                                        </svg>
                                        {{ $destination->quantity }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDEBAR --}}
                <div class="space-y-6">
                    {{-- PRODUCT IMAGE --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">{{ __('Product Reference') }}</h3>
                        
                        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                            @if($sourcingOrder->quotation->sourcingRequest->product_image)
                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                     alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name }}" 
                                     class="w-full aspect-square object-cover"/>
                            @else
                                <div class="w-full aspect-square flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-12 h-12 mx-auto text-slate-400 dark:text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ __('No image') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- TIMELINE --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">{{ __('Order Timeline') }}</h3>
                        
                        <div class="space-y-4">
                            <div class="flex gap-4">
                                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Order Created') }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $sourcingOrder->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-10 h-10 bg-[#0BA6DF]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Last Updated') }}</p>
                                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $sourcingOrder->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PAYMENT VERIFICATION --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">{{ __('Payment Verification') }}</h3>
                        
                        @if ($sourcingOrder->proof_of_payment_path)
                            <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $sourcingOrder) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 mb-3 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('Download Proof') }}
                            </a>

                            <form action="{{ route('admin.sourcing-orders.reject-proof', $sourcingOrder) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label for="rejection_reason" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Rejection Reason') }}</label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="3" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-700 rounded-lg text-xs focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white" placeholder="{{ __('Provide reason...') }}" required minlength="10"></textarea>
                                </div>
                                <button type="submit" class="w-full px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                    {{ __('Reject Proof') }}
                                </button>
                            </form>
                        @else
                            <div class="text-center py-6">
                                <svg class="w-10 h-10 mx-auto text-slate-400 dark:text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ __('No proof submitted') }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-500 mt-1">{{ __('Awaiting client submission') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        html {
            scroll-behavior: smooth;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1e293b; /* slate-800 */
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569; /* slate-600 */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b; /* slate-500 */
        }
    </style>
</x-app-layout>