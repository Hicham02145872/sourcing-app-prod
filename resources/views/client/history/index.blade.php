<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('client.dashboard')],
    ['label' => 'Activity History']
]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Activity History') }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('A chronological overview of all your activities and transactions') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ __('Filter') }}
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 border border-transparent rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ __('Export') }}
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($timeline->isEmpty())
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                    <div class="text-center py-20 px-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/50 dark:to-purple-900/50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No Activity Yet') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 max-w-md mx-auto mb-6">{{ __('Your activity timeline is empty. Start by creating requests and orders to see your history here.') }}</p>
                        <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-md transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Get Started') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Stats Overview --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Total Events') }}</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $timeline->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-100 to-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('This Week') }}</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $timeline->where('date', '>=', now()->startOfWeek())->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('This Month') }}</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $timeline->where('date', '>=', now()->startOfMonth())->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ __('Last Activity') }}</p>
                                <p class="text-sm font-bold text-gray-900 mt-1">{{ $timeline->first()['date']->diffForHumans() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ __('Activity Timeline') }}</h3>
                            <span class="ml-auto px-2.5 py-0.5 text-xs font-semibold text-violet-700 dark:text-violet-300 bg-violet-100 dark:bg-violet-500/20 rounded-full">
                                {{ $timeline->count() }} {{ __('events') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="relative">
                            {{-- Vertical line --}}
                            <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-violet-200 via-purple-200 to-violet-200"></div>
                            
                            <div class="space-y-6">
                                @foreach($timeline as $index => $event)
                                    <div class="relative flex items-start gap-4 group">
                                        {{-- Timeline dot --}}
                                        <div class="relative z-10 flex items-center justify-center w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 text-white rounded-full shadow-lg group-hover:shadow-xl transition-shadow flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>

                                        {{-- Event card --}}
                                        <div class="flex-1 p-4 bg-gradient-to-r from-gray-50 to-gray-50 dark:from-gray-800 dark:to-gray-800 hover:from-violet-50 hover:to-purple-50 dark:hover:from-gray-700 dark:hover:to-gray-700 border border-gray-200 dark:border-gray-700 hover:border-violet-300 rounded-xl transition-all duration-200 group-hover:shadow-md">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $event['title'] }}</h4>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $event['date']->format('F j, Y') }}</p>
                                                </div>
                                                <span class="px-2.5 py-1 text-xs font-semibold text-violet-700 dark:text-violet-300 bg-violet-100 dark:bg-violet-500/20 rounded-full">
                                                    {{ $event['date']->diffForHumans() }}
                                                </span>
                                            </div>
                                            
                                            @if(isset($event['description']))
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ $event['description'] }}</p>
                                            @endif
                                            
                                            @if(isset($event['metadata']))
                                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Details') }}:</span>
                                                    @foreach($event['metadata'] as $key => $value)
                                                        <span class="px-2 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">
                                                            {{ $key }}: {{ $value }}
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
                </div>
            @endif
        </div>
    </div>
</x-app-layout>