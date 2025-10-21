<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('client.dashboard')],
    ['label' => 'Sourcing Orders', 'url' => route('client.sourcing-orders.index')],
    ['label' => 'Details']
]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Sourcing Order Details') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Order #{{ $sourcingOrder->id }}</p>
            </div>
            <a href="{{ route('client.sourcing-orders.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Orders') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Product Information --}}
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold text-gray-900">{{ __('Product Information') }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 text-xs font-medium text-violet-700 bg-violet-50 rounded border border-violet-200">
                                        {{ $sourcingOrder->quotation->sourcingRequest->category->name }}
                                    </span>
                                    <span class="px-2.5 py-1 text-xs font-medium rounded border
                                        {{ $sourcingOrder->status === 'pending_payment' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 
                                           ($sourcingOrder->status === 'payment_pending_verification' ? 'bg-blue-50 text-blue-700 border-blue-200' : 
                                           'bg-green-50 text-green-700 border-green-200') }}">
                                        {{ ucfirst(str_replace('_', ' ', $sourcingOrder->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Product Image --}}
                                <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                                    @if ($sourcingOrder->quotation->sourcingRequest->product_image)
                                        <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                             alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name }}" 
                                             class="w-full h-64 object-cover">
                                    @else
                                        <div class="w-full h-64 flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="w-16 h-16 bg-white rounded-lg border border-gray-200 flex items-center justify-center mx-auto mb-2">
                                                    <span class="text-2xl font-semibold text-gray-400">{{ mb_substr($sourcingOrder->quotation->sourcingRequest->product_name, 0, 1) }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ __('No image') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Details --}}
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-4">{{ $sourcingOrder->quotation->sourcingRequest->product_name }}</h4>
                                    </div>

                                    @if ($sourcingOrder->quotation->sourcingRequest->product_url)
                                        <a href="{{ $sourcingOrder->quotation->sourcingRequest->product_url }}" target="_blank" 
                                           class="inline-flex items-center text-sm font-medium text-violet-600 hover:text-violet-700">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            {{ __('View Source') }}
                                        </a>
                                    @endif

                                    <div class="pt-4 space-y-3">
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Additional Notes') }}</p>
                                            <p class="text-sm text-gray-700">{{ $sourcingOrder->quotation->sourcingRequest->note ?? __('{{ __('No notes provided') }}') }}</p>
                                        </div>

                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Shipping Method') }}</p>
                                            <p class="text-sm text-gray-900">{{ ucfirst($sourcingOrder->quotation->sourcingRequest->shipping_method ?? __('{{ __('Not specified') }}')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quotation Details --}}
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900">{{ __('Quotation Details') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Unit Price') }}</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ number_format($sourcingOrder->quotation->unit_price, 2) }} {{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Commission') }}</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ number_format($sourcingOrder->quotation->commission_service, 2) }} {{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Unit Weight') }}</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ number_format($sourcingOrder->quotation->unit_weight, 2) }} g</p>
                                    </div>
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">{{ __('Delivery Cost') }}</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ number_format($sourcingOrder->quotation->delivery_cost_china, 2) }} {{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                </div>
                                
                                <div class="p-5 bg-violet-50 border border-violet-200 rounded-lg">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-700">{{ __('Total Amount') }}</p>
                                        <p class="text-2xl font-bold text-violet-700">{{ number_format($sourcingOrder->quotation->amount, 2) }} {{ $sourcingOrder->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment --}}
                    <div class="bg-white border border-gray-200 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-base font-semibold text-gray-900">{{ __('Payment') }}</h3>
                        </div>
                        <div class="p-6">
                            @if($sourcingOrder->status === 'pending_payment')
                                <div class="pt-6 border-t border-gray-200">
                                    <form action="{{ route('client.sourcing-orders.upload-proof-of-payment', $sourcingOrder) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Upload Proof of Payment') }}</label>
                                        <input type="file" name="proof_of_payment" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 border border-gray-300 rounded-lg mb-3"/>
                                        <button type="submit" class="w-full py-2.5 px-4 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg">
                                            {{ __('Submit Payment Proof') }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($sourcingOrder->proof_of_payment_path)
                                <div class="pt-6 border-t border-gray-200 text-center">
                                    <svg class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900 mb-2">{{ __('Payment proof uploaded') }}</p>
                                    <a href="{{ asset('storage/' . $sourcingOrder->proof_of_payment_path) }}" target="_blank" 
                                       class="text-sm font-medium text-violet-600 hover:text-violet-700">
                                        {{ __('View Document') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-lg sticky top-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-violet-50">
                            <h3 class="text-base font-semibold text-gray-900">{{ __('Summary') }}</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Order ID') }}</span>
                                <span class="font-medium text-gray-900">#{{ $sourcingOrder->id }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Status') }}</span>
                                <span class="font-medium text-gray-900">{{ __(\ucfirst(str_replace('_', ' ', $sourcingOrder->status))) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Category') }}</span>
                                <span class="font-medium text-gray-900">{{ $sourcingOrder->quotation->sourcingRequest->category->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('Total Quantity') }}</span>
                                <span class="font-medium text-gray-900">{{ number_format($sourcingOrder->quotation->sourcingRequest->destinations->sum('quantity')) }}</span>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-200">
                                <div class="p-4 bg-violet-50 rounded-lg">
                                    <p class="text-xs font-medium text-gray-600 uppercase mb-1">{{ __('Total Amount') }}</p>
                                    <p class="text-xl font-bold text-violet-700">{{ number_format($sourcingOrder->quotation->amount, 2) }} {{ $sourcingOrder->quotation->currency }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
