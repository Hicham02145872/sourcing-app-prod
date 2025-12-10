<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#EF7722] to-[#FAA533] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-4a2 2 0 012-2h12a2 2 0 012 2v4zM13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Client Management') }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Manage and monitor all your clients') }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Export') }}
                    </button>
                    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Client') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- STATISTICS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Clients --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('Total Clients') }}</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $totalUsers }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                                {{ __('All registered') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-4a2 2 0 012-2h12a2 2 0 012 2v4zM13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Active Clients --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('Active') }}</p>
                            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ $activeUsers }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Currently active') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- New This Month --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('New This Month') }}</p>
                            <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $newUsersThisMonth }}</p>
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Recent signups') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Inactive Clients --}}
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ __('Inactive') }}</p>
                            <p class="text-3xl font-bold text-slate-600 dark:text-slate-400 mt-2">{{ $inactiveUsers }}</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Not active recently') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900/50 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN TABLE CARD --}}
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                
                {{-- HEADER --}}
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-[#EF7722] rounded-full animate-pulse"></div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('All Clients') }}</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                <span class="font-semibold text-[#EF7722]">{{ $users->total() }}</span> {{ Str::plural('client', $users->total()) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- FILTERS --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                            {{-- SEARCH --}}
                            <div class="relative flex-1 max-w-md">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search"
                                       placeholder="{{ __('Search by name or email...') }}" 
                                       value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-4 py-2.5 text-sm border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                            </div>

                            {{-- STATUS FILTER --}}
                            <div class="w-full sm:w-40">
                                <select name="status" class="block w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm font-semibold text-slate-900 dark:text-white">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                </select>
                            </div>

                            {{-- ROLE FILTER --}}
                            <div class="w-full sm:w-40">
                                <select name="role" class="block w-full px-4 py-2.5 text-sm border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm font-semibold text-slate-900 dark:text-white">
                                    <option value="">{{ __('All Roles') }}</option>
                                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                {{ __('Filter') }}
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                                {{ __('Clear') }}
                            </a>
                        </div>
                    </form>
                </div>

                {{-- CONTENT --}}
                @if ($users->isEmpty())
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-20 px-6">
                        <div class="mx-auto w-20 h-20 bg-[#EF7722]/10 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-4a2 2 0 012-2h12a2 2 0 012 2v4zM13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('No clients found') }}</h3>
                    </div>
                @else
                    {{-- TABLE --}}
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Client') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Email') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Role') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Status') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Joined') }}
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700" id="users-table-body">
                                @include('admin.users.partials.users_table')
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div id="users-pagination" class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                        @include('admin.users.partials.pagination')
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            const usersTableBody = document.getElementById('users-table-body');
            const usersPagination = document.getElementById('users-pagination');
            let searchTimeout;

            function fetchUsers(searchTerm, page = 1) {
                const status = document.querySelector('select[name="status"]').value;
                const role = document.querySelector('select[name="role"]').value;
                
                axios.get('{{ route('admin.users.index') }}', {
                    params: { 
                        search: searchTerm, 
                        page: page,
                        status: status,
                        role: role
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    usersTableBody.innerHTML = response.data.table;
                    usersPagination.innerHTML = response.data.pagination;
                    attachPaginationListeners();
                })
                .catch(error => {
                    console.error('Error fetching users:', error);
                });
            }

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    fetchUsers(this.value);
                }, 300);
            });

            document.querySelectorAll('select[name="status"], select[name="role"]').forEach(select => {
                select.addEventListener('change', function() {
                    fetchUsers(searchInput.value);
                });
            });

            function attachPaginationListeners() {
                usersPagination.querySelectorAll('.pagination a').forEach(link => {
                    link.removeEventListener('click', handlePaginationClick);
                    link.addEventListener('click', handlePaginationClick);
                });
            }

            function handlePaginationClick(event) {
                event.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get('page');
                const searchTerm = searchInput.value;
                fetchUsers(searchTerm, page);
            }

            attachPaginationListeners();
        });
    </script>
    @endpush

    <style>
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #f8fafc;
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