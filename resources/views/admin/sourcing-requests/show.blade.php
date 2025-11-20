<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Request Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('View and manage sourcing request information') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.sourcing-requests.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-[#fffff] dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column - Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Status Update Card --}}
                    <div class="bg-gradient-to-br from-[#EF7722]/5 to-[#FAA533]/5 dark:from-[#EF7722]/10 dark:to-[#FAA533]/10 rounded-lg shadow-sm border border-[#EF7722]/20 dark:border-[#FAA533]/20 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Update Request Status') }}</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Change the current status of this sourcing request') }}</p>
                            </div>
                        </div>

                        {{-- Current Status Display --}}
                        <div class="mb-6 p-4 bg-white dark:bg-slate-800 rounded-lg border border-[#EBEBEB] dark:border-slate-600 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Current Status') }}</p>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['color' => '[#FAA533]', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'in_review' => ['color' => '[#0BA6DF]', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                            'quoted' => ['color' => '[#EF7722]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'accepted' => ['color' => '[#0BA6DF]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'completed' => ['color' => '[#EF7722]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'rejected' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                            'cancelled' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                        ];
                                        $statusData = $statusConfig[$sourcingRequest->status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-bold bg-{{ $statusData['color'] }}/10 dark:bg-{{ $statusData['color'] }}/20 text-{{ $statusData['color'] }} dark:text-{{ $statusData['color'] }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                        </svg>
                                        {{ ucfirst($sourcingRequest->status) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Last updated') }}</p>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $sourcingRequest->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Status Update Buttons --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach (\App\Models\SourcingRequest::STATUSES as $status)
                                @if ($sourcingRequest->canTransitionTo($status, auth()->user()))
                                    <form action="{{ route('admin.sourcing-requests.update-status', $sourcingRequest) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $status }}">
                                        <button type="submit" 
                                                onclick="return confirm('{{ __('Are you sure you want to update the status to') }} {{ __($status) }}?')"
                                                class="w-full px-3 py-2.5 bg-white dark:bg-slate-800 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 text-slate-900 dark:text-white font-semibold rounded-lg border border-[#EBEBEB] dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col items-center justify-center gap-1.5 group text-xs">
                                            @php
                                                $btnConfig = [
                                                    'pending' => ['color' => '[#FAA533]', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    'in_review' => ['color' => '[#0BA6DF]', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                                    'quoted' => ['color' => '[#EF7722]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    'accepted' => ['color' => '[#0BA6DF]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    'completed' => ['color' => '[#EF7722]', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                    'rejected' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                                    'cancelled' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                                ];
                                                $btnData = $btnConfig[$status] ?? $btnConfig['pending'];
                                            @endphp
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-{{ $btnData['color'] }}/10 dark:bg-{{ $btnData['color'] }}/20 text-{{ $btnData['color'] }} dark:text-{{ $btnData['color'] }} group-hover:scale-110 transition-transform">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $btnData['icon'] }}"/>
                                                </svg>
                                            </div>
                                            <span class="font-bold">{{ __(ucfirst($status)) }}</span>
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Request Details Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Request Information') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Complete request details and specifications') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Product Name --}}
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Product Name') }}</p>
                                    <p class="text-base font-bold text-slate-900 dark:text-white bg-[#EBEBEB] dark:bg-slate-700/50 rounded-lg px-4 py-3 border border-[#EBEBEB] dark:border-slate-600">
                                        {{ $sourcingRequest->product_name }}
                                    </p>
                                </div>
                            </div>

                            {{-- Product URL --}}
                            @if ($sourcingRequest->product_url)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Product URL') }}</p>
                                    <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                       class="inline-flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-[#0BA6DF]/10 to-[#0BA6DF]/5 dark:from-[#0BA6DF]/20 dark:to-[#0BA6DF]/10 hover:from-[#0BA6DF]/20 dark:hover:from-[#0BA6DF]/30 text-[#0BA6DF] dark:text-[#0BA6DF] rounded-lg border border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30 transition-all duration-200 w-full group shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        <span class="flex-1 truncate font-medium text-sm">{{ Str::limit($sourcingRequest->product_url, 50) }}</span>
                                        <svg class="w-4 h-4 flex-shrink-0 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endif

                            {{-- Product Information --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Category --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Category') }}</p>
                                        <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] border border-[#EF7722]/20 dark:border-[#EF7722]/30">
                                            {{ $sourcingRequest->category?->name }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Sourcing Location --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Sourcing Location') }}</p>
                                        <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold bg-[#FAA533]/10 dark:bg-[#FAA533]/20 text-[#FAA533] dark:text-[#FAA533] border border-[#FAA533]/20 dark:border-[#FAA533]/30 capitalize">
                                            {{ $sourcingRequest->sourcing_location }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Shipping Method --}}
                            @if ($sourcingRequest->shipping_method)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Shipping Method') }}</p>
                                    <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold 
                                        {{ $sourcingRequest->shipping_method === 'air' ? 'bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 text-[#0BA6DF] dark:text-[#0BA6DF] border border-[#0BA6DF]/20 dark:border-[#0BA6DF]/30' : 'bg-[#FAA533]/10 dark:bg-[#FAA533]/20 text-[#FAA533] dark:text-[#FAA533] border border-[#FAA533]/20 dark:border-[#FAA533]/30' }}">
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

                            {{-- Additional Notes --}}
                            @if ($sourcingRequest->note)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Additional Notes') }}</p>
                                    <div class="bg-[#EBEBEB] dark:bg-slate-700/50 rounded-lg p-4 border border-[#EBEBEB] dark:border-slate-600">
                                        <p class="text-sm text-slate-900 dark:text-white leading-relaxed">{{ $sourcingRequest->note }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Client Information Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#0BA6DF] dark:text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Client Information') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Client details and contact information') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-[#0BA6DF]/10 to-[#0BA6DF]/5 dark:from-[#0BA6DF]/20 dark:to-[#0BA6DF]/10 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-[#0BA6DF] dark:text-[#0BA6DF]">
                                        {{ substr($sourcingRequest->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-slate-900 dark:text-white">{{ $sourcingRequest->user->name }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $sourcingRequest->user->email }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-2">
                                        {{ __('Member since') }} {{ $sourcingRequest->user->created_at->format('M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#FAA533] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Contact Information') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Client contact details and location') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-3">
                            {{-- Phone Number --}}
                            <div class="flex items-center justify-between py-3 px-4 bg-[#EBEBEB] dark:bg-slate-700/50 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Phone Number') }}:</span>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $sourcingRequest->phone_number ?? 'N/A' }}</span>
                            </div>

                            {{-- Address --}}
                            <div class="flex items-center justify-between py-3 px-4 bg-[#EBEBEB] dark:bg-slate-700/50 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Address') }}:</span>
                                <span class="text-sm font-bold text-slate-900 dark:text-white text-right">{{ $sourcingRequest->address ?? 'N/A' }}</span>
                            </div>

                            {{-- Location Map --}}
                            @if ($sourcingRequest->latitude && $sourcingRequest->longitude)
                            <div class="mt-4">
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $sourcingRequest->latitude }},{{ $sourcingRequest->longitude }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-[#EF7722] hover:bg-[#FAA533] dark:bg-[#EF7722] dark:hover:bg-[#FAA533] text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Shipping Destinations') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Product destinations and quantities') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach ($sourcingRequest->destinations as $destination)
                                    <div class="flex items-center justify-between p-4 bg-[#EBEBEB] dark:bg-slate-700/50 rounded-lg border border-[#EBEBEB] dark:border-slate-600 hover:border-[#EF7722] dark:hover:border-[#FAA533] transition-colors duration-200">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white dark:bg-slate-600 rounded-lg flex items-center justify-center border border-[#EBEBEB] dark:border-slate-500">
                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-xl"></span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-white">{{ $destination->country->name }}</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $destination->service->name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-3 py-1.5 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] rounded-lg text-sm font-bold">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="lg:col-span-1 space-y-6">
                    {{-- Product Image Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FAA533]/10 dark:bg-[#FAA533]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#FAA533] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Product Image') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Visual reference for sourcing') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($sourcingRequest->product_image)
                                <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                     alt="{{ $sourcingRequest->product_name }}" 
                                     class="w-full aspect-square object-cover rounded-lg border-2 border-[#EBEBEB] dark:border-slate-600 shadow-sm"/>
                            @else
                                <div class="w-full aspect-square bg-gradient-to-br from-[#EBEBEB] to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-lg border-2 border-dashed border-[#EBEBEB] dark:border-slate-500 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto text-slate-400 dark:text-slate-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('No image available') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Timeline Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-[#EBEBEB] dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-[#EBEBEB] dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#0BA6DF] dark:text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Request Timeline') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Request history and timeline') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#EF7722]/10 dark:bg-[#EF7722]/20 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Request Created') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $sourcingRequest->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#0BA6DF]/10 dark:bg-[#0BA6DF]/20 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#0BA6DF] dark:text-[#0BA6DF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Last Updated') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $sourcingRequest->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enterprise styling */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Responsive improvements */
        @media (max-width: 1024px) {
            .sticky {
                position: relative;
                top: 0;
            }
        }
    </style>
</x-app-layout>