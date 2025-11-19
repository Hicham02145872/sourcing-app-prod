{{-- resources/views/components/sidebar-enterprise-custom.blade.php --}}
@props(['role' => 'client'])

<aside class="fixed top-[5rem] bottom-0 left-0 z-50 w-72 bg-gradient-to-b from-white to-[#EBEBEB] dark:from-gray-800 dark:to-gray-900 border-r border-[#EBEBEB] dark:border-gray-700 shadow-xl transform transition-transform duration-300 lg:translate-x-0 flex flex-col" 
       id="sidebar"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    {{-- Main Content Wrapper --}}
    <div class="flex-1 overflow-y-auto">
        {{-- Navigation --}}
        <nav class="px-3 py-4">
            @if($role === 'client')
                {{-- Client Navigation --}}
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Main') }}</div>
                    
                    <a href="{{ route('client.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-4">{{ __('Sourcing') }}</div>

                    <a href="{{ route('client.sourcing-requests.handling') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.sourcing-requests.handling') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span>{{ __('Handling') }}</span>
                    </a>

                    <a href="{{ route('client.quotations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.quotations.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        <span>{{ __('Quotations') }}</span>
                    </a>

                    <a href="{{ route('client.sourcing-requests.create') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('client.sourcing-requests.create') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-[#EF7722] dark:text-[#FAA533] hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 border border-[#EF7722] dark:border-[#FAA533] hover:border-[#FAA533] dark:hover:border-[#EF7722] hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>{{ __('New Request') }}</span>
                    </a>

                    <a href="{{ route('client.sourcing-orders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.sourcing-orders.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                        </svg>
                        <span>{{ __('My Orders') }}</span>
                    </a>

                    <a href="{{ route('client.history') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('client.history') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span>{{ __('History') }}</span>
                    </a>
                </div>

            @else
                {{-- Admin Navigation --}}
                <div class="space-y-1">
                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Admin') }}</div>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>

                    <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mt-4">{{ __('Management') }}</div>

                    <a href="{{ route('admin.sourcing-requests.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.sourcing-requests.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        <span>{{ __('All Sourcing Requests') }}</span>
                    </a>

                    <a href="{{ route('admin.quotations.select-request') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.quotations.select-request') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-[#EF7722] dark:text-[#FAA533] hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 border border-[#EF7722] dark:border-[#FAA533] hover:border-[#FAA533] dark:hover:border-[#EF7722] hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        <span>{{ __('Create Quotation') }}</span>
                    </a>

                    <a href="{{ route('admin.quotations.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.quotations.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        <span>{{ __('All Quotations') }}</span>
                    </a>

                    <a href="{{ route('admin.payment-methods.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.payment-methods.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                        </svg>
                        <span>{{ __('Payment Methods') }}</span>
                    </a>

                    <a href="{{ route('admin.sourcing-orders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.sourcing-orders.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                        </svg>
                        <span>{{ __('Sourcing Orders') }}</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                        <span>{{ __('Manage Clients') }}</span>
                    </a>

                    <a href="{{ route('admin.social-media-links.edit') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.social-media-links.edit') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                        </svg>
                        <span>{{ __('Social Media Links') }}</span>
                    </a>
                </div>

                @if(auth()->user()->isSuperAdmin())
                    <div class="space-y-1 mt-4">
                        <div class="px-3 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ __('Super Admin') }}</div>
                        
                        <a href="{{ route('admin.super-admin.list-admins') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.super-admin.list-admins') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                            <span>{{ __('Manage Admin Users') }}</span>
                        </a>

                        <a href="{{ route('admin.super-admin.create-admin') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.super-admin.create-admin') ? 'bg-[#EF7722] dark:bg-[#EF7722] text-white shadow-sm' : 'text-slate-700 dark:text-slate-300 hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 hover:translate-x-1' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            <span>{{ __('Create Admin User') }}</span>
                        </a>
                    </div>
                @endif
            @endif
        </nav>

        {{-- Notification Center --}}
        <div class="mx-3 mb-4 mt-2" x-data="notificationCenter" x-init="init()">
            <div class="bg-[#FAA533]/10 dark:bg-[#FAA533]/10 rounded-lg p-4 border border-[#FAA533]/30 dark:border-[#FAA533]/20">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-[#EF7722] dark:bg-[#EF7722] rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Notifications') }}</h3>
                    </div>
                    <span x-show="unreadCount > 0" class="bg-[#EF7722] text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                </div>
                
                <div class="space-y-2" x-show="!loading && notifications.length > 0">
                    <template x-for="notification in notifications.slice(0, 3)" :key="notification.id">
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-3 border border-[#FAA533]/20 dark:border-[#FAA533]/10 hover:border-[#FAA533]/40 dark:hover:border-[#FAA533]/30 transition-colors cursor-pointer">
                            <a :href="notification.click_action || '#'" @click.prevent="markAsRead(notification.id, notification.click_action)">
                                <div class="flex items-start gap-2">
                                    <div x-show="!notification.read_at" class="w-2 h-2 bg-[#EF7722] rounded-full mt-1.5 flex-shrink-0"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-slate-900 dark:text-white" x-text="notification.title"></p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 line-clamp-1" x-text="notification.body"></p>
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
                    <div class="inline-block w-4 h-4 border-2 border-[#FAA533]/30 border-t-[#EF7722] dark:border-[#FAA533]/20 dark:border-t-[#FAA533] rounded-full animate-spin"></div>
                </div>

                <a href="{{ route('notifications.index') }}" class="w-full mt-3 px-3 py-2 text-xs font-semibold text-[#EF7722] dark:text-[#FAA533] hover:bg-[#FAA533]/10 dark:hover:bg-[#FAA533]/10 rounded-lg transition-colors text-center block border border-[#EF7722]/30 dark:border-[#FAA533]/20">
                    {{ __('View All Notifications') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Footer / User Info & Logout --}}
    <div class="border-t border-[#EBEBEB] dark:border-slate-700 p-4 bg-[#EBEBEB] dark:bg-slate-900 mt-auto">
        @if($role === 'client')
            {{-- WhatsApp Contact Section --}}
            @if($socialMediaLinks->whatsapp_number)
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-3 mb-3 shadow-sm hover:shadow-md transition-all duration-200 group">
                <a href="https://wa.me/{{ $socialMediaLinks->whatsapp_number }}" target="_blank" class="flex items-center gap-3 text-white hover:text-white transition-colors">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.496"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <span class="text-sm font-bold block">{{ __('24/7 Support') }}</span>
                        <span class="text-xs opacity-90">{{ __('Chat with us on WhatsApp') }}</span>
                    </div>
                    <svg class="w-4 h-4 opacity-80 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 0 00-2 2v10a2 2 0 002 2h10a2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            @endif
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-lg p-3 mb-3 border border-[#EBEBEB] dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#EF7722] to-[#FAA533] dark:from-[#EF7722] dark:to-[#FAA533] rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
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
            <button type="submit" class="flex items-center justify-center gap-2 w-full px-3 py-2 rounded-lg text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200 border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500/30 hover:translate-x-1 group">
                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
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
        background: #EF7722;
    }

    .dark #sidebar::-webkit-scrollbar-thumb:hover {
        background: #FAA533;
    }

    /* Line clamp utility */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationCenter', () => ({
            notifications: [],
            unreadCount: 0,
            loading: true,
            error: null,

            async init() {
                await this.fetchNotifications();
                // Optionally, set up polling for new notifications
                // setInterval(() => this.fetchNotifications(), 60000);
            },

            async fetchNotifications() {
                this.loading = true;
                try {
                    const response = await fetch('{{ route("notifications.index") }}', {
                    const data = await response.json();
                    this.notifications = data.notifications.data;
                    this.unreadCount = data.notifications.data.filter(n => !n.read_at).length;
                } catch (error) {
                    console.error('Error fetching notifications:', error);
                } finally {
                    this.loading = false;
                }
            },

            async markAsRead(notificationId, clickAction) {
                try {
                    await fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    this.notifications = this.notifications.map(n => 
                        n.id === notificationId ? { ...n, read_at: new Date().toISOString() } : n
                    );
                    this.unreadCount = this.notifications.filter(n => !n.read_at).length;
                    if (clickAction) {
                        window.location.href = clickAction;
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
        }));
    });
</script>
@endpush