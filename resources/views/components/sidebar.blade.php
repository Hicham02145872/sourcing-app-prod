{{-- resources/views/components/sidebar-enterprise-blue.blade.php --}}
@props(['role' => 'client'])

<aside class="fixed top-[4rem] bottom-0 left-0 z-50 w-72 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-xl transform transition-transform duration-300 lg:translate-x-0 flex flex-col" 
       id="sidebar"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    {{-- Logo Section --}}
    

    {{-- Main Content Wrapper --}}
    <div class="flex-1 overflow-y-auto">
        {{-- Navigation --}}
        <nav class="px-3 py-4">
            @if($role === 'client')
                {{-- Client Navigation --}}
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Main') }}</div>
                    
                    <a href="{{ route('client.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-4">{{ __('Sourcing') }}</div>

                    <a href="{{ route('client.sourcing-requests.handling') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('client.sourcing-requests.handling') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ __('Handling') }}</span>
                    </a>

                    <a href="{{ route('client.quotations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('client.quotations.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-3 10v-5m-5 5h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ __('Quotations') }}</span>
                    </a>

                    <a href="{{ route('client.sourcing-requests.create') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('client.sourcing-requests.create') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-blue-200 dark:border-blue-700' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>{{ __('New Request') }}</span>
                    </a>

                    <a href="{{ route('client.sourcing-orders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('client.sourcing-orders.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>{{ __('My Orders') }}</span>
                    </a>

                    <a href="{{ route('client.history') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('client.history') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ __('History') }}</span>
                    </a>
                </div>

            @else
                {{-- Admin Navigation --}}
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Admin') }}</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-4">{{ __('Management') }}</div>

                    <a href="{{ route('admin.sourcing-requests.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.sourcing-requests.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>{{ __('All Sourcing Requests') }}</span>
                    </a>

                    <a href="{{ route('admin.quotations.select-request') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('admin.quotations.select-request') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-blue-200 dark:border-blue-700' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-3 10v-5m-5 5h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ __('Create Quotation') }}</span>
                    </a>

                    <a href="{{ route('admin.quotations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.quotations.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>{{ __('All Quotations') }}</span>
                    </a>

                    <a href="{{ route('admin.payment-methods.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.payment-methods.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span>{{ __('Payment Methods') }}</span>
                    </a>

                    <a href="{{ route('admin.sourcing-orders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.sourcing-orders.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>{{ __('Sourcing Orders') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.users.index') ? 'bg-blue-600 dark:bg-blue-700 text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>{{ __('Manage Clients') }}</span>
                    </a>
                </div>
            @endif
        </nav>

        {{-- Notification Center --}}
        <div class="mx-3 mb-4 mt-2" x-data="notificationCenter" x-init="init()">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-700">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-blue-600 dark:bg-blue-700 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Notifications') }}</h3>
                    </div>
                    <span x-show="unreadCount > 0" class="bg-red-600 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                </div>
                
                <div class="space-y-2" x-show="!loading && notifications.length > 0">
                    <template x-for="notification in notifications.slice(0, 3)" :key="notification.id">
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-3 border border-blue-100 dark:border-blue-800 hover:border-blue-300 dark:hover:border-blue-600 transition-colors cursor-pointer">
                            <a :href="notification.data.click_action || '#'" @click.prevent="markAsRead(notification.id, notification.data.click_action)">
                                <div class="flex items-start gap-2">
                                    <div x-show="!notification.read_at" class="w-2 h-2 bg-blue-600 rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-slate-900 dark:text-white" x-text="notification.data.title"></p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 line-clamp-1" x-text="notification.data.body"></p>
                                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1" x-text="formatDate(notification.created_at)"></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </template>
                </div>

                <div x-show="!loading && notifications.length === 0" class="text-center py-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('No new notifications') }}</p>
                </div>

                <div x-show="loading" class="text-center py-4">
                    <div class="inline-block w-4 h-4 border-2 border-blue-300 border-t-blue-600 dark:border-blue-700 dark:border-t-blue-400 rounded-full animate-spin"></div>
                </div>

                <a href="{{ route('notifications.index') }}" class="w-full mt-3 px-3 py-2 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-colors text-center block border border-blue-200 dark:border-blue-700">
                    {{ __('View All Notifications') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Footer / User Info & Logout --}}
    <div class="border-t border-slate-200 dark:border-slate-700 p-4 bg-slate-50 dark:bg-slate-900 mt-auto">
        <div class="bg-white dark:bg-slate-800 rounded-lg p-3 mb-3 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 dark:bg-blue-700 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-white">{{ substr(Auth::user()->name ?? 'G', 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name ?? __('Guest') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ strtoupper($role) }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-lg text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>{{ __('Logout') }}</span>
            </button>
        </form>
    </div>
</aside>

{{-- Overlay for mobile --}}
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900 bg-opacity-50 z-40 lg:hidden backdrop-blur-sm"
     style="display: none;">
</div>

<style>
    /* Custom scrollbar for sidebar */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }

    #sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    #sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .dark #sidebar::-webkit-scrollbar-thumb {
        background: #475569;
    }

    #sidebar::-webkit-scrollbar-thumb:hover {
        background: #3b82f6;
    }

    .dark #sidebar::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }

    /* Line clamp utility */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>