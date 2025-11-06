<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Quotations') }}
                    </h2>
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">{{ __('Review and manage quotations for your sourcing requests') }}</p>
                </div>
                <a href="{{ route('client.sourcing-requests.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Requests') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if ($sourcingRequests->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="text-center py-16 px-6">
                        <div class="mx-auto w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No Pending Quotations') }}</h3>
                        <p class="text-base text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">{{ __('Quotations for your sourcing requests will appear here once they are created by our team') }}</p>
                        <a href="{{ route('client.sourcing-requests.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            {{ __('View Sourcing Requests') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Statistics Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Pending Quotations') }}</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequests->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Ready to Accept') }}</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequests->whereNotNull('quotation_id')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Under Review') }}</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequests->whereNull('quotation_id')->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    
                    {{-- Header --}}
                    <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Active Quotations') }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Review quotations awaiting your action') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600">
                                <div class="w-2.5 h-2.5 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sourcingRequests->count() }} {{ __('Active') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Quotations List --}}
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($sourcingRequests as $request)
                            <div class="group p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                    {{-- Request Info --}}
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        {{-- Product Image --}}
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                                @if ($request->product_image)
                                                    <img src="{{ asset('storage/' . $request->product_image) }}"
                                                         alt="{{ $request->product_name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-blue-50 dark:bg-blue-900/20">
                                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                                            {{ mb_substr($request->product_name, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Request Details --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                                        {{ $request->product_name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 dark:bg-blue-900/30 rounded-full text-xs font-medium text-blue-700 dark:text-blue-300">
                                                            {{ $request->category->name }}
                                                        </span>
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $request->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Status Badge --}}
                                                <div class="flex items-center gap-2">
                                                    @if ($request->quotation)
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ __('Ready to Accept') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ __('Under Review') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Metadata --}}
                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                                {{-- Quotation Status --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Quotation') }}:</span>
                                                    <span class="{{ $request->quotation ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }} font-semibold">
                                                        {{ $request->quotation ? __('Ready') : __('Pending') }}
                                                    </span>
                                                </div>

                                                {{-- Destinations --}}
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Destinations') }}:</span>
                                                    <div class="flex items-center -space-x-1">
                                                        @foreach($request->destinations->take(3) as $destination)
                                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-base border-2 border-white dark:border-gray-800 rounded shadow-xs" title="{{ $destination->country->name }}"></span>
                                                        @endforeach
                                                        @if($request->destinations->count() > 3)
                                                            <span class="text-xs font-bold text-gray-500 dark:text-gray-400 ml-1">
                                                                +{{ $request->destinations->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Total Amount --}}
                                                @if ($request->quotation)
                                                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ __('Total') }}:</span>
                                                    <span class="text-gray-900 dark:text-white font-semibold">
                                                        {{ number_format($request->quotation->amount, 2) }} {{ $request->quotation->currency }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Quotation Details --}}
                                            @if ($request->quotation)
                                            <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-gray-600 dark:text-gray-400">{{ __('Unit Price') }}:</span>
                                                        <span class="font-semibold text-gray-900 dark:text-white ml-2">{{ number_format($request->quotation->unit_price, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600 dark:text-gray-400">{{ __('Commission') }}:</span>
                                                        <span class="font-semibold text-gray-900 dark:text-white ml-2">{{ number_format($request->quotation->commission_service, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-col sm:flex-row lg:flex-col gap-2 lg:pl-4">
                                        <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md w-full sm:w-auto lg:w-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>{{ __('View Details') }}</span>
                                        </a>
                                        @if ($request->quotation)
                                            <form action="{{ route('client.quotations.accept', $request->quotation) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ __('Accept Quotation') }}</span>
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