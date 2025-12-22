{{-- components/layout/notification-center.blade.php --}}
<div class="sm:flex sm:items-center sm:gap-4" x-data="notificationCenter" @show-success-toast.window="showSuccessToast($event.detail)" @show-error-toast.window="showErrorToast($event.detail)">
    <div class="relative">
        <button @click="open = !open; if(open) fetchNotifications()" 
                class="relative p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 focus:outline-none group"
                :class="{ 'bg-slate-100 dark:bg-slate-800 text-[#EF7722]': open }">
            <svg class="h-6 w-6 transition-colors" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V5a1 1 0 00-2 0v.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span x-show="unreadCount > 0" 
                  class="absolute top-2 right-2 flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#EF7722] opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#EF7722]"></span>
            </span>
        </button>

        {{-- Panneau de Notifications --}}
        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="absolute right-0 mt-3 w-[440px] bg-white dark:bg-slate-900 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-slate-200 dark:border-slate-800 overflow-hidden z-50 ring-1 ring-slate-200/50 dark:ring-slate-800/50"
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