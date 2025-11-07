<header class="fixed top-0 left-0 right-0 z-40 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl border-b border-gray-200/80 dark:border-gray-700/80 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2 -ml-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex-1">
                @if (isset($header))
                    <div class="py-2">{{ $header }}</div>
                @endif
            </div>

            <div class="flex items-center gap-3">
                @include('components.layout.dark-mode-toggle')
                @include('components.layout.language-switcher')
                @include('components.layout.notification-center')
                @include('components.layout.user-menu')
            </div>
        </div>
    </div>
</header>
