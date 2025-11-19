<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -m-6 p-6 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-3xl text-gray-900 dark:text-white tracking-tight">
                        {{ __('Categories') }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1.5">{{ __('Organize and manage product categories') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Category') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-full mx-auto sm:px-6 lg:px-4">
            <!-- Stats Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Categories') }}</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $categories->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Active Categories') }}</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $categories->count() }}</p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Products') }}</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">{{ $categories->sum('products_count') ?? '0' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-700 rounded-xl p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
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

            <!-- Search Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form action="{{ route('admin.categories.index') }}" method="GET">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                        <div class="flex-1 max-w-lg">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" placeholder="{{ __('Search categories...') }}" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-[#EF7722] focus:border-[#EF7722] text-sm dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        {{ __('ID') }}
                                        <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Category Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Products') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Created Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        #{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-[#EF7722]/10 rounded-xl flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-[#EF7722] dark:text-[#FAA533]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Product category') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700">
                                           {{ __('Active') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            {{ $category->products_count ?? '0' }} {{ __('items') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $category->created_at ? $category->created_at->translatedFormat('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center p-1.5 text-gray-400 hover:text-[#EF7722] dark:hover:text-[#FAA533] transition-colors" title="{{ __('Edit') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this category? This action cannot be undone.') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors" title="{{ __('Delete') }}">
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

                <!-- Empty State -->
                @if($categories->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ __('No categories found') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Get started by creating your first category.') }}</p>
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Category') }}
                    </a>
                </div>
                @endif

                <!-- Pagination -->
                @if($categories->hasPages())
                <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>