<div class="hidden sm:flex sm:items-center sm:gap-4" x-data="notificationCenter" @show-success-toast.window="showSuccessToast($event.detail)" @show-error-toast.window="showErrorToast($event.detail)">
    <div class="relative">
        <button @click="open = !open; if(open) fetchNotifications()" 
                class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 group"
                :class="{ 'bg-gray-100 dark:bg-gray-700': open }">
            <svg class="h-6 w-6 text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span x-show="unreadCount > 0" 
                  class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-full shadow-lg animate-pulse-ring" 
                  x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
        </button>

        {{-- Panneau de Notifications --}}
        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-3 w-[420px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200/80 dark:border-gray-700 overflow-hidden z-50"
             style="display: none;">
            
            @include('components.layout.notification-header')
            @include('components.layout.notification-loading')
            @include('components.layout.notification-error')
            @include('components.layout.notification-list')
            @include('components.layout.notification-empty')
            @include('components.layout.notification-footer')
        </div>
    </div>
</div>