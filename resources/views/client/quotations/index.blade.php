<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Quotations') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('Review and accept quotations for your sourcing requests') }}</p>
            </div>
            <a href="{{ route('client.sourcing-requests.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Requests') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-gray-100/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
                @if ($sourcingRequests->isEmpty())
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No Pending Quotations') }}</h3>
                        <p class="text-base text-gray-600 mb-8">{{ __('Quotations for your sourcing requests will appear here') }}</p>
                        <a href="{{ route('client.sourcing-requests.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            {{ __('View Sourcing Requests') }}
                        </a>
                    </div>
                @else
                    <!-- Header Section -->
                    <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ __('Pending Quotations') }}</h3>
                                    <p class="text-sm text-gray-600">{{ $sourcingRequests->count() }} {{ Str::plural('quotation', $sourcingRequests->count()) }} {{ __('awaiting review') }}</p>
                                </div>
                            </div>
                            
                            <!-- Stats Badge -->
                            <div class="flex items-center gap-3">
                                <div class="px-4 py-2 bg-white rounded-xl border-2 border-green-200 shadow-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-bold text-gray-900">{{ $sourcingRequests->count() }} {{ __('Active') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white rounded-2xl border-2 border-gray-200 hover:border-green-300 hover:shadow-xl transition-all duration-300 overflow-hidden">
                                    <!-- Image Section -->
                                    <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
                                        @if ($request->product_image)
                                            <img src="{{ asset('storage/' . $request->product_image) }}"
                                                 alt="{{ $request->product_name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-20 h-20 bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center shadow-md">
                                                    <span class="text-3xl font-extrabold text-green-600">
                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full backdrop-blur-md shadow-md border bg-blue-100/95 text-blue-800 border-blue-300">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                        <!-- Quotation Badge -->
                                        <div class="absolute bottom-3 left-3">
                                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500 rounded-full shadow-md">
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-xs font-bold text-white">{{ __('Quotation Ready') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <!-- Header -->
                                        <div class="mb-4">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-green-600 transition-colors">
                                                {{ $request->product_name }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center px-2.5 py-1 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-green-700">{{ $request->category->name }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quotation Details -->
                                        @if ($request->quotation)
                                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-4 mb-4 shadow-sm">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/>
                                                    </svg>
                                                    <h4 class="text-sm font-bold text-green-800 uppercase tracking-wide">{{ __('Quotation Details') }}</h4>
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between items-center p-2 bg-white rounded-lg border border-green-200">
                                                        <span class="text-gray-600 text-sm font-medium">{{ __('Unit Price') }}:</span>
                                                        <span class="font-bold text-gray-900">{{ number_format($request->quotation->unit_price, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center p-2 bg-white rounded-lg border border-green-200">
                                                        <span class="text-gray-600 text-sm font-medium">{{ __('Commission') }}:</span>
                                                        <span class="font-bold text-gray-900">{{ number_format($request->quotation->commission_service, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center p-3 bg-green-100 rounded-lg border-2 border-green-300 mt-3">
                                                        <span class="text-green-800 text-sm font-bold uppercase">{{ __('Total Amount') }}:</span>
                                                        <span class="font-extrabold text-green-700 text-lg">{{ number_format($request->quotation->amount, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Destinations -->
                                        @if($request->destinations->count() > 0)
                                            <div class="mb-4 p-3 bg-purple-50 rounded-xl border border-purple-200">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-purple-700 uppercase">{{ __('Destinations') }}</span>
                                                </div>
                                                <div class="space-y-1.5">
                                                    @foreach($request->destinations->take(2) as $destination)
                                                        <div class="flex items-center justify-between text-xs bg-white rounded-lg px-2.5 py-1.5 border border-purple-200">
                                                            <div class="flex items-center gap-2">
                                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-base"></span>
                                                                <span class="text-gray-800 font-semibold">{{ $destination->country->name }}</span>
                                                            </div>
                                                            <span class="px-2 py-0.5 font-bold text-purple-700 bg-purple-100 rounded-full">{{ $destination->quantity }}</span>
                                                        </div>
                                                    @endforeach
                                                    @if($request->destinations->count() > 2)
                                                        <p class="text-xs font-bold text-purple-600 pl-2.5">+{{ $request->destinations->count() - 2 }} {{ __('more') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 border-2 border-gray-300 hover:border-gray-400 text-gray-700 text-sm font-bold rounded-xl transition-all duration-200 shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>{{ __('View') }}</span>
                                            </a>
                                            @if ($request->quotation)
                                                <form action="{{ route('client.quotations.accept', $request->quotation) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>{{ __('Accept') }}</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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