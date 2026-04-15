{{-- components/layout/language-switcher.blade.php --}}
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="relative p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 focus:outline-none group"
            :class="{ 'bg-slate-100 dark:bg-slate-800 text-[#EF7722]': open }">
        <svg class="h-6 w-6 transition-colors" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
        </svg>
    </button>
    
    <div x-show="open" 
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="absolute end-0 mt-3 w-56 bg-white dark:bg-slate-900 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-200 dark:border-slate-800 overflow-hidden z-50 ring-1 ring-slate-200/50 dark:ring-slate-800/50" 
         style="display: none;">
        
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
            <p class="text-sm font-bold text-slate-800 dark:text-white">{{ __('Language') }}</p>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-wider font-bold mt-1">{{ __('Select preferred') }}</p>
        </div>
        
        <div class="py-2">
            {{-- English --}}
            <a href="{{ route('language.switch', 'en') }}" 
               class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-200 group {{ app()->getLocale() == 'en' ? 'bg-slate-50 dark:bg-slate-800/50 text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-[#EF7722]' }}">
                <span class="fi fi-gb text-lg rounded-sm shadow-sm ring-1 ring-slate-200/50 dark:ring-slate-700/50"></span>
                <span class="font-bold">English</span>
                @if(app()->getLocale() == 'en')
                    <svg class="ml-auto h-4 w-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @endif
            </a>
            
            {{-- French --}}
            <a href="{{ route('language.switch', 'fr') }}" 
               class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-200 group {{ app()->getLocale() == 'fr' ? 'bg-slate-50 dark:bg-slate-800/50 text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-[#EF7722]' }}">
                <span class="fi fi-fr text-lg rounded-sm shadow-sm ring-1 ring-slate-200/50 dark:ring-slate-700/50"></span>
                <span class="font-bold">Français</span>
                @if(app()->getLocale() == 'fr')
                    <svg class="ml-auto h-4 w-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @endif
            </a>

            {{-- Arabic --}}
            <a href="{{ route('language.switch', 'ar') }}" 
               class="flex items-center gap-3 px-6 py-3 text-sm transition-all duration-200 group {{ app()->getLocale() == 'ar' ? 'bg-slate-50 dark:bg-slate-800/50 text-[#EF7722]' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-[#EF7722]' }}">
                <span class="fi fi-sa text-lg rounded-sm shadow-sm ring-1 ring-slate-200/50 dark:ring-slate-700/50"></span>
                <span class="font-bold">العربية</span>
                @if(app()->getLocale() == 'ar')
                    <svg class="ml-auto h-4 w-4 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                @endif
            </a>
        </div>
    </div>
</div>