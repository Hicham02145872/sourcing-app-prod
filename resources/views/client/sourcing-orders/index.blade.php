<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Your Sourcing Orders') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Track the progress of your accepted quotations') }}</p>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if ($sourcingOrders->isEmpty())
                    {{-- Empty State --}}
                    <div class="text-center py-16 px-6">
                        <div class="mx-auto w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No Sourcing Orders Yet') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-8">{{ __('Accepted quotations will appear here') }}</p>
                        <a href="{{ route('client.quotations.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                            </svg>
                            {{ __('View Quotations') }}
                        </a>
                    </div>
                @else
                    {{-- Header Section --}}
                    <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Active Orders') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $sourcingOrders->count() }} {{ Str::plural('order', $sourcingOrders->count()) }} {{ __('in progress') }}</p>
                                </div>
                            </div>
                            
                            {{-- Filter Options --}}
                            <div class="flex items-center gap-3">
                                <select class="px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 dark:text-white font-medium">
                                    <option>{{ __('All Statuses') }}</option>
                                    <option>{{ __('Pending Payment') }}</option>
                                    <option>{{ __('Processing') }}</option>
                                    <option>{{ __('Shipped') }}</option>
                                    <option>{{ __('Delivered') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Orders List --}}
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($sourcingOrders as $order)
                            <div class="group p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                    {{-- Order Info --}}
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        {{-- Product Image --}}
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                                @if ($order->quotation->sourcingRequest->product_image)
                                                    <img src="{{ asset('storage/' . $order->quotation->sourcingRequest->product_image) }}"
                                                         alt="{{ $order->quotation->sourcingRequest->product_name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-blue-50 dark:bg-blue-900/20">
                                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                            {{ mb_substr($order->quotation->sourcingRequest->product_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Order Details --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                                        {{ $order->quotation->sourcingRequest->product_name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 dark:bg-blue-900/30 rounded-full text-xs font-medium text-blue-700 dark:text-blue-300">
                                                            {{ $order->quotation->sourcingRequest->category->name }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ __('Order ID') }}: #{{ $order->id }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Status Badge --}}
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $statusConfig = [
                                                            'pending_payment' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                            'processing' => ['color' => 'blue', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                                            'shipped' => ['color' => 'purple', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                                                            'delivered' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                        ];
                                                        $statusData = $statusConfig[$order->status] ?? $statusConfig['pending_payment'];
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900/30 text-{$statusData['color']}-700 dark:text-{$statusData['color']}-300" }}">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                                        </svg>
                                                        {{ __(ucfirst(str_replace('_', ' ', $order->status))) }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Metadata --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                                {{-- Total Amount --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Total Amount') }}:</span>
                                                    <span class="text-gray-900 dark:text-white font-semibold">
                                                        {{ number_format($order->total_amount, 2) }} {{ $order->quotation->currency }}
                                                    </span>
                                                </div>

                                                {{-- Payment Status --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Payment') }}:</span>
                                                    <span class="{{ $order->proof_of_payment_path ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }} font-semibold">
                                                        {{ $order->proof_of_payment_path ? __('Verified') : __('Pending') }}
                                                    </span>
                                                </div>

                                                {{-- Destinations --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Destinations') }}:</span>
                                                    <div class="flex items-center -space-x-1">
                                                        @foreach($order->quotation->sourcingRequest->destinations->take(3) as $destination)
                                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-base border-2 border-white dark:border-gray-800 rounded shadow-xs" title="{{ $destination->country->name }}"></span>
                                                        @endforeach
                                                        @if($order->quotation->sourcingRequest->destinations->count() > 3)
                                                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 ml-1">
                                                                +{{ $order->quotation->sourcingRequest->destinations->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Additional Info --}}
                                            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span>{{ __('Created') }}: {{ $order->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ __('Updated') }}: {{ $order->updated_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Button --}}
                                    <div class="flex-shrink-0 lg:pl-4">
                                        <a href="{{ route('client.sourcing-orders.show', $order) }}" 
                                           class="w-full lg:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 group shadow-sm hover:shadow-md">
                                            <span>{{ __('View Details') }}</span>
                                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($sourcingOrders->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700 dark:text-gray-400">
                                    {{ __('Showing') }}
                                    <span class="font-medium">{{ $sourcingOrders->firstItem() }}</span>
                                    {{ __('to') }}
                                    <span class="font-medium">{{ $sourcingOrders->lastItem() }}</span>
                                    {{ __('of') }}
                                    <span class="font-medium">{{ $sourcingOrders->total() }}</span>
                                    {{ __('results') }}
                                </div>
                                <div class="flex gap-1">
                                    {{ $sourcingOrders->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .grid-cols-1\.5 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-layout>