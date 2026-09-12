<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Client Management') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700 cursor-pointer">Dashboard</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">Clients</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-slate-600 transition-colors" title="Actualiser">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <div class="h-6 w-px bg-slate-200"></div>
                        <span class="text-xs text-slate-500">{{ $users->total() }} records</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- Section 1: KPIs (Summary Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Clients -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-orange-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Clients') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
                        </div>
                        <div class="p-1.5 bg-slate-50 rounded text-slate-400 group-hover:text-orange-600 group-hover:bg-orange-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">Base de données complète</div>
                </div>

                <!-- Active -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-emerald-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Active') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($activeUsers) }}</h3>
                        </div>
                        <div class="p-1.5 bg-emerald-50 rounded text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-emerald-600 font-medium">Comptes vérifiés</div>
                </div>

                <!-- New This Month -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-blue-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('New This Month') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($newUsersThisMonth) }}</h3>
                        </div>
                        <div class="p-1.5 bg-blue-50 rounded text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-blue-600 font-medium">Croissance récente</div>
                </div>

                <!-- Inactive -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4 flex flex-col justify-between hover:border-slate-300 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Inactive') }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($inactiveUsers) }}</h3>
                        </div>
                        <div class="p-1.5 bg-slate-100 rounded text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-slate-400">À relancer</div>
                </div>
            </div>

            <!-- Section 2: Toolbar & Filters (Sticky) -->
            <div class="sticky top-20 z-10 bg-white rounded-lg border border-slate-200 shadow-sm p-3">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col lg:flex-row gap-3 items-center justify-between">
                    
                    <!-- Search & Filters Group -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto flex-1">
                        <!-- Search -->
                        <div class="relative w-full sm:w-72">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search users...') }}"
                                class="w-full pl-9 pr-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors">
                        </div>

                        <!-- Role Filter -->
                        <div class="w-full sm:w-40">
                            <select name="role" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
                                <option value="">{{ __('All Roles') }}</option>
                                <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>{{ __('Client') }}</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full sm:w-40">
                            <select name="status" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                            </select>
                        </div>

                        <!-- FCM Filter -->
                        <div class="w-full sm:w-40">
                            <select name="fcm_status" class="w-full px-3 py-1.5 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block">
                                <option value="">{{ __('All Devices') }}</option>
                                <option value="has_token" {{ request('fcm_status') == 'has_token' ? 'selected' : '' }}>{{ __('With Token') }}</option>
                                <option value="no_token" {{ request('fcm_status') == 'no_token' ? 'selected' : '' }}>{{ __('No Token') }}</option>
                            </select>
                        </div>

                        @include('admin.partials.date-range-fields')
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 w-full lg:w-auto justify-end">
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 rounded-md transition-colors">
                            {{ __('Reset') }}
                        </a>
                        <button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-md transition-colors shadow-sm flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            {{ __('Filter') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section 3: Data Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('User Profile') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Role') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Status') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Joined') }}</th>
                                <th scope="col" class="px-6 py-3 text-right text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200" id="users-table-body" x-data="{ expandedId: null }">
                            @include('admin.users.partials.users_table_rows', ['users' => $users])
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div id="users-pagination" class="bg-white px-6 py-3 border-t border-slate-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('input[name="search"]');
            const usersTableBody = document.getElementById('users-table-body');
            const usersPagination = document.getElementById('users-pagination');
            let searchTimeout;

            function fetchUsers(searchTerm, page = 1) {
                const status = document.querySelector('select[name="status"]').value;
                const role = document.querySelector('select[name="role"]').value;
                const fcm_status = document.querySelector('select[name="fcm_status"]').value;
                const dateDebut = document.querySelector('input[name="date_debut"]')?.value ?? '';
                const dateFin = document.querySelector('input[name="date_fin"]')?.value ?? '';

                axios.get('{{ route('admin.users.index') }}', {
                    params: { 
                        search: searchTerm, 
                        page: page,
                        status: status,
                        role: role,
                        fcm_status: fcm_status,
                        date_debut: dateDebut,
                        date_fin: dateFin
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    // Assuming the controller returns 'table' and 'pagination' HTML fragments
                    // If your controller returns the full view, you might need to parse it differently
                    if(response.data.table) {
                        usersTableBody.innerHTML = response.data.table;
                    }
                    if(response.data.pagination) {
                        if(usersPagination) usersPagination.innerHTML = response.data.pagination;
                    }
                    // Re-attach pagination listeners after content update
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

            document.querySelectorAll('select[name="status"], select[name="role"], select[name="fcm_status"]').forEach(select => {
                select.addEventListener('change', function() {
                    fetchUsers(searchInput.value);
                });
            });

            function attachPaginationListeners() {
                if(!usersPagination) return;
                usersPagination.querySelectorAll('a').forEach(link => {
                    link.removeEventListener('click', handlePaginationClick);
                    link.addEventListener('click', handlePaginationClick);
                });
            }

            function handlePaginationClick(event) {
                event.preventDefault();
                if(this.getAttribute('href')) {
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page');
                    const searchTerm = searchInput.value;
                    fetchUsers(searchTerm, page);
                }
            }

            attachPaginationListeners();
        });
    </script>
    @endpush
</x-app-layout>