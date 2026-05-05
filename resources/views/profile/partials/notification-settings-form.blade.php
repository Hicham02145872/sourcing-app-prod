<!-- resources/views/profile/partials/notification-settings-form.blade.php -->
<section class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-white/20 dark:border-slate-700/30 rounded-2xl shadow-xl p-8 w-full group transition-all duration-300 hover:shadow-2xl">
    <header class="mb-8 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/40 text-[#EF7722] flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                {{ __('Notification Settings') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __("Stay updated on your sourcing requests and orders in real-time.") }}
            </p>
        </div>
    </header>

    <div class="space-y-8" x-data="{ 
        permission: Notification.permission,
        loading: false,
        async requestPermission() {
            this.loading = true;
            await window.requestFcmPermission();
            this.permission = Notification.permission;
            this.loading = false;
        }
    }">
        <div class="flex flex-col md:flex-row items-center justify-between p-6 bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/50 gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <span class="text-base font-bold text-slate-900 dark:text-white">{{ __('Push Notifications') }}</span>
                    <template x-if="permission === 'granted'">
                        <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-200/50">
                            {{ __('Active') }}
                        </span>
                    </template>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ __('Receive alerts directly on your device (Mobile & Desktop).') }}</p>
                
                <template x-if="permission === 'denied'">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('BLOCKED (Check browser settings)') }}
                    </span>
                </template>
            </div>

            <button 
                @click="requestPermission()"
                x-show="permission !== 'granted'"
                :disabled="loading"
                class="w-full md:w-auto inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:shadow-lg hover:shadow-orange-500/30 disabled:bg-slate-300 text-white text-sm font-bold rounded-xl transition-all transform hover:-translate-y-0.5 active:scale-95 shadow-sm"
            >
                <span x-show="!loading">{{ __('Enable Notifications') }}</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Processing...') }}
                </span>
            </button>
            
            <div x-show="permission === 'granted'" class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-800/30 shadow-inner">
                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
            <div class="group/notif p-6 border border-slate-100 dark:border-slate-700/50 rounded-2xl bg-white dark:bg-slate-900/30 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300">
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/40 rounded-xl text-[#EF7722] group-hover/notif:scale-110 transition-transform shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-base font-bold text-slate-800 dark:text-slate-100">{{ __('New Quotations') }}</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ __('Be notified when an administrator sends a new quotation for your requests.') }}</p>
            </div>

            <div class="group/notif p-6 border border-slate-100 dark:border-slate-700/50 rounded-2xl bg-white dark:bg-slate-900/30 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300">
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl text-blue-600 group-hover/notif:scale-110 transition-transform shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-base font-bold text-slate-800 dark:text-slate-100">{{ __('Logistics Tracking') }}</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ __('Track every step: UAE Arrival, Customs Clearance, Out for delivery.') }}</p>
            </div>
        </div>
    </div>
</section>
