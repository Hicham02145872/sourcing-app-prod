<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Quotations Management') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Review and manage quotations for your sourcing requests') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.sourcing-requests.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Requests') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-[#fffff] dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            @if ($sourcingRequests->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700">
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Pending Quotations') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Quotations for your sourcing requests will appear here once they are created by our team') }}</p>
                        <a href="{{ route('client.sourcing-requests.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __('View Sourcing Requests') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Control Panel --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 mb-6">
                    <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Active Quotations') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                            <span class="font-semibold text-[#EF7722]">{{ $sourcingRequests->count() }}</span> {{ Str::plural(__('quotation'), $sourcingRequests->count()) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <select class="px-4 py-2 text-sm border border-[#EBEBEB] dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 dark:text-white font-medium shadow-sm">
                                    <option>{{ __('All Quotations') }}</option>
                                    <option>{{ __('Ready to Accept') }}</option>
                                    <option>{{ __('Under Review') }}</option>
                                </select>
                                <button class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('Export') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Statistics Bar --}}
                    <div class="px-6 py-4 bg-[#EBEBEB] dark:bg-slate-900/50 border-b border-[#EBEBEB] dark:border-slate-700">
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                            {{-- Total --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->count() }}</p>
                                </div>
                            </div>

                            {{-- Ready --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Ready') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->whereNotNull('quotation_id')->count() }}</p>
                                </div>
                            </div>

                            {{-- Pending --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Pending') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequests->whereNull('quotation_id')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quotations List --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                    <div class="divide-y divide-[#EBEBEB] dark:divide-slate-700">
                        @foreach ($sourcingRequests as $request)
                            <div class="group p-6 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 transition-colors duration-150">
                                <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                    {{-- Request Info --}}
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        {{-- Product Image --}}
                                        <div class="flex-shrink-0">
                                            <div class="w-16 h-16 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border-2 border-[#EBEBEB] dark:border-slate-600 overflow-hidden shadow-sm">
                                                @if ($request->product_image)
                                                    <img src="{{ asset('storage/' . $request->product_image) }}"
                                                         alt="{{ $request->product_name }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10 dark:bg-[#EF7722]/20">
                                                        <span class="text-lg font-bold text-[#EF7722]">
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
                                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-[#EF7722] transition-colors truncate">
                                                        {{ $request->product_name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-1.5">
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EF7722]/10 text-[#EF7722]">
                                                            {{ $request->category->name }}
                                                        </span>
                                                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                                            {{ $request->created_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Status Badge --}}
                                                <div class="flex items-center gap-2">
                                                    @if ($request->quotation)
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-bold" style="background-color: #0BA6DF22; color: #0BA6DF;">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ __('Ready to Accept') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-bold" style="background-color: #FAA53322; color: #FAA533;">
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
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span class="font-semibold text-slate-600 dark:text-slate-400">{{ __('Quotation') }}:</span>
                                                    <span class="{{ $request->quotation ? 'text-[#0BA6DF]' : 'text-[#FAA533]' }} font-bold">
                                                        {{ $request->quotation ? __('Ready') : __('Pending') }}
                                                    </span>
                                                </div>

                                                {{-- Destinations --}}
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-semibold text-slate-600 dark:text-slate-400">{{ __('Destinations') }}:</span>
                                                    <div class="flex items-center gap-1">
                                                        @foreach($request->destinations->take(3) as $destination)
                                                            <span class="fi fi-{{ strtolower($destination->country->code) }} text-base border border-[#EBEBEB] dark:border-slate-600 rounded-sm" title="{{ $destination->country->name }}"></span>
                                                        @endforeach
                                                        @if($request->destinations->count() > 3)
                                                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 ml-1">
                                                                +{{ $request->destinations->count() - 3 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Total Amount --}}
                                                @if ($request->quotation)
                                                <div class="flex items-center gap-2 text-sm">
                                                    <svg class="w-4 h-4 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-semibold text-slate-600 dark:text-slate-400">{{ __('Total') }}:</span>
                                                    <span class="text-slate-900 dark:text-white font-bold">
                                                        {{ number_format($request->quotation->amount, 2) }} {{ $request->quotation->currency }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Quotation Details --}}
                                            @if ($request->quotation)
                                            <div class="mt-4 p-4 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg border border-[#0BA6DF]">
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="text-slate-600 dark:text-slate-400 font-semibold">{{ __('Unit Price') }}:</span>
                                                        <span class="font-bold text-slate-900 dark:text-white ml-2">{{ number_format($request->quotation->unit_price, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-slate-600 dark:text-slate-400 font-semibold">{{ __('Commission') }}:</span>
                                                        <span class="font-bold text-slate-900 dark:text-white ml-2">{{ number_format($request->quotation->commission_service, 2) }} {{ $request->quotation->currency }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:pl-4 lg:border-l lg:border-[#EBEBEB] lg:dark:border-slate-700">
                                        <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 border border-[#EBEBEB] dark:border-slate-600 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto lg:w-full whitespace-nowrap">
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
                                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-bold rounded-lg transition-all duration-200 shadow-sm hover:shadow whitespace-nowrap">
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
        /* Enterprise table styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #EF7722;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Line clamp utility */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>