<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('client.dashboard')],
    ['label' => __('Activity History')]
]">
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 dark:bg-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Activity History') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('A chronological overview of all your activities and transactions') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            
            @if($timeline->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Activity Yet') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Your activity timeline is empty. Start by creating requests and orders to see your history here.') }}</p>
                        <a href="{{ route('client.dashboard') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Get Started') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Control Panel --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 mb-6">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Activity Timeline') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $timeline->count() }}</span> {{ Str::plural(__('event'), $timeline->count()) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <select class="px-4 py-2 text-sm border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-slate-700 dark:text-white font-medium shadow-sm">
                                    <option>{{ __('All Events') }}</option>
                                    <option>{{ __('Orders') }}</option>
                                    <option>{{ __('Quotations') }}</option>
                                    <option>{{ __('Requests') }}</option>
                                </select>
                                <button class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('Export') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Statistics Bar --}}
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total Events') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $timeline->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('This Week') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $timeline->where('date', '>=', now()->startOfWeek())->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('This Month') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $timeline->where('date', '>=', now()->startOfMonth())->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Last Activity') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $timeline->first()['date']->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timeline Content --}}
                <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6">
                        <div class="relative">
                            {{-- Vertical line --}}
                            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-700"></div>
                            
                            <div class="space-y-6">
                                @foreach($timeline as $index => $event)
                                    <div class="relative flex items-start gap-4 group">
                                        {{-- Timeline dot --}}
                                        <div class="relative z-10 flex items-center justify-center w-12 h-12 bg-blue-600 dark:bg-blue-700 rounded-lg shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>

                                        {{-- Event card --}}
                                        <div class="flex-1 p-5 bg-slate-50 dark:bg-slate-900/50 hover:bg-blue-50 dark:hover:bg-slate-700/50 border border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 rounded-lg transition-all duration-200 group-hover:shadow-md">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">{{ $event['title'] }}</h4>
                                                    <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span>{{ $event['date']->format('F j, Y') }}</span>
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $event['date']->diffForHumans() }}
                                                </span>
                                            </div>
                                            
                                            @if(isset($event['description']))
                                                <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $event['description'] }}</p>
                                            @endif
                                            
                                            @if(isset($event['metadata']))
                                                <div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ __('Details') }}:</span>
                                                    @foreach($event['metadata'] as $key => $value)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                                                            <span class="text-slate-500 dark:text-slate-400">{{ $key }}:</span>
                                                            <span>{{ $value }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if($timeline->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ __('Showing') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $timeline->firstItem() }}</span>
                                    {{ __('to') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $timeline->lastItem() }}</span>
                                    {{ __('of') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $timeline->total() }}</span>
                                    {{ Str::plural(__('results'), $timeline->total()) }}
                                </div>
                                <div class="flex gap-1">
                                    {{ $timeline->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
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
            background: #3b82f6;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>
