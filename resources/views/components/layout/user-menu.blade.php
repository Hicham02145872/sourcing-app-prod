{{-- components/layout/user-menu.blade.php --}}
<div class="flex items-center gap-3 ml-4">
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" 
                class="flex items-center gap-2.5 p-2 pr-3 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-[#EF7722]/10 dark:hover:bg-[#EF7722]/20 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-1 group"
                :class="{ 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 ring-2 ring-[#EF7722]/20': open }">
            <div class="w-9 h-9 bg-gradient-to-br from-[#EF7722] via-[#FAA533] to-[#EF7722] rounded-xl flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-white dark:ring-slate-800 group-hover:shadow-[#EF7722]/50 transition-shadow duration-200">
                {{ strtoupper(substr(optional(auth()->user())->name, 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <p class="text-sm font-bold text-slate-900 dark:text-white leading-none">{{ optional(auth()->user())->name }}</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ ucfirst(optional(auth()->user())->role ?? 'Utilisateur') }}</p>
            </div>
            <svg class="w-4 h-4 text-[#EF7722] dark:text-[#FAA533] transition-transform duration-200" 
                 :class="{ 'rotate-180': open }" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-[#EBEBEB] dark:border-slate-600 overflow-hidden z-50 ring-1 ring-[#EF7722]/10"
             style="display: none;">
            
            {{-- User Info Header --}}
            <div class="px-4 py-3 bg-gradient-to-r from-[#EF7722]/5 to-[#FAA533]/5 dark:from-[#EF7722]/10 dark:to-[#FAA533]/10 border-b border-[#EBEBEB] dark:border-slate-700">
                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ optional(auth()->user())->name }}</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 truncate mt-0.5">{{ optional(auth()->user())->email }}</p>
                <span class="inline-flex items-center mt-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] border border-[#EF7722]/20 dark:border-[#FAA533]/30">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    {{ ucfirst(optional(auth()->user())->role ?? 'Utilisateur') }}
                </span>
            </div>
            
            {{-- Menu Items --}}
            <div class="py-1">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 hover:text-[#EF7722] dark:hover:text-[#FAA533] transition-all duration-200 group">
                    <svg class="w-5 h-5 text-slate-400 dark:text-slate-500 group-hover:text-[#EF7722] dark:group-hover:text-[#FAA533] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-semibold">Mon Profil</span>
                </a>
                
                {{-- Add more menu items here as needed --}}
            </div>

            {{-- Logout Section --}}
            <div class="border-t border-[#EBEBEB] dark:border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200 group">
                        <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>