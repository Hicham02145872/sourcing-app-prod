<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1A6 6 0 019 14h6a6 6 0 016 6v1z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Manage Admin Users') }}</h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('View and manage system administrators') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.super-admin.create-admin') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-yellow-600 hover:from-yellow-600 hover:to-red-600 text-white text-sm font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Create New Admin') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-red-50 via-white to-yellow-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800 min-h-screen" x-data="{
        showAdminModal: false,
        selectedAdmin: null,
        openAdminModal(adminId) {
            this.showAdminModal = true;
            fetch(`/admins/${adminId}`)
                .then(response => response.json())
                .then(data => {
                    this.selectedAdmin = data;
                });
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">{{ __('Total Admins') }}</p>
                            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-2">{{ $admins->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-yellow-100 dark:from-red-900/30 dark:to-yellow-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">{{ __('Super Admins') }}</p>
                            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-2">{{ $admins->where('role', 'super_admin')->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-green-100 dark:from-emerald-900/30 dark:to-green-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">{{ __('Regular Admins') }}</p>
                            <p class="text-4xl font-bold text-slate-900 dark:text-white mt-2">{{ $admins->where('role', 'admin')->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLE CARD --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                {{-- TABLE HEADER --}}
                <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-red-50 to-yellow-50 dark:from-slate-800 dark:to-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-yellow-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Administrator List') }}</h3>
                    </div>
                </div>

                {{-- RESPONSIVE TABLE --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Role') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Created At') }}</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse ($admins as $admin)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 bg-gradient-to-br from-red-600 to-yellow-600 rounded-lg flex items-center justify-center shadow-md">
                                                <span class="text-sm font-bold text-white">{{ substr($admin->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $admin->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $admin->email }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold {{ $admin->role === 'super_admin' ? 'bg-gradient-to-r from-emerald-100 to-green-100 dark:from-emerald-900/50 dark:to-green-900/50 text-emerald-700 dark:text-emerald-300' : 'bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/50 dark:to-cyan-900/50 text-blue-700 dark:text-blue-300' }} shadow-sm">
                                            @if($admin->role === 'super_admin')
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endif
                                            {{ ucfirst(str_replace('_', ' ', $admin->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $admin->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button 
                                                @click="openAdminModal({{ $admin->id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm"
                                                title="{{ __('View details') }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ __('View') }}
                                            </button>
                                            <a href="{{ route('admin.super-admin.edit-admin', $admin->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm"
                                                title="{{ __('Edit') }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('admin.super-admin.destroy-admin', $admin->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this admin user?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-red-700 dark:text-red-400 bg-white dark:bg-slate-800 border border-red-300 dark:border-red-700 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all shadow-sm"
                                                    title="{{ __('Delete') }}"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-gradient-to-br from-red-100 to-yellow-100 dark:from-red-900/30 dark:to-yellow-900/30 rounded-2xl flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                                </svg>
                                            </div>
                                            <p class="text-lg font-bold text-slate-900 dark:text-white mb-2">{{ __('No administrators found') }}</p>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">{{ __('Create the first admin to get started') }}</p>
                                            <a href="{{ route('admin.super-admin.create-admin') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-yellow-600 hover:from-yellow-600 hover:to-red-600 text-white text-sm font-bold rounded-lg shadow-lg hover:shadow-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                {{ __('Create New Admin') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER --}}
                @if($admins->count() > 0)
                <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold">
                            {{ __('Showing :count administrators', ['count' => $admins->count()]) }}
                        </p>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Last updated') }}: {{ now()->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alpine.js Modal -->
    <div x-cloak>
        <!-- View Admin Modal -->
        <div x-show="showAdminModal" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showAdminModal" 
                     @click="showAdminModal = false; selectedAdmin = null"
                     class="fixed inset-0 bg-slate-900/75 dark:bg-slate-900/90 backdrop-blur-sm transition-opacity" 
                     aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showAdminModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
                    <div class="bg-gradient-to-r from-red-50 to-yellow-50 dark:from-slate-800 dark:to-slate-700 px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white" id="modal-title">
                                {{ __('Admin Details') }}
                            </h3>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-800 px-6 py-6">
                        <div x-show="selectedAdmin" class="space-y-4">
                            <div class="flex items-start justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Name') }}:</span>
                                <span x-text="selectedAdmin?.name" class="text-sm text-slate-900 dark:text-white font-semibold"></span>
                            </div>
                            <div class="flex items-start justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Email') }}:</span>
                                <span x-text="selectedAdmin?.email" class="text-sm text-slate-900 dark:text-white font-semibold"></span>
                            </div>
                            <div class="flex items-start justify-between py-3 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Role') }}:</span>
                                <span x-text="selectedAdmin?.role" class="text-sm text-slate-900 dark:text-white font-semibold capitalize"></span>
                            </div>
                            <div class="flex items-start justify-between py-3">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ __('Created At') }}:</span>
                                <span x-text="selectedAdmin?.created_at ? new Date(selectedAdmin.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'}) : ''" class="text-sm text-slate-900 dark:text-white font-semibold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 px-6 py-4 flex justify-end">
                        <button type="button" 
                                @click="showAdminModal = false; selectedAdmin = null" 
                                class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-yellow-600 hover:from-yellow-600 hover:to-red-600 text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #fef2f2;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #dc2626, #ef4444);
            border-radius: 5px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #ef4444, #fbbf24);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #b91c1c, #dc2626);
        }
    </style>
</x-app-layout>