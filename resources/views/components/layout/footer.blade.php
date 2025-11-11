<footer class="fixed bottom-0 right-0 z-30 lg:left-72 border-t bg-white dark:bg-gray-800 border-blue-200/50 dark:border-blue-800/50 w-full">
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            {{-- Copyright Section --}}
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>&copy; {{ date('Y') }} <strong class="text-blue-700 dark:text-blue-400">{{ config('app.name') }}</strong>. Tous droits réservés.</span>
            </div>
            
            {{-- Footer Links --}}
            <div class="flex items-center gap-6">
                <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-400 transition-colors duration-200 font-medium relative group">
                    <span>Confidentialité</span>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 dark:bg-blue-400 group-hover:w-full transition-all duration-200"></span>
                </a>
                <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-400 transition-colors duration-200 font-medium relative group">
                    <span>Conditions</span>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 dark:bg-blue-400 group-hover:w-full transition-all duration-200"></span>
                </a>
                <a href="#" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-700 dark:hover:text-blue-400 transition-colors duration-200 font-medium relative group">
                    <span>Support</span>
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 dark:bg-blue-400 group-hover:w-full transition-all duration-200"></span>
                </a>
            </div>
        </div>
    </div>
</footer>