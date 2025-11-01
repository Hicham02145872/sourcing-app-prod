<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-violet-600 to-violet-500 text-white -m-6 p-8 mb-0">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-4xl text-white tracking-tight">
                        {{ __('Client Management') }}
                    </h2>
                    <p class="text-violet-100 mt-2 text-lg">{{ __('Manage and monitor all your clients') }}</p>
                </div>
                <div class="hidden lg:flex items-center space-x-2 text-violet-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3.622a1.5 1.5 0 01-1.5-1.5V2.5A1.5 1.5 0 013.622 1h16.756a1.5 1.5 0 011.5 1.5V9M6 12H3m15 0h-3m6 3h3m-3 3h3"/>
                    </svg>
                    <span class="text-sm">{{ $users->total() }} {{ __('Total Clients') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 via-white to-violet-50 dark:from-gray-900 dark:via-gray-800 dark:to-violet-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">




            </div>

            <!-- Main Table Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-violet-100 dark:border-gray-700 overflow-hidden">

                <!-- Search & Filter Section -->
                <div class="border-b border-violet-100 dark:border-gray-700 px-8 py-6 bg-gradient-to-r from-gray-50 to-violet-50 dark:from-gray-700 dark:to-gray-600">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ __('Clients') }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('View and manage all your clients') }}</p>
                        </div>
                        <div class="flex items-center space-x-2 w-full lg:w-auto">
                            <div class="flex-1 lg:flex-none relative">
                                <input type="text" id="user-search-input" name="search" placeholder="{{ __('Search by name or email...') }}" 
                                       value="{{ request('search') }}"
                                       class="block w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-violet-500 focus:border-transparent transition">
                                <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Role') }}</th>
                                <th class="px-8 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>

                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @include('admin.users.partials.users_table')
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div id="users-pagination" class="border-t border-gray-200 dark:border-gray-700 px-8 py-4 bg-gray-50 dark:bg-gray-700">
                    @include('admin.users.partials.pagination')
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('user-search-input');
            const usersTableBody = document.getElementById('users-table-body');
            const usersPagination = document.getElementById('users-pagination');
            let searchTimeout;

            function fetchUsers(searchTerm, page = 1) {
                axios.get('{{ route('admin.users.index') }}', {
                    params: { search: searchTerm, page: page },
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
                }, 300); // 300ms delay after typing stops
            });

            function attachPaginationListeners() {
                usersPagination.querySelectorAll('.pagination a').forEach(link => {
                    link.removeEventListener('click', handlePaginationClick); // Prevent multiple listeners
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

            attachPaginationListeners(); // Attach listeners on initial load
        });
    </script>
    @endpush
</x-app-layout>