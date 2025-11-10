{{-- Error State --}}
<div x-show="error && !loading" class="p-8">
    <div class="flex flex-col items-center justify-center gap-4 text-center">
        <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-full">
            <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Erreur de chargement</h4>
            <p class="text-xs text-gray-600 dark:text-gray-400" x-text="error"></p>
        </div>
        <button @click="fetchNotifications" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Réessayer
        </button>
    </div>
</div>