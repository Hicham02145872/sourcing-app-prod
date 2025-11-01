<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-semibold text-gray-900 dark:text-white">
                    {{ __('Payment Methods') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Manage payment options') }}</p>
            </div>
            <a href="{{ route('admin.payment-methods.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ __('Add Method') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form action="{{ route('admin.payment-methods.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                        <div class="flex-1 max-w-lg">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="{{ __('Search payment methods...') }}" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500 text-sm dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition-colors duration-200">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if($paymentMethods->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-12">
                    <div class="text-center max-w-sm mx-auto">
                        <div class="w-16 h-16 bg-violet-50 dark:bg-violet-900/50 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('No payment methods') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ __('Create your first payment method to get started') }}</p>
                        <a href="{{ route('admin.payment-methods.create') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            {{ __('Create Method') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- Stats Summary --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Methods') }}</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $paymentMethods->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-violet-50 dark:bg-violet-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Active') }}</p>
                                <p class="text-2xl font-semibold text-green-600 dark:text-green-400 mt-1">{{ $paymentMethods->where('is_active', true)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Inactive') }}</p>
                                <p class="text-2xl font-semibold text-gray-400 mt-1">{{ $paymentMethods->where('is_active', false)->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Methods Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($paymentMethods as $paymentMethod)
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:border-violet-300 dark:hover:border-violet-600 hover:shadow-lg transition-all duration-200">
                            {{-- Card Header with Logo --}}
                            <div class="relative bg-gradient-to-br from-violet-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 p-8 h-40 flex items-center justify-center border-b border-gray-100 dark:border-gray-700">
                                @if($paymentMethod->logo_path)
                                    <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" 
                                         alt="{{ $paymentMethod->name }}" 
                                         class="w-32 h-32 object-contain">
                                @else
                                    <div class="w-20 h-20 bg-white dark:bg-gray-700 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                        <svg class="w-10 h-10 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Status Badge --}}
                                <div class="absolute top-4 right-4">
                                    @if($paymentMethod->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 text-xs font-medium rounded-md">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
{{ __('Active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-medium rounded-md">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                            {{ __('Inactive') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $paymentMethod->name }}</h3>
                                
                                {{-- Payment Details --}}
                                @if($paymentMethod->details && count($paymentMethod->details) > 0)
                                    <div class="space-y-2 mb-6">
                                        @foreach($paymentMethod->details as $key => $value)
                                            <div class="flex items-start justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $key }}</span>
                                                <span class="text-sm text-gray-900 dark:text-white text-right ml-2 font-medium">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-400 mb-6">{{ __('No details') }}</p>
                                @endif

                                {{-- Actions --}}
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" 
                                       class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-violet-50 dark:bg-gray-700 hover:bg-violet-100 dark:hover:bg-gray-600 text-violet-700 dark:text-violet-300 text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('admin.payment-methods.destroy', $paymentMethod) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this payment method?') }}');"
                                          class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 dark:bg-red-900/50 hover:bg-red-100 dark:hover:bg-red-900/80 text-red-700 dark:text-red-300 text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                <div class="mt-6">
                    {{ $paymentMethods->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>