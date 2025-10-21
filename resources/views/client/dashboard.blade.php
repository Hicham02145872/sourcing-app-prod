<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -m-6 p-6 mb-0">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Client Dashboard') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Manage and track your sourcing requests') }}</p>
                </div>
                <a href="{{ route('client.sourcing-requests.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('New Request') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            {{-- Welcome Banner --}}
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-500/5 via-purple-500/5 to-transparent"></div>
                <div class="relative px-6 py-8 sm:px-8 sm:py-10">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <div class="space-y-2">
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ __('Welcome Back!') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base">{{ __('Manage your sourcing requests efficiently and track their progress') }}</p>
                        </div>
                        <div class="hidden lg:block flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/50 dark:to-purple-900/50 rounded-2xl flex items-center justify-center shadow-sm">
                                <svg class="w-10 h-10 text-violet-600 dark:text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-amber-200 dark:hover:border-amber-500 transition-all duration-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">{{ __('Action Required') }}</p>
                            <p class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">{{ $sourcingRequests->where('status', 'quoted')->count() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Requests awaiting your response') }}</p>
                        </div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/50 dark:to-amber-900/50 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-blue-200 dark:hover:border-blue-500 transition-all duration-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">{{ __('In Progress') }}</p>
                            <p class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">{{ $sourcingRequests->whereIn('status', ['pending', 'in_review', 'accepted'])->count() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Currently being processed') }}</p>
                        </div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-900/50 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md hover:border-green-200 dark:hover:border-green-500 transition-all duration-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1 uppercase tracking-wide">{{ __('Completed') }}</p>
                            <p class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">{{ $sourcingRequests->where('status', 'completed')->count() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Successfully delivered') }}</p>
                        </div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/50 dark:to-green-900/50 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sourcing Requests Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ __('Your Sourcing Requests') }}
                            </h3>
                            <p class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-400">{{ __('View and manage all your requests') }}</p>
                        </div>
                        <div class="w-full sm:w-auto">
                            <label for="statusFilter" class="sr-only">{{ __('Filter by status') }}</label>
                            <select id="statusFilter" class="w-full sm:w-auto px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white dark:bg-gray-700 dark:text-white font-medium shadow-sm transition-all">
                                <option value="all">{{ __('All Status') }}</option>
                                <option value="action_required">{{ __('🔔 Action Required') }}</option>
                                <option value="processing">{{ __('🔄 In Progress') }}</option>
                                <option value="completed">{{ __('✅ Completed') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    @if ($sourcingRequests->isEmpty())
                        <div class="text-center py-16 sm:py-20">
                            <div class="relative inline-flex items-center justify-center mb-6">
                                <div class="absolute inset-0 bg-violet-100 dark:bg-violet-900/50 rounded-full animate-ping opacity-20"></div>
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/50 dark:to-purple-900/50 rounded-full flex items-center justify-center shadow-sm">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No requests yet') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6 sm:mb-8 text-sm sm:text-base px-4">{{ __('Start by creating your first sourcing request') }}</p>
                            <a href="{{ route('client.sourcing-requests.create') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Create First Request') }}
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden hover:shadow-lg hover:border-violet-300 dark:hover:border-violet-600 transition-all duration-300 sourcing-request-card" data-status="{{ $request->status }}">
                                    {{-- Image Section --}}
                                    <div class="relative h-44 sm:h-48 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 overflow-hidden">
                                        @if ($request->product_image)
                                            <img src="{{ asset('storage/' . $request->product_image) }}" 
                                                 alt="{{ $request->product_name }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/50 dark:to-purple-900/50 rounded-2xl flex items-center justify-center shadow-md">
                                                    <span class="text-2xl sm:text-3xl font-bold text-violet-600 dark:text-violet-400">
                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full backdrop-blur-sm shadow-sm border
                                                @if($request->status === 'pending') bg-amber-100/95 text-amber-800 border-amber-300 dark:bg-amber-900/50 dark:text-amber-300 dark:border-amber-700
                                                @elseif($request->status === 'quoted') bg-orange-100/95 text-orange-800 border-orange-300 dark:bg-orange-900/50 dark:text-orange-300 dark:border-orange-700
                                                @elseif($request->status === 'in_review') bg-blue-100/95 text-blue-800 border-blue-300 dark:bg-blue-900/50 dark:text-blue-300 dark:border-blue-700
                                                @elseif($request->status === 'accepted') bg-purple-100/95 text-purple-800 border-purple-300 dark:bg-purple-900/50 dark:text-purple-300 dark:border-purple-700
                                                @elseif($request->status === 'completed') bg-green-100/95 text-green-800 border-green-300 dark:bg-green-900/50 dark:text-green-300 dark:border-green-700
                                                @else bg-gray-100/95 text-gray-800 border-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 @endif">
                                                {{ __(ucfirst(str_replace('_', ' ', $request->status))) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Content Section --}}
                                    <div class="p-4 sm:p-5">
                                        <div class="mb-4">
                                            <h4 class="font-bold text-base sm:text-lg text-gray-900 dark:text-white mb-1 line-clamp-1 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">
                                                {{ $request->product_name }}
                                            </h4>
                                            <p class="text-xs sm:text-sm text-violet-600 dark:text-violet-400 font-semibold">{{ $request->category->name }}</p>
                                        </div>

                                        <div class="space-y-3 mb-4">
                                            {{-- Shipping Method --}}
                                            <div class="flex items-center gap-2.5 text-sm">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/50 dark:to-blue-900/50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-gray-500 dark:text-gray-400 text-xs font-medium block">{{ __('Shipping') }}</span>
                                                    <p class="font-semibold text-gray-900 dark:text-white text-sm truncate capitalize">{{ $request->shipping_method ? __(ucfirst($request->shipping_method)) : __('N/A') }}</p>
                                                </div>
                                            </div>

                                            {{-- Destinations --}}
                                            <div class="flex items-start gap-2.5 text-sm">
                                                <div class="w-8 h-8 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/50 dark:to-green-900/50 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-gray-500 dark:text-gray-400 text-xs font-medium block mb-1.5">{{ __('Destinations') }}</span>
                                                    <div class="space-y-1.5">
                                                        @foreach ($request->destinations->take(2) as $destination)
                                                            <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-gray-700 rounded-lg px-2.5 py-1.5 border border-gray-200 dark:border-gray-600">
                                                                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                                                                    <span class="fi fi-{{ strtolower($destination->country->code) }} text-base flex-shrink-0"></span>
                                                                    <span class="text-gray-800 dark:text-gray-200 font-medium truncate">{{ $destination->country->name }}</span>
                                                                </div>
                                                                <span class="ml-2 px-2 py-0.5 font-semibold text-violet-700 dark:text-violet-300 bg-violet-100 dark:bg-violet-500/20 rounded-full text-xs flex-shrink-0">{{ $destination->quantity }}</span>
                                                            </div>
                                                        @endforeach
                                                        @if ($request->destinations->count() > 2)
                                                            <p class="text-xs font-semibold text-violet-600 dark:text-violet-400 pl-1">+{{ $request->destinations->count() - 2 }} {{ __('more') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                                            <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                                                <span>{{ __('View Details') }}</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                            <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-violet-50 dark:hover:bg-gray-700 rounded-xl transition-all duration-200 border border-transparent hover:border-violet-200 dark:hover:border-violet-600" title="{{ __('Edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-gray-700 rounded-xl transition-all duration-200 border border-transparent hover:border-red-200 dark:hover:border-red-600" title="{{ __('Delete') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Empty State for Filtered Results --}}
                        <div id="emptyFilterState" class="hidden text-center py-16">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ __('No requests found') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm">{{ __('Try changing the filter to see more results') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusFilter = document.getElementById('statusFilter');
            const sourcingRequestCards = document.querySelectorAll('.sourcing-request-card');
            const emptyFilterState = document.getElementById('emptyFilterState');

            if (statusFilter) {
                statusFilter.addEventListener('change', function () {
                    const selectedStatus = this.value;
                    const processingStatuses = ['pending', 'in_review', 'accepted'];
                    let visibleCount = 0;

                    sourcingRequestCards.forEach(function (card) {
                        const cardStatus = card.dataset.status;
                        let show = false;

                        if (selectedStatus === 'all') {
                            show = true;
                        } else if (selectedStatus === 'action_required') {
                            show = (cardStatus === 'quoted');
                        } else if (selectedStatus === 'processing') {
                            show = processingStatuses.includes(cardStatus);
                        } else {
                            show = (cardStatus === selectedStatus);
                        }

                        if (show) {
                            card.style.display = 'block';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Show/hide empty state
                    if (emptyFilterState) {
                        if (visibleCount === 0) {
                            emptyFilterState.classList.remove('hidden');
                        } else {
                            emptyFilterState.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script> --}}
    @endpush
</x-app-layout>