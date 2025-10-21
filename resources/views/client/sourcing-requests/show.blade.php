<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('client.dashboard')],
    ['label' => 'Sourcing Requests', 'url' => route('client.sourcing-requests.index')],
    ['label' => 'Details']
]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Sourcing Request Details') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Request #{{ $sourcingRequest->id }}</p>
            </div>
            <a href="{{ route('client.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Status Banner --}}
            <div class="mb-6 p-4 rounded-lg border
                {{ $sourcingRequest->status === 'pending' ? 'bg-yellow-50 border-yellow-200' : 
                   ($sourcingRequest->status === 'active' ? 'bg-blue-50 border-blue-200' : 
                   'bg-green-50 border-green-200') }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $sourcingRequest->status === 'pending' ? 'bg-yellow-100' : 
                               ($sourcingRequest->status === 'active' ? 'bg-blue-100' : 'bg-green-100') }}">
                            <svg class="w-5 h-5 {{ $sourcingRequest->status === 'pending' ? 'text-yellow-600' : 
                                       ($sourcingRequest->status === 'active' ? 'text-blue-600' : 'text-green-600') }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($sourcingRequest->status === 'pending')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @elseif($sourcingRequest->status === 'active')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ __('Status') }}: {{ __(ucfirst($sourcingRequest->status)) }}</p>
                            <p class="text-xs text-gray-600">{{ __('Last updated') }} {{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full border
                        {{ $sourcingRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : 
                           ($sourcingRequest->status === 'active' ? 'bg-blue-100 text-blue-800 border-blue-300' : 
                           'bg-green-100 text-green-800 border-green-300') }}">
                        {{ ucfirst($sourcingRequest->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Product Information --}}
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <h3 class="text-base font-semibold text-gray-900">{{ __('Product Information') }}</h3>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium text-violet-700 bg-white rounded-full border border-violet-200">
                                    {{ $sourcingRequest->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Product Image --}}
                                <div class="relative group">
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl overflow-hidden">
                                        @if ($sourcingRequest->product_image)
                                            <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                                 alt="{{ $sourcingRequest->product_name }}" 
                                                 class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105">
                                        @else
                                            <div class="w-full h-64 flex items-center justify-center">
                                                <div class="text-center">
                                                    <div class="w-20 h-20 bg-white rounded-2xl border-2 border-gray-200 flex items-center justify-center mx-auto mb-3 shadow-sm">
                                                        <span class="text-3xl font-bold text-gray-400">{{ mb_substr($sourcingRequest->product_name, 0, 1) }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-500 font-medium">{{ __('No image available') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Product Details --}}
                                <div class="flex flex-col justify-between">
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $sourcingRequest->product_name }}</h4>
                                            @if ($sourcingRequest->product_url)
                                                <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                                   class="inline-flex items-center gap-1.5 text-sm font-medium text-violet-600 hover:text-violet-700 hover:gap-2 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    {{ __('View Source Product') }}
                                                </a>
                                            @endif
                                        </div>

                                        <div class="space-y-3 pt-2">
                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('Additional Notes') }}</p>
                                                <p class="text-sm text-gray-700 leading-relaxed">{{ $sourcingRequest->note ?? __('No notes provided') }}</p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">{{ __('Shipping Method') }}</p>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                    </svg>
                                                    <p class="text-sm font-medium text-gray-900">{{ ucfirst($sourcingRequest->shipping_method ?? __('Not specified')) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Destinations --}}
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-base font-semibold text-gray-900">{{ __('Shipping Destinations') }}</h3>
                                <span class="ml-auto px-2.5 py-0.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">
                                    {{ $sourcingRequest->destinations->count() }} {{ trans_choice('destination', $sourcingRequest->destinations->count()) }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach ($sourcingRequest->destinations as $index => $destination)
                                    <div class="group relative p-4 bg-gradient-to-r from-gray-50 to-gray-50 hover:from-violet-50 hover:to-purple-50 border border-gray-200 hover:border-violet-300 rounded-xl transition-all duration-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 text-white text-sm font-bold rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="fi fi-{{ strtolower($destination->country->code) }} text-xl"></span>
                                                        <p class="font-semibold text-gray-900">{{ $destination->country->name }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                        </svg>
                                                        <p class="text-xs text-gray-600 font-medium">{{ $destination->service->name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-gray-900">{{ number_format($destination->quantity) }}</p>
                                                <p class="text-xs text-gray-500 font-medium">{{ __('units') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Quotation --}}
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                    <h3 class="text-base font-semibold text-gray-900">{{ __('Price Quotation') }}</h3>
                                </div>
                                @if ($sourcingRequest->quotation)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border
                                        @if($sourcingRequest->quotation->status === 'approved') bg-green-100 text-green-800 border-green-300
                                        @elseif($sourcingRequest->quotation->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-300
                                        @else bg-gray-100 text-gray-800 border-gray-300
                                        @endif">
                                        {{ ucfirst($sourcingRequest->quotation->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            @if ($sourcingRequest->quotation)
                                <div class="space-y-5">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-50 border border-blue-200 rounded-xl">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">{{ __('Unit Price') }}</p>
                                            </div>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($sourcingRequest->quotation->unit_price, 2) }} <span class="text-sm text-gray-600">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-50 border border-purple-200 rounded-xl">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="text-xs font-semibold text-purple-700 uppercase tracking-wide">{{ __('Commission') }}</p>
                                            </div>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($sourcingRequest->quotation->commission_service, 2) }} <span class="text-sm text-gray-600">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                        <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-50 border border-orange-200 rounded-xl">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                                </svg>
                                                <p class="text-xs font-semibold text-orange-700 uppercase tracking-wide">{{ __('Unit Weight') }}</p>
                                            </div>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($sourcingRequest->quotation->unit_weight, 2) }} <span class="text-sm text-gray-600">g</span></p>
                                        </div>
                                        <div class="p-4 bg-gradient-to-br from-teal-50 to-teal-50 border border-teal-200 rounded-xl">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <p class="text-xs font-semibold text-teal-700 uppercase tracking-wide">{{ __('Delivery Cost') }}</p>
                                            </div>
                                            <p class="text-xl font-bold text-gray-900">{{ number_format($sourcingRequest->quotation->delivery_cost_china, 2) }} <span class="text-sm text-gray-600">{{ $sourcingRequest->quotation->currency }}</span></p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 bg-gradient-to-r from-violet-100 via-purple-100 to-violet-100 border-2 border-violet-300 rounded-xl shadow-sm">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-semibold text-violet-700 uppercase tracking-wide mb-1">{{ __('Total Amount') }}</p>
                                                <p class="text-3xl font-extrabold text-violet-800">{{ number_format($sourcingRequest->quotation->amount, 2) }} <span class="text-lg">{{ $sourcingRequest->quotation->currency }}</span></p>
                                            </div>
                                            <svg class="w-12 h-12 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                            </svg>
                                        </div>
                                    </div>

                                    @if($sourcingRequest->quotation->sourcingOrder === null)
                                        <form action="{{ route('client.quotations.accept', $sourcingRequest->quotation) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('Accept Quotation') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 mb-1">{{ __('Quotation Pending') }}</p>
                                    <p class="text-xs text-gray-500">{{ __("We're preparing your quote") }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Methods --}}
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden" x-data="{ selectedMethod: null }">
                        <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <h3 class="text-base font-semibold text-gray-900">{{ __('Payment Methods') }}</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-6">
                                @foreach($paymentMethods as $paymentMethod)
                                    <button @click="selectedMethod = selectedMethod === '{{ $paymentMethod->name }}' ? null : '{{ $paymentMethod->name }}'" 
                                            class="w-full p-4 text-left border rounded-xl transition-all duration-200"
                                            :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'border-violet-400 bg-violet-50 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm'">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                @if($paymentMethod->logo_path)
                                                    <div class="w-12 h-12 rounded-lg border-2 bg-white p-1.5 shadow-sm">
                                                        <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-contain">
                                                    </div>
                                                @else
                                                    <div class="w-12 h-12 flex items-center justify-center rounded-lg border-2 bg-gradient-to-br from-gray-50 to-gray-100 shadow-sm">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-semibold text-gray-900">{{ $paymentMethod->name }}</span>
                                            </div>
                                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="selectedMethod === '{{ $paymentMethod->name }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                        <div x-show="selectedMethod === '{{ $paymentMethod->name }}'" 
                                             x-transition 
                                             class="mt-4 pt-4 border-t border-gray-200">
                                            <div class="space-y-2.5">
                                                @foreach($paymentMethod->details as $key => $value)
                                                    <div class="flex justify-between items-center p-2.5 bg-white rounded-lg border border-gray-100">
                                                        <span class="text-sm text-gray-600 font-medium">{{ $key }}:</span>
                                                        <span class="text-sm font-semibold text-gray-900">{{ $value }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            @if($sourcingRequest->quotation && $sourcingRequest->quotation->order && $sourcingRequest->quotation->order->status === 'pending_payment')
                                <div class="pt-6 border-t-2 border-gray-200">
                                    <form action="{{ route('client.sourcing-orders.upload-proof-of-payment', $sourcingRequest->quotation->order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                {{ __('Upload Proof of Payment') }}
                                            </label>
                                            <div class="relative">
                                                <input type="file" 
                                                       name="proof_of_payment" 
                                                       class="block w-full text-sm text-gray-600 
                                                              file:mr-4 file:py-3 file:px-5
                                                              file:rounded-lg file:border-0
                                                              file:text-sm file:font-semibold
                                                              file:bg-violet-50 file:text-violet-700
                                                              hover:file:bg-violet-100
                                                              file:cursor-pointer file:transition-colors
                                                              border-2 border-dashed border-gray-300 rounded-xl
                                                              hover:border-violet-400 transition-colors
                                                              cursor-pointer p-3"
                                                       required/>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-500">{{ __('Accepted formats: PDF, JPG, PNG (Max: 5MB)') }}</p>
                                        </div>
                                        <button type="submit" 
                                                class="w-full py-3.5 px-6 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Submit Payment Proof') }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($sourcingRequest->quotation && $sourcingRequest->quotation->order && $sourcingRequest->quotation->order->proof_of_payment_path)
                                <div class="pt-6 border-t-2 border-gray-200">
                                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-base font-bold text-gray-900 mb-2">{{ __('Payment Proof Uploaded') }}</p>
                                        <p class="text-sm text-gray-600 mb-4">{{ __('Your payment proof has been successfully submitted') }}</p>
                                        <a href="{{ asset('storage/' . $sourcingRequest->quotation->order->proof_of_payment_path) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-green-700 bg-white border-2 border-green-300 rounded-lg hover:bg-green-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ __('View Document') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-gradient-to-r from-violet-100 to-purple-100 border-b border-violet-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="text-base font-bold text-gray-900">{{ __('Order Summary') }}</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm font-medium text-gray-600">{{ __('Request ID') }}</span>
                                <span class="text-sm font-bold text-gray-900">#{{ $sourcingRequest->id }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm font-medium text-gray-600">{{ __('Status') }}</span>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full
                                    {{ $sourcingRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($sourcingRequest->status === 'active' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($sourcingRequest->status) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm font-medium text-gray-600">{{ __('Category') }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ $sourcingRequest->category->name }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm font-medium text-gray-600">{{ __('Destinations') }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ $sourcingRequest->destinations->count() }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm font-medium text-gray-600">{{ __('Total Quantity') }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($sourcingRequest->destinations->sum('quantity')) }}</span>
                            </div>
                            
                            @if($sourcingRequest->quotation)
                            <div class="pt-4 border-t-2 border-gray-200">
                                <div class="p-5 bg-gradient-to-br from-violet-100 via-purple-100 to-violet-100 rounded-xl border-2 border-violet-300 shadow-sm">
                                    <div class="text-center">
                                        <p class="text-xs font-bold text-violet-700 uppercase tracking-wide mb-2">{{ __('Grand Total') }}</p>
                                        <p class="text-3xl font-extrabold text-violet-800 mb-1">{{ number_format($sourcingRequest->quotation->amount, 2) }}</p>
                                        <p class="text-sm font-semibold text-violet-600">{{ $sourcingRequest->quotation->currency }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="pt-4 border-t-2 border-gray-200">
                                <div class="text-center text-xs text-gray-500">
                                    <p class="mb-1">{{ __('Created') }} {{ $sourcingRequest->created_at->diffForHumans() }}</p>
                                    <p>{{ __('Updated') }} {{ $sourcingRequest->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>