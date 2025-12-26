{{-- resources/views/components/sidebar-enterprise-custom.blade.php --}}
@props(['role' => 'client', 'adminSourcingRequestCount' => 0, 'clientQuotationCount' => 0])

<aside class="fixed top-[5rem] bottom-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 shadow-sm transform transition-transform duration-300 lg:translate-x-0 flex flex-col" 
       id="sidebar"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    
    {{-- Main Content Wrapper --}}
    <div class="flex-1 overflow-y-auto pt-6 px-4">
        {{-- Navigation --}}
        <nav class="space-y-6">
            @if($role === 'client')
                {{-- Client Navigation --}}
                
                {{-- Section: Dashboard --}}
                <div>
                     <a href="{{ route('client.dashboard') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                {{-- Section: Sourcing --}}
                <div>
                     <div class="px-3 mb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Sourcing') }}</div>
                     <div class="space-y-1">
                        <a href="{{ route('client.sourcing-requests.create') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.sourcing-requests.create') ? 'bg-[#EF7722] text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            <span>{{ __('New Request') }}</span>
                        </a>

                        <a href="{{ route('client.sourcing-requests.handling') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.sourcing-requests.handling') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span>{{ __('Handling') }}</span>
                        </a>

                        <a href="{{ route('client.sourcing-requests.archived') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.sourcing-requests.archived') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.75 7.5h16.5m-16.5 0h16.5m-16.5 0L3.75 3h16.5L20.25 7.5" />
                            </svg>
                            <span>{{ __('Archived') }}</span>
                        </a>
                     </div>
                </div>

                {{-- Section: Quotes & Orders --}}
                <div>
                     <div class="px-3 mb-2 mt-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Quotes & Orders') }}</div>
                     <div class="space-y-1">
                        <a href="{{ route('client.quotations.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.quotations.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                            <span>{{ __('Quotations') }}</span>
                            @if($clientQuotationCount > 0)
                                <span class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $clientQuotationCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('client.sourcing-orders.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.sourcing-orders.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                            </svg>
                            <span>{{ __('My Orders') }}</span>
                        </a>

                        <div class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-slate-400 dark:text-slate-500 cursor-not-allowed group relative transition-colors duration-200">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" />
                            </svg>
                            <span>{{ __('Track Shipment') }}</span>
                            <span class="ml-auto text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ __('Coming Soon') }}</span>
                        </div>

                        <div class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-slate-400 dark:text-slate-500 cursor-not-allowed group relative transition-colors duration-200">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                            <span>{{ __('Refunds') }}</span>
                            <span class="ml-auto text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ __('Coming Soon') }}</span>
                        </div>


                        <a href="{{ route('client.history') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('client.history') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span>{{ __('History') }}</span>
                        </a>
                     </div>
                </div>

            @else
                {{-- Admin Navigation --}}
                
                {{-- Section: Dashboard --}}
                <div>
                     <a href="{{ route('admin.dashboard') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>

                {{-- Section: Workflow --}}
                <div>
                     <div class="px-3 mb-2 mt-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Workflow') }}</div>
                     <div class="space-y-1">
                        <a href="{{ route('admin.sourcing-requests.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.sourcing-requests.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                            <span>{{ __('Requests') }}</span>
                            @if($adminSourcingRequestCount > 0)
                                <span class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $adminSourcingRequestCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.quotations.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.quotations.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                            <span>{{ __('Quotations') }}</span>
                        </a>

                        <a href="{{ route('admin.quotations.select-request') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.quotations.select-request') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                           <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                           </svg>
                           <span>{{ __('Create Quotation') }}</span>
                       </a>

                        <a href="{{ route('admin.sourcing-orders.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.sourcing-orders.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                            </svg>
                            <span>{{ __('Orders') }}</span>
                        </a>

                        <div class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium text-slate-400 dark:text-slate-500 cursor-not-allowed group relative transition-colors duration-200">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                            <span>{{ __('Refunds') }}</span>
                            <span class="ml-auto text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100 px-1.5 py-0.5 rounded uppercase opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ __('Coming Soon') }}</span>
                        </div>

                     </div>
                </div>

                {{-- Section: Management --}}
                @if(auth()->user()->isSuperAdmin())
                <div>
                     <div class="space-y-1 mt-4">
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                            <span>{{ __('Clients') }}</span>
                        </a>

                        <a href="{{ route('admin.payment-methods.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.payment-methods.index') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                            </svg>
                            <span>{{ __('Payments') }}</span>
                        </a>

                        <a href="{{ route('admin.social-media-links.edit') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.social-media-links.edit') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                            <span>{{ __('Social Media') }}</span>
                        </a>

                        <a href="{{ route('admin.reports.sales-margin') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.reports.sales-margin') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                           <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                            </svg>
                            <span>{{ __('Reports') }}</span>
                        </a>
                     </div>
                </div>
                @endif

                @if(auth()->user()->isSuperAdmin())
                {{-- Section: Quick Management (Dropdown) --}}
                <div x-data="{ open: {{ request()->routeIs('admin.countries.*') || request()->routeIs('admin.services.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }">
                     <button @click="open = !open" 
                             class="flex items-center justify-between w-full gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white mt-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                            </svg>
                            <span>{{ __('Quick Management') }}</span>
                        </div>
                        <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         style="display: none;"
                         class="space-y-1 mt-1">
                         
                        {{-- Countries --}}
                        <a href="{{ route('admin.countries.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 pl-11 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.countries.*') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <span>{{ __('Countries') }}</span>
                        </a>

                        {{-- Services --}}
                        <a href="{{ route('admin.services.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 pl-11 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.services.*') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <span>{{ __('Services') }}</span>
                        </a>

                        {{-- Categories --}}
                        <a href="{{ route('admin.categories.index') }}" 
                           class="flex items-center gap-3 px-3 py-2 pl-11 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                            <span>{{ __('Categories') }}</span>
                        </a>
                    </div>
                </div>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    {{-- Section: Super Admin --}}
                    <div>
                         <div class="px-3 mb-2 mt-4 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('System') }}</div>
                         <div class="space-y-1">
                            <a href="{{ route('admin.super-admin.list-admins') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.super-admin.list-admins') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                                </svg>
                                <span>{{ __('Admins') }}</span>
                            </a>
                            <a href="{{ route('admin.super-admin.create-admin') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.super-admin.create-admin') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                                </svg>
                                <span>{{ __('New Admin') }}</span>
                            </a>
                            
                            <a href="{{ route('admin.google-sheets.settings') }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs('admin.google-sheets.*') ? 'bg-[#EF7722]/10 text-[#EF7722] dark:text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>{{ __('Google Sheets') }}</span>
                            </a>
                         </div>
                    </div>
                @endif
            @endif
        </nav>
    </div>

    {{-- Footer / User Info & Logout --}}
    <div class="border-t border-slate-100 dark:border-slate-800 p-4 bg-slate-50/50 dark:bg-slate-900/50 mt-auto">
        @if($role === 'client')
            @if($socialMediaLinks->whatsapp_number)
            <div class="mb-4 text-center">
                <a href="https://wa.me/{{ $socialMediaLinks->whatsapp_number }}" target="_blank" 
                   class="inline-flex items-center justify-center gap-2 w-full px-3 py-2 text-xs font-semibold text-white bg-[#25D366] hover:bg-[#20b858] rounded-md transition-shadow shadow-sm hover:shadow">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893-.001-3.189-1.262-6.209-3.553-8.496"/></svg>
                    <span>{{ __('WhatsApp Support') }}</span>
                </a>
            </div>
            @endif
        @endif

        <div class="flex items-center gap-3 px-1 mb-3">
            <div class="w-8 h-8 rounded-lg overflow-hidden flex-shrink-0 shadow-sm">
                @if (Auth::user()->profile_photo_path)
                    <img class="h-full w-full object-cover" src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" />
                @else
                    <div class="h-full w-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                        <span class="text-xs font-bold text-white">{{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 dark:text-gray-200 truncate">{{ Auth::user()->name ?? __('Guest') }}</p>
                <p class="text-[10px] uppercase font-bold text-slate-500 tracking-wide">{{ strtoupper($role) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" onsubmit="localStorage.removeItem('spam_warning_dismissed')">
            @csrf
            <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 rounded-md text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-red-600 dark:hover:text-red-400 transition-colors group">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                </svg>
                <span>{{ __('Sign Out') }}</span>
            </button>
        </form>
    </div>
</aside>

{{-- Mobile Overlay --}}
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm"
     style="display: none;">
</div>

<style>
    /* Clean Scrollbar */
    #sidebar::-webkit-scrollbar { width: 4px; }
    #sidebar::-webkit-scrollbar-track { background: transparent; }
    #sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .dark #sidebar::-webkit-scrollbar-thumb { background: #475569; }
    #sidebar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
