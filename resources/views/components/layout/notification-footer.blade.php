{{-- Footer avec Actions --}}
<div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-6 py-3 flex items-center justify-between"
     x-show="notifications.length > 0">
    <button @click="clearAll()"
            class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-500/20 dark:hover:text-red-400 px-3 py-1.5 rounded-lg transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Tout supprimer
    </button>
    <a href="{{ route('notifications.index') }}" 
       class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-500/20 dark:hover:text-indigo-400 px-3 py-1.5 rounded-lg transition-all">
        Voir tout
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>