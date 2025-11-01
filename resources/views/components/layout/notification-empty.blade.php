{{-- Message Vide --}}
<div x-show="!loading && filteredNotifications.length === 0" 
     class="px-6 py-16 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 mb-4">
        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
        <span x-show="filter === 'all'">Aucune notification</span>
        <span x-show="filter === 'unread'">Aucune notification non lue</span>
        <span x-show="filter === 'read'">Aucune notification lue</span>
    </h4>
    <p class="text-sm text-gray-500 dark:text-gray-400">
        <span x-show="searchQuery">Essayez une autre recherche</span>
        <span x-show="!searchQuery && filter === 'all'">Vous êtes à jour !</span>
        <span x-show="!searchQuery && filter === 'unread'">Toutes vos notifications ont été lues</span>
    </p>
</div>