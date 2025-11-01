{{-- En-tête Amélioré --}}
<div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200/80 dark:border-gray-700">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Notifications
        </h3>
        <div class="flex items-center gap-2">
            <button @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-gray-700 px-3 py-1.5 rounded-lg transition-all"
                    title="Tout marquer comme lu">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Tout lire
            </button>
        </div>
    </div>

    {{-- Filtres et Recherche --}}
    <div class="space-y-2">
        <div class="flex items-center gap-2">
            <button @click="filter = 'all'" 
                    :class="filter === 'all' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'bg-white/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600'"
                    class="flex-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-all">
                Toutes
                <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                      :class="filter === 'all' ? 'bg-indigo-100 dark:bg-indigo-500/50 text-indigo-700 dark:text-white' : 'bg-gray-200 dark:bg-gray-500 text-gray-600 dark:text-gray-200'"
                      x-text="notifications.length"></span>
            </button>
            <button @click="filter = 'unread'" 
                    :class="filter === 'unread' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'bg-white/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600'"
                    class="flex-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-all">
                Non lues
                <span class="ml-1.5 px-1.5 py-0.5 rounded-full text-[10px] font-bold"
                      :class="filter === 'unread' ? 'bg-indigo-100 dark:bg-indigo-500/50 text-indigo-700 dark:text-white' : 'bg-gray-200 dark:bg-gray-500 text-gray-600 dark:text-gray-200'"
                      x-text="unreadCount"></span>
            </button>
            <button @click="filter = 'read'" 
                    :class="filter === 'read' ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-white shadow-sm' : 'bg-white/50 dark:bg-gray-600/50 text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-600'"
                    class="flex-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-all">
                Lues
            </button>
        </div>

        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" 
                   x-model="searchQuery"
                   placeholder="Rechercher une notification..."
                   class="w-full pl-9 pr-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white">
        </div>
    </div>
</div>