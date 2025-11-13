<header class="fixed top-0 left-0 right-0 z-40 bg-white/98 dark:bg-gray-900/98 backdrop-blur-xl border-b border-blue-200/60 dark:border-blue-900/40 shadow-sm">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Mobile Menu Button --}}
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden p-2.5 -ml-2 text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>

            {{-- Header Content --}}
            <div class="flex-1 px-4">
                @if (isset($header))
                    <div class="py-2">{{ $header }}</div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3">
                @if(auth()->check() && auth()->user()->role === 'client')
                    {{-- Delivery Time Text --}}
                    <div class="hidden md:flex items-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400 font-medium whitespace-nowrap mr-4">
                            Estimated delivery-time between 15 - 20 working days
                        </p>
                    </div>

                    {{-- Social Media Icons --}}
                    <div class="flex items-center gap-1">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" 
                           class="p-2.5 text-[#1877F2] hover:bg-[#1877F2]/10 rounded-xl transition-all duration-200 group" 
                           title="Facebook">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                            </svg>
                        </a>
                        
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" 
                           class="p-2.5 text-[#E4405F] hover:bg-[#E4405F]/10 rounded-xl transition-all duration-200 group" 
                           title="Instagram">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>

                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" 
                           class="p-2.5 text-[#0A66C2] hover:bg-[#0A66C2]/10 rounded-xl transition-all duration-200 group" 
                           title="LinkedIn">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>

                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" 
                           class="p-2.5 text-gray-900 dark:text-white hover:bg-gray-900/10 dark:hover:bg-white/10 rounded-xl transition-all duration-200 group" 
                           title="Twitter">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Espace entre les icônes sociales et le dark mode --}}
                    <div class="w-4"></div>
                @endif

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