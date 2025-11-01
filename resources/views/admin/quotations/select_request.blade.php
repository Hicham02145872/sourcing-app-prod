<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-violet-600 to-violet-500 text-white -m-6 p-8 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-4xl text-white tracking-tight">
                        {{ __('Sourcing Requests') }}
                    </h2>
                    <p class="text-violet-100 mt-2 text-lg">{{ __('Manage and create quotations for pending sourcing requests') }}</p>
                </div>
                <div class="hidden lg:flex flex-col items-end">
                    <div class="flex items-center space-x-2 text-violet-100 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ now()->format('F d, Y') }}</span>
                    </div>
                    <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-violet-400 bg-opacity-30 text-violet-50">{{ __('Live Portal') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-white to-violet-50 dark:from-gray-900 dark:via-gray-800 dark:to-violet-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-violet-50 to-violet-25 dark:from-violet-900 dark:to-violet-800 rounded-xl p-6 shadow-sm border-l-4 border-violet-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('In Review') }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequestsInReview }}</p>
                        </div>
                        <svg class="w-12 h-12 text-violet-200 dark:text-violet-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-blue-25 dark:from-blue-900 dark:to-blue-800 rounded-xl p-6 shadow-sm border-l-4 border-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('Pending Quotations') }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $pendingQuotations }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-200 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002 2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-25 dark:from-emerald-900 dark:to-emerald-800 rounded-xl p-6 shadow-sm border-l-4 border-emerald-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ __('This Month') }}</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $sourcingRequestsThisMonth }}</p>
                        </div>
                        <svg class="w-12 h-12 text-emerald-200 dark:text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-violet-100 dark:border-gray-700 overflow-hidden">
                
                <!-- Header -->
                <div class="border-b border-violet-100 dark:border-gray-700 px-8 py-6 flex items-center justify-between bg-gradient-to-r from-gray-50 to-violet-50 dark:from-gray-700 dark:to-gray-600">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Sourcing Requests') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Select a request to proceed with quotation creation') }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <button class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 016-1h4a1 1 0 016 1v2H3V4z"/>
                            </svg>
                            {{ __('Filter') }}
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Product Name') }}</th>
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Category') }}</th>
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Requested By') }}</th>
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-8 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($sourcingRequests as $request)
                                <tr class="hover:bg-violet-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-8 py-5 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $request->product_name }}
                                    </td>
                                    <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-violet-100 dark:bg-violet-900 text-violet-800 dark:text-violet-300">
                                            {{ $request->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gradient-to-br from-violet-400 to-violet-600 rounded-full mr-3 flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($request->user->name, 0, 2) }}
                                            </div>
                                            {{ $request->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-sm">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300">
                                            {{ __('In Review') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('admin.quotations.create', $request) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-violet-600 to-violet-500 hover:from-violet-700 hover:to-violet-600 text-white font-medium text-sm rounded-lg transition transform hover:scale-105">
                                            {{ __('Create Quote') }}
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-lg font-medium">{{ __('No sourcing requests found') }}</p>
                                        <p class="text-sm mt-1">{{ __('All requests have been processed or no requests are pending.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="border-t border-gray-200 dark:border-gray-700 px-8 py-4 flex items-center justify-between bg-gray-50 dark:bg-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Showing') }} <span class="font-semibold">{{ count($sourcingRequests) }}</span> {{ __('request(s)') }}
                    </p>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600 transition">
                            ← {{ __('Previous') }}
                        </button>
                        <button class="px-4 py-2 border border-violet-300 dark:border-violet-700 bg-gradient-to-r from-violet-50 to-violet-25 dark:from-violet-900 dark:to-violet-800 rounded-lg text-sm font-medium text-violet-700 dark:text-violet-300 hover:shadow-md transition">
                            {{ __('Next') }} →
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>