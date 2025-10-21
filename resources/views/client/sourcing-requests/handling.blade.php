<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Sourcing Requests') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('Manage and track all your sourcing requests') }}</p>
            </div>
            <a href="{{ route('client.sourcing-requests.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('New Request') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-gray-100/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
                @if ($sourcingRequests->isEmpty())
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-24 h-24 bg-gradient-to-br from-violet-100 to-purple-100 rounded-full flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-12 h-12 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('No Active Sourcing Requests') }}</h3>
                        <p class="text-base text-gray-600 mb-8">{{ __('Create your first sourcing request to get started') }}</p>
                        <a href="{{ route('client.sourcing-requests.create') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Create First Request') }}
                        </a>
                    </div>
                @else
                    <!-- Header Section -->
                    <div class="px-6 py-5 bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center shadow-sm">
                                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ __('Active Requests') }}</h3>
                                    <p class="text-sm text-gray-600">{{ $sourcingRequests->count() }} {{ Str::plural('request', $sourcingRequests->count()) }} {{ __('in total') }}</p>
                                </div>
                            </div>
                            
                            <!-- Filter/Sort Options -->
                            <div class="flex items-center gap-3">
                                <select class="px-4 py-2.5 text-sm border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 bg-white font-medium shadow-sm">
                                    <option>{{ __('All Statuses') }}</option>
                                    <option>{{ __('Pending') }}</option>
                                    <option>{{ __('Active') }}</option>
                                    <option>{{ __('Completed') }}</option>
                                </select>
                                <button class="p-2.5 border-2 border-gray-300 rounded-xl hover:border-violet-400 hover:bg-violet-50 transition-all shadow-sm">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($sourcingRequests as $request)
                                <div class="group bg-white rounded-2xl border-2 border-gray-200 hover:border-violet-300 hover:shadow-xl transition-all duration-300 overflow-hidden">
                                    <!-- Image Section -->
                                    <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-50 overflow-hidden">
                                        @if ($request->product_image)
                                            <img src="{{ asset('storage/' . $request->product_image) }}"
                                                 alt="{{ $request->product_name }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-20 h-20 bg-gradient-to-br from-violet-100 to-purple-100 rounded-2xl flex items-center justify-center shadow-md">
                                                    <span class="text-3xl font-extrabold text-violet-600">
                                                        {{ mb_substr($request->product_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-full backdrop-blur-md shadow-md border
                                                @if($request->status === 'pending') bg-amber-100/95 text-amber-800 border-amber-300
                                                @elseif($request->status === 'active') bg-green-100/95 text-green-800 border-green-300
                                                @else bg-gray-100/95 text-gray-800 border-gray-300 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <!-- Header -->
                                        <div class="mb-4">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-violet-600 transition-colors">
                                                {{ $request->product_name }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center px-2.5 py-1 bg-violet-50 rounded-lg border border-violet-200">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <span class="text-xs font-bold text-violet-700">{{ $request->category->name }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        @if($request->note)
                                            <div class="mb-4 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                                <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                                                    {{ $request->note }}
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Metadata -->
                                        <div class="space-y-2.5 mb-5">
                                            @if($request->product_url)
                                                <div class="flex items-center gap-2 p-2.5 bg-blue-50 rounded-lg border border-blue-200">
                                                    <div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101"/>
                                                        </svg>
                                                    </div>
                                                    <a href="{{ $request->product_url }}" target="_blank" class="text-sm font-semibold text-blue-700 hover:text-blue-800 truncate flex-1">
                                                        {{ __('View Source Link') }}
                                                    </a>
                                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            @if($request->shipping_method)
                                                <div class="flex items-center gap-2 p-2.5 bg-green-50 rounded-lg border border-green-200">
                                                    <div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-bold text-green-700 capitalize">{{ $request->shipping_method }}</span>
                                                </div>
                                            @endif
                                            @if($request->destinations->count() > 0)
                                                <div class="p-2.5 bg-purple-50 rounded-lg border border-purple-200">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <div class="w-7 h-7 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        </div>
                                                        <span class="text-xs font-bold text-purple-700 uppercase">{{ __('Destinations') }}</span>
                                                    </div>
                                                    <div class="space-y-1.5 ml-9">
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
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('client.sourcing-requests.show', $request) }}" 
                                           class="w-full bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-bold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                                            <span>{{ __('View Details') }}</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
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