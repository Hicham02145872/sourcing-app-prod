<div x-data="{
    theme: localStorage.getItem('color-theme') || 'light',
    toggleTheme() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('color-theme', this.theme);
        if (this.theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" class="relative">
    <button @click="toggleTheme()" 
            class="relative p-2.5 rounded-xl hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 group overflow-hidden">
        
        {{-- Light Mode Icon (Moon) --}}
        <svg x-show="theme === 'light'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 rotate-90 scale-50"
             x-transition:enter-end="opacity-100 rotate-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 rotate-0 scale-100"
             x-transition:leave-end="opacity-0 -rotate-90 scale-50"
             class="h-6 w-6 text-blue-700 group-hover:text-blue-900 transition-colors" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
        
        {{-- Dark Mode Icon (Sun) --}}
        <svg x-show="theme === 'dark'" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -rotate-90 scale-50"
             x-transition:enter-end="opacity-100 rotate-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 rotate-0 scale-100"
             x-transition:leave-end="opacity-0 rotate-90 scale-50"
             class="h-6 w-6 text-blue-400 group-hover:text-blue-300 transition-colors" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        
        {{-- Subtle Background Glow --}}
        <div class="absolute inset-0 bg-blue-600/5 dark:bg-blue-400/5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 -z-10"></div>
    </button>
</div>