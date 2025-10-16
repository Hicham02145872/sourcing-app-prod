<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('client.dashboard')],
    ['label' => 'Sourcing Requests', 'url' => route('client.sourcing-requests.index')],
    ['label' => 'Details']
]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent tracking-tight">
                    {{ __('Sourcing Request Details') }}
                </h2>
            </div>
            <a href="{{ route('client.dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-blue-50 via-white to-blue-50">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Bento Grid Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 auto-rows-auto">

                {{-- Product Card - Large Featured --}}
                <div class="lg:col-span-8 bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="grid md:grid-cols-2 gap-0">
                        {{-- Image Section --}}
                        <div class="relative">
                            @if ($sourcingRequest->product_image)
                                <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" alt="{{ $sourcingRequest->product_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-600 text-6xl font-bold">
                                    {{ mb_substr($sourcingRequest->product_name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute top-4 right-4">
                                <span class="px-4 py-2 rounded-full text-xs font-semibold shadow-lg
                                    {{ $sourcingRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 
                                       ($sourcingRequest->status === 'active' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 
                                       'bg-green-100 text-green-700 border border-green-200') }}">
                                    <span class="inline-block w-2 h-2 rounded-full mr-2
                                        {{ $sourcingRequest->status === 'pending' ? 'bg-yellow-500' : 
                                           ($sourcingRequest->status === 'active' ? 'bg-blue-500' : 'bg-green-500') }}">
                                    </span>
                                    {{ ucfirst($sourcingRequest->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Product Info Section --}}
                        <div class="p-8 flex flex-col justify-between">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center gap-2 text-blue-600 text-sm font-medium mb-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $sourcingRequest->category->name }}
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $sourcingRequest->product_name }}</h3>
                                </div>

                                <div class="space-y-3">
                                    @if ($sourcingRequest->product_url)
                                        <a href="{{ $sourcingRequest->product_url }}" target="_blank" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium group">
                                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                            {{ __('View Product Link') }}
                                        </a>
                                    @endif

                                    <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-xs font-semibold text-blue-900 mb-1">{{ __('Note') }}</p>
                                            <p class="text-sm text-blue-700">{{ $sourcingRequest->note ?? __('No additional notes') }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs font-semibold text-blue-900">{{ __('Shipping Method') }}</p>
                                            <p class="text-sm text-blue-700 font-medium">{{ ucfirst($sourcingRequest->shipping_method ?? __('N/A')) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions Card - Vertical --}}
                <div class="lg:col-span-4 bg-gradient-to-br from-blue-600 to-blue-700 rounded-3xl shadow-sm border border-blue-200 p-6 flex flex-col justify-between text-white">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold">{{ __('Quick Actions') }}</h3>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="{{ route('client.sourcing-requests.edit', $sourcingRequest) }}" 
                               class="w-full flex items-center justify-between gap-3 px-5 py-4 bg-white text-blue-700 font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>{{ __('Edit Request') }}</span>
                                </div>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>

                            <form action="{{ route('client.sourcing-requests.destroy', $sourcingRequest) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this sourcing request?')" 
                                        class="w-full flex items-center justify-between gap-3 px-5 py-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>{{ __('Delete Request') }}</span>
                                    </div>
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-white/10 rounded-xl backdrop-blur-sm border border-white/20">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold mb-1">{{ __('Need Help?') }}</p>
                                <p class="text-xs text-blue-100">{{ __('Contact support for assistance with your request.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Destinations Grid --}}
                <div class="lg:col-span-12">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">{{ __('Destinations') }}</h3>
                    </div>
                </div>

                @foreach ($sourcingRequest->destinations as $index => $destination)
                    <div class="lg:col-span-4 bg-white rounded-2xl shadow-sm border border-blue-100 p-6 hover:shadow-md hover:border-blue-200 transition-all duration-300 group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                        </div>

                        <h4 class="text-lg font-bold text-gray-900 mb-4">{{ $destination->country->name }}</h4>

                        <div class="space-y-3">
                            <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-blue-600 font-semibold">{{ __('Service') }}</p>
                                    <p class="text-sm text-gray-900 font-medium">{{ $destination->service->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs text-green-600 font-semibold">{{ __('Quantity') }}</p>
                                    <p class="text-sm text-gray-900 font-medium">{{ $destination->quantity }} {{ __('units') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>