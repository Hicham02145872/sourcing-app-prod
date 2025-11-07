{{-- Liste des Notifications --}}
<div class="max-h-[480px] overflow-y-auto scrollbar-thin" 
     x-show="!loading && filteredNotifications.length > 0">
    <template x-for="(n, index) in filteredNotifications.slice(0, 5)" :key="n.id || index">
        <div class="notification-item relative group border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gradient-to-r hover:from-gray-50 hover:to-indigo-50/30 dark:hover:from-gray-700 dark:hover:to-gray-700/50"
             :class="{ 'bg-indigo-50/40 dark:bg-indigo-500/10': !n.read_at }">
            <a :href="n.click_action || '#'" 
               @click.prevent="markAsRead(n.id, n.click_action)"
               class="flex items-start gap-3 px-6 py-4">
                
                {{-- Icône avec Badge --}}
                <div class="flex-shrink-0 mt-0.5 relative">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-sm ring-2 transition-all duration-200"
                         :class="[
                             getNotificationColor(n.type || 'default').bg,
                             getNotificationColor(n.type || 'default').text,
                             !n.read_at ? getNotificationColor(n.type || 'default').ring + ' ring-opacity-20' : 'ring-transparent'
                         ]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-html="getNotificationIcon(n.type || 'default')"></svg>
                    </div>
                    <span x-show="!n.read_at" 
                          class="absolute -top-1 -right-1 w-3 h-3 bg-indigo-600 rounded-full ring-2 ring-white dark:ring-gray-800 animate-pulse"></span>
                </div>

                {{-- Contenu --}}
                <div class="flex-1 min-w-0 space-y-1">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-1" 
                            :class="{ 'font-bold': !n.read_at }"
                            x-text="n.title || 'Notification'"></h4>
                        <span class="flex-shrink-0 text-xs text-gray-500 dark:text-gray-400 font-medium" 
                              x-text="formatDate(n.created_at)"></span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed" 
                       x-text="n.body || 'Aucun contenu disponible'"></p>
                    
                    {{-- Tags / Catégorie --}}
                    <div class="flex items-center gap-2 pt-1" x-show="n.category">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span x-text="n.category"></span>
                        </span>
                    </div>
                </div>

                {{-- Bouton Supprimer --}}
                <button @click="deleteNotification(n.id, $event)"
                        class="flex-shrink-0 opacity-0 group-hover:opacity-100 p-1.5 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-all duration-200"
                        title="Supprimer">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </a>
        </div>
    </template>
</div>