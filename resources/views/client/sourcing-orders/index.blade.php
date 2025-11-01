<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-black-900 tracking-tight">
                    {{ __('Your Sourcing Orders') }}
                </h2>
                <p class="mt-0 text-sm text-blackk-600">{{ __('Track the progress of your accepted quotations') }}</p>
            </div>
            <a href="{{ route('client.dashboard') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-4 bg-gradient-to-br from-gray-50 to-gray-100/50 min-h-screen">
        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                @if ($sourcingOrders->isEmpty())
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No Sourcing Orders Yet') }}</h3>
                        <p class="text-base text-gray-600 mb-8">{{ __('Accepted quotations will appear here') }}</p>
                        <a href="{{ route('client.quotations.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                            </svg>
                            {{ __('View Quotations') }}
                        </a>
                    </div>
                @else
                    <!-- Header Section -->
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ __('Active Orders') }}</h3>
                                    <p class="text-sm text-gray-600">{{ $sourcingOrders->count() }} {{ Str::plural('order', $sourcingOrders->count()) }} {{ __('in progress') }}</p>
                                </div>
                            </div>
                            
                            <!-- Filter Options -->
                            <div class="flex items-center gap-3">
                                <select class="px-4 py-2.5 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white font-medium shadow-sm">
                                    <option>{{ __('All Statuses') }}</option>
                                    <option>{{ __('Pending Payment') }}</option>
                                    <option>{{ __('Processing') }}</option>
                                    <option>{{ __('Shipped') }}</option>
                                    <option>{{ __('Delivered') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Grid -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($sourcingOrders as $order)
                                <div class="group bg-white rounded-2xl border-2 border-gray-200 hover:border-blue-300 hover:shadow-xl transition-all duration-300 overflow-hidden">
                                    <!-- Header with Gradient -->
                                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 text-white relative overflow-hidden">
                                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                                        <div class="relative">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-bold mb-1.5 line-clamp-1">
                                                        {{ $order->quotation->sourcingRequest->product_name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                        <span class="text-blue-100 text-sm font-medium">
                                                            {{ $order->quotation->sourcingRequest->category->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-white/20 backdrop-blur-sm border border-white/30 whitespace-nowrap ml-2">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between pt-3 border-t border-white/20">
                                                <span class="text-xs text-blue-100 font-medium uppercase tracking-wide">{{ __('Order ID') }}</span>
                                                <span class="text-sm font-bold">#{{ $order->id }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Body -->
                                    <div class="p-5">
                                        @if ($order->quotation->sourcingRequest->product_image)
                                            <div class="mb-4 rounded-xl overflow-hidden border-2 border-gray-200">
                                                <img src="{{ asset('storage/' . $order->quotation->sourcingRequest->product_image) }}"
                                                     alt="{{ $order->quotation->sourcingRequest->product_name }}"
                                                     class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                                            </div>
                                        @endif

                                        <!-- Order Details -->
                                        <div class="space-y-3 mb-5">
                                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-gray-700">{{ __('Total Amount') }}:</span>
                                                </div>
                                                <span class="text-blue-700 font-bold text-base">
                                                    {{ number_format($order->total_amount, 2) }} {{ $order->quotation->currency }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-sm font-semibold text-gray-700">{{ __('Status') }}:</span>
                                                </div>
                                                <span class="text-gray-900 font-bold text-sm capitalize">
                                                    {{ str_replace('_', ' ', $order->status) }}
                                                </span>
                                            </div>

                                            @if ($order->proof_of_payment_path)
                                                <div class="p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span class="text-sm font-semibold text-green-700">{{ __('Payment Proof') }}</span>
                                                        </div>
                                                        <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $order) }}" 
                                                           target="_blank" 
                                                           class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 hover:text-green-800 transition-colors">
                                                            {{ __('Download') }}
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="p-3 bg-gradient-to-r from-red-50 to-orange-50 rounded-xl border border-red-200">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                            <span class="text-sm font-semibold text-red-700">{{ __('Payment Proof') }}</span>
                                                        </div>
                                                        <span class="text-xs font-bold text-red-700">{{ __('Not uploaded') }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Destinations -->
                                        @if($order->quotation->sourcingRequest->destinations->count() > 0)
                                            <div class="mb-4 p-3 bg-purple-50 rounded-xl border border-purple-200">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-purple-700 uppercase">{{ __('Destinations') }}</span>
                                                </div>
                                                <div class="space-y-1.5">
                                                    @foreach($order->quotation->sourcingRequest->destinations->take(2) as $destination)
                                                        <div class="flex items-center justify-between text-xs bg-white rounded-lg px-2.5 py-1.5 border border-purple-200">
                                                            <div class="flex items-center gap-2">
                                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-base"></span>
                                                                <span class="text-gray-800 font-semibold">{{ $destination->country->name }}</span>
                                                            </div>
                                                            <span class="px-2 py-0.5 font-bold text-purple-700 bg-purple-100 rounded-full">{{ $destination->quantity }}</span>
                                                        </div>
                                                    @endforeach
                                                    @if($order->quotation->sourcingRequest->destinations->count() > 2)
                                                        <p class="text-xs font-bold text-purple-600 pl-2.5">+{{ $order->quotation->sourcingRequest->destinations->count() - 2 }} {{ __('more') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Footer -->
                                    <div class="px-5 pb-5">
                                        <a href="{{ route('client.sourcing-orders.show', $order) }}" 
                                           class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center group">
                                            <span>{{ __('View Order Details') }}</span>
                                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>