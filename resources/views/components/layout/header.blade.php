<header class="fixed top-0 left-0 right-0 z-40 bg-white/98 dark:bg-gray-900/98 backdrop-blur-xl border-b border-blue-200/60 dark:border-blue-900/40 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Mobile Menu Button --}}
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2.5 -ml-2 text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Header Content --}}
            <div class="flex-1 px-4">
                @if(auth()->check() && auth()->user()->role === 'client')
                    <p class="text-base text-gray-600 dark:text-gray-400 font-bold text-center">Estimated delivery-time between 15 - 20 working days</p>
                @endif
                @if (isset($header))
                    <div class="py-2">{{ $header }}</div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                @include('components.layout.dark-mode-toggle')
                @include('components.layout.language-switcher')
                @include('components.layout.notification-center')
                @include('components.layout.user-menu')
            </div>
        </div>
    </div>
    
    {{-- Subtle Bottom Gradient --}}
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-500/20 to-transparent"></div>
</header>