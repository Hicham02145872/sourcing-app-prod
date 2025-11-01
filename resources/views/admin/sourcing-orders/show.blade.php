<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl shadow-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Sourcing Order Details') }}
                    </h2>
                    <p class="text-base text-gray-600 dark:text-gray-400 mt-1">{{ __('Comprehensive overview of sourcing order information') }}</p>
                </div>
            </div>
            <a href="{{ route('admin.sourcing-orders.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 text-base font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column - Product Information --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Product Details Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-7 py-5 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-blue-50 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Product Information') }}</h3>
                            </div>
                        </div>

                        <div class="p-7 space-y-6">
                            {{-- Product Name --}}
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Product Name') }}</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A' }} {{-- Added null coalescing --}}
                                    </p>
                                </div>
                            </div>

                            {{-- Product URL --}}
                            @if ($sourcingOrder->quotation->sourcingRequest->product_url)
                                <div class="flex items-start gap-4">
                                    <div class="w-11 h-11 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Product URL') }}</p>
                                        <a href="{{ $sourcingOrder->quotation->sourcingRequest->product_url }}" target="_blank" class="text-base text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 break-all inline-flex items-center gap-1.5 font-medium">
                                            {{ Str::limit($sourcingOrder->quotation->sourcingRequest->product_url, 50) }}
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Category --}}
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Category') }}</p>
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300">
                                        {{ $sourcingOrder->quotation->sourcingRequest->category->name ?? 'N/A' }} {{-- Added null coalescing --}}
                                    </span>
                                </div>
                            </div>

                            {{-- Shipping Method --}}
                            @if ($sourcingOrder->quotation->sourcingRequest->shipping_method)
                                <div class="flex items-start gap-4">
                                    <div class="w-11 h-11 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Shipping Method') }}</p>
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold 
                                            {{ $sourcingOrder->quotation->sourcingRequest->shipping_method === 'air' ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'bg-cyan-50 dark:bg-cyan-900/50 text-cyan-700 dark:text-cyan-300' }}">
                                            @if($sourcingOrder->quotation->sourcingRequest->shipping_method === 'air')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            @endif
                                            {{ ucfirst($sourcingOrder->quotation->sourcingRequest->shipping_method) }} {{ __('Freight') }}
                                        </span>
                                    </div>
                                </div>
                            @endif

                            {{-- Additional Notes --}}
                            @if ($sourcingOrder->quotation->sourcingRequest->note)
                                <div class="flex items-start gap-4">
                                    <div class="w-11 h-11 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ __('Additional Notes') }}</p>
                                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 rounded-xl p-4 border border-gray-200 dark:border-gray-600 leading-relaxed">
                                            {{ $sourcingOrder->quotation->sourcingRequest->note }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Client Information Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-7 py-5 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-purple-50 dark:bg-purple-900/50 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Client Information') }}</h3>
                            </div>
                        </div>

                        <div class="p-7">
                            @if ($sourcingOrder->user) {{-- Ensure user exists --}}
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/50 dark:to-indigo-900/50 rounded-2xl flex items-center justify-center border border-purple-200 dark:border-purple-800">
                                        <span class="text-3xl font-bold text-purple-600 dark:text-purple-300">
                                            {{ substr($sourcingOrder->user->name, 0, 1) ?? '?' }} {{-- Added null coalescing for name --}}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $sourcingOrder->user->name ?? 'Unknown Client' }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $sourcingOrder->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400 italic">
                                    {{ __('No client information available.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Destinations Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-7 py-5 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-green-50 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Destinations') }}</h3>
                            </div>
                        </div>

                        <div class="p-7">
                            @if ($sourcingOrder->quotation->sourcingRequest->destinations->isNotEmpty()) {{-- Check if destinations exist --}}
                                <div class="space-y-4">
                                    @foreach ($sourcingOrder->quotation->sourcingRequest->destinations as $destination)
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-br from-gray-50 to-gray-100/70 dark:from-gray-700 dark:to-gray-800/70 rounded-2xl border border-gray-200 dark:border-gray-600 shadow-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-11 h-11 bg-white dark:bg-gray-700 rounded-xl flex items-center justify-center border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $destination->country->name ?? 'N/A' }}</p>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ $destination->service->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                            <span class="px-5 py-2 text-base font-extrabold text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/50 rounded-full">
                                                × {{ $destination->quantity ?? 0 }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-gray-500 dark:text-gray-400 italic">
                                    {{ __('No destinations specified for this order.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="lg:col-span-1 space-y-8 overflow-visible">
                    {{-- Product Image Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700 sticky top-8">
                        <div class="px-7 py-5 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-amber-50 dark:bg-amber-900/50 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Product Image') }}</h3>
                            </div>
                        </div>

                        <div class="p-7">
                            @if($sourcingOrder->quotation->sourcingRequest->product_image)
                                {{-- Ensure the image path is valid and symlinked --}}
                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                     alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'Product Image' }}" 
                                     class="w-full aspect-square object-cover rounded-2xl border-2 border-gray-200 dark:border-gray-700 shadow-md"/>
                            @else
                                <div class="w-full aspect-square bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                    <div class="text-center p-6">
                                        <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">{{ __('No image available') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Status & Timeline Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-7 py-5 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 bg-blue-50 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Status & Timeline') }}</h3>
                            </div>
                        </div>

                        <div class="p-7 space-y-6">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">{{ __('Current Status') }}</p>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-semibold
                                    @if($sourcingOrder->status === 'pending_payment') bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300
                                    @elseif($sourcingOrder->status === 'payment_pending_verification') bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300
                                    @elseif($sourcingOrder->status === 'payment_accepted') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300
                                    @elseif($sourcingOrder->status === 'payment_rejected') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $sourcingOrder->status ?? 'Unknown')) }} {{-- Added null coalescing --}}
                                </span>
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
                                <div class="flex items-center gap-4 text-base">
                                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Created') }}</p>
                                        <p class="font-bold text-gray-900 dark:text-white mt-0.5">{{ $sourcingOrder->created_at->format('d M Y, H:i') ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 text-base">
                                    <svg class="w-6 h-6 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ __('Last Updated') }}</p>
                                        <p class="font-bold text-gray-900 dark:text-white mt-0.5">{{ $sourcingOrder->updated_at->format('d M Y, H:i') ?? 'N/A' }}</p>
                                    </div>
                        </div>
                    </div>

                    {{-- Update Status Form --}}
                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 rounded-3xl shadow-lg border border-indigo-200 dark:border-indigo-900 p-7">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-11 h-11 bg-indigo-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Update Status') }}</h3>
                        </div>

                        <form action="{{ route('admin.sourcing-orders.update-status', $sourcingOrder) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('New Status') }}</label>
                                <select name="status" id="status" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-base py-2.5 px-4 transition-colors duration-200">
                                    {{-- Ensure App\Models\SourcingOrder::STATUSES is defined and accessible --}}
                                    @foreach (App\Models\SourcingOrder::STATUSES as $statusOption) {{-- Renamed $status to $statusOption to avoid conflict --}}
                                        <option value="{{ $statusOption }}" {{ ($sourcingOrder->status ?? '') == $statusOption ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $statusOption)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md transition-colors duration-200 text-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Update Status') }}
                            </button>
                        </form>
                    </div>

                    {{-- Proof of Payment Card --}}
                    @if ($sourcingOrder->proof_of_payment_path)
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <div class="px-7 py-5 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 bg-teal-50 dark:bg-teal-900/50 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Proof of Payment') }}</h3>
                                </div>
                            </div>
                            <div class="p-7 text-center space-y-5">
                                <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $sourcingOrder) }}" 
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3 text-base font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-800 rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900 shadow-sm transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    {{ __('Download Proof of Payment') }}
                                </a>

                                <form action="{{ route('admin.sourcing-orders.reject-proof', $sourcingOrder) }}" method="POST" class="mt-6 space-y-4">
                                    @csrf
                                    <div>
                                        <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('Rejection Reason') }}</label>
                                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-red-500 focus:ring-red-500 rounded-xl shadow-sm p-3 transition-colors duration-200" placeholder="{{ __('Provide a reason for rejecting the payment proof...') }}" required minlength="10"></textarea>
                                        @error('rejection_reason') {{-- Display validation errors for rejection_reason --}}
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-md transition-colors duration-200 text-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('Reject Proof') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Placeholder if no proof of payment has been uploaded yet --}}
                        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-700 p-7 text-center">
                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2zM15 10v4.586a1 1 0 01-.293.707l-2.414 2.414a1 1 0 01-1.414 0l-2.414-2.414A1 1 0 019 14.586V10a2 2 0 012-2h2a2 2 0 012 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('No Proof of Payment') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">{{ __('The client has not yet uploaded proof of payment for this order.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>