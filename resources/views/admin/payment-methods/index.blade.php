<x-app-layout>
    <!-- Main Container: Enterprise Slate Background (Style Sourcing Request) -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Icone Branding (Adapté pour Paiement) -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Payment Methods') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition-colors">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Configuration') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-900 border border-transparent text-white hover:bg-slate-800 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            {{ __('Add Method') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            
            <!-- Section 1: KPIs (Summary Cards) - Enterprise Style -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Methods') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $paymentMethods->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-slate-100 rounded text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Available options') }}</div>
                </div>

                <!-- Active -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-emerald-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Active') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $paymentMethods->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-emerald-50 rounded text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-emerald-600 font-medium">{{ __('Online for clients') }}</div>
                </div>

                <!-- Inactive -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-red-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Inactive') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $paymentMethods->where('is_active', false)->count() }}</h3>
                        </div>
                        <div class="p-1.5 bg-red-50 rounded text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Disabled or drafts') }}</div>
                </div>

                <!-- Featured -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-200 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Featured') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $paymentMethods->where('is_featured', true)->count() ?? 0 }}</h3>
                        </div>
                        <div class="p-1.5 bg-blue-50 rounded text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">{{ __('Recommended') }}</div>
                </div>
            </div>

            <!-- Section 2: Filters Bar (Sticky) -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 sticky top-20 z-10">
                <form action="{{ route('admin.payment-methods.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        
                        <div class="flex-1 w-full md:w-auto flex flex-col md:flex-row gap-3">
                            <!-- Search -->
                            <div class="relative w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search for a method...') }}"
                                    class="w-full pl-9 pr-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors placeholder:text-slate-400">
                            </div>

                             <button type="submit" class="w-full md:w-auto px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm">
                                {{ __('Filter') }}
                            </button>
                        </div>

                        <!-- Reset -->
                        <div>
                             <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-orange-600 transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Section 3: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">{{ __('List of methods') }}</h3>
                    <div class="text-xs text-slate-500">
                         @if($paymentMethods instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <span class="font-medium text-slate-900">{{ $paymentMethods->firstItem() ?? 0 }}-{{ $paymentMethods->lastItem() ?? 0 }}</span> {{ __('of') }} <span class="font-medium text-slate-900">{{ $paymentMethods->total() }}</span>
                         @else
                            {{ __('Total:') }} <span class="font-medium text-slate-900">{{ $paymentMethods->count() }}</span>
                         @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                         <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-64">{{ __('Method') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Details') }}</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Type') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Created Date') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($paymentMethods as $method)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <!-- Method Name & Logo -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 flex-shrink-0 rounded bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                                @if ($method->logo_path)
                                                    <img src="{{ media_url($method->logo_path) }}" alt="" class="h-full w-full object-cover">
                                                @else
                                                    <span class="text-xs font-bold text-slate-400">{{ substr($method->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-slate-900 truncate" title="{{ $method->name }}">{{ $method->name }}</div>
                                                <div class="text-xs text-slate-500">ID: {{ $method->id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Details -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        @if($method->details && count($method->details) > 0)
                                            <div class="flex flex-col text-xs">
                                                @php
                                                    $firstDetail = array_slice($method->details, 0, 1, true);
                                                    $key = array_key_first($firstDetail);
                                                    $value = $firstDetail[$key];
                                                @endphp
                                                <span class="text-slate-700 font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                <span class="text-slate-500 truncate max-w-[150px]">{{ $value }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">{{ __('No details') }}</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                     <td class="px-6 py-3 whitespace-nowrap text-center">
                                        @if($method->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">
                                                {{ __('Inactive') }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Type -->
                                     <td class="px-6 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                            {{ $method->type ?? __('Standard') }}
                                        </span>
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <div class="text-sm text-slate-600">{{ $method->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $method->updated_at->diffForHumans() }}</div>
                                    </td>

                                    <!-- Actions -->
                                     <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.payment-methods.edit', $method) }}" class="text-slate-400 hover:text-orange-600 transition-colors" title="{{ __('Edit') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            
                                            <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this method?') }}');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors" title="{{ __('Delete') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                             <div class="h-12 w-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                            </div>
                                            <h3 class="text-sm font-medium text-slate-900">{{ __('No methods found') }}</h3>
                                            <p class="text-xs text-slate-500 mt-1">{{ __('Start by adding a new payment method.') }}</p>
                                            <a href="{{ route('admin.payment-methods.create') }}" class="mt-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-orange-700 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                                {{ __('Create now') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($paymentMethods instanceof \Illuminate\Pagination\LengthAwarePaginator && $paymentMethods->hasPages())
                    <div class="bg-white px-6 py-3 border-t border-slate-200">
                        {{ $paymentMethods->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>