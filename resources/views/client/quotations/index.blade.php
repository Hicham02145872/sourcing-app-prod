<x-app-layout>
    <x-slot name="header">
        
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-black tracking-tight">
                        {{ __('Quotations') }}
                    </h2>
                    <p class="text-black-50 mt-0 ">{{ __('Review and manage quotations for your sourcing requests') }}</p>
                </div>
                <a href="{{ route('client.sourcing-requests.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-black-400 bg-white hover:bg-gray-50 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Requests') }}
                </a>
            </div>
        
    </x-slot>

    <div class="py-4 bg-gradient-to-br from-gray-50 via-white to-violet-50 dark:from-gray-900 dark:via-gray-800 dark:to-violet-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if ($sourcingRequests->isEmpty())
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-violet-100 dark:border-gray-700 overflow-hidden">
                    <div class="text-center py-24 px-6">
                        <div class="mx-auto w-28 h-28 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900 dark:to-purple-800 rounded-2xl flex items-center justify-center mb-8 shadow-lg">
                            <svg class="w-14 h-14 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('No Pending Quotations') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-10 max-w-md mx-auto">{{ __('Quotations for your sourcing requests will appear here once they are created by our team') }}</p>
                        <a href="{{ route('client.sourcing-requests.index') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-700 hover:to-violet-600 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            {{ __('View Sourcing Requests') }}
                        </a>
                    </div>
                </div>
            @else
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-violet-50 to-violet-25 dark:from-violet-900 dark:to-violet-800 rounded-xl p-6 shadow-sm border-l-4 border-violet-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Pending Quotations') }}</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequests->count() }}</p>
                            </div>
                            <svg class="w-12 h-12 text-violet-200 dark:text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-25 dark:from-emerald-900 dark:to-emerald-800 rounded-xl p-6 shadow-sm border-l-4 border-emerald-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Ready to Accept') }}</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequests->whereNotNull('quotation_id')->count() }}</p>
                            </div>
                            <svg class="w-12 h-12 text-emerald-200 dark:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-25 dark:from-blue-900 dark:to-blue-800 rounded-xl p-6 shadow-sm border-l-4 border-blue-600">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Under Review') }}</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequests->whereNull('quotation_id')->count() }}</p>
                            </div>
                            <svg class="w-12 h-12 text-blue-200 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Main Table Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-violet-100 dark:border-gray-700 overflow-hidden">
                    
                    <!-- Header -->
                    <div class="border-b border-violet-100 dark:border-gray-700 px-8 py-6 bg-gradient-to-r from-gray-50 to-violet-50 dark:from-gray-700 dark:to-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900 dark:to-purple-800 rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Active Quotations') }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Review quotations awaiting your action') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-violet-200 dark:border-violet-700 shadow-sm">
                                <div class="w-2.5 h-2.5 bg-violet-500 rounded-full animate-pulse"></div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sourcingRequests->count() }} {{ __('Active') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white dark:bg-gray-700 rounded-2xl border-2 border-violet-100 dark:border-gray-600 hover:border-violet-400 hover:shadow-2xl transition-all duration-300 overflow-hidden hover:-translate-y-1">
                                    
                                    <!-- Image Section -->
                                    <div class="relative h-48 bg-gradient-to-br from-violet-50 to-purple-50 dark:from-gray-600 dark:to-gray-500 overflow-hidden">
                                        @if ($request->product_image)
                                            <img src="{{ asset('storage/' . $request->product_image) }}"
                                                 alt="{{ $request->product_name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-20 h-20 bg-gradient-to-br from-violet-200 to-purple-200 dark:from-violet-800 dark:to-purple-800 rounded-2xl flex items-center justify-center shadow-md">
                                                    <span class="text-3xl font-extrabold text-violet-600 dark:text-violet-400">
                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <!-- Status Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full bg-violet-100 dark:bg-violet-900 text-violet-800 dark:text-violet-300 shadow-md backdrop-blur-sm border border-violet-200 dark:border-violet-700">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>

                                        <!-- Quotation Badge -->
                                        @if ($request->quotation)
                                            <div class="absolute bottom-4 left-4">
                                                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 rounded-full shadow-lg transition">
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-white">{{ __('Ready') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <!-- Product Info -->
                                        <div class="mb-5">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                                                {{ $request->product_name }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-3 py-1 bg-violet-100 dark:bg-violet-900 rounded-lg border border-violet-200 dark:border-violet-700">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-violet-700 dark:text-violet-300">{{ $request->category->name }}</span>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Quotation Details -->
                                        @if ($request->quotation)
                                            <div class="bg-gradient-to-br from-emerald-50 to-emerald-25 dark:from-emerald-900 dark:to-emerald-800 border-2 border-emerald-200 dark:border-emerald-700 rounded-xl p-5 mb-5 shadow-sm">
                                                <div class="flex items-center gap-2 mb-4">
                                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <h4 class="text-sm font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wide">{{ __('Quotation') }}</h4>
                                                </div>
                                                <div class="space-y-2.5">
                                                    <div class="flex justify-between items-center text-sm">
                                                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('Unit Price') }}:</span>
                                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($request->quotation->unit_price, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center text-sm">
                                                        <span class="text-gray-600 dark:text-gray-400 font-medium">{{ __('Commission') }}:</span>
                                                        <span class="font-bold text-gray-900 dark:text-white">{{ number_format($request->quotation->commission_service, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center p-3 bg-emerald-100 dark:bg-emerald-800 rounded-lg border-2 border-emerald-300 dark:border-emerald-600 mt-3">
                                                        <span class="text-emerald-900 dark:text-emerald-200 text-sm font-bold uppercase">{{ __('Total') }}</span>
                                                        <span class="font-extrabold text-emerald-700 dark:text-emerald-300 text-lg">{{ number_format($request->quotation->amount, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Destinations -->
                                        @if($request->destinations->count() > 0)
                                            <div class="mb-5 p-4 bg-violet-50 dark:bg-violet-900 rounded-xl border border-violet-200 dark:border-violet-700">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-violet-700 dark:text-violet-300 uppercase">{{ __('Destinations') }}</span>
                                                </div>
                                                <div class="space-y-2">
                                                    @foreach($request->destinations->take(2) as $destination)
                                                        <div class="flex items-center justify-between text-xs bg-white dark:bg-gray-700 rounded-lg px-3 py-2 border border-violet-200 dark:border-violet-700">
                                                            <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $destination->country->name }}</span>
                                                            <span class="px-2.5 py-0.5 font-bold text-violet-700 dark:text-violet-300 bg-violet-100 dark:bg-violet-800 rounded-full text-xs">{{ $destination->quantity }}</span>
                                                        </div>
                                                    @endforeach
                                                    @if($request->destinations->count() > 2)
                                                        <p class="text-xs font-bold text-violet-600 dark:text-violet-300 pl-2">+{{ $request->destinations->count() - 2 }} {{ __('more') }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Action Buttons -->
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-2 border-violet-200 dark:border-violet-700 text-violet-700 dark:text-violet-300 text-sm font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
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
                                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 dark:from-emerald-700 dark:to-emerald-600 dark:hover:from-emerald-800 dark:hover:to-emerald-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
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
                </div>
            @endif

        </div>
    </div>
</x-app-layout>