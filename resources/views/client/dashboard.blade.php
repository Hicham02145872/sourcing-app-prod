<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    {{ __('Client Dashboard') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Manage and track your sourcing requests') }}</p>
            </div>
            <a href="{{ route('client.sourcing-requests.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('New Request') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Welcome Banner --}}
            <div class="relative bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 via-purple-500/5 to-transparent"></div>
                <div class="relative px-8 py-10">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <h3 class="text-2xl font-bold text-gray-900">{{ __('Welcome Back!') }}</h3>
                            <p class="text-gray-600">{{ __('Manage your sourcing requests efficiently and track their progress') }}</p>
                        </div>
                        <div class="hidden lg:block">
                            <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Total Requests') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $sourcingRequests->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Active') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $sourcingRequests->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 mb-1">{{ __('Pending') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $sourcingRequests->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sourcing Requests Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ __('Your Sourcing Requests') }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">{{ __('View and manage all your requests') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <select id="statusFilter" class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="all">{{ __('All Status') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="active">{{ __('Active') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    @if ($sourcingRequests->isEmpty())
                        <div class="text-center py-16">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('No requests yet') }}</h3>
                            <p class="text-gray-600 mb-6">{{ __('Start by creating your first sourcing request') }}</p>
                            <a href="{{ route('client.sourcing-requests.create') }}" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Create First Request') }}
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-200 sourcing-request-card" data-status="{{ $request->status }}">
                                    {{-- Image Section --}}
                                    <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
                                        @if ($request->product_image)
                                            <img src="{{ asset('storage/' . $request->product_image) }}" 
                                                 alt="{{ $request->product_name }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl flex items-center justify-center">
                                                    <span class="text-3xl font-bold text-indigo-600">
                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full backdrop-blur-sm
                                                @if($request->status === 'pending') bg-amber-100/90 text-amber-800
                                                @elseif($request->status === 'active') bg-green-100/90 text-green-800
                                                @else bg-gray-100/90 text-gray-800 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Content Section --}}
                                    <div class="p-5">
                                        <div class="mb-4">
                                            <h4 class="font-semibold text-lg text-gray-900 mb-1 line-clamp-1">{{ $request->product_name }}</h4>
                                            <p class="text-sm text-indigo-600 font-medium">{{ $request->category->name }}</p>
                                        </div>

                                        <div class="space-y-3 mb-5">
                                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-gray-500 text-xs">{{ __('Shipping') }}</span>
                                                    <p class="font-medium text-gray-900 truncate">{{ $request->shipping_method ?? __('N/A') }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-start gap-2 text-sm">
                                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-gray-500 text-xs">{{ __('Destinations') }}</span>
                                                    <div class="mt-1 space-y-1.5">
                                                        @foreach ($request->destinations->take(2) as $destination)
                                                            <div class="flex items-center justify-between text-xs bg-gray-50 rounded-lg px-2.5 py-1.5">
                                                                <span class="text-gray-700 truncate">{{ $destination->country->name }}</span>
                                                                <span class="ml-2 font-semibold text-indigo-600 flex-shrink-0">× {{ $destination->quantity }}</span>
                                                            </div>
                                                        @endforeach
                                                        @if ($request->destinations->count() > 2)
                                                            <p class="text-xs text-gray-500 pl-2.5">+{{ $request->destinations->count() - 2 }} {{ __('more') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                            <a href="{{ route('client.sourcing-requests.show', $request) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                {{ __('View Details') }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                            <button class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="//code.tidio.co/pz38nwvmrzgi6zgfh8p6kwlp7nordcgs.js" async></script>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusFilter = document.getElementById('statusFilter');
            const sourcingRequestCards = document.querySelectorAll('.sourcing-request-card');

            if (statusFilter) {
                statusFilter.addEventListener('change', function () {
                    const selectedStatus = this.value;

                    sourcingRequestCards.forEach(function (card) {
                        const cardStatus = card.dataset.status;

                        if (selectedStatus === 'all' || cardStatus === selectedStatus) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>