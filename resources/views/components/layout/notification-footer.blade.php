{{-- Footer --}}
<div x-show="!loading && !error && notifications.length > 0" 
     class="px-6 py-3 border-t border-blue-100 dark:border-blue-900/30 bg-gradient-to-r from-blue-50/50 to-blue-100/30 dark:from-blue-900/10 dark:to-blue-800/5">
    <div class="flex items-center justify-between">
        <button @click="clearAll" 
                class="text-xs font-medium text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 rounded px-2 py-1">
            Tout effacer
        </button>
        <a href="/notifications" 
           class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 rounded px-2 py-1">
            Voir tout
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>