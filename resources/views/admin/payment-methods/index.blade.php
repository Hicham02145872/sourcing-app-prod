<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Payment Methods Management') }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Configure and manage payment options for your platform') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            @if ($paymentMethods->isEmpty())
                {{-- EMPTY STATE --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('No Payment Methods') }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Create your first payment method to enable payment processing for your clients') }}</p>
                        <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('Create Payment Method') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- CONTROL PANEL --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Payment Methods') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                            <span class="font-semibold text-[#EF7722]">{{ $paymentMethods->count() }}</span> {{ trans_choice('method', $paymentMethods->count()) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                <form action="{{ route('admin.payment-methods.index') }}" method="GET" class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <input type="text" 
                                               name="search" 
                                               placeholder="{{ __('Search methods...') }}" 
                                               value="{{ request('search') }}"
                                               class="pl-10 pr-4 py-2.5 text-sm border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 font-semibold shadow-sm text-slate-900 dark:text-white">
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        {{ __('Search') }}
                                    </button>
                                </form>
                                <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg transition-all shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ __('Add New') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- STATISTICS BAR --}}
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $paymentMethods->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Active') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $paymentMethods->where('is_active', true)->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Inactive') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $paymentMethods->where('is_active', false)->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Featured') }}</p>
                                    <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $paymentMethods->where('is_featured', true)->count() ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT METHODS TABLE --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Method') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Details') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Type') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Date') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach ($paymentMethods as $method)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 overflow-hidden flex-shrink-0">
                                                    @if ($method->logo_path)
                                                        <img src="{{ asset('storage/' . $method->logo_path) }}"
                                                             alt="{{ $method->name }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-[#EF7722]/10">
                                                            <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $method->name }}</div>
                                                    <div class="text-xs text-slate-600 dark:text-slate-400">#{{ $method->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($method->details && count($method->details) > 0)
                                                <div class="text-sm text-slate-900 dark:text-white">
                                                    @php
                                                        $firstDetail = array_slice($method->details, 0, 1, true);
                                                        $key = array_key_first($firstDetail);
                                                        $value = $firstDetail[$key];
                                                    @endphp
                                                    <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                    <span class="text-slate-600 dark:text-slate-400">{{ $value }}</span>
                                                </div>
                                                @if(count($method->details) > 1)
                                                    <div class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                                                        +{{ count($method->details) - 1 }} {{ __('more details') }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('No details') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($method->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ __('Active') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ __('Inactive') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#EF7722]/10 text-[#EF7722] rounded-lg text-xs font-bold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                {{ $method->type ?? __('Standard') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $method->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-slate-600 dark:text-slate-400">{{ $method->updated_at->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.payment-methods.edit', $method) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-xs font-bold rounded-lg transition-all shadow-sm hover:shadow">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('admin.payment-methods.destroy', $method) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this payment method?') }}');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm hover:shadow">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    @if($paymentMethods->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="text-sm text-slate-700 dark:text-slate-300">
                                    {{ __('Showing') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $paymentMethods->firstItem() }}</span>
                                    {{ __('to') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $paymentMethods->lastItem() }}</span>
                                    {{ __('of') }}
                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $paymentMethods->total() }}</span>
                                    {{ __('results') }}
                                </div>
                                <div class="flex gap-1">
                                    {{ $paymentMethods->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>