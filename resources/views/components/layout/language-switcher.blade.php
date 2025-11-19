{{-- components/layout/language-switcher.blade.php --}}
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" 
            class="relative p-2.5 rounded-xl hover:bg-[#EF7722]/10 dark:hover:bg-[#EF7722]/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 group"
            :class="{ 'bg-[#EF7722]/10 dark:bg-[#EF7722]/20 ring-2 ring-[#EF7722]/20': open }">
        <svg class="h-6 w-6 text-[#EF7722] dark:text-[#FAA533] group-hover:text-[#EF7722] dark:group-hover:text-[#FAA533] transition-colors" 
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
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-[#EBEBEB] dark:border-slate-600 overflow-hidden z-50 ring-1 ring-[#EF7722]/10" 
         style="display: none;">
        
        {{-- Header --}}
        <div class="px-4 py-3 border-b border-[#EBEBEB] dark:border-slate-700 bg-gradient-to-r from-[#EF7722]/5 to-[#FAA533]/5 dark:from-[#EF7722]/10 dark:to-[#FAA533]/10">
            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ __('Language') }}</p>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Select your preferred language') }}</p>
        </div>
        
        <div class="py-1">
            {{-- English --}}
            <a href="{{ route('language.switch', 'en') }}" 
               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 hover:text-[#EF7722] dark:hover:text-[#FAA533] transition-all duration-200 group @if(app()->getLocale() == 'en') bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] @endif">
                <span class="fi fi-gb text-lg rounded-sm shadow-sm"></span>
                <span class="font-semibold">English</span>
                @if(app()->getLocale() == 'en')
                    <svg class="ml-auto h-4 w-4 text-[#EF7722] dark:text-[#FAA533]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>
            
            {{-- French --}}
            <a href="{{ route('language.switch', 'fr') }}" 
               class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-[#EF7722]/5 dark:hover:bg-[#EF7722]/10 hover:text-[#EF7722] dark:hover:text-[#FAA533] transition-all duration-200 group @if(app()->getLocale() == 'fr') bg-[#EF7722]/10 dark:bg-[#EF7722]/20 text-[#EF7722] dark:text-[#FAA533] @endif">
                <span class="fi fi-fr text-lg rounded-sm shadow-sm"></span>
                <span class="font-semibold">Français</span>
                @if(app()->getLocale() == 'fr')
                    <svg class="ml-auto h-4 w-4 text-[#EF7722] dark:text-[#FAA533]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>
        </div>
    </div>
</div>