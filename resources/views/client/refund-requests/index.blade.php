<x-app-layout>
    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            {{-- Welcome Banner --}}
            <div class="relative bg-gradient-to-r from-[#EF7722] to-[#FAA533] dark:from-[#EF7722] dark:to-[#FAA533] rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:16px_16px]"></div>
                <div class="relative px-6 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-white">
                                {{ __('Refund Management') }}
                            </h3>
                            <p class="text-sm text-white/80 mt-2 max-w-xl">
                                {{ __('Easily request and track refunds for your delivered orders. Our team reviews every claim to ensure your satisfaction.') }}
                            </p>
                        </div>
                        <div class="hidden lg:flex">
                             <svg class="w-16 h-16 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                             </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Breadcrumbs --}}
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('client.dashboard') }}" class="text-xs font-semibold text-slate-500 hover:text-[#EF7722] dark:text-slate-400 transition-colors uppercase tracking-wider">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-xs font-bold text-[#EF7722] uppercase tracking-wider">{{ __('Refunds') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Statistics Bar --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 mb-6">
                {{-- ... stats content ... --}}
                <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Eligible Orders --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Eligible Orders') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $stats['eligible_count'] }}</p>
                            </div>
                        </div>

                        {{-- Pending Refunds --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Pending Claims') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $stats['pending_count'] }}</p>
                            </div>
                        </div>

                        {{-- Approved Refunds --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Approved') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $stats['approved_count'] }}</p>
                            </div>
                        </div>

                        {{-- Total Refunded --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                <span class="text-[#0BA6DF] font-bold text-lg">$</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total Refunded') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_refunded'], 2) }} <small class="text-[10px] text-slate-400">{{ $stats['currency'] }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Existing Refund Requests --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('My Refund Claims') }}</h3>
                </div>
                <div class="p-0">
                    @if ($refundRequests->isEmpty())
                        <div class="text-center py-10 px-6">
                            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('You have no refund claims.') }}</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('ID') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Order') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Amount') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Date') }}</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                    @foreach ($refundRequests as $request)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                            <td class="px-6 py-4 text-xs font-bold text-slate-900 dark:text-white">#{{ $request->id }}</td>
                                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                                <div class="font-medium text-slate-900 dark:text-white">#{{ $request->sourcingOrder->display_id }}</div>
                                                <div class="text-xs">{{ $request->sourcingOrder->quotation->sourcingRequest->product_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white">
                                                {{ number_format($request->amount_requested, 2) }} {{ $request->sourcingOrder->quotation->currency }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusClasses = [
                                                        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                                        'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
                                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                        'under_review' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                    ];
                                                    $statusClass = $statusClasses[$request->status] ?? 'bg-slate-100 text-slate-800';
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wide {{ $statusClass }}">
                                                    {{ str_replace('_', ' ', $request->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-500">{{ $request->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('client.refund-requests.show', $request) }}" class="text-slate-400 hover:text-[#EF7722] transition-colors font-bold text-xs uppercase tracking-wider">{{ __('View') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($refundRequests->hasPages())
                            <div class="px-6 py-4 border-t border-[#EBEBEB] dark:border-slate-700">
                                {{ $refundRequests->appends(['orders_page' => request('orders_page')])->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Eligible Delivered Orders') }}</h3>
                </div>

                <div class="p-0">
                    @if ($deliveredOrders->isEmpty())
                        {{-- Empty State --}}
                        <div class="text-center py-20 px-6">
                            <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No orders ready for refund') }}</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Refunds can only be requested for orders that have been successfully delivered.') }}</p>
                            <a href="{{ route('client.sourcing-orders.index') }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">
                                {{ __('View My Orders') }}
                            </a>
                        </div>
                    @else
                        {{-- Orders Table --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                <thead class="bg-[#EBEBEB] dark:bg-slate-900/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Order') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Product') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Order Value') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Delivery Date') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-slate-800 divide-y divide-[#EBEBEB] dark:divide-slate-700">
                                    @foreach ($deliveredOrders as $order)
                                        <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-xs font-semibold text-[#EF7722]">#{{ $order->display_id }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600 overflow-hidden flex-shrink-0">
                                                        @if ($order->quotation->sourcingRequest->product_image)
                                                            <img src="{{ asset('storage/' . $order->quotation->sourcingRequest->product_image) }}"
                                                                 alt="{{ $order->quotation->sourcingRequest->product_name }}"
                                                                 class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                                <span class="text-sm font-bold text-[#EF7722]">
                                                                    {{ mb_substr($order->quotation->sourcingRequest->product_name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-sm font-semibold text-slate-900 dark:text-white truncate max-w-xs">
                                                        {{ $order->quotation->sourcingRequest->product_name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                    {{ number_format($order->total_amount, 2) }} {{ $order->quotation->currency }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-slate-900 dark:text-white">{{ $order->updated_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $order->updated_at->diffForHumans() }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <a href="{{ route('client.refund-requests.create', $order) }}" 
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#FAA533] text-white text-xs font-bold rounded-lg shadow-sm transition-all duration-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                                    </svg>
                                                    {{ __('Request Refund') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if($deliveredOrders->hasPages())
                            <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-t border-[#EBEBEB] dark:border-slate-700">
                                {{ $deliveredOrders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
