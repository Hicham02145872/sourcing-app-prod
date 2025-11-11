<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-violet-600 dark:bg-violet-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Order Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('View and manage sourcing order information') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.sourcing-orders.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Column - Main Content --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Status Update Card --}}
                    <div class="bg-gradient-to-br from-violet-50 to-indigo-50 dark:from-violet-900/20 dark:to-indigo-900/20 rounded-lg shadow-sm border border-violet-200 dark:border-violet-700 p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Update Order Status') }}</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Change the current status of this sourcing order') }}</p>
                            </div>
                        </div>

                        {{-- Current Status Display --}}
                        <div class="mb-6 p-4 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-600 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Current Status') }}</p>
                                    @php
                                        $statusConfig = [
                                            'pending_payment' => ['color' => 'amber', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'paid' => ['color' => 'emerald', 'icon' => 'M5 13l4 4L19 7'],
                                            'processing' => ['color' => 'blue', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                                            'shipped' => ['color' => 'purple', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                                            'delivered' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'completed' => ['color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'cancelled' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                        ];
                                        $statusData = $statusConfig[$sourcingOrder->status] ?? $statusConfig['pending_payment'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-bold {{ "bg-{$statusData['color']}-100 dark:bg-{$statusData['color']}-900/30 text-{$statusData['color']}-700 dark:text-{$statusData['color']}-400" }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusData['icon'] }}"/>
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $sourcingOrder->status)) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Last updated') }}</p>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $sourcingOrder->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Status Update Form --}}
                        <form action="{{ route('admin.sourcing-orders.update-status', $sourcingOrder) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('New Status') }}</label>
                                <select name="status" id="status" class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:bg-slate-700 dark:text-white shadow-sm font-medium">
                                    @foreach (App\Models\SourcingOrder::STATUSES as $statusOption)
                                        <option value="{{ $statusOption }}" {{ $sourcingOrder->status == $statusOption ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-violet-600 hover:bg-violet-700 dark:bg-violet-700 dark:hover:bg-violet-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ __('Update Status') }}
                            </button>
                        </form>
                    </div>

                    {{-- Order Details Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-violet-100 dark:bg-violet-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Order Information') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Complete order details and specifications') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Order ID --}}
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Order ID') }}</p>
                                    <p class="text-base font-bold text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-700/50 rounded-lg px-4 py-3 border border-slate-200 dark:border-slate-600">
                                        #{{ str_pad($sourcingOrder->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Total Amount --}}
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Total Amount') }}</p>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white bg-gradient-to-r from-violet-50 to-indigo-50 dark:from-violet-900/20 dark:to-indigo-900/20 rounded-lg px-4 py-3 border border-violet-200 dark:border-violet-700">
                                        {{ number_format($sourcingOrder->total_amount, 2) }} 
                                        <span class="text-base font-normal text-slate-600 dark:text-slate-400">{{ $sourcingOrder->quotation->currency }}</span>
                                    </p>
                                </div>
                            </div>

                            {{-- Product Information --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Product Name --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Product Name') }}</p>
                                        <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-700">
                                            {{ $sourcingOrder->quotation->sourcingRequest->product_name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Category --}}
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Category') }}</p>
                                        <span class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-semibold bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 border border-violet-200 dark:border-violet-700">
                                            {{ $sourcingOrder->quotation->sourcingRequest->category->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Shipping Method --}}
                            @if ($sourcingOrder->quotation->sourcingRequest->shipping_method)
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Shipping Method') }}</p>
                                    <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold 
                                        {{ $sourcingOrder->quotation->sourcingRequest->shipping_method === 'air' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-700' : 'bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-700' }}">
                                        @if($sourcingOrder->quotation->sourcingRequest->shipping_method === 'air')
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
                    </div>

                    {{-- Client Information Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <div class="w-16 h-16 bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ substr($sourcingOrder->user->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-slate-900 dark:text-white">{{ $sourcingOrder->user->name }}</h4>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $sourcingOrder->user->email }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-500 mt-2">
                                        {{ __('Member since') }} {{ $sourcingOrder->user->created_at->format('M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Destinations Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                @foreach ($sourcingOrder->quotation->sourcingRequest->destinations as $destination)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600 hover:border-violet-300 dark:hover:border-violet-600 transition-colors duration-200">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white dark:bg-slate-600 rounded-lg flex items-center justify-center border border-slate-200 dark:border-slate-500">
                                                <span class="fi fi-{{ strtolower($destination->country->code) }} text-xl"></span>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900 dark:text-white">{{ $destination->country->name }}</p>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $destination->service->name }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-3 py-1.5 bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 rounded-lg text-sm font-bold">
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
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden sticky top-6">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            @if($sourcingOrder->quotation->sourcingRequest->product_image)
                                <img src="{{ asset('storage/' . $sourcingOrder->quotation->sourcingRequest->product_image) }}" 
                                     alt="{{ $sourcingOrder->quotation->sourcingRequest->product_name }}" 
                                     class="w-full aspect-square object-cover rounded-lg border-2 border-slate-200 dark:border-slate-600 shadow-sm"/>
                            @else
                                <div class="w-full aspect-square bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-600 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-500 flex items-center justify-center">
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
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Order Timeline') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Order history and timeline') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Order Created') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $sourcingOrder->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Last Updated') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $sourcingOrder->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Proof of Payment Card --}}
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Proof of Payment') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Payment verification documents') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            @if ($sourcingOrder->proof_of_payment_path)
                                <a href="{{ route('admin.sourcing-orders.download-proof-of-payment', $sourcingOrder) }}" 
                                   class="inline-flex items-center justify-center gap-2 w-full px-4 py-3 bg-violet-600 hover:bg-violet-700 dark:bg-violet-700 dark:hover:bg-violet-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ __('Download Proof of Payment') }}
                                </a>

                                <form action="{{ route('admin.sourcing-orders.reject-proof', $sourcingOrder) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="rejection_reason" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">{{ __('Rejection Reason') }}</label>
                                        <textarea name="rejection_reason" id="rejection_reason" rows="3" class="block w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-slate-700 dark:text-white shadow-sm" placeholder="{{ __('Provide a reason for rejecting the payment proof...') }}" required minlength="10"></textarea>
                                    </div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('Reject Proof') }}
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('No proof of payment uploaded') }}</p>
                                </div>
                            @endif
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