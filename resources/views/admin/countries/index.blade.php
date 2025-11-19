<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Countries Management') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Manage countries and their information') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.countries.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Add Country') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" class="inline-flex text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Control Panel --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 mb-6">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Countries Database') }}</h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                        <span class="font-semibold text-[#EF7722] dark:text-[#FAA533]">{{ $countries->total() }}</span> {{ Str::plural(__('country'), $countries->total()) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <form action="{{ route('admin.countries.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                            <div class="relative flex-1 lg:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="{{ __('Search countries...') }}" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent text-sm dark:bg-slate-700 dark:text-white font-medium shadow-sm">
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                {{ __('Search') }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Statistics Bar --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Total Countries') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $countries->total() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Active') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $countries->total() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Regions') }}</p>
                                <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $countries->unique('code')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Countries Table --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-1">
                                        {{ __('ID') }}
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Country') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Code') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach ($countries as $country)
                                <tr class="hover:bg-[#EF7722]/5 dark:hover:bg-[#FAA533]/10 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white">#{{ str_pad($country->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                                <span class="fi fi-{{ strtolower($country->code) }} text-xl"></span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $country->name }}</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ __('Country') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                            {{ strtoupper($country->code) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Active') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.countries.edit', $country) }}" 
                                               class="inline-flex items-center p-1.5 text-slate-400 hover:text-[#EF7722] dark:hover:text-[#FAA533] transition-colors" 
                                               title="{{ __('Edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this country? This action cannot be undone.') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center p-1.5 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" 
                                                        title="{{ __('Delete') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Empty State --}}
                @if($countries->isEmpty())
                <div class="text-center py-20 px-6">
                    <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('No Countries Found') }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6 max-w-sm mx-auto">{{ __('Get started by creating your first country.') }}</p>
                    <a href="{{ route('admin.countries.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Country') }}
                    </a>
                </div>
                @endif

                {{-- Pagination --}}
                @if($countries->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-slate-700 dark:text-slate-300">
                                {{ __('Showing') }}
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $countries->firstItem() }}</span>
                                {{ __('to') }}
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $countries->lastItem() }}</span>
                                {{ __('of') }}
                                <span class="font-semibold text-slate-900 dark:text-white">{{ $countries->total() }}</span>
                                {{ __('results') }}
                            </div>
                            <div class="flex gap-1">
                                {{ $countries->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Enterprise table styling */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        /* Custom scrollbar for table */
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

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #EF7722;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</x-app-layout>