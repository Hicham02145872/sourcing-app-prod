<!-- resources/views/profile/edit.blade.php -->
<x-app-layout>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900 font-sans text-slate-900 pb-12 -mt-4">
        
        <!-- Premium Header Area -->
        <div class="relative bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-[#EF7722] to-[#FAA533] text-white shadow-lg shadow-orange-500/20">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ __('My Account') }}</h1>
                            <nav class="flex items-center gap-2 mt-1" aria-label="Breadcrumb">
                                <span class="text-sm font-medium text-slate-500 hover:text-orange-500 transition-colors cursor-pointer">{{ __('Settings') }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('Profile & Security') }}</span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Left Column: Summary & Quick Links (1/3) -->
                <div class="w-full lg:w-1/3 space-y-8">
                    @include('profile.partials.user-summary-card')
                    
                    <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-white/20 dark:border-slate-700/30 rounded-2xl p-6 shadow-xl hidden lg:block">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">{{ __('Quick Help') }}</h4>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('Profile Security') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Keep your password complex to ensure your data remains safe.') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ __('Push Alerts') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ __('Enable notifications to receive real-time updates on your requests.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Settings Forms (2/3) -->
                <div class="w-full lg:w-2/3 space-y-10">
                    
                    <div id="profile-info" class="scroll-mt-32">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div id="password-settings" class="scroll-mt-32">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div id="notification-settings" class="scroll-mt-32">
                        @include('profile.partials.notification-settings-form')
                    </div>

                    <div class="pt-6 border-t border-slate-200 dark:border-slate-800">
                        @include('profile.partials.delete-user-form')
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>