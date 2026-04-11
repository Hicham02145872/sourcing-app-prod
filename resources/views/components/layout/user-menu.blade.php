{{-- components/layout/user-menu.blade.php --}}
<div class="flex items-center gap-3 ml-4">
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" 
                class="flex items-center gap-3 p-1.5 pr-4 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-2xl transition-all duration-200 focus:outline-none group"
                :class="{ 'bg-slate-100 dark:bg-slate-800 text-[#EF7722]': open }">
            
            {{-- Avatar with refined styling --}}
            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden shadow-sm group-hover:border-[#EF7722]/30 transition-colors">
                @if (auth()->user()?->profile_photo_path)
                    <img class="h-full w-full object-cover" src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" />
                @else
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">
                        {{ strtoupper(substr(optional(auth()->user())->name, 0, 1)) }}
                    </span>
                @endif
            </div>

            <div class="hidden md:block text-left">
                <p class="text-sm font-bold text-slate-800 dark:text-white leading-tight truncate max-w-[120px]">
                    {{ optional(auth()->user())->name }}
                </p>
                <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-500 mt-0.5">
                    {{ optional(auth()->user())->role ?? __('User') }}
                </p>
            </div>

            <svg class="w-4 h-4 text-slate-400 group-hover:text-[#EF7722] transition-all duration-200" 
                 :class="{ 'rotate-180 text-[#EF7722]': open }" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="absolute right-0 mt-3 w-72 bg-white dark:bg-slate-900 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-200 dark:border-slate-800 overflow-hidden z-50 ring-1 ring-slate-200/50 dark:ring-slate-800/50"
             style="display: none;">
            
            {{-- User Info Header - Enterprise Style --}}
            <div class="px-6 py-6 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex flex-col gap-1">
                    <p class="text-base font-bold text-slate-800 dark:text-white">{{ optional(auth()->user())->name }}</p>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 truncate">{{ optional(auth()->user())->email }}</p>
                </div>
                
                <div class="mt-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 tracking-wider uppercase">
                        {{ optional(auth()->user())->role ?? __('User') }}
                    </span>
                </div>
            </div>
            
            {{-- Menu Items --}}
            <div class="py-2">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-6 py-3.5 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-[#EF7722] dark:hover:text-[#EF7722] transition-all duration-200 group">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center group-hover:bg-[#EF7722]/10 transition-colors">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-[#EF7722] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold">{{ __('My Profile') }}</span>
                </a>
            </div>

            {{-- Logout Section --}}
            <div class="border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900">
                <form method="POST" action="{{ route('logout') }}" onsubmit="localStorage.removeItem('spam_warning_dismissed')">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 w-full text-left px-6 py-4 text-sm font-bold text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center group-hover:border-rose-100 dark:group-hover:border-rose-900/30 transition-colors">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span>{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>