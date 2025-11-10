<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg border border-blue-400/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Request Details') }}
                            </h2>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ __('View and manage sourcing request information') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.sourcing-requests.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column - Main Content --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Status Update Card --}}
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl shadow-lg border border-blue-200 dark:border-blue-700/50 p-8 overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-blue-200 dark:bg-blue-900/30 rounded-full -mr-20 -mt-20 opacity-20"></div>
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('Update Request Status') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Change the current status of this sourcing request') }}</p>
                                </div>
                            </div>

                            {{-- Current Status Display --}}
                            <div class="mb-8 p-5 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Current Status') }}</p>
                                        <span class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold shadow-md
                                            @if($sourcingRequest->status === 'pending') bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-700
                                            @elseif($sourcingRequest->status === 'active') bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-700
                                            @elseif($sourcingRequest->status === 'completed') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700
                                            @elseif($sourcingRequest->status === 'cancelled') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-700
                                            @else bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 @endif">
                                            @if($sourcingRequest->status === 'pending')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @elseif($sourcingRequest->status === 'active')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                            @elseif($sourcingRequest->status === 'completed')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @elseif($sourcingRequest->status === 'cancelled')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            @endif
                                            {{ ucfirst($sourcingRequest->status) }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-slate-600 dark:text-slate-400 font-medium">{{ __('Last updated') }}</p>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white mt-1">{{ $sourcingRequest->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Actions --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                @foreach (\App\Models\SourcingRequest::STATUSES as $status)
                                    @if ($sourcingRequest->canTransitionTo($status, auth()->user()))
                                        <form action="{{ route('admin.sourcing-requests.update-status', $sourcingRequest) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <button type="submit" 
                                                    onclick="return confirm('{{ __('Are you sure you want to update the status to') }} {{ __($status) }}?')"
                                                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-slate-900 dark:text-white font-semibold rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center gap-2 group
                                                        @if($sourcingRequest->status === $status) ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/30 border-blue-500 @endif">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                                    @if($status === 'pending') bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                                                    @elseif($status === 'active') bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400
                                                    @elseif($status === 'completed') bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
                                                    @elseif($status === 'cancelled') bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                                    @else bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 @endif group-hover:scale-110 transition-transform">
                                                    @if($status === 'pending')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @elseif($status === 'active')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        </svg>
                                                    @elseif($status === 'completed')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @elseif($status === 'cancelled')
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <span class="text-xs font-bold text-center">{{ __(ucfirst($status)) }}</span>
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Product Details Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product Information') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Complete product details and specifications') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            {{-- Product Name --}}
                            <div class="flex items-start gap-5">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Product Name') }}</p>
                                    <p class="text-base font-semibold text-slate-900 dark:text-white bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-600/50 rounded-xl px-5 py-4 border border-slate-200 dark:border-slate-600 shadow-sm">
                                        {{ $sourcingRequest->product_name }}
                                    </p>
                                </div>
                            </div>

                            {{-- Product URL --}}
                            @if ($sourcingRequest->product_url)
                            <div class="flex items-start gap-5">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Product URL') }}</p>
                                    <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                       class="inline-flex items-center gap-3 px-5 py-4 bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-900/20 dark:to-blue-900/10 hover:from-blue-100 dark:hover:from-blue-900/30 text-blue-700 dark:text-blue-300 rounded-xl border border-blue-200 dark:border-blue-700/50 transition-all duration-200 w-full group shadow-sm hover:shadow-md">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        <span class="flex-1 truncate font-medium">{{ Str::limit($sourcingRequest->product_url, 50) }}</span>
                                        <svg class="w-5 h-5 flex-shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endif

                            {{-- Category & Shipping Method --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                {{-- Category --}}
                                <div class="flex items-start gap-5">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Category') }}</p>
                                        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700/50 shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0L10 9.414l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $sourcingRequest->category->name }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Shipping Method --}}
                                @if ($sourcingRequest->shipping_method)
                                <div class="flex items-start gap-5">
                                    <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Shipping Method') }}</p>
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold shadow-sm
                                            {{ $sourcingRequest->shipping_method === 'air' ? 'bg-gradient-to-r from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700/50' : 'bg-gradient-to-r from-cyan-100 to-cyan-50 dark:from-cyan-900/30 dark:to-cyan-800/20 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-700/50' }}">
                                            @if($sourcingRequest->shipping_method === 'air')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                                {{ __('Air Freight') }}
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15l5.12-5.12A3 3 0 0110.24 9H13a2 2 0 012 2v1a2 2 0 002 2h3.28a1 1 0 01.948 1.316l-1.4 4.2A2 2 0 0118.36 21H5.64a2 2 0 01-1.946-1.484l-1.4-4.2A1 1 0 013.28 14H6a2 2 0 002-2v-1a2 2 0 00-2-2H4.76a3 3 0 01-2.12-.879L3 15z"/>
                                                </svg>
                                                {{ __('Sea Freight') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Sourcing Location --}}
                            <div class="flex items-start gap-5">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Sourcing Location') }}</p>
                                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-bold bg-gradient-to-r from-purple-100 to-purple-50 dark:from-purple-900/30 dark:to-purple-800/20 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-700/50 capitalize shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $sourcingRequest->sourcing_location }}
                                    </span>
                                </div>
                            </div>

                            {{-- Additional Notes --}}
                            @if ($sourcingRequest->note)
                            <div class="flex items-start gap-5">
                                <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">{{ __('Additional Notes') }}</p>
                                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-600/50 rounded-xl p-5 border border-slate-200 dark:border-slate-600 shadow-sm">
                                        <p class="text-base text-slate-900 dark:text-white leading-relaxed">{{ $sourcingRequest->note }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Client Information Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900/30 dark:to-purple-800/20 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Client Information') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Client details and contact information') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-xl flex items-center justify-center shadow-md border-2 border-purple-200 dark:border-purple-700/50">
                                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ substr($sourcingRequest->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->user->name }}</h4>
                                    <p class="text-base text-slate-600 dark:text-slate-400 mt-1">{{ $sourcingRequest->user->email }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-2 font-medium">
                                        {{ __('Member since') }} <span class="font-bold">{{ $sourcingRequest->user->created_at->format('M Y') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900/30 dark:to-green-800/20 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Contact Information') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Client contact details and location') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-4">
                            {{-- Phone Number --}}
                            <div class="flex items-center justify-between py-4 px-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-600/50 rounded-xl border border-slate-200 dark:border-slate-600 shadow-sm hover:shadow-md transition-all">
                                <span class="text-base font-bold text-slate-700 dark:text-slate-300">{{ __('Phone Number') }}:</span>
                                <span class="text-base font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->phone_number ?? 'N/A' }}</span>
                            </div>

                            {{-- Address --}}
                            <div class="flex items-center justify-between py-4 px-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-600/50 rounded-xl border border-slate-200 dark:border-slate-600 shadow-sm hover:shadow-md transition-all">
                                <span class="text-base font-bold text-slate-700 dark:text-slate-300">{{ __('Address') }}:</span>
                                <span class="text-base font-bold text-slate-900 dark:text-white text-right">{{ $sourcingRequest->address ?? 'N/A' }}</span>
                            </div>

                            {{-- Location Map --}}
                            @if ($sourcingRequest->latitude && $sourcingRequest->longitude)
                            <div class="mt-6">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $sourcingRequest->latitude }},{{ $sourcingRequest->longitude }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center gap-3 w-full px-5 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-base font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ __('View on Google Maps') }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Destinations Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-50 dark:from-orange-900/30 dark:to-orange-800/20 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Shipping Destinations') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Product destinations and quantities') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="space-y-4">
                                @foreach ($sourcingRequest->destinations as $destination)
                                    <div class="flex items-center justify-between p-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-600/50 rounded-xl border border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 bg-white dark:bg-slate-700 rounded-xl flex items-center justify-center border-2 border-slate-200 dark:border-slate-600 shadow-sm">
                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-2xl"></span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white text-lg">{{ $destination->country->name }}</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $destination->service->name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-bold shadow-sm border border-blue-200 dark:border-blue-700/50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ $destination->quantity }} {{ __('units') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="lg:col-span-1 space-y-8">
                    {{-- Product Image Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden sticky top-6">
                        <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-50 dark:from-amber-900/30 dark:to-amber-800/20 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product Image') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Visual reference for sourcing') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($sourcingRequest->product_image)
                                <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                     alt="{{ $sourcingRequest->product_name }}" 
                                     class="w-full aspect-square object-cover rounded-xl border-2 border-slate-200 dark:border-slate-600 shadow-lg hover:shadow-xl transition-all"/>
                            @else
                                <div class="w-full aspect-square bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-xl border-2 border-dashed border-slate-300 dark:border-slate-500 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-base font-medium text-slate-500 dark:text-slate-400">{{ __('No image available') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Timeline Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-slate-50 to-white dark:from-slate-900 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Request Timeline') }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Request history and timeline') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 space-y-5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900/30 dark:to-green-800/20 rounded-full flex items-center justify-center flex-shrink-0 shadow-md mt-1">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-slate-900 dark:text-white">{{ __('Request Created') }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $sourcingRequest->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900/30 dark:to-blue-800/20 rounded-full flex items-center justify-center flex-shrink-0 shadow-md mt-1">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-slate-900 dark:text-white">{{ __('Last Updated') }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $sourcingRequest->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth scrolling and transitions */
        html {
            scroll-behavior: smooth;
        }

        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Responsive improvements */
        @media (max-width: 1024px) {
            .sticky {
                position: relative;
                top: 0;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }
    </style>
</x-app-layout>