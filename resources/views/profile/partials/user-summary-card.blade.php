<!-- resources/views/profile/partials/user-summary-card.blade.php -->
<div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-white/20 dark:border-slate-700/30 rounded-2xl shadow-xl overflow-hidden group transition-all duration-300 hover:shadow-2xl hover:bg-white/80 dark:hover:bg-slate-800/80">
    <div class="relative h-32 bg-gradient-to-r from-[#EF7722] to-[#FAA533]">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
    </div>
    <div class="px-6 pb-8 text-center -mt-16 relative z-10">
        <div class="relative inline-block group/avatar">
            <div class="w-32 h-32 rounded-full border-4 border-white dark:border-slate-800 shadow-xl overflow-hidden bg-slate-100 dark:bg-slate-700">
                @if($user->profile_photo_path)
                    <img src="{{ media_url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EF7722&color=fff&size=200" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @endif
            </div>
            <button class="absolute bottom-1 right-1 p-2 bg-white dark:bg-slate-800 rounded-full shadow-lg text-[#EF7722] hover:scale-110 transition-transform opacity-0 group-hover/avatar:opacity-100 border border-slate-100 dark:border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
        </div>
        
        <h3 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">{{ $user->name }}</h3>
        <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">{{ $user->email }}</p>
        
        <div class="mt-6 flex items-center justify-center gap-2">
            <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-full text-xs font-bold uppercase tracking-wider border border-orange-200/50 dark:border-orange-800/30">
                {{ $user->role ?? __('Client') }}
            </span>
            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-full text-xs font-bold uppercase tracking-wider border border-slate-200/50 dark:border-slate-600/30">
                {{ __('Member since') }} {{ $user->created_at->format('M Y') }}
            </span>
        </div>
        
        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50 flex flex-col gap-3">
            <a href="#profile-info" class="flex items-center gap-3 px-4 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group/link">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 flex items-center justify-center group-hover/link:bg-emerald-600 group-hover/link:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <span class="text-sm font-semibold">{{ __('Account Details') }}</span>
                <svg class="ml-auto w-4 h-4 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
            <a href="#password-settings" class="flex items-center gap-3 px-4 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group/link">
                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center group-hover/link:bg-blue-600 group-hover/link:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <span class="text-sm font-semibold">{{ __('Security') }}</span>
                <svg class="ml-auto w-4 h-4 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
            <a href="#notification-settings" class="flex items-center gap-3 px-4 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group/link">
                <div class="w-8 h-8 rounded-lg bg-orange-50 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center group-hover/link:bg-orange-600 group-hover/link:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
                <span class="text-sm font-semibold">{{ __('Notifications') }}</span>
                <svg class="ml-auto w-4 h-4 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>
</div>
